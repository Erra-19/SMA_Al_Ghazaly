<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testing API Al Ghazaly</title>
    @php($viteManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true))
    <link rel="stylesheet" href="/build/{{ $viteManifest['resources/css/app.css']['file'] }}">
    <script type="module" src="/build/{{ $viteManifest['resources/js/testing.js']['file'] }}"></script>
</head>
<body>
    <main class="testing-shell">
        <section class="testing-header">
            <div>
                <p class="eyebrow">Functional testing</p>
                <h1>Al Ghazaly API Lab</h1>
                <p>Halaman sementara untuk tim frontend mencoba endpoint utama, input data ke DB, dan cek data publik yang tampil di beranda.</p>
            </div>
            <div class="testing-header-actions">
                <a class="secondary-action" href="/">Beranda</a>
                <button class="primary-action" id="run-smoke-test" type="button">Run smoke test</button>
            </div>
        </section>

        <section class="testing-grid">
            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>1. Auth admin</h2>
                    <span class="status-pill" id="token-state">Belum login</span>
                </div>
                <form id="test-login-form" class="form-grid two-col">
                    <label>Email
                        <input name="email" type="email" value="admin@alghazaly.test" required>
                    </label>
                    <label>Password
                        <input name="password" type="password" value="password" required>
                    </label>
                    <button class="primary-action" type="submit">Login</button>
                    <button class="secondary-button" id="test-logout" type="button">Clear token</button>
                </form>
                <pre class="test-output" id="auth-output">Token admin akan tampil di sini.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>2. Public read</h2>
                    <button class="ghost-action" id="load-public-test" type="button">Load public</button>
                </div>
                <div class="button-row">
                    <button class="mini-button" data-public-endpoint="/profile" type="button">Profile</button>
                    <button class="mini-button" data-public-endpoint="/posts" type="button">Posts</button>
                    <button class="mini-button" data-public-endpoint="/teachers" type="button">Teachers</button>
                    <button class="mini-button" data-public-endpoint="/testimonials" type="button">Testimonials</button>
                    <button class="mini-button" data-public-endpoint="/albums" type="button">Albums</button>
                </div>
                <pre class="test-output" id="public-output">Klik endpoint publik untuk cek data beranda.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>3. Profil beranda</h2>
                    <span class="status-pill">Admin</span>
                </div>
                <form id="test-settings-form" class="form-grid">
                    <label>Nama sekolah
                        <input name="school_name" value="SMA Al Ghazaly">
                    </label>
                    <label>Hero subtitle
                        <textarea name="homepage_hero_subtitle" rows="3">Membangun generasi berilmu, berakhlak, dan berprestasi.</textarea>
                    </label>
                    <label>Email sekolah
                        <input name="school_email" type="email" value="info@alghazaly.sch.id">
                    </label>
                    <label>Status PPDB
                        <select name="ppdb_status">
                            <option value="open">Buka</option>
                            <option value="closed">Tutup</option>
                        </select>
                    </label>
                    <button class="primary-action" type="submit">Simpan settings</button>
                </form>
                <pre class="test-output" id="settings-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>4. Berita/acara</h2>
                    <span class="status-pill">Admin ke beranda</span>
                </div>
                <form id="test-post-form" class="form-grid">
                    <label>Judul
                        <input name="title" value="Berita Testing Frontend">
                    </label>
                    <label>Tipe
                        <select name="type">
                            <option value="news">Berita</option>
                            <option value="event">Acara</option>
                        </select>
                    </label>
                    <label>Ringkasan
                        <textarea name="excerpt" rows="2">Data testing dari halaman API Lab.</textarea>
                    </label>
                    <label>Konten
                        <textarea name="content" rows="4">Konten ini dibuat untuk mengecek input admin dan tampilan publik.</textarea>
                    </label>
                    <label class="checkbox-row">
                        <input name="is_published" type="checkbox" checked>
                        Published
                    </label>
                    <button class="primary-action" type="submit">Create post</button>
                </form>
                <pre class="test-output" id="post-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>5. Guru</h2>
                    <span class="status-pill">Admin ke beranda</span>
                </div>
                <form id="test-teacher-form" class="form-grid two-col">
                    <label>NIP
                        <input name="nip" placeholder="Auto jika kosong">
                    </label>
                    <label>Nama
                        <input name="name" value="Guru Testing">
                    </label>
                    <label>Jabatan
                        <input name="position" value="Guru">
                    </label>
                    <label>Mapel
                        <input name="subject" value="Matematika">
                    </label>
                    <label class="checkbox-row span-2">
                        <input name="is_active" type="checkbox" checked>
                        Tampil publik
                    </label>
                    <button class="primary-action" type="submit">Create teacher</button>
                </form>
                <pre class="test-output" id="teacher-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>6. Testimoni</h2>
                    <span class="status-pill">Admin ke beranda</span>
                </div>
                <form id="test-testimonial-form" class="form-grid">
                    <label>Nama
                        <input name="name" value="Orang Tua Testing">
                    </label>
                    <label>Peran
                        <input name="role" value="Orang tua siswa">
                    </label>
                    <label>Rating
                        <input name="rating" type="number" min="1" max="5" value="5">
                    </label>
                    <label>Isi
                        <textarea name="content" rows="3">Testimoni testing dari API Lab.</textarea>
                    </label>
                    <label class="checkbox-row">
                        <input name="is_published" type="checkbox" checked>
                        Published
                    </label>
                    <button class="primary-action" type="submit">Create testimonial</button>
                </form>
                <pre class="test-output" id="testimonial-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>7. Album</h2>
                    <span class="status-pill">Admin ke beranda</span>
                </div>
                <form id="test-album-form" class="form-grid">
                    <label>Judul
                        <input name="title" value="Album Testing">
                    </label>
                    <label>Cover
                        <input name="cover" value="/images/school-hero.png">
                    </label>
                    <label>Deskripsi
                        <textarea name="description" rows="3">Album testing dari API Lab.</textarea>
                    </label>
                    <label class="checkbox-row">
                        <input name="is_published" type="checkbox" checked>
                        Published
                    </label>
                    <button class="primary-action" type="submit">Create album</button>
                </form>
                <pre class="test-output" id="album-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>8. PPDB register</h2>
                    <span class="status-pill">Public</span>
                </div>
                <form id="test-registration-form" class="form-grid two-col">
                    <label>Nama siswa
                        <input name="student_name" value="Siswa Testing" required>
                    </label>
                    <label>NISN
                        <input name="nisn">
                    </label>
                    <label>Gender
                        <select name="gender">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </label>
                    <label>Tahun ajaran
                        <input name="academic_year" value="2026/2027">
                    </label>
                    <label>Orang tua
                        <input name="parent_name" value="Wali Testing">
                    </label>
                    <label>No. HP
                        <input name="parent_phone" value="081234567890">
                    </label>
                    <label class="span-2">Alamat
                        <textarea name="address" rows="2">Alamat testing</textarea>
                    </label>
                    <button class="primary-action" type="submit">Create registration</button>
                </form>
                <pre class="test-output" id="registration-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel">
                <div class="panel-heading">
                    <h2>9. PPDB status/payment/docs</h2>
                    <span class="status-pill">Public</span>
                </div>
                <div class="form-grid">
                    <label>Registration ID
                        <input id="test-registration-id">
                    </label>
                    <label>Registration number
                        <input id="test-registration-number">
                    </label>
                    <label>Upload dokumen
                        <input id="test-document-file" type="file">
                    </label>
                    <div class="button-row">
                        <button class="secondary-button" id="test-check-status" type="button">Check status</button>
                        <button class="secondary-button" id="test-create-payment" type="button">Create payment</button>
                        <button class="secondary-button" id="test-upload-document" type="button">Upload document</button>
                    </div>
                    <label>Persen cicilan
                        <select id="test-installment-percent">
                            <option value="10">10% dari sisa</option>
                            <option value="25">25% dari sisa</option>
                            <option value="50">50% dari sisa</option>
                            <option value="100">Lunasi sisa</option>
                        </select>
                    </label>
                </div>
                <pre class="test-output" id="ppdb-output">Belum dites.</pre>
            </article>

            <article class="panel testing-panel testing-wide">
                <div class="panel-heading">
                    <h2>10. Admin PPDB</h2>
                    <button class="ghost-action" id="load-admin-registrations" type="button">Load registrations</button>
                </div>
                <div class="form-grid two-col">
                    <label>Registration ID
                        <input id="admin-registration-id">
                    </label>
                    <label>Status
                        <select id="admin-registration-status">
                            <option value="submitted">submitted</option>
                            <option value="document_review">document_review</option>
                            <option value="verified">verified</option>
                            <option value="accepted">accepted</option>
                            <option value="rejected">rejected</option>
                        </select>
                    </label>
                    <button class="primary-action" id="test-update-registration-status" type="button">Update status</button>
                    <button class="secondary-button" id="load-admin-dashboard" type="button">Load dashboard</button>
                    <label>Payment ID
                        <input id="admin-payment-id" placeholder="isi dari response create payment">
                    </label>
                    <label>Nominal terbayar
                        <input id="admin-paid-amount" type="number" min="0" value="50000">
                    </label>
                    <label>Status payment
                        <select id="admin-payment-status">
                            <option value="">auto dari nominal</option>
                            <option value="partial">partial</option>
                            <option value="paid">paid</option>
                            <option value="pending">pending</option>
                            <option value="failed">failed</option>
                            <option value="expired">expired</option>
                            <option value="refunded">refunded</option>
                        </select>
                    </label>
                    <button class="secondary-button" id="test-update-payment" type="button">Update payment</button>
                </div>
                <pre class="test-output tall" id="admin-ppdb-output">Belum dites.</pre>
            </article>
        </section>

        <section class="panel schema-panel" aria-labelledby="schema-title">
            <div class="panel-heading">
                <div>
                    <p class="eyebrow">Database map</p>
                    <h2 id="schema-title">Struktur database ringkas</h2>
                </div>
                <span class="status-pill">ERD simple</span>
            </div>

            <div class="schema-map">
                <article class="schema-table">
                    <h3>roles</h3>
                    <p>role_id, name</p>
                    <span>1 -> users</span>
                </article>
                <article class="schema-table">
                    <h3>users</h3>
                    <p>id, role_id, name, email</p>
                    <span>auth admin/operator</span>
                </article>
                <article class="schema-table">
                    <h3>settings</h3>
                    <p>key, value, group, is_public</p>
                    <span>profil, kontak, PPDB</span>
                </article>
                <article class="schema-table">
                    <h3>posts</h3>
                    <p>title, type, content, event_date</p>
                    <span>berita/acara beranda</span>
                </article>
                <article class="schema-table">
                    <h3>pages</h3>
                    <p>title, slug, content</p>
                    <span>halaman company profile</span>
                </article>
                <article class="schema-table highlight">
                    <h3>registrations</h3>
                    <p>registration_number, student_name, status, payment_total_amount, payment_paid_amount, payment_remaining_amount, payment_method</p>
                    <span>pusat alur PPDB + ringkasan tagihan</span>
                </article>
                <article class="schema-table">
                    <h3>registration_documents</h3>
                    <p>registration_id, document_type, file_path, status</p>
                    <span>many -> registrations</span>
                </article>
                <article class="schema-table">
                    <h3>payments</h3>
                    <p>registration_id, order_id, amount, paid_amount, status</p>
                    <span>partial/paid -> students jika accepted</span>
                </article>
                <article class="schema-table">
                    <h3>payment_history</h3>
                    <p>payment_id, status, payload</p>
                    <span>many -> payments</span>
                </article>
                <article class="schema-table">
                    <h3>students</h3>
                    <p>registration_id, nis, name, status</p>
                    <span>hasil accepted PPDB</span>
                </article>
                <article class="schema-table">
                    <h3>teachers</h3>
                    <p>name, position, subject, is_active</p>
                    <span>guru beranda</span>
                </article>
                <article class="schema-table">
                    <h3>albums</h3>
                    <p>title, slug, cover, is_published</p>
                    <span>galeri kegiatan</span>
                </article>
                <article class="schema-table">
                    <h3>medias</h3>
                    <p>filename, path, mime_type, size</p>
                    <span>many-to-many albums</span>
                </article>
                <article class="schema-table">
                    <h3>album_medias</h3>
                    <p>album_id, media_id, order</p>
                    <span>pivot album-media</span>
                </article>
                <article class="schema-table">
                    <h3>testimonials</h3>
                    <p>name, role, content, rating</p>
                    <span>testimoni publik</span>
                </article>
                <article class="schema-table">
                    <h3>alumni</h3>
                    <p>name, graduation_year, achievement</p>
                    <span>alumni publik</span>
                </article>
            </div>

            <div class="schema-flow" aria-label="Alur relasi utama">
                <span>roles</span>
                <strong>-></strong>
                <span>users</span>
                <strong>-></strong>
                <span>registrations</span>
                <strong>-></strong>
                <span>documents / payments</span>
                <strong>-></strong>
                <span>students</span>
            </div>
        </section>

        <section class="testing-docs">
            <article class="panel doc-panel">
                <div class="panel-heading">
                    <div>
                        <p class="eyebrow">API routes</p>
                        <h2>Endpoint utama</h2>
                    </div>
                    <span class="status-pill">Cheat sheet</span>
                </div>
                <div class="route-groups">
                    <div class="route-group">
                        <h3>Public beranda</h3>
                        <code>GET /api/profile</code>
                        <code>GET /api/posts</code>
                        <code>GET /api/posts/{slug}</code>
                        <code>GET /api/teachers</code>
                        <code>GET /api/testimonials</code>
                        <code>GET /api/albums</code>
                        <code>GET /api/albums/{slug}</code>
                    </div>
                    <div class="route-group">
                        <h3>Auth admin</h3>
                        <code>POST /api/auth/login</code>
                        <code>GET /api/auth/me</code>
                        <code>POST /api/auth/logout</code>
                    </div>
                    <div class="route-group">
                        <h3>Admin content</h3>
                        <code>PUT /api/admin/settings</code>
                        <code>POST /api/admin/posts</code>
                        <code>POST /api/admin/teachers</code>
                        <code>POST /api/admin/testimonials</code>
                        <code>POST /api/admin/albums</code>
                        <code>POST /api/admin/medias</code>
                    </div>
                    <div class="route-group">
                        <h3>PPDB public</h3>
                        <code>POST /api/registrations</code>
                        <code>GET /api/registrations/{number}/status</code>
                        <code>POST /api/registrations/{id}/documents</code>
                        <code>POST /api/registrations/{id}/payment</code>
                    </div>
                    <div class="route-group">
                        <h3>Admin PPDB</h3>
                        <code>GET /api/admin/dashboard</code>
                        <code>GET /api/admin/registrations</code>
                        <code>GET /api/admin/registrations/{id}</code>
                        <code>PATCH /api/admin/registrations/{id}/status</code>
                        <code>PATCH /api/admin/registrations/{id}/documents/{documentId}</code>
                        <code>GET /api/admin/payments</code>
                        <code>PATCH /api/admin/payments/{id}</code>
                    </div>
                    <div class="route-group">
                        <h3>Pesan/form</h3>
                        <code>GET /api/forms/{slug}</code>
                        <code>POST /api/forms/{slug}/submit</code>
                        <code>GET /api/admin/messages</code>
                    </div>
                </div>
            </article>

            <article class="panel doc-panel">
                <div class="panel-heading">
                    <div>
                        <p class="eyebrow">Use flow</p>
                        <h2>Alur utama aplikasi</h2>
                    </div>
                    <span class="status-pill">Frontend flow</span>
                </div>
                <div class="flow-lanes">
                    <div class="flow-lane">
                        <h3>Company profile</h3>
                        <span>Load profile</span>
                        <strong>-></strong>
                        <span>Render hero/kontak</span>
                        <strong>-></strong>
                        <span>Load posts, teachers, albums</span>
                        <strong>-></strong>
                        <span>Detail berita/acara/album</span>
                    </div>
                    <div class="flow-lane">
                        <h3>PPDB public</h3>
                        <span>Daftar</span>
                        <strong>-></strong>
                        <span>Simpan nomor</span>
                        <strong>-></strong>
                        <span>Upload dokumen</span>
                        <strong>-></strong>
                        <span>Buat payment</span>
                        <strong>-></strong>
                        <span>Cek status</span>
                    </div>
                    <div class="flow-lane">
                        <h3>Admin PPDB</h3>
                        <span>Login</span>
                        <strong>-></strong>
                        <span>List pendaftar</span>
                        <strong>-></strong>
                        <span>Review dokumen</span>
                        <strong>-></strong>
                        <span>Verified</span>
                        <strong>-></strong>
                        <span>Accepted/rejected</span>
                        <strong>-></strong>
                        <span>Accepted + partial/paid auto jadi murid</span>
                    </div>
                </div>
            </article>

            <article class="panel doc-panel">
                <div class="panel-heading">
                    <div>
                        <p class="eyebrow">Data shape</p>
                        <h2>Field yang perlu dipakai frontend</h2>
                    </div>
                    <span class="status-pill">Response guide</span>
                </div>
                <div class="shape-grid">
                    <pre class="code-sample">Post card
{
  "title": "...",
  "slug": "...",
  "type": "news|event",
  "excerpt": "...",
  "event_date": "...",
  "event_location": "...",
  "is_published": true
}</pre>
                    <pre class="code-sample">Teacher card
{
  "teacher_id": 1,
  "name": "...",
  "position": "...",
  "subject": "...",
  "photo": "...",
  "bio": "..."
}</pre>
                    <pre class="code-sample">PPDB status
{
  "registration_id": 1,
  "registration_number": "...",
  "student_name": "...",
  "status": "verified",
  "payment_total_amount": "250000.00",
  "payment_paid_amount": "50000.00",
  "payment_remaining_amount": "200000.00",
  "payment_method": "installment",
  "payment": {
    "status": "partial|paid",
    "amount": 250000,
    "paid_amount": 50000
  },
  "documents": []
}</pre>
                    <pre class="code-sample">Album card
{
  "album_id": 1,
  "title": "...",
  "slug": "...",
  "cover": "...",
  "description": "..."
}</pre>
                </div>
            </article>

            <article class="panel doc-panel">
                <div class="panel-heading">
                    <div>
                        <p class="eyebrow">Midtrans</p>
                        <h2>Contoh flow pembayaran</h2>
                    </div>
                    <span class="status-pill">Payment guide</span>
                </div>
                <div class="midtrans-grid">
                    <div>
                        <h3>1. Frontend buat payment</h3>
                        <pre class="code-sample">POST /api/registrations/{id}/payment
{
  "installment_percent": 10
}

Response jika biaya 0:
{
  "status": "paid",
  "payment_type": "free"
}

Response jika Midtrans aktif:
{
  "snap_token": "MIDTRANS_SNAP_TOKEN",
  "order_id": "PPDB-...",
  "amount": 25000,
  "status": "pending",
  "payment_summary": {
    "total_amount": 250000,
    "paid_amount": 0,
    "remaining_amount": 250000
  }
}</pre>
                    </div>
                    <div>
                        <h3>2. Contoh Snap JS</h3>
                        <pre class="code-sample">&lt;script src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="MIDTRANS_CLIENT_KEY"&gt;&lt;/script&gt;

window.snap.pay(payment.snap_token, {
  onSuccess: (result) =&gt; checkStatus(),
  onPending: (result) =&gt; checkStatus(),
  onError: (result) =&gt; showError(result),
  onClose: () =&gt; showInfo('Pembayaran belum selesai')
});</pre>
                    </div>
                    <div>
                        <h3>3. Webhook backend</h3>
                        <pre class="code-sample">POST /api/webhooks/midtrans

Dipanggil Midtrans, bukan frontend.
Backend akan update:
- payments.status
- payments.paid_at
- payment_history.payload
- registration status jika perlu</pre>
                    </div>
                </div>
            </article>

            <article class="panel doc-panel">
                <div class="panel-heading">
                    <div>
                        <p class="eyebrow">Notes</p>
                        <h2>Credential, enum, dan checklist</h2>
                    </div>
                    <span class="status-pill">Handoff</span>
                </div>
                <div class="handoff-grid">
                    <div class="handoff-box">
                        <h3>Credential testing</h3>
                        <code>email: admin@alghazaly.test</code>
                        <code>password: password</code>
                        <p>Gunakan Bearer token untuk semua route `/api/admin/*`.</p>
                    </div>
                    <div class="handoff-box">
                        <h3>Status enum</h3>
                        <code>registration: submitted</code>
                        <code>registration: document_review</code>
                        <code>registration: verified</code>
                        <code>registration: accepted</code>
                        <code>registration: rejected</code>
                        <code>document: pending|verified|rejected</code>
                        <code>payment: pending|paid|failed|expired</code>
                    </div>
                    <div class="handoff-box">
                        <h3>Upload notes</h3>
                        <p>Upload dokumen PPDB wajib `multipart/form-data`.</p>
                        <code>documents[0][type]</code>
                        <code>documents[0][file]</code>
                    </div>
                    <div class="handoff-box">
                        <h3>Frontend checklist</h3>
                        <p>Landing, berita detail, acara detail, album detail, PPDB form, cek status PPDB, admin content, admin PPDB, login/logout, empty/error/loading states.</p>
                    </div>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
