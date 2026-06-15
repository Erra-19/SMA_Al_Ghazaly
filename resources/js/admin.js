import './bootstrap'
import Alpine from 'alpinejs'

// ── API Client ────────────────────────────────────────────────────────────────
const api = window.axios.create({ baseURL: '/api', headers: { Accept: 'application/json' } })
api.interceptors.request.use(cfg => {
    const t = localStorage.getItem('ag_token')
    if (t) cfg.headers.Authorization = `Bearer ${t}`
    return cfg
})
api.interceptors.response.use(r => r, err => {
    if (err.response?.status === 401) {
        localStorage.removeItem('ag_token'); localStorage.removeItem('ag_user')
        window.location.href = '/admin/login'
    }
    return Promise.reject(err)
})

// ── Helpers ─────────────────────────────────────────────────────────────────
const fmt = {
    date: v => v ? new Date(v).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '—',
    datetime: v => v ? new Date(v).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—',
    currency: v => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(v ?? 0),
    number: v => new Intl.NumberFormat('id-ID').format(v ?? 0),
    initials: (n = '') => n.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase() || '?',
    err: (e, fb = 'Terjadi kesalahan.') =>
        e?.response?.data?.message ||
        Object.values(e?.response?.data?.errors || {})?.[0]?.[0] ||
        fb,
    unwrap: r => r?.data?.data ?? r?.data ?? [],
}

async function uploadImageFor(component, field, files) {
    const file = files?.[0]
    if (!file) return

    const fd = new FormData()
    fd.append('file', file)

    try {
        const r = await api.post('/admin/medias', fd)
        const media = r.data?.data ?? r.data
        const url = media.url || (media.path ? `/storage/${media.path}` : '')
        if (!url) throw new Error('URL file tidak ditemukan.')
        component.form[field] = url
        Alpine.store('adm').notify('Gambar berhasil diupload.')
    } catch (e) {
        Alpine.store('adm').notify(fmt.err(e, 'Upload gambar gagal.'), 'error')
    }
}

function openMediaPickerFor(component, field) {
    Alpine.store('mediaPicker').open(component, field)
}

async function downloadFile(url, filename) {
    try {
        const r = await api.get(url, { responseType: 'blob' })
        const blobUrl = URL.createObjectURL(r.data)
        const a = document.createElement('a')
        a.href = blobUrl; a.download = filename
        document.body.appendChild(a); a.click()
        document.body.removeChild(a)
        setTimeout(() => URL.revokeObjectURL(blobUrl), 1000)
    } catch (e) {
        Alpine.store('adm').notify(fmt.err(e, 'Download gagal.'), 'error')
    }
}

window.uploadImageFor = uploadImageFor
window.openMediaPickerFor = openMediaPickerFor

// ── Global Store ──────────────────────────────────────────────────────────────
Alpine.store('adm', {
    user: null, page: 'dashboard',
    toast: { show: false, msg: '', type: 'success' },
    internalUnread: 0,
    _timer: null,

    boot() {
        const token = localStorage.getItem('ag_token')
        if (!token) { window.location.href = '/admin/login'; return }
        this.user = JSON.parse(localStorage.getItem('ag_user') || '{}')
        this.refreshUnread()
    },

    async refreshUnread() {
        try {
            const r = await api.get('/admin/internal-messages/unread-count')
            this.internalUnread = r.data?.count ?? 0
        } catch {}
    },

    go(p) { this.page = p; window.scrollTo(0, 0) },

    notify(msg, type = 'success') {
        clearTimeout(this._timer)
        this.toast = { show: true, msg, type }
        this._timer = setTimeout(() => { this.toast.show = false }, 3500)
    },

    async logout() {
        try { await api.post('/auth/logout') } catch {}
        localStorage.removeItem('ag_token'); localStorage.removeItem('ag_user')
        window.location.href = '/admin/login'
    },

    hasRole(...roles) { return roles.includes(this.user?.role) },
})

Alpine.store('mediaPicker', {
    show: false,
    loading: false,
    items: [],
    target: null,
    field: '',
    _callback: null,

    async open(target, field, callback = null) {
        this.target = target
        this.field = field
        this._callback = callback
        this.show = true
        if (!this.items.length) await this.load()
    },

    async load() {
        this.loading = true
        try {
            const r = await api.get('/admin/medias', { params: { type: 'image', per_page: 200 } })
            const raw = r.data?.data ?? r.data ?? []
            this.items = Array.isArray(raw) ? raw : []
        } catch (e) {
            Alpine.store('adm').notify(fmt.err(e, 'Media belum bisa dimuat.'), 'error')
        } finally {
            this.loading = false
        }
    },

    choose(file) {
        if (this._callback) {
            this._callback(file)
            this.close()
            return
        }
        const url = file.url || (file.path ? `/storage/${file.path}` : '')
        if (this.target && this.field && url) {
            this.target.form[this.field] = url
            Alpine.store('adm').notify('Media dipilih.')
        }
        this.close()
    },

    close() {
        this.show = false
        this.target = null
        this.field = ''
        this._callback = null
    },
})

// ── Login ─────────────────────────────────────────────────────────────────────
Alpine.data('loginPage', () => ({
    email: '', password: '', loading: false, error: '', showPass: false,
    async submit() {
        this.loading = true; this.error = ''
        try {
            const r = await api.post('/auth/login', { email: this.email, password: this.password })
            localStorage.setItem('ag_token', r.data.token)
            localStorage.setItem('ag_user', JSON.stringify(r.data.user))
            window.location.href = '/admin'
        } catch (e) { this.error = fmt.err(e, 'Email atau password salah.') }
        finally { this.loading = false }
    },
}))

// ── Dashboard ─────────────────────────────────────────────────────────────────
Alpine.data('dashboardPage', () => ({
    stats: [], recentRegs: [], pendingPayments: [], upcomingEvents: [],
    monthlyData: [], maxMonth: 1, regStatus: {}, loading: true,

    get donutGradient() {
        const t = this.regStatus.total || 1
        const a  = (this.regStatus.accepted  ?? 0) / t * 100
        const ab = a + (this.regStatus.submitted ?? 0) / t * 100
        const abc = ab + ((this.regStatus.document_review ?? 0) + (this.regStatus.verified ?? 0)) / t * 100
        const abcd = abc + (this.regStatus.rejected ?? 0) / t * 100
        return `conic-gradient(#22c55e ${a.toFixed(1)}%, #fbbf24 0 ${ab.toFixed(1)}%, #60a5fa 0 ${abc.toFixed(1)}%, #f87171 0 ${abcd.toFixed(1)}%, #e5e7eb 0)`
    },

    async load() {
        this.loading = true
        try {
            const r = await api.get('/admin/dashboard')
            const d = r.data?.data ?? r.data ?? {}

            this.regStatus = d.registrations ?? {}
            const pay = d.payments ?? {}

            this.stats = [
                {
                    label: 'Total Pendaftar',
                    value: d.registrations?.total ?? 0,
                    sub: `${d.registrations?.accepted ?? 0} diterima · ${d.registrations?.rejected ?? 0} ditolak`,
                    bg: 'bg-green-100',
                    action: 'registrations',
                    icon: '<svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                },
                {
                    label: 'Bukti Bayar Masuk',
                    value: pay.needs_verify ?? 0,
                    sub: `${pay.paid ?? 0} lunas · ${pay.pending ?? 0} menunggu`,
                    bg: 'bg-orange-100',
                    action: 'payments',
                    icon: '<svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                },
                {
                    label: 'Total Post',
                    value: d.posts ?? 0,
                    sub: 'artikel & berita',
                    bg: 'bg-purple-100',
                    action: 'posts',
                    icon: '<svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/></svg>',
                },
                {
                    label: 'Pesan Belum Dibaca',
                    value: d.unread_messages ?? 0,
                    sub: 'dari pengunjung',
                    bg: 'bg-blue-100',
                    action: 'messages',
                    icon: '<svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                },
            ]

            const monthly = d.registrations_by_month ?? []
            this.monthlyData = monthly
            this.maxMonth = Math.max(1, ...monthly.map(m => m.count))
            this.recentRegs      = d.recent_registrations ?? []
            this.pendingPayments = d.pending_payments ?? []
            this.upcomingEvents  = d.upcoming_events ?? []
        } catch (e) { console.error(e) }
        finally { this.loading = false }
    },

    fmtDate(v) {
        if (!v) return ''
        return new Date(v).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
    },
}))

// ── CRUD Factory ──────────────────────────────────────────────────────────────
function makeCrud(endpoint, defaultForm) {
    const pageName = endpoint.split('/')[0]
    return {
        items: [], meta: {}, loading: true, saving: false,
        page: 1, _loaded: false,
        showModal: false, modalMode: 'add',
        form: { ...defaultForm },
        editId: null,
        confirmId: null,
        formError: null,

        init() {
            if (this.$store.adm.page === pageName) {
                this._loaded = true; this.load()
            }
            this.$watch('$store.adm.page', p => {
                if (p === pageName && !this._loaded) { this._loaded = true; this.load() }
            })
        },

        async load(p) {
            if (p !== undefined) this.page = p
            this.loading = true
            try {
                const r = await api.get(`/admin/${endpoint}`, {
                    params: { page: this.page, per_page: 15 },
                })
                const raw = r.data
                this.items = Array.isArray(raw.data) ? raw.data : (Array.isArray(raw) ? raw : [])
                this.meta = raw.meta ?? {}
            } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
            finally { this.loading = false }
        },

        openAdd() {
            this.form = { ...defaultForm }
            this.editId = null
            this.modalMode = 'add'
            this.formError = null
            this.showModal = true
        },

        edit(item) {
            this.form = { ...defaultForm, ...item }
            this.editId = item.id
            this.modalMode = 'edit'
            this.formError = null
            this.showModal = true
        },

        async save() {
            this.saving = true; this.formError = null
            try {
                if (this.modalMode === 'edit') {
                    await api.put(`/admin/${endpoint}/${this.editId}`, this.form)
                    this.$store.adm.notify('Data berhasil diperbarui.')
                } else {
                    await api.post(`/admin/${endpoint}`, this.form)
                    this.$store.adm.notify('Data berhasil ditambahkan.')
                }
                this.showModal = false; await this.load()
            } catch (e) { this.formError = fmt.err(e) }
            finally { this.saving = false }
        },

        async remove(id) {
            const target = id ?? this.confirmId
            if (!target) return
            try {
                await api.delete(`/admin/${endpoint}/${target}`)
                this.$store.adm.notify('Data berhasil dihapus.')
                this.confirmId = null; await this.load()
            } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        },
    }
}

// ── Posts ─────────────────────────────────────────────────────────────────────
Alpine.data('postsPage', () => ({
    ...makeCrud('posts', {
        title: '', slug: '', content: '', thumbnail: '',
        type: 'news', category: '', post_status: 'Penting',
        summary: '', is_published: 0,
        event_start_at: '', event_end_at: '', event_location: '',
    }),
    categories: [],
    _loaded: false,

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const [pr, cr] = await Promise.all([
                api.get('/admin/posts', { params: { page: this.page, per_page: 15 } }),
                this.categories.length ? Promise.resolve(null) : api.get('/admin/categories', { params: { per_page: 100 } }),
            ])
            const raw = pr.data
            this.items = Array.isArray(raw.data) ? raw.data : []
            this.meta = raw.meta ?? {}
            if (cr) {
                const cats = cr.data?.data ?? cr.data ?? []
                this.categories = Array.isArray(cats) ? cats : []
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },
}))

// ── Categories ────────────────────────────────────────────────────────────────
Alpine.data('categoriesPage', () => ({ ...makeCrud('categories', { name: '', description: '' }), _loaded: false }))

// ── Programs ──────────────────────────────────────────────────────────────────
Alpine.data('programsPage', () => ({
    ...makeCrud('programs', {
        title: '', type: 'unggulan', subtitle: '', description: '', image: '',
        icon: 'BookOpen', badge: '', stats: '', features: '', order: 0, is_published: false,
    }),
    _loaded: false,
    edit(item) {
        this.form = { ...this.form, ...item, features: Array.isArray(item.features) ? item.features.join('\n') : (item.features ?? '') }
        this.editId = item.id
        this.modalMode = 'edit'
        this.formError = null
        this.showModal = true
    },
}))

// ── Facilities ────────────────────────────────────────────────────────────────
Alpine.data('facilitiesPage', () => ({
    ...makeCrud('facilities', {
        name: '', category: 'akademik', image: '', icon_name: '', short_desc: '',
        long_desc: '', capacity: '', specs: '', operational_hours: '', location: '',
        order: 0, is_featured: false, is_published: false,
    }),
    _loaded: false,
    edit(item) {
        this.form = { ...this.form, ...item, specs: Array.isArray(item.specs) ? item.specs.join('\n') : (item.specs ?? '') }
        this.editId = item.id
        this.modalMode = 'edit'
        this.formError = null
        this.showModal = true
    },
}))

// ── Teachers ──────────────────────────────────────────────────────────────────
Alpine.data('teachersPage', () => ({
    ...makeCrud('teachers', {
        name: '', position: '', subject: '', bio: '', photo: '', phone: '', email: '',
        category: 'mipa', education: '', philosophy: '', experience: '',
        tags: '', order: 1, is_active: 1, is_leadership: 0,
    }),
    _loaded: false,

    async downloadTemplate() {
        await downloadFile('/admin/teachers/template', 'template-guru.xlsx')
    },

    async importFile(files) {
        const file = files?.[0]
        if (!file) return
        const fd = new FormData()
        fd.append('file', file)
        try {
            const r = await api.post('/admin/teachers/import', fd)
            this.$store.adm.notify(r.data.message)
            if (r.data.errors?.length) r.data.errors.forEach(e => console.warn('[Import Guru]', e))
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Albums ────────────────────────────────────────────────────────────────────
Alpine.data('albumsPage', () => ({
    ...makeCrud('albums', { title: '', description: '', cover_image: '', date: '', is_published: 0 }),
    _loaded: false,
    photosModal: { show: false, albumId: null, albumTitle: '', medias: [], saving: false },

    openPhotos(item) {
        this.photosModal = { show: true, albumId: item.album_id ?? item.id, albumTitle: item.title, medias: [], saving: false }
        api.get(`/admin/albums/${item.album_id ?? item.id}`)
            .then(r => { this.photosModal.medias = r.data?.medias ?? [] })
            .catch(e => this.$store.adm.notify(fmt.err(e), 'error'))
    },

    openPhotosPicker() {
        const self = this
        Alpine.store('mediaPicker').open(null, null, (file) => {
            const id = file.media_id ?? file.id
            if (!self.photosModal.medias.find(m => (m.media_id ?? m.id) === id)) {
                self.photosModal.medias.push(file)
                self.savePhotos()
            } else {
                Alpine.store('adm').notify('Foto sudah ada di album ini.', 'error')
            }
        })
    },

    removePhoto(mediaId) {
        this.photosModal.medias = this.photosModal.medias.filter(m => (m.media_id ?? m.id) !== mediaId)
        this.savePhotos()
    },

    async savePhotos() {
        this.photosModal.saving = true
        try {
            const ids = this.photosModal.medias.map(m => m.media_id ?? m.id)
            await api.put(`/admin/albums/${this.photosModal.albumId}`, { medias: ids })
            this.$store.adm.notify('Foto album disimpan.')
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.photosModal.saving = false }
    },
}))

// ── Testimonials ──────────────────────────────────────────────────────────────
Alpine.data('testimonialsPage', () => ({
    ...makeCrud('testimonials', {
        alumnus_id: null,
        name: '', role: '', content: '', photo: '',
        university: '', major: '', graduation_year: '',
        rating: 5, is_published: 1, order: 0,
    }),
    alumniList: [],    // list of all alumni for the picker dropdown
    _loaded: false,

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const [tr, ar] = await Promise.all([
                api.get('/admin/testimonials', { params: { page: this.page, per_page: 15 } }),
                this.alumniList.length ? Promise.resolve(null) : api.get('/admin/alumni', { params: { per_page: 200 } }),
            ])
            const raw = tr.data
            this.items = Array.isArray(raw.data) ? raw.data : (Array.isArray(raw) ? raw : [])
            this.meta  = raw.meta ?? { total: raw.total ?? 0, current_page: raw.current_page ?? 1, last_page: raw.last_page ?? 1 }
            if (ar) {
                const list = ar.data?.data ?? ar.data ?? []
                this.alumniList = Array.isArray(list) ? list : []
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    // When an alumnus is selected in the picker, auto-fill empty fields
    onAlumnusChange() {
        if (!this.form.alumnus_id) return
        const a = this.alumniList.find(x => x.alumnus_id == this.form.alumnus_id || x.id == this.form.alumnus_id)
        if (!a) return
        if (!this.form.name)            this.form.name            = a.name           ?? ''
        if (!this.form.photo)           this.form.photo           = a.photo          ?? ''
        if (!this.form.university)      this.form.university      = a.current_institution ?? a.occupation ?? ''
        if (!this.form.major)           this.form.major           = a.major          ?? ''
        if (!this.form.graduation_year) this.form.graduation_year = a.graduation_year ?? ''
    },
}))

// ── Alumni ────────────────────────────────────────────────────────────────────
Alpine.data('alumniPage', () => ({
    ...makeCrud('alumni', { name: '', graduation_year: '', occupation: '', location: '', photo: '', story: '' }),
    _loaded: false,
}))

// ── Media ─────────────────────────────────────────────────────────────────────
Alpine.data('mediaPage', () => ({
    items: [], filtered: [], loading: true, uploading: false, uploadProgress: 0,
    confirmId: null, selected: null, filterType: 'all', _loaded: false,

    init() {
        if (this.$store.adm.page === 'media') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'media' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load() {
        this.loading = true
        try {
            const r = await api.get('/admin/medias', { params: { per_page: 200 } })
            const raw = r.data?.data ?? r.data ?? []
            this.items = Array.isArray(raw) ? raw : []
            this.filterItems()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    filterItems() {
        if (this.filterType === 'all') { this.filtered = this.items; return }
        this.filtered = this.items.filter(f => {
            const m = f.mime_type ?? ''
            if (this.filterType === 'image') return m.startsWith('image')
            if (this.filterType === 'video') return m.startsWith('video')
            if (this.filterType === 'document') return !m.startsWith('image') && !m.startsWith('video')
            return true
        })
    },

    selectFile(file) {
        this.selected = this.selected?.id === file.id ? null : file
    },

    async upload(fileList) {
        const files = Array.from(fileList)
        if (!files.length) return
        this.uploading = true; this.uploadProgress = 0
        try {
            for (let i = 0; i < files.length; i++) {
                const fd = new FormData()
                fd.append('file', files[i])
                await api.post('/admin/medias', fd)
                this.uploadProgress = Math.round(((i + 1) / files.length) * 100)
            }
            this.$store.adm.notify(`${files.length} file berhasil diupload.`)
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.uploading = false }
    },

    async remove(id) {
        const target = id ?? this.confirmId
        try {
            await api.delete(`/admin/medias/${target}`)
            this.$store.adm.notify('File dihapus.')
            this.confirmId = null; this.selected = null; await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    copyUrl(url) {
        navigator.clipboard?.writeText(url).then(() => this.$store.adm.notify('URL disalin!'))
    },
}))

// ── Settings ──────────────────────────────────────────────────────────────────
Alpine.data('settingsPage', () => ({
    form: {}, loading: true, saving: false, _loaded: false,
    ppdbFees: [],   // [{name:'', amount:0}]

    init() {
        if (this.$store.adm.page === 'settings') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'settings' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load() {
        this.loading = true
        try {
            const r = await api.get('/admin/settings')
            const raw = r.data?.data ?? r.data
            const rows = Array.isArray(raw)
                ? raw
                : Object.values(raw || {}).flat()
            this.form = rows.reduce((a, i) => ({ ...a, [i.key]: i.value }), {})
            // Parse fee items dari JSON string
            try { this.ppdbFees = JSON.parse(this.form.ppdb_payment_items || '[]') } catch { this.ppdbFees = [] }
            if (!this.form.ppdb_payment_mode)      this.form.ppdb_payment_mode      = 'full'
            if (!this.form.ppdb_installment_count) this.form.ppdb_installment_count = '2'
            if (!this.form.ppdb_installment_dp)    this.form.ppdb_installment_dp    = ''
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    addFee() {
        this.ppdbFees.push({ name: '', amount: '' })
    },

    removeFee(i) {
        this.ppdbFees.splice(i, 1)
    },

    get ppdbFeesTotal() {
        return this.ppdbFees.reduce((s, f) => s + (parseInt(f.amount) || 0), 0)
    },

    get ppdbInstallmentTerms() {
        return Math.max(1, parseInt(this.form.ppdb_installment_count || 2) - 1)
    },

    get ppdbInstallmentAmount() {
        const dp = parseInt(this.form.ppdb_installment_dp || 0)
        return Math.ceil((this.ppdbFeesTotal - dp) / this.ppdbInstallmentTerms)
    },

    fmtRp(v) {
        return new Intl.NumberFormat('id-ID').format(v || 0)
    },

    async save() {
        this.saving = true
        try {
            // Sync ppdbFees → form sebelum save
            this.form.ppdb_payment_items = JSON.stringify(
                this.ppdbFees.filter(f => f.name?.trim()).map(f => ({ name: f.name.trim(), amount: parseInt(f.amount) || 0 }))
            )

            const publicKeys = [
                'school_name', 'tagline', 'address', 'phone', 'email', 'whatsapp', 'npsn',
                'description', 'meta_description', 'meta_keywords',
                'school_vision', 'school_missions',
                'bank_name', 'bank_account_number', 'bank_account_name',
                'instagram', 'facebook', 'youtube', 'twitter',
                'logo', 'school_logo', 'favicon', 'hero_image', 'profile_image',
                'ppdb_open', 'ppdb_status', 'academic_year', 'ppdb_academic_year',
                'registration_fee', 'ppdb_registration_fee', 'quota',
                'ppdb_payment_items', 'ppdb_payment_mode',
                'ppdb_installment_count', 'ppdb_installment_dp',
                'maps_url', 'maps_embed_url', 'latitude', 'longitude',
                'homepage_hero_title', 'homepage_hero_subtitle',
                'footer_text', 'seo_title', 'seo_description',
                'stat_total_teachers', 'stat_total_new_students',
                'stat_total_students', 'stat_total_alumni',
            ]
            const visualKeys = ['logo', 'school_logo', 'favicon', 'hero_image', 'profile_image']
            const groupMap = {
                logo: 'visual', school_logo: 'visual', favicon: 'visual',
                hero_image: 'visual', profile_image: 'visual',
                school_name: 'general', tagline: 'general', footer_text: 'general', npsn: 'general',
                description: 'general', school_vision: 'general', school_missions: 'general',
                address: 'contact', phone: 'contact', email: 'contact',
                whatsapp: 'contact', maps_url: 'contact', maps_embed_url: 'contact',
                latitude: 'contact', longitude: 'contact',
                instagram: 'social', facebook: 'social', youtube: 'social', twitter: 'social',
                bank_name: 'payment', bank_account_number: 'payment', bank_account_name: 'payment',
                ppdb_open: 'ppdb', ppdb_status: 'ppdb', academic_year: 'ppdb',
                ppdb_academic_year: 'ppdb', registration_fee: 'ppdb',
                ppdb_registration_fee: 'ppdb', quota: 'ppdb',
                ppdb_payment_items: 'ppdb', ppdb_payment_mode: 'ppdb',
                ppdb_installment_count: 'ppdb', ppdb_installment_dp: 'ppdb',
                homepage_hero_title: 'homepage', homepage_hero_subtitle: 'homepage',
                seo_title: 'seo', seo_description: 'seo', meta_description: 'seo',
                meta_keywords: 'seo',
                stat_total_teachers: 'profile', stat_total_new_students: 'profile',
                stat_total_students: 'profile', stat_total_alumni: 'profile',
            }
            const settings = Object.entries(this.form).map(([key, value]) => ({
                key,
                value: value == null ? '' : String(value),
                type: visualKeys.includes(key) ? 'image' : 'text',
                group: groupMap[key] ?? 'general',
                is_public: publicKeys.includes(key),
            }))

            if (this.form.logo && !this.form.school_logo) {
                settings.push({
                    key: 'school_logo',
                    value: String(this.form.logo),
                    type: 'image',
                    group: 'visual',
                    is_public: true,
                })
            }

            await api.put('/admin/settings', { settings })
            this.$store.adm.notify('Pengaturan berhasil disimpan.')
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.saving = false }
    },
}))

// ── Form Builder ──────────────────────────────────────────────────────────────
Alpine.data('formBuilderPage', () => ({
    forms: [], loading: true, saving: false, activeForm: null,
    showAddModal: false, _loaded: false,
    newForm: { name: '', slug: '', type: 'ppdb' },

    init() {
        if (this.$store.adm.page === 'form-builder') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'form-builder' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load() {
        this.loading = true
        try {
            const r = await api.get('/admin/forms')
            this.forms = Array.isArray(r.data) ? r.data : (r.data?.data ?? [])
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    selectForm(f) {
        const form = JSON.parse(JSON.stringify(f))
        if (form.steps) {
            form.steps.forEach(step => {
                step._open = true
                ;(step.fields || []).forEach(field => {
                    if (field.options) field.options_raw = Array.isArray(field.options) ? field.options.join(', ') : field.options
                })
            })
        }
        this.activeForm = form
    },

    openAddForm() {
        this.newForm = { name: '', slug: '', type: 'ppdb' }
        this.showAddModal = true
    },

    async createForm() {
        if (!this.newForm.name || !this.newForm.slug) return this.$store.adm.notify('Nama dan slug wajib diisi.', 'error')
        try {
            const r = await api.post('/admin/forms', { ...this.newForm, fields: [], steps: [], is_active: 1 })
            this.$store.adm.notify('Form dibuat.')
            this.showAddModal = false
            await this.load()
            this.selectForm(r.data?.data ?? r.data)
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async saveFormInfo() {
        this.saving = true
        try {
            await api.put(`/admin/forms/${this.activeForm.form_id}`, {
                name: this.activeForm.name,
                slug: this.activeForm.slug,
                type: this.activeForm.type,
                description: this.activeForm.description,
                is_active: this.activeForm.is_active,
            })
            this.$store.adm.notify('Info form disimpan.')
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.saving = false }
    },

    async saveSteps() {
        this.saving = true
        try {
            // Normalize options sebelum simpan
            const steps = (this.activeForm.steps || []).map(step => ({
                label: step.label,
                short_label: step.short_label,
                fields: (step.fields || []).map(f => ({
                    key: f.key, label: f.label, type: f.type,
                    placeholder: f.placeholder || '',
                    required: Boolean(f.required),
                    options: f.options_raw ? f.options_raw.split(',').map(s => s.trim()) : (f.options || []),
                }))
            }))
            await api.put(`/admin/forms/${this.activeForm.form_id}`, { steps })
            this.$store.adm.notify('Langkah & field disimpan.')
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.saving = false }
    },

    async deleteForm() {
        if (!confirm(`Hapus form "${this.activeForm.name}"?`)) return
        try {
            await api.delete(`/admin/forms/${this.activeForm.form_id}`)
            this.$store.adm.notify('Form dihapus.')
            this.activeForm = null
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    addStep() {
        if (!this.activeForm.steps) this.activeForm.steps = []
        this.activeForm.steps.push({ label: 'Langkah Baru', short_label: 'Baru', fields: [], _open: true })
    },

    removeStep(si) {
        if (!confirm('Hapus langkah ini beserta semua fieldnya?')) return
        this.activeForm.steps.splice(si, 1)
    },

    moveStep(si, dir) {
        const arr = this.activeForm.steps
        const ni = si + dir
        if (ni < 0 || ni >= arr.length) return
        ;[arr[si], arr[ni]] = [arr[ni], arr[si]]
    },

    addField(step) {
        if (!step.fields) step.fields = []
        step.fields.push({ key: '', label: '', type: 'text', placeholder: '', required: false, options: [], options_raw: '' })
    },

    removeField(step, fi) {
        step.fields.splice(fi, 1)
    },

    moveField(step, fi, dir) {
        const arr = step.fields
        const ni = fi + dir
        if (ni < 0 || ni >= arr.length) return
        ;[arr[fi], arr[ni]] = [arr[ni], arr[fi]]
    },

    async activateForm(f) {
        try {
            await api.patch(`/admin/forms/${f.form_id}/activate`)
            this.$store.adm.notify(`Form "${f.name}" diaktifkan. Form lain tipe ${f.type.toUpperCase()} dinonaktifkan.`)
            await this.load()
            // Sync activeForm jika yang sedang diedit
            if (this.activeForm) {
                const updated = this.forms.find(x => x.form_id === this.activeForm.form_id)
                if (updated) this.activeForm.is_active = updated.is_active
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Students ──────────────────────────────────────────────────────────────────
Alpine.data('studentsPage', () => ({
    items: [], meta: {}, loading: true, saving: false,
    showModal: false, modalMode: 'add', editId: null,
    confirmId: null, formError: null, _loaded: false,
    filters: { search: '', grade_level: '', status: '', academic_year: '' },
    form: {
        name: '', nis: '', nisn: '', nik: '', gender: '',
        birth_place: '', birth_date: '',
        phone: '', email: '', address: '', photo: '',
        grade_level: '', major: '', academic_year: '', status: 'active',
        parent_name: '', parent_phone: '',
        previous_school: '', notes: '', order: 0,
    },

    init() {
        if (this.$store.adm.page === 'students') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'students' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load(p) {
        this.loading = true
        try {
            const r = await api.get('/admin/students', {
                params: { page: p ?? 1, per_page: 20, ...this.filters },
            })
            const raw = r.data
            this.items = Array.isArray(raw.data) ? raw.data : (Array.isArray(raw) ? raw : [])
            this.meta  = raw.meta ?? {}
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    openAdd() {
        this.form = {
            name: '', nis: '', nisn: '', nik: '', gender: '',
            birth_place: '', birth_date: '',
            phone: '', email: '', address: '', photo: '',
            grade_level: '', major: '', academic_year: '', status: 'active',
            parent_name: '', parent_phone: '',
            previous_school: '', notes: '', order: 0,
        }
        this.editId = null; this.modalMode = 'add'
        this.formError = null; this.showModal = true
    },

    edit(item) {
        this.form = { ...this.form, ...item }
        this.editId = item.student_id; this.modalMode = 'edit'
        this.formError = null; this.showModal = true
    },

    async save() {
        this.saving = true; this.formError = null
        try {
            if (this.modalMode === 'edit') {
                await api.put(`/admin/students/${this.editId}`, this.form)
                this.$store.adm.notify('Data murid diperbarui.')
            } else {
                await api.post('/admin/students', this.form)
                this.$store.adm.notify('Data murid ditambahkan.')
            }
            this.showModal = false; await this.load()
        } catch (e) { this.formError = fmt.err(e) }
        finally { this.saving = false }
    },

    async remove(id) {
        try {
            await api.delete(`/admin/students/${id}`)
            this.$store.adm.notify('Data murid dihapus.')
            this.confirmId = null; await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async downloadTemplate() {
        await downloadFile('/admin/students/template', 'template-murid.xlsx')
    },

    async importFile(files) {
        const file = files?.[0]
        if (!file) return
        const fd = new FormData()
        fd.append('file', file)
        try {
            const r = await api.post('/admin/students/import', fd)
            this.$store.adm.notify(r.data.message)
            if (r.data.errors?.length) r.data.errors.forEach(e => console.warn('[Import Murid]', e))
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Academic Calendar ─────────────────────────────────────────────────────────
Alpine.data('academicCalendarPage', () => ({
    items: [], loading: true, showModal: false, saving: false,
    editId: null, yearFilter: '', _loaded: false,
    form: { title: '', description: '', start_date: '', end_date: '', category: 'Akademik', color: 'green', academic_year: '', is_published: 1 },

    init() {
        if (this.$store.adm.page === 'academic-calendars') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'academic-calendars' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load() {
        this.loading = true
        try {
            const params = this.yearFilter ? { academic_year: this.yearFilter } : {}
            const r = await api.get('/admin/academic-calendars', { params })
            this.items = Array.isArray(r.data) ? r.data : (r.data?.data ?? [])
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    openAdd() {
        this.editId = null
        this.form = { title: '', description: '', start_date: '', end_date: '', category: 'Akademik', color: 'green', academic_year: '', is_published: 1 }
        this.showModal = true
    },

    openEdit(item) {
        this.editId = item.calendar_id
        this.form = {
            title: item.title || '',
            description: item.description || '',
            start_date: item.start_date ? item.start_date.split('T')[0] : '',
            end_date: item.end_date ? item.end_date.split('T')[0] : '',
            category: item.category || 'Akademik',
            color: item.color || 'green',
            academic_year: item.academic_year || '',
            is_published: item.is_published ? 1 : 0,
        }
        this.showModal = true
    },

    async save() {
        if (!this.form.title || !this.form.start_date) {
            return this.$store.adm.notify('Judul dan tanggal mulai wajib diisi.', 'error')
        }
        this.saving = true
        try {
            if (this.editId) {
                await api.put(`/admin/academic-calendars/${this.editId}`, this.form)
            } else {
                await api.post('/admin/academic-calendars', this.form)
            }
            this.$store.adm.notify('Agenda disimpan.')
            this.showModal = false
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.saving = false }
    },

    async remove(id) {
        if (!confirm('Hapus agenda ini?')) return
        try {
            await api.delete(`/admin/academic-calendars/${id}`)
            this.$store.adm.notify('Agenda dihapus.')
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Registrations ─────────────────────────────────────────────────────────────
Alpine.data('registrationsPage', () => ({
    items: [], meta: {}, loading: true, statusFilter: '', page: 1,
    detail: null,

    init() {
        if (this.$store.adm.page === 'registrations') this.load()
        this.$watch('$store.adm.page', p => { if (p === 'registrations') this.load(1) })
    },

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const r = await api.get('/admin/registrations', {
                params: { page: this.page, per_page: 20, status: this.statusFilter },
            })
            const raw = r.data
            this.items = Array.isArray(raw.data) ? raw.data : []
            this.meta = raw.meta ?? {
                total: raw.total ?? 0,
                current_page: raw.current_page ?? 1,
                last_page: raw.last_page ?? 1,
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    async openDetail(item) {
        try {
            const r = await api.get(`/admin/registrations/${item.registration_id}`)
            this.detail = r.data?.data ?? r.data
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async updateStatus(status) {
        if (!this.detail) return
        try {
            await api.patch(`/admin/registrations/${this.detail.registration_id}/status`, { status })
            this.$store.adm.notify('Status pendaftar diperbarui.')
            this.detail = { ...this.detail, status }
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Payments ──────────────────────────────────────────────────────────────────
Alpine.data('paymentsPage', () => ({
    items: [], meta: {}, loading: true, statusFilter: '', proofFilter: false,
    search: '', searchTimer: null,
    page: 1, detail: null, paidInput: 0,
    summary: { paid: 0, partial: 0, pending: 0, total_amount: 0 },

    init() {
        if (this.$store.adm.page === 'payments') this.load()
        this.$watch('$store.adm.page', p => { if (p === 'payments') this.load(1) })
    },

    onSearch() {
        clearTimeout(this.searchTimer)
        this.searchTimer = setTimeout(() => this.load(1), 350)
    },

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const params = {
                page: this.page, per_page: 20,
                status: this.statusFilter || undefined,
                search: this.search.trim() || undefined,
            }
            if (this.proofFilter) params.has_proof = 1
            const r = await api.get('/admin/payments', { params })
            const raw = r.data

            this.items = Array.isArray(raw.data) ? raw.data : []

            // Handle both Laravel paginator (flat) and API Resource (meta-wrapped)
            this.meta = raw.meta ?? {
                total:        raw.total        ?? 0,
                current_page: raw.current_page ?? 1,
                last_page:    raw.last_page    ?? 1,
            }

            if (raw.summary) {
                this.summary = raw.summary
            } else {
                const paid    = this.items.filter(i => i.status === 'paid')
                const partial = this.items.filter(i => i.status === 'partial')
                this.summary = {
                    paid:         paid.length,
                    partial:      partial.length,
                    pending:      this.items.filter(i => i.status === 'pending').length,
                    // parseFloat: amount comes as decimal string "500000.00" from API
                    total_amount: [...paid, ...partial].reduce((s, i) => s + parseFloat(i.amount ?? 0), 0),
                }
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    async openDetail(item) {
        if (this.detail?.payment_id === item.payment_id) { this.detail = null; return }
        this.detail = item
        this.paidInput = parseFloat(item.paid_amount ?? 0)
        try {
            const r = await api.get(`/admin/payments/${item.payment_id ?? item.id}`)
            this.detail = r.data
            this.paidInput = parseFloat(this.detail.paid_amount ?? 0)
        } catch (e) {}
    },

    // ── payment actions ──────────────────────────────────────────────────────
    async confirmPaid(id, quickAmount = null) {
        // quickAmount: passed from quick-action button (bypasses paidInput)
        const total = quickAmount ?? parseFloat(this.detail?.amount ?? 0)
        const paid  = quickAmount !== null ? total : (this.paidInput > 0 ? this.paidInput : total)
        try {
            const r = await api.patch(`/admin/payments/${id}`, {
                status: 'paid',
                paid_amount: paid,
            })
            this.$store.adm.notify('Pembayaran dikonfirmasi lunas.')
            this.detail = r.data?.data ?? this.detail
            this.paidInput = parseFloat(this.detail?.paid_amount ?? 0)
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async confirmPartial(id) {
        try {
            const r = await api.patch(`/admin/payments/${id}`, {
                status: 'partial',
                paid_amount: this.paidInput,
            })
            this.$store.adm.notify('Pembayaran sebagian dikonfirmasi.')
            this.detail = r.data?.data ?? this.detail
            this.paidInput = parseFloat(this.detail?.paid_amount ?? 0)
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async rejectPayment(id) {
        const reason = prompt('Alasan penolakan (opsional):') ?? ''
        if (reason === null) return
        try {
            const r = await api.patch(`/admin/payments/${id}/reject`, { reason })
            this.$store.adm.notify('Bukti pembayaran ditolak.')
            this.detail = r.data?.data ?? this.detail
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    // ── accept student ───────────────────────────────────────────────────────
    async acceptStudent() {
        const regId = this.detail?.registration_id ?? this.detail?.registration?.registration_id
        if (!regId) return this.$store.adm.notify('ID pendaftaran tidak ditemukan.', 'error')
        if (!confirm('Terima pendaftar ini sebagai murid baru?')) return
        try {
            await api.patch(`/admin/registrations/${regId}/status`, { status: 'accepted' })
            this.$store.adm.notify('Pendaftar diterima! Data murid berhasil disinkronkan.')
            const r = await api.get(`/admin/payments/${this.detail.payment_id}`)
            this.detail = r.data
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Messages ──────────────────────────────────────────────────────────────────
Alpine.data('messagesPage', () => ({
    // ── Tab state ─────────────────────────────────────────────────────────────
    tab: 'public',          // 'public' | 'internal'
    internalTab: 'inbox',   // 'inbox' | 'sent'

    // ── Public messages state ──────────────────────────────────────────────────
    pubItems: [], pubMeta: {}, pubLoading: true, pubPage: 1,
    pubDetail: null, pubConfirmId: null, pubSearch: '',

    // ── Internal messages state ────────────────────────────────────────────────
    intItems: [], intMeta: {}, intLoading: false, intPage: 1,
    intDetail: null, intConfirmId: null,
    adminList: [],   // dropdown penerima saat compose

    // ── Compose state ─────────────────────────────────────────────────────────
    showCompose: false, composeSaving: false, composeError: null,
    composeForm: { receiver_id: '', subject: '', body: '' },

    _loaded: false,

    init() {
        if (this.$store.adm.page === 'messages') { this._loaded = true; this.loadPublic() }
        this.$watch('$store.adm.page', p => {
            if (p === 'messages' && !this._loaded) { this._loaded = true; this.loadPublic() }
        })
        this.$watch('tab', t => {
            if (t === 'internal' && this.intItems.length === 0) this.loadInternal()
        })
        this.$watch('internalTab', () => {
            this.intPage = 1; this.intDetail = null; this.loadInternal()
        })
    },

    // ── Public tab ────────────────────────────────────────────────────────────
    async loadPublic(p) {
        if (p !== undefined) this.pubPage = p
        this.pubLoading = true
        try {
            const r = await api.get('/admin/messages', { params: { page: this.pubPage, per_page: 20 } })
            const raw = r.data
            this.pubItems = Array.isArray(raw.data) ? raw.data : []
            this.pubMeta  = raw.meta ?? { total: raw.total ?? 0, current_page: raw.current_page ?? 1, last_page: raw.last_page ?? 1 }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.pubLoading = false }
    },

    openPubDetail(item) {
        this.pubDetail = item
        if (!item.is_read) {
            api.get(`/admin/messages/${item.id}`).catch(() => {})
            const idx = this.pubItems.findIndex(m => m.id === item.id)
            if (idx >= 0) this.pubItems[idx] = { ...item, is_read: true }
        }
    },

    async removePub(id) {
        try {
            await api.delete(`/admin/messages/${id}`)
            this.$store.adm.notify('Pesan dihapus.')
            if (this.pubDetail?.id === id) this.pubDetail = null
            this.pubConfirmId = null
            await this.loadPublic()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    // ── Internal tab ──────────────────────────────────────────────────────────
    async loadInternal(p) {
        if (p !== undefined) this.intPage = p
        this.intLoading = true
        try {
            const endpoint = this.internalTab === 'sent'
                ? '/admin/internal-messages/sent'
                : '/admin/internal-messages/inbox'
            const [mr, ar] = await Promise.all([
                api.get(endpoint, { params: { page: this.intPage, per_page: 20 } }),
                this.adminList.length ? Promise.resolve(null) : api.get('/admin/internal-messages/admins'),
            ])
            const raw = mr.data
            this.intItems = Array.isArray(raw.data) ? raw.data : []
            this.intMeta  = raw.meta ?? { total: raw.total ?? 0, current_page: raw.current_page ?? 1, last_page: raw.last_page ?? 1 }
            if (ar) this.adminList = ar.data ?? []
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.intLoading = false }
    },

    async openIntDetail(item) {
        this.intDetail = item
        if (!item.is_read && this.internalTab === 'inbox') {
            try {
                await api.get(`/admin/internal-messages/${item.id}`)
                const idx = this.intItems.findIndex(m => m.id === item.id)
                if (idx >= 0) this.intItems[idx] = { ...item, is_read: true }
                // update sidebar badge
                if (this.$store.adm.internalUnread > 0) this.$store.adm.internalUnread--
            } catch {}
        }
    },

    async removeInt(id) {
        try {
            await api.delete(`/admin/internal-messages/${id}`)
            this.$store.adm.notify('Pesan dihapus.')
            if (this.intDetail?.id === id) this.intDetail = null
            this.intConfirmId = null
            await this.loadInternal()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    // ── Compose ───────────────────────────────────────────────────────────────
    openCompose() {
        this.composeForm = { receiver_id: '', subject: '', body: '' }
        this.composeError = null
        this.showCompose = true
        if (this.adminList.length === 0) {
            api.get('/admin/internal-messages/admins').then(r => { this.adminList = r.data ?? [] }).catch(() => {})
        }
    },

    async sendMessage() {
        if (!this.composeForm.body.trim()) { this.composeError = 'Isi pesan tidak boleh kosong.'; return }
        this.composeSaving = true; this.composeError = null
        try {
            await api.post('/admin/internal-messages', {
                receiver_id: this.composeForm.receiver_id || null,
                subject:     this.composeForm.subject || null,
                body:        this.composeForm.body,
            })
            this.$store.adm.notify('Pesan terkirim.')
            this.showCompose = false
            // Reload sent if on sent tab, else just close
            if (this.internalTab === 'sent') await this.loadInternal()
        } catch (e) { this.composeError = fmt.err(e) }
        finally { this.composeSaving = false }
    },

    // ── Helpers ───────────────────────────────────────────────────────────────
    fmtMsgDate(v) {
        if (!v) return ''
        const d = new Date(v)
        const now = new Date()
        const diffDays = Math.floor((now - d) / 86400000)
        if (diffDays === 0) return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
        if (diffDays < 7)  return d.toLocaleDateString('id-ID', { weekday: 'short' })
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })
    },
}))

// ── Users ─────────────────────────────────────────────────────────────────────
Alpine.data('usersPage', () => ({
    ...makeCrud('users', { name: '', email: '', password: '', role: 'admin' }),
    showPass: false, _loaded: false,

    edit(item) {
        this.form = { name: item.name, email: item.email, password: '', role: item.role }
        this.editId = item.id
        this.modalMode = 'edit'
        this.formError = null
        this.showModal = true
    },
}))

// ── Boot ──────────────────────────────────────────────────────────────────────
window.Alpine = Alpine
Alpine.start()
