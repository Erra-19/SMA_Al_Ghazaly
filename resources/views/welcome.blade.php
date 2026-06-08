<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Al Ghazaly School</title>
    @php($viteManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true))
    <link rel="stylesheet" href="/build/{{ $viteManifest['resources/css/app.css']['file'] }}">
    @if(config('services.midtrans.client_key'))
        <script
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
    <script type="module" src="/build/{{ $viteManifest['resources/js/app.js']['file'] }}"></script>
</head>
<body>
    <div id="school-app" class="app-shell">
        <header class="site-header">
            <a class="brand" href="#home" data-view="home" aria-label="Beranda Al Ghazaly">
                <span class="brand-mark">AG</span>
                <span>
                    <strong>Al Ghazaly</strong>
                    <small>School Profile</small>
                </span>
            </a>

            <nav class="top-nav" aria-label="Navigasi utama">
                <button class="nav-link is-active" data-view="home" type="button">Beranda</button>
                <button class="nav-link" data-view="ppdb" type="button">PPDB</button>
                <button class="nav-link" data-view="admin" type="button">Admin</button>
            </nav>
        </header>

        <main>
            <section class="view is-visible" id="view-home">
                <section class="hero" aria-labelledby="hero-title">
                    <img src="/images/school-hero.png" alt="Lingkungan sekolah Al Ghazaly" class="hero-image">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <p class="eyebrow">Profil sekolah dan penerimaan siswa baru</p>
                        <h1 id="hero-title">Al Ghazaly School</h1>
                        <p class="hero-copy">Ruang informasi sementara untuk profil sekolah, berita, acara, dan alur PPDB sampai tampilan final dari tim frontend siap.</p>
                        <div class="hero-actions">
                            <button class="primary-action" data-view="ppdb" type="button">Daftar PPDB</button>
                            <a class="secondary-action" href="#news">Lihat berita</a>
                        </div>
                    </div>
                </section>

                <section class="quick-stats" aria-label="Ringkasan sekolah">
                    <article>
                        <strong id="stat-posts">0</strong>
                        <span>Berita & acara</span>
                    </article>
                    <article>
                        <strong id="stat-teachers">0</strong>
                        <span>Guru terdata</span>
                    </article>
                    <article>
                        <strong id="stat-albums">0</strong>
                        <span>Album galeri</span>
                    </article>
                    <article>
                        <strong id="stat-ppdb">Buka</strong>
                        <span>Status PPDB</span>
                    </article>
                </section>

                <section class="content-band profile-band">
                    <div class="section-heading">
                        <p class="eyebrow">Company profile</p>
                        <h2>Informasi sekolah dalam satu halaman</h2>
                    </div>
                    <div class="profile-grid">
                        <article>
                            <h3>Profil</h3>
                            <p id="profile-summary">Memuat profil sekolah...</p>
                        </article>
                        <article>
                            <h3>Program</h3>
                            <p>Informasi akademik, kegiatan siswa, fasilitas, dan kontak bisa diisi melalui settings atau halaman admin.</p>
                        </article>
                        <article>
                            <h3>Kontak</h3>
                            <p id="profile-contact">Kontak sekolah akan tampil dari data publik yang tersedia.</p>
                        </article>
                    </div>
                </section>

                <section class="content-band" id="news">
                    <div class="section-heading with-action">
                        <div>
                            <p class="eyebrow">Publikasi</p>
                            <h2>Berita dan acara terbaru</h2>
                        </div>
                        <button class="ghost-action" id="refresh-public" type="button">Refresh</button>
                    </div>
                    <div class="card-grid" id="post-list">
                        <p class="muted">Memuat berita dan acara...</p>
                    </div>
                </section>

                <section class="content-band">
                    <div class="section-heading">
                        <p class="eyebrow">Tenaga pendidik</p>
                        <h2>Guru dan staf sekolah</h2>
                    </div>
                    <div class="card-grid compact-grid" id="teacher-list">
                        <p class="muted">Memuat data guru...</p>
                    </div>
                </section>

                <section class="content-band">
                    <div class="section-heading">
                        <p class="eyebrow">Cerita warga sekolah</p>
                        <h2>Testimoni</h2>
                    </div>
                    <div class="card-grid compact-grid" id="testimonial-list">
                        <p class="muted">Memuat testimoni...</p>
                    </div>
                </section>

                <section class="content-band">
                    <div class="section-heading">
                        <p class="eyebrow">Galeri</p>
                        <h2>Album kegiatan</h2>
                    </div>
                    <div class="card-grid compact-grid" id="album-list">
                        <p class="muted">Memuat album...</p>
                    </div>
                </section>
            </section>

            <section class="view" id="view-ppdb">
                <section class="page-title">
                    <p class="eyebrow">Penerimaan peserta didik baru</p>
                    <h1>PPDB Online</h1>
                    <p>Alur sementara: daftar, cek status, upload dokumen, lalu buat konfirmasi pembayaran jika sudah dibutuhkan.</p>
                </section>

                <div class="workspace-grid ppdb-grid">
                    <form class="panel form-panel" id="registration-form">
                        <div class="panel-heading">
                            <h2>Form pendaftaran</h2>
                            <span class="status-pill">Publik</span>
                        </div>
                        <div class="form-grid two-col">
                            <label>Nama siswa
                                <input name="student_name" required autocomplete="name">
                            </label>
                            <label>NISN
                                <input name="nisn">
                            </label>
                            <label>Tempat lahir
                                <input name="birth_place">
                            </label>
                            <label>Tanggal lahir
                                <input name="birth_date" type="date">
                            </label>
                            <label>Jenis kelamin
                                <select name="gender">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </label>
                            <label>Pilihan jurusan
                                <input name="major_choice" placeholder="IPA / IPS / Keagamaan">
                            </label>
                            <label>Tahun ajaran
                                <input name="academic_year" value="2026/2027">
                            </label>
                            <label>Gelombang
                                <input name="wave" value="1">
                            </label>
                            <label class="span-2">Alamat
                                <textarea name="address" rows="3"></textarea>
                            </label>
                            <label>No. HP siswa
                                <input name="phone">
                            </label>
                            <label>Asal sekolah
                                <input name="previous_school">
                            </label>
                            <label>Nama orang tua
                                <input name="parent_name">
                            </label>
                            <label>No. HP orang tua
                                <input name="parent_phone">
                            </label>
                            <label class="span-2">Pekerjaan orang tua
                                <input name="parent_job">
                            </label>
                        </div>
                        <button class="primary-action full-width" type="submit">Kirim pendaftaran</button>
                        <p class="form-feedback" id="registration-feedback"></p>
                    </form>

                    <aside class="side-stack">
                        <form class="panel" id="status-form">
                            <div class="panel-heading">
                                <h2>Cek status</h2>
                            </div>
                            <label>Nomor pendaftaran
                                <input name="registration_number" required placeholder="PPDB-...">
                            </label>
                            <button class="secondary-button" type="submit">Cek</button>
                            <div class="result-box" id="status-result">Status akan tampil di sini.</div>
                        </form>

                        <form class="panel" id="document-form">
                            <div class="panel-heading">
                                <h2>Upload dokumen</h2>
                            </div>
                            <label>ID pendaftaran
                                <input name="registration_id" required>
                            </label>
                            <label>Jenis dokumen
                                <select name="document_type">
                                    <option value="birth_certificate">Akta kelahiran</option>
                                    <option value="family_card">Kartu keluarga</option>
                                    <option value="report_card">Rapor</option>
                                    <option value="photo">Pas foto</option>
                                </select>
                            </label>
                            <label>File
                                <input name="document_file" type="file" required>
                            </label>
                            <button class="secondary-button" type="submit">Upload</button>
                            <p class="form-feedback" id="document-feedback"></p>
                        </form>

                        <form class="panel" id="payment-form">
                            <div class="panel-heading">
                                <h2>Konfirmasi bayar</h2>
                            </div>
                            <label>ID pendaftaran
                                <input name="registration_id" required>
                            </label>
                            <button class="secondary-button" type="submit">Buat pembayaran</button>
                            <p class="form-feedback" id="payment-feedback"></p>
                        </form>
                    </aside>
                </div>
            </section>

            <section class="view" id="view-admin">
                <section class="page-title admin-title">
                    <div>
                        <p class="eyebrow">Back office</p>
                        <h1>Admin sekolah</h1>
                        <p>Panel sementara untuk cek dashboard, konten berita/acara, dan antrean PPDB.</p>
                    </div>
                    <button class="ghost-action hidden" id="logout-admin" type="button">Logout</button>
                </section>

                <section class="admin-login panel" id="admin-login-panel">
                    <form id="admin-login-form">
                        <div class="panel-heading">
                            <h2>Login admin</h2>
                            <span class="status-pill">Sanctum</span>
                        </div>
                        <div class="form-grid two-col">
                            <label>Email
                                <input name="email" type="email" value="admin@alghazaly.test" required>
                            </label>
                            <label>Password
                                <input name="password" type="password" value="password" required>
                            </label>
                        </div>
                        <button class="primary-action" type="submit">Masuk</button>
                        <p class="form-feedback" id="admin-login-feedback"></p>
                    </form>
                </section>

                <section class="admin-workspace hidden" id="admin-workspace">
                    <div class="admin-tabs" role="tablist" aria-label="Admin views">
                        <button class="tab-button is-active" data-admin-tab="dashboard" type="button">Dashboard</button>
                        <button class="tab-button" data-admin-tab="profile" type="button">Profil</button>
                        <button class="tab-button" data-admin-tab="content" type="button">Konten</button>
                        <button class="tab-button" data-admin-tab="teachers" type="button">Guru</button>
                        <button class="tab-button" data-admin-tab="testimonials" type="button">Testimoni</button>
                        <button class="tab-button" data-admin-tab="albums" type="button">Galeri</button>
                        <button class="tab-button" data-admin-tab="ppdb" type="button">PPDB</button>
                    </div>

                    <div class="admin-tab is-visible" id="admin-tab-dashboard">
                        <div class="metric-grid" id="admin-metrics"></div>
                    </div>

                    <div class="admin-tab" id="admin-tab-profile">
                        <div class="workspace-grid">
                            <form class="panel" id="profile-form">
                                <div class="panel-heading">
                                    <h2>Profil beranda</h2>
                                    <span class="status-pill">Publik</span>
                                </div>
                                <div class="form-grid">
                                    <label>Nama sekolah
                                        <input name="school_name" required>
                                    </label>
                                    <label>Judul hero
                                        <input name="homepage_hero_title" required>
                                    </label>
                                    <label>Deskripsi hero/profil
                                        <textarea name="homepage_hero_subtitle" rows="4"></textarea>
                                    </label>
                                    <label>Alamat
                                        <textarea name="school_address" rows="3"></textarea>
                                    </label>
                                    <label>Email
                                        <input name="school_email" type="email">
                                    </label>
                                    <label>Telepon
                                        <input name="school_phone">
                                    </label>
                                    <label>WhatsApp
                                        <input name="school_whatsapp">
                                    </label>
                                    <label>Status PPDB
                                        <select name="ppdb_status">
                                            <option value="open">Buka</option>
                                            <option value="closed">Tutup</option>
                                        </select>
                                    </label>
                                    <label>Tahun ajaran PPDB
                                        <input name="ppdb_academic_year">
                                    </label>
                                    <label>Biaya pendaftaran
                                        <input name="ppdb_registration_fee" type="number" min="0">
                                    </label>
                                </div>
                                <button class="primary-action" type="submit">Simpan profil</button>
                                <p class="form-feedback" id="profile-feedback"></p>
                            </form>
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Preview data publik</h2>
                                    <button class="ghost-action" id="refresh-profile" type="button">Refresh</button>
                                </div>
                                <div class="result-box" id="profile-preview">Data profil akan tampil setelah login.</div>
                            </section>
                        </div>
                    </div>

                    <div class="admin-tab" id="admin-tab-content">
                        <div class="workspace-grid">
                            <form class="panel" id="post-form">
                                <div class="panel-heading">
                                    <h2>Buat konten</h2>
                                </div>
                                <div class="form-grid">
                                    <label>Judul
                                        <input name="title" required>
                                    </label>
                                    <label>Tipe
                                        <select name="type">
                                            <option value="news">Berita</option>
                                            <option value="event">Acara</option>
                                        </select>
                                    </label>
                                    <label>Tanggal acara
                                        <input name="event_date" type="datetime-local">
                                    </label>
                                    <label>Lokasi acara
                                        <input name="event_location">
                                    </label>
                                    <label>Ringkasan
                                        <textarea name="excerpt" rows="3"></textarea>
                                    </label>
                                    <label>Konten
                                        <textarea name="content" rows="6" required></textarea>
                                    </label>
                                </div>
                                <label class="checkbox-row">
                                    <input name="is_published" type="checkbox" checked>
                                    Terbitkan ke publik
                                </label>
                                <button class="primary-action" type="submit">Simpan konten</button>
                                <p class="form-feedback" id="post-feedback"></p>
                            </form>
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Konten terbaru</h2>
                                    <button class="ghost-action" id="refresh-admin-posts" type="button">Refresh</button>
                                </div>
                                <div class="list-table" id="admin-post-list"></div>
                            </section>
                        </div>
                    </div>

                    <div class="admin-tab" id="admin-tab-teachers">
                        <div class="workspace-grid">
                            <form class="panel" id="teacher-form">
                                <div class="panel-heading">
                                    <h2>Input guru</h2>
                                </div>
                                <div class="form-grid two-col">
                                    <label>NIP
                                        <input name="nip" placeholder="Isi manual atau otomatis">
                                    </label>
                                    <label>Nama
                                        <input name="name" required>
                                    </label>
                                    <label>Jabatan
                                        <input name="position" required>
                                    </label>
                                    <label>Mata pelajaran
                                        <input name="subject">
                                    </label>
                                    <label>Email
                                        <input name="email" type="email">
                                    </label>
                                    <label>No. HP
                                        <input name="phone">
                                    </label>
                                    <label>Urutan
                                        <input name="order" type="number" min="0" value="0">
                                    </label>
                                    <label class="span-2">Bio
                                        <textarea name="bio" rows="4"></textarea>
                                    </label>
                                </div>
                                <label class="checkbox-row">
                                    <input name="is_active" type="checkbox" checked>
                                    Tampilkan di beranda
                                </label>
                                <button class="primary-action" type="submit">Simpan guru</button>
                                <p class="form-feedback" id="teacher-feedback"></p>
                            </form>
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Guru tersimpan</h2>
                                    <button class="ghost-action" id="refresh-teachers" type="button">Refresh</button>
                                </div>
                                <div class="list-table" id="admin-teacher-list"></div>
                            </section>
                        </div>
                    </div>

                    <div class="admin-tab" id="admin-tab-testimonials">
                        <div class="workspace-grid">
                            <form class="panel" id="testimonial-form">
                                <div class="panel-heading">
                                    <h2>Input testimoni</h2>
                                </div>
                                <div class="form-grid two-col">
                                    <label>Nama
                                        <input name="name" required>
                                    </label>
                                    <label>Peran
                                        <input name="role" placeholder="Orang tua / Alumni">
                                    </label>
                                    <label>Rating
                                        <input name="rating" type="number" min="1" max="5" value="5">
                                    </label>
                                    <label class="span-2">Isi testimoni
                                        <textarea name="content" rows="5" required></textarea>
                                    </label>
                                </div>
                                <label class="checkbox-row">
                                    <input name="is_published" type="checkbox" checked>
                                    Tampilkan di beranda
                                </label>
                                <button class="primary-action" type="submit">Simpan testimoni</button>
                                <p class="form-feedback" id="testimonial-feedback"></p>
                            </form>
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Testimoni tersimpan</h2>
                                    <button class="ghost-action" id="refresh-testimonials" type="button">Refresh</button>
                                </div>
                                <div class="list-table" id="admin-testimonial-list"></div>
                            </section>
                        </div>
                    </div>

                    <div class="admin-tab" id="admin-tab-albums">
                        <div class="workspace-grid">
                            <form class="panel" id="album-form">
                                <div class="panel-heading">
                                    <h2>Input album</h2>
                                </div>
                                <div class="form-grid">
                                    <label>Judul album
                                        <input name="title" required>
                                    </label>
                                    <label>Cover URL
                                        <input name="cover" placeholder="/images/school-hero.png">
                                    </label>
                                    <label>Deskripsi
                                        <textarea name="description" rows="4"></textarea>
                                    </label>
                                    <label>Urutan
                                        <input name="order" type="number" min="0" value="0">
                                    </label>
                                </div>
                                <label class="checkbox-row">
                                    <input name="is_published" type="checkbox" checked>
                                    Tampilkan di beranda
                                </label>
                                <button class="primary-action" type="submit">Simpan album</button>
                                <p class="form-feedback" id="album-feedback"></p>
                            </form>
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Album tersimpan</h2>
                                    <button class="ghost-action" id="refresh-albums" type="button">Refresh</button>
                                </div>
                                <div class="list-table" id="admin-album-list"></div>
                            </section>
                        </div>
                    </div>

                    <div class="admin-tab" id="admin-tab-ppdb">
                        <div class="workspace-grid">
                            <section class="panel">
                                <div class="panel-heading">
                                    <h2>Antrean pendaftaran</h2>
                                    <button class="ghost-action" id="refresh-registrations" type="button">Refresh</button>
                                </div>
                                <div class="list-table" id="registration-list"></div>
                            </section>
                            <section class="panel" id="registration-detail">
                                <div class="panel-heading">
                                    <h2>Detail PPDB</h2>
                                    <span class="status-pill">Pilih data</span>
                                </div>
                                <p class="muted">Pilih pendaftaran dari daftar untuk melihat detail.</p>
                            </section>
                        </div>
                    </div>
                </section>
            </section>
        </main>
    </div>
</body>
</html>
