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

window.uploadImageFor = uploadImageFor
window.openMediaPickerFor = openMediaPickerFor

// ── Global Store ──────────────────────────────────────────────────────────────
Alpine.store('adm', {
    user: null, page: 'dashboard',
    toast: { show: false, msg: '', type: 'success' },
    _timer: null,

    boot() {
        const token = localStorage.getItem('ag_token')
        if (!token) { window.location.href = '/admin/login'; return }
        this.user = JSON.parse(localStorage.getItem('ag_user') || '{}')
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

    async open(target, field) {
        this.target = target
        this.field = field
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
    stats: [], recentRegs: [], recentMsgs: [], loading: true,

    async load() {
        this.loading = true
        try {
            const [dashR, msgR] = await Promise.all([
                api.get('/admin/dashboard'),
                api.get('/admin/messages', { params: { per_page: 5 } }),
            ])
            const d = dashR.data?.data ?? dashR.data ?? {}

            this.stats = [
                {
                    label: 'Total Pendaftar',
                    value: d.registrations?.total ?? 0,
                    sub: `${d.registrations?.accepted ?? 0} diterima`,
                    trendUp: true,
                    bg: 'bg-green-100',
                    icon: '<svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                },
                {
                    label: 'Pembayaran Lunas',
                    value: d.payments?.paid ?? 0,
                    sub: `${d.payments?.pending ?? 0} menunggu`,
                    trendUp: true,
                    bg: 'bg-blue-100',
                    icon: '<svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                },
                {
                    label: 'Total Post',
                    value: d.posts ?? 0,
                    sub: 'artikel & berita',
                    trendUp: false,
                    bg: 'bg-purple-100',
                    icon: '<svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/></svg>',
                },
                {
                    label: 'Pesan Belum Dibaca',
                    value: d.unread_messages ?? 0,
                    sub: 'dari pengunjung',
                    trendUp: false,
                    bg: 'bg-yellow-100',
                    icon: '<svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                },
            ]
            this.recentRegs = d.recent_registrations ?? []
            const msgs = msgR.data?.data ?? msgR.data ?? []
            this.recentMsgs = Array.isArray(msgs) ? msgs.slice(0, 5) : []
        } catch (e) { console.error(e) }
        finally { this.loading = false }
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

// ── Pages CMS ─────────────────────────────────────────────────────────────────
Alpine.data('pagesCmsPage', () => ({
    ...makeCrud('pages', { title: '', slug: '', content: '', is_published: 0, meta_description: '' }),
    _loaded: false,

    // Override init: store page key is 'pages-cms', endpoint is 'pages'
    init() {
        if (this.$store.adm.page === 'pages-cms') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'pages-cms' && !this._loaded) { this._loaded = true; this.load() }
        })
    },
}))

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
}))

// ── Albums ────────────────────────────────────────────────────────────────────
Alpine.data('albumsPage', () => ({
    ...makeCrud('albums', { title: '', description: '', cover_image: '', date: '', is_published: 0 }),
    _loaded: false,
}))

// ── Testimonials ──────────────────────────────────────────────────────────────
Alpine.data('testimonialsPage', () => ({
    ...makeCrud('testimonials', {
        name: '', role: '', content: '', photo: '',
        university: '', major: '', graduation_year: '',
        rating: 5, is_published: 1, order: 0,
    }),
    _loaded: false,
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
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    async save() {
        this.saving = true
        try {
            const publicKeys = [
                'school_name', 'tagline', 'address', 'phone', 'email', 'whatsapp', 'npsn',
                'description', 'meta_description', 'meta_keywords',
                'school_vision', 'school_missions',
                'instagram', 'facebook', 'youtube', 'twitter',
                'logo', 'school_logo', 'favicon', 'hero_image', 'profile_image',
                'ppdb_open', 'academic_year', 'registration_fee', 'quota',
                'maps_url', 'maps_embed_url', 'latitude', 'longitude',
            ]
            const visualKeys = ['logo', 'school_logo', 'favicon', 'hero_image', 'profile_image']
            const settings = Object.entries(this.form).map(([key, value]) => ({
                key,
                value: value == null ? '' : String(value),
                type: visualKeys.includes(key) ? 'image' : 'text',
                group: visualKeys.includes(key) ? 'visual' : 'general',
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
    items: [], meta: {}, loading: true, statusFilter: '', page: 1, _loaded: false,
    detail: null,

    init() {
        if (this.$store.adm.page === 'registrations') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'registrations' && !this._loaded) { this._loaded = true; this.load() }
        })
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
            this.meta = raw.meta ?? {}
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    async openDetail(item) {
        try {
            const r = await api.get(`/admin/registrations/${item.id}`)
            this.detail = r.data?.data ?? r.data
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },

    async updateStatus(status) {
        if (!this.detail) return
        try {
            await api.patch(`/admin/registrations/${this.detail.id}/status`, { status })
            this.$store.adm.notify('Status pendaftar diperbarui.')
            this.detail = { ...this.detail, status }
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Payments ──────────────────────────────────────────────────────────────────
Alpine.data('paymentsPage', () => ({
    items: [], meta: {}, loading: true, statusFilter: '', page: 1, _loaded: false,
    summary: { paid: 0, pending: 0, total_amount: 0 },

    init() {
        if (this.$store.adm.page === 'payments') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'payments' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const r = await api.get('/admin/payments', {
                params: { page: this.page, per_page: 20, status: this.statusFilter },
            })
            const raw = r.data
            this.items = Array.isArray(raw.data) ? raw.data : []
            this.meta = raw.meta ?? {}
            if (raw.summary) this.summary = raw.summary
            else {
                // compute from items if no summary from API
                const paid = this.items.filter(i => i.status === 'paid')
                this.summary = {
                    paid: paid.length,
                    pending: this.items.filter(i => i.status === 'pending').length,
                    total_amount: paid.reduce((s, i) => s + (i.amount ?? 0), 0),
                }
            }
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    async confirmPaid(id) {
        try {
            await api.patch(`/admin/payments/${id}`, { status: 'paid' })
            this.$store.adm.notify('Pembayaran dikonfirmasi lunas.')
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
    },
}))

// ── Messages ──────────────────────────────────────────────────────────────────
Alpine.data('messagesPage', () => ({
    items: [], meta: {}, loading: true, page: 1, _loaded: false,
    detail: null, confirmId: null, search: '',

    init() {
        if (this.$store.adm.page === 'messages') { this._loaded = true; this.load() }
        this.$watch('$store.adm.page', p => {
            if (p === 'messages' && !this._loaded) { this._loaded = true; this.load() }
        })
    },

    async load(p) {
        if (p !== undefined) this.page = p
        this.loading = true
        try {
            const r = await api.get('/admin/messages', { params: { page: this.page, per_page: 20 } })
            const raw = r.data
            this.items = Array.isArray(raw.data) ? raw.data : []
            this.meta = raw.meta ?? {}
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
        finally { this.loading = false }
    },

    filterMessages() {
        // client-side search (items already loaded)
        // reload with search if backend supports it, else filter locally
    },

    openDetail(item) {
        this.detail = item
        if (!item.is_read) {
            api.get(`/admin/messages/${item.id}`).catch(() => {})
            const idx = this.items.findIndex(m => m.id === item.id)
            if (idx >= 0) this.items[idx] = { ...item, is_read: true }
        }
    },

    async remove(id) {
        const target = id ?? this.confirmId
        try {
            await api.delete(`/admin/messages/${target}`)
            this.$store.adm.notify('Pesan dihapus.')
            if (this.detail?.id === target) this.detail = null
            this.confirmId = null
            await this.load()
        } catch (e) { this.$store.adm.notify(fmt.err(e), 'error') }
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
