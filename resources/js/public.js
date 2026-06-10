import Alpine from 'alpinejs'
import axios from 'axios'

window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// ─── Global Store ─────────────────────────────────────────────────────────────
Alpine.store('pub', {
    tab: 'home',
    settings: {},
    menuOpen: false,
    loading: false,

    init() {
        // Hash-based navigation
        const hash = location.hash.slice(1)
        const valid = ['home','profil','program','pengajar','fasilitas','kontak','ppdb']
        if (valid.includes(hash)) this.tab = hash

        window.addEventListener('popstate', () => {
            const h = location.hash.slice(1)
            if (valid.includes(h)) this.tab = h
        })

        this.loadSettings()
    },

    async loadSettings() {
        try {
            const r = await axios.get('/api/settings')
            this.settings = r.data ?? {}
        } catch {}
    },

    go(t) {
        this.tab = t
        this.menuOpen = false
        window.scrollTo({ top: 0, behavior: 'smooth' })
        history.pushState(null, '', '#' + t)
    },

    get schoolName()  { return this.settings.school_name  ?? 'SMA Al-Ghazaly Bogor' },
    get schoolLogo()  { return this.settings.school_logo  ?? '' },
    get schoolPhone() { return this.settings.phone        ?? '' },
    get schoolEmail() { return this.settings.email        ?? '' },
    get schoolAddress(){ return this.settings.address     ?? '' },
})

// ─── Home Page ─────────────────────────────────────────────────────────────────
Alpine.data('homePage', () => ({
    announcements: [],
    events: [],
    articles: [],
    testimonials: [],
    loading: true,
    _loaded: false,

    // Quick PPDB status check
    statusCode: '',
    statusResult: null,
    statusLoading: false,

    selectedPost: null,

    init() {
        if (this.$store.pub.tab === 'home') this._load()
        this.$watch('$store.pub.tab', t => { if (t === 'home' && !this._loaded) this._load() })
    },

    async _load() {
        if (this._loaded) return
        this._loaded = true
        this.loading = true
        try {
            const [ann, evt, art, tes] = await Promise.all([
                axios.get('/api/posts', { params: { type: 'news',    per_page: 6 } }),
                axios.get('/api/posts', { params: { type: 'event',   per_page: 6 } }),
                axios.get('/api/posts', { params: { type: 'article', per_page: 3 } }),
                axios.get('/api/testimonials'),
            ])
            this.announcements = ann.data?.data ?? ann.data ?? []
            this.events        = evt.data?.data ?? evt.data ?? []
            this.articles      = art.data?.data ?? art.data ?? []
            this.testimonials  = tes.data?.data ?? tes.data ?? []
        } catch (e) { console.error('homePage load:', e) }
        finally { this.loading = false }
    },

    async checkStatus() {
        if (!this.statusCode.trim()) return
        this.statusLoading = true
        this.statusResult  = null
        try {
            const r = await axios.get('/api/registrations/' + this.statusCode.trim() + '/status')
            this.statusResult = { found: true, ...r.data }
        } catch {
            this.statusResult = { found: false }
        } finally { this.statusLoading = false }
    },

    statusBadge(s) {
        const m = { submitted:'Menunggu Verifikasi', document_review:'Review Dokumen',
                    verified:'Terverifikasi', accepted:'Diterima', rejected:'Ditolak' }
        return m[s] ?? s
    },

    statusColor(s) {
        const m = { submitted:'bg-yellow-100 text-yellow-800', document_review:'bg-blue-100 text-blue-800',
                    verified:'bg-green-100 text-green-800', accepted:'bg-emerald-100 text-emerald-800',
                    rejected:'bg-red-100 text-red-800' }
        return m[s] ?? 'bg-gray-100 text-gray-700'
    },

    fmtDate(d) {
        if (!d) return ''
        return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })
    },

    fmtDateShort(d) {
        if (!d) return ''
        return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
    },

    excerpt(text, len = 120) {
        if (!text) return ''
        return text.length > len ? text.slice(0, len) + '…' : text
    },
}))

// ─── Profil Page ───────────────────────────────────────────────────────────────
Alpine.data('profilPage', () => ({
    _loaded: false,
    init() {
        if (this.$store.pub.tab === 'profil') this._loaded = true
        this.$watch('$store.pub.tab', t => { if (t === 'profil') this._loaded = true })
    },
}))

// ─── Program Page ──────────────────────────────────────────────────────────────
Alpine.data('programPage', () => ({
    activeProgram: null,
    programs: [
        {
            id: 1,
            icon: '📖',
            title: 'Program Tahfidz',
            subtitle: 'Hafalan Al-Quran',
            color: '#019342',
            desc: 'Program unggulan hafalan Al-Quran dengan bimbingan para hafidz berpengalaman. Siswa ditargetkan menyelesaikan minimal 5 juz selama 3 tahun.',
            features: ['Target 5–30 Juz selama SMA', 'Bimbingan Hafidz bersertifikat', 'Ujian tahsin berkala', 'Sertifikat kelulusan tahfidz'],
            tag: 'Unggulan',
        },
        {
            id: 2,
            icon: '🎓',
            title: 'Jalur PTN',
            subtitle: 'Pathway Perguruan Tinggi Negeri',
            color: '#191654',
            desc: 'Program intensif persiapan masuk Perguruan Tinggi Negeri terbaik. Didukung tutor berpengalaman dan materi latihan UTBK/SNBT terkini.',
            features: ['Bimbel intensif UTBK/SNBT', 'Try out berkala', 'Konseling pilihan jurusan', 'Alumni di UI, ITB, UGM, dsb.'],
            tag: 'Akademik',
        },
        {
            id: 3,
            icon: '🔬',
            title: 'Sains & Teknologi',
            subtitle: 'Science & Technology',
            color: '#0369a1',
            desc: 'Pengembangan kemampuan sains dan teknologi dengan laboratorium modern. Mendorong inovasi dan riset sedari bangku SMA.',
            features: ['Lab Fisika, Kimia & Biologi modern', 'Lab Komputer & Coding', 'Olimpiade Sains Nasional', 'Proyek riset mandiri'],
            tag: 'Inovasi',
        },
        {
            id: 4,
            icon: '🌟',
            title: 'Leadership',
            subtitle: 'Pembentukan Karakter',
            color: '#b45309',
            desc: 'Pembentukan karakter pemimpin Islami yang berintegritas. Melalui OSIS, ekstrakurikuler, dan kegiatan sosial kemasyarakatan.',
            features: ['OSIS & MPK aktif', 'Pramuka & PMR', 'Kegiatan sosial & dakwah', 'Seminar kepemimpinan'],
            tag: 'Karakter',
        },
    ],
    faqs: [
        { q: 'Apakah program tahfidz wajib diikuti semua siswa?', a: 'Ya, program tahfidz merupakan salah satu keunggulan utama SMA Al-Ghazaly dan diikuti oleh seluruh siswa dengan target hafalan sesuai kemampuan masing-masing.' },
        { q: 'Berapa jam per hari dialokasikan untuk tahfidz?', a: 'Sekitar 1–2 jam per hari, dilaksanakan di pagi hari sebelum kegiatan belajar reguler dimulai.' },
        { q: 'Bagaimana track record siswa masuk PTN?', a: 'Lebih dari 70% lulusan kami berhasil masuk PTN terkemuka setiap tahunnya, termasuk UI, ITB, IPB, UGM, dan Universitas Islam Negeri.' },
        { q: 'Apakah ada ekstrakurikuler yang tersedia?', a: 'Tersedia lebih dari 15 ekstrakurikuler meliputi Pramuka, PMR, Rohis, Basket, Futsal, English Club, Jurnalistik, dan lain-lain.' },
    ],
    openFaq: null,
}))

// ─── Pengajar Page ─────────────────────────────────────────────────────────────
Alpine.data('pengajarPage', () => ({
    teachers: [],
    loading: true,
    _loaded: false,
    search: '',
    filterCat: 'semua',
    selectedTeacher: null,

    categories: [
        { key: 'semua',          label: 'Semua Guru' },
        { key: 'imtak',          label: 'Imtak & Keagamaan' },
        { key: 'mipa',           label: 'MIPA & Teknologi' },
        { key: 'social-english', label: 'IPS & Bahasa' },
        { key: 'bk-staf',        label: 'Konseling & Staf' },
    ],

    init() {
        if (this.$store.pub.tab === 'pengajar') this._load()
        this.$watch('$store.pub.tab', t => { if (t === 'pengajar' && !this._loaded) this._load() })
    },

    async _load() {
        if (this._loaded) return
        this._loaded = true
        this.loading = true
        try {
            const r = await axios.get('/api/teachers')
            this.teachers = r.data ?? []
        } catch (e) { console.error('pengajarPage:', e) }
        finally { this.loading = false }
    },

    get leadership() {
        return this.teachers.filter(t => t.is_leadership == 1 || t.is_leadership === true)
    },

    get filtered() {
        let list = this.teachers
        if (this.filterCat !== 'semua') list = list.filter(t => t.category === this.filterCat)
        if (this.search.trim()) {
            const s = this.search.toLowerCase()
            list = list.filter(t =>
                (t.name ?? '').toLowerCase().includes(s) ||
                (t.subject ?? '').toLowerCase().includes(s) ||
                (t.position ?? '').toLowerCase().includes(s)
            )
        }
        return list
    },

    get stats() {
        return {
            total:    this.teachers.length,
            imtak:    this.teachers.filter(t => t.category === 'imtak').length,
            mipa:     this.teachers.filter(t => t.category === 'mipa').length,
            lainnya:  this.teachers.filter(t => !['imtak','mipa'].includes(t.category)).length,
        }
    },

    tagsArray(t) {
        if (!t) return []
        if (Array.isArray(t)) return t
        return t.split(',').map(x => x.trim()).filter(Boolean)
    },

    initials(name) {
        return (name ?? '').split(' ').slice(0,2).map(n => n[0]).join('').toUpperCase()
    },
}))

// ─── Fasilitas Page ────────────────────────────────────────────────────────────
Alpine.data('fasilitasPage', () => ({
    albums: [],
    loading: true,
    _loaded: false,
    selectedAlbum: null,
    albumMedia: [],
    albumLoading: false,
    lightboxImg: null,

    init() {
        if (this.$store.pub.tab === 'fasilitas') this._load()
        this.$watch('$store.pub.tab', t => { if (t === 'fasilitas' && !this._loaded) this._load() })
    },

    async _load() {
        if (this._loaded) return
        this._loaded = true
        this.loading = true
        try {
            const r = await axios.get('/api/albums')
            this.albums = r.data?.data ?? r.data ?? []
        } catch (e) { console.error('fasilitasPage:', e) }
        finally { this.loading = false }
    },

    async openAlbum(album) {
        this.selectedAlbum = album
        this.albumMedia    = []
        this.albumLoading  = true
        try {
            const r = await axios.get('/api/albums/' + album.slug)
            this.albumMedia = r.data?.medias ?? []
        } catch {}
        finally { this.albumLoading = false }
    },
}))

// ─── Kontak Page ───────────────────────────────────────────────────────────────
Alpine.data('kontakPage', () => ({
    form: { name: '', email: '', phone: '', subject: 'informasi_umum', message: '' },
    sending: false,
    sent: false,
    error: '',

    subjects: [
        { value: 'informasi_umum', label: 'Informasi Umum' },
        { value: 'ppdb',           label: 'PPDB / Pendaftaran' },
        { value: 'akademik',       label: 'Akademik' },
        { value: 'keuangan',       label: 'Keuangan' },
        { value: 'lainnya',        label: 'Lainnya' },
    ],

    async send() {
        this.error = ''
        if (!this.form.name.trim() || !this.form.email.trim() || !this.form.message.trim()) {
            this.error = 'Nama, email, dan pesan wajib diisi.'
            return
        }
        this.sending = true
        try {
            await axios.post('/api/contact', this.form)
            this.sent = true
            this.form = { name: '', email: '', phone: '', subject: 'informasi_umum', message: '' }
        } catch (e) {
            this.error = e.response?.data?.message ?? 'Gagal mengirim. Coba lagi.'
        } finally { this.sending = false }
    },

    reset() { this.sent = false; this.error = '' },
}))

// ─── PPDB Page ─────────────────────────────────────────────────────────────────
Alpine.data('ppdbPage', () => ({
    activeTab: 'daftar', // 'daftar' | 'status'
    step: 1,
    totalSteps: 6,
    submitting: false,
    submitted: false,
    registrationNumber: '',
    formError: '',

    // Status check
    statusCode: '',
    statusResult: null,
    statusLoading: false,

    form: {
        // Step 1 — Jenis & Pilihan
        jenis_pendaftaran: 'Siswa Baru',
        no_peserta_un: '', no_skhun: '', no_ijazah: '',
        academic_year: '2026/2027',
        wave: 'Gelombang 1',
        major_choice: 'MIPA',
        previous_school: '',

        // Step 2 — Data Pribadi
        student_name: '', nisn: '', nik: '',
        gender: 'Laki-laki',
        birth_place: '', birth_date: '',
        agama: 'Islam',
        kebutuhan_khusus: 'Tidak Ada',
        address: '',
        rt: '', rw: '', nama_kelurahan: '', kecamatan: '', kode_pos: '',
        tinggal_bersama: 'Orang Tua',
        transportasi: 'Kendaraan Pribadi',
        phone: '', email: '',

        // Step 3 — Ayah
        nama_ayah: '',
        tahun_lahir_ayah: '',
        pendidikan_ayah: 'SLTA / Sederajat',
        pekerjaan_ayah: '',
        penghasilan_ayah: 'Rp. 1.000.000 - Rp. 3.000.000',

        // Step 4 — Ibu
        nama_ibu: '',
        tahun_lahir_ibu: '',
        pendidikan_ibu: 'SLTA / Sederajat',
        pekerjaan_ibu: 'Ibu Rumah Tangga',
        penghasilan_ibu: 'Tidak Berpenghasilan',

        // Step 5 — Wali
        mempunyai_wali: 0,
        nama_wali: '', tahun_lahir_wali: '', pendidikan_wali: '', pekerjaan_wali: '', penghasilan_wali: '',

        // Step 6 — Periodik
        tinggi_badan: '', berat_badan: '',
        jarak_sekolah: '<= 1 KM',
        jarak_sekolah_km: 1,
        waktu_tempuh: 15,
        jumlah_saudara_kandung: 0,
    },

    pendidikanOptions: ['TK / Sederajat','SD / Sederajat','SLTP / Sederajat','SLTA / Sederajat',
                        'D1','D2','D3','D4 / S1','S2','S3','Tidak Sekolah'],
    penghasilanOptions: ['Tidak Berpenghasilan','< Rp. 1.000.000',
                         'Rp. 1.000.000 - Rp. 3.000.000','Rp. 3.000.000 - Rp. 5.000.000',
                         'Rp. 5.000.000 - Rp. 10.000.000','> Rp. 10.000.000'],
    jarakOptions: ['<= 1 KM','2 - 3 KM','4 - 6 KM','7 - 9 KM','10 - 15 KM','> 15 KM'],

    stepTitles: [
        'Jenis & Pilihan Sekolah',
        'Data Pribadi Calon Siswa',
        'Data Orang Tua — Ayah',
        'Data Orang Tua — Ibu',
        'Data Wali (Opsional)',
        'Data Periodik & Tinjauan',
    ],

    get currentStepTitle() { return this.stepTitles[this.step - 1] ?? '' },

    validate() {
        this.formError = ''
        if (this.step === 2) {
            if (!this.form.student_name.trim()) { this.formError = 'Nama lengkap siswa wajib diisi.'; return false }
            if (!this.form.gender)              { this.formError = 'Jenis kelamin wajib dipilih.'; return false }
            if (!this.form.birth_date)          { this.formError = 'Tanggal lahir wajib diisi.'; return false }
            if (!this.form.phone.trim())        { this.formError = 'Nomor HP/WA wajib diisi.'; return false }
        }
        if (this.step === 3) {
            if (!this.form.nama_ayah.trim())    { this.formError = 'Nama ayah wajib diisi.'; return false }
        }
        if (this.step === 4) {
            if (!this.form.nama_ibu.trim())     { this.formError = 'Nama ibu wajib diisi.'; return false }
        }
        return true
    },

    next() { if (this.validate()) { this.step++; window.scrollTo({ top: 0 }) } },
    prev() { this.step--; this.formError = ''; window.scrollTo({ top: 0 }) },

    async submit() {
        if (!this.validate()) return
        this.submitting = true
        this.formError  = ''
        try {
            const r = await axios.post('/api/registrations', this.form)
            this.registrationNumber = r.data.registration_number
            this.submitted = true
            window.scrollTo({ top: 0 })
        } catch (e) {
            this.formError = e.response?.data?.message ?? 'Terjadi kesalahan. Silakan coba lagi.'
            if (e.response?.data?.errors) {
                const errs = e.response.data.errors
                this.formError = Object.values(errs).flat().join(' ')
            }
        } finally { this.submitting = false }
    },

    reset() {
        this.submitted = false
        this.step = 1
        this.registrationNumber = ''
        this.formError = ''
    },

    async checkStatus() {
        if (!this.statusCode.trim()) return
        this.statusLoading = true
        this.statusResult  = null
        try {
            const r = await axios.get('/api/registrations/' + this.statusCode.trim() + '/status')
            this.statusResult = { found: true, ...r.data }
        } catch { this.statusResult = { found: false } }
        finally { this.statusLoading = false }
    },

    statusBadge(s) {
        const m = { submitted:'Menunggu Verifikasi', document_review:'Review Dokumen',
                    verified:'Terverifikasi', accepted:'Diterima', rejected:'Ditolak' }
        return m[s] ?? s
    },

    statusColor(s) {
        const m = { submitted:'bg-yellow-100 text-yellow-800', document_review:'bg-blue-100 text-blue-800',
                    verified:'bg-green-100 text-green-800', accepted:'bg-emerald-100 text-emerald-800',
                    rejected:'bg-red-100 text-red-800' }
        return m[s] ?? 'bg-gray-100 text-gray-700'
    },

    fmtDate(d) {
        if (!d) return '-'
        return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })
    },
}))

Alpine.start()
