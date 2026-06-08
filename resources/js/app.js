import './bootstrap';

const api = window.axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
    },
});

const state = {
    token: localStorage.getItem('alghazaly_admin_token'),
    selectedRegistration: null,
    lastRegistrationStatus: null,
};

if (state.token) {
    api.defaults.headers.common.Authorization = `Bearer ${state.token}`;
}

const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => Array.from(document.querySelectorAll(selector));

function setFeedback(selector, message, type = '') {
    const element = $(selector);
    if (!element) {
        return;
    }

    element.textContent = message || '';
    element.classList.toggle('is-error', type === 'error');
    element.classList.toggle('is-success', type === 'success');
}

function payloadFromForm(form) {
    return Object.fromEntries(new FormData(form).entries());
}

function unwrapCollection(response) {
    const data = response?.data;
    if (Array.isArray(data)) {
        return data;
    }

    if (Array.isArray(data?.data)) {
        return data.data;
    }

    if (Array.isArray(data?.posts)) {
        return data.posts;
    }

    if (Array.isArray(data?.registrations)) {
        return data.registrations;
    }

    return [];
}

function readError(error, fallback = 'Terjadi kendala. Coba ulangi sebentar lagi.') {
    const data = error?.response?.data;
    if (data?.message) {
        return data.message;
    }

    const firstValidation = data?.errors ? Object.values(data.errors).flat()[0] : null;
    return firstValidation || fallback;
}

function flattenSettings(payload) {
    const settings = payload?.settings || payload || {};
    const output = {};

    Object.values(settings).flat().forEach((item) => {
        if (item?.key) {
            output[item.key] = item.value;
        }
    });

    return output;
}

function initials(name = '') {
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase() || 'AG';
}

function switchView(view) {
    $$('.view').forEach((section) => section.classList.toggle('is-visible', section.id === `view-${view}`));
    $$('.nav-link').forEach((button) => button.classList.toggle('is-active', button.dataset.view === view));
    window.location.hash = view === 'home' ? '' : view;
}

function switchAdminTab(tab) {
    $$('.admin-tab').forEach((section) => section.classList.toggle('is-visible', section.id === `admin-tab-${tab}`));
    $$('.tab-button').forEach((button) => button.classList.toggle('is-active', button.dataset.adminTab === tab));
}

function badge(text) {
    return `<span class="status-pill">${text || '-'}</span>`;
}

function formatDate(value) {
    if (!value) {
        return '-';
    }

    const date = new Date(value);
    return Number.isNaN(date.getTime())
        ? value
        : date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0));
}

async function loadPublicData() {
    try {
        const [profile, posts, teachers, albums] = await Promise.allSettled([
            api.get('/profile'),
            api.get('/posts'),
            api.get('/teachers'),
            api.get('/albums'),
        ]);

        const postItems = posts.status === 'fulfilled' ? unwrapCollection(posts.value) : [];
        const teacherItems = teachers.status === 'fulfilled' ? unwrapCollection(teachers.value) : [];
        const albumItems = albums.status === 'fulfilled' ? unwrapCollection(albums.value) : [];
        const testimonialItems = await api.get('/testimonials')
            .then(unwrapCollection)
            .catch(() => []);

        $('#stat-posts').textContent = postItems.length;
        $('#stat-teachers').textContent = teacherItems.length;
        $('#stat-albums').textContent = albumItems.length;

        if (profile.status === 'fulfilled') {
            const data = flattenSettings(profile.value.data?.data || profile.value.data);
            const schoolName = data.homepage_hero_title || data.school_name || 'Al Ghazaly School';
            const ppdbOpen = String(data.ppdb_status || '').toLowerCase() === 'open' || data.ppdb_status === true;
            $('#hero-title').textContent = schoolName;
            $('#stat-ppdb').textContent = ppdbOpen ? 'Buka' : 'Tutup';
            document.title = schoolName;
            $('#profile-summary').textContent = data.homepage_hero_subtitle || data.school_description || data.about_school || data.footer_text || 'Profil sekolah siap ditampilkan dari pengaturan publik.';
            $('#profile-contact').textContent = [
                data.school_address || data.address,
                data.school_phone || data.phone,
                data.school_email || data.email,
                data.school_whatsapp || data.whatsapp,
            ].filter(Boolean).join(' | ') || 'Alamat, telepon, dan email bisa diatur dari admin settings.';
        }

        renderPublicPosts(postItems);
        renderPublicTeachers(teacherItems);
        renderPublicTestimonials(testimonialItems);
        renderPublicAlbums(albumItems);
    } catch (error) {
        renderPublicPosts([]);
        renderPublicTeachers([]);
        renderPublicTestimonials([]);
        renderPublicAlbums([]);
    }
}

function renderPublicPosts(posts) {
    const list = $('#post-list');
    if (!posts.length) {
        list.innerHTML = '<p class="muted">Belum ada berita atau acara yang dipublikasikan.</p>';
        return;
    }

    list.innerHTML = posts.slice(0, 6).map((post) => `
        <article class="card">
            <div class="card-meta">
                ${badge(post.type === 'event' ? 'Acara' : 'Berita')}
                ${post.event_date ? badge(formatDate(post.event_date)) : ''}
            </div>
            <h3>${post.title}</h3>
            <p>${post.excerpt || post.content?.slice(0, 150) || 'Konten publik sekolah.'}</p>
        </article>
    `).join('');
}

function renderPublicTeachers(teachers) {
    const list = $('#teacher-list');
    if (!teachers.length) {
        list.innerHTML = '<p class="muted">Belum ada data guru yang ditampilkan.</p>';
        return;
    }

    list.innerHTML = teachers.slice(0, 8).map((teacher) => `
        <article class="card person-card">
            ${teacher.photo ? `<img class="thumb" src="${teacher.photo}" alt="${teacher.name}">` : `<span class="avatar-fallback">${initials(teacher.name)}</span>`}
            <div>
                <h3>${teacher.name}</h3>
                <p>${teacher.position || '-'}${teacher.subject ? ` | ${teacher.subject}` : ''}</p>
            </div>
        </article>
    `).join('');
}

function renderPublicTestimonials(testimonials) {
    const list = $('#testimonial-list');
    if (!testimonials.length) {
        list.innerHTML = '<p class="muted">Belum ada testimoni yang dipublikasikan.</p>';
        return;
    }

    list.innerHTML = testimonials.slice(0, 4).map((testimonial) => `
        <article class="card">
            <div class="card-meta">${badge(`${testimonial.rating || 5}/5`)}</div>
            <h3>${testimonial.name}</h3>
            <p>${testimonial.content || '-'}</p>
            <p><strong>${testimonial.role || 'Warga sekolah'}</strong></p>
        </article>
    `).join('');
}

function renderPublicAlbums(albums) {
    const list = $('#album-list');
    if (!albums.length) {
        list.innerHTML = '<p class="muted">Belum ada album yang dipublikasikan.</p>';
        return;
    }

    list.innerHTML = albums.slice(0, 4).map((album) => `
        <article class="card album-card">
            <img class="thumb" src="${album.cover || '/images/school-hero.png'}" alt="${album.title}">
            <div>
                <h3>${album.title}</h3>
                <p>${album.description || 'Dokumentasi kegiatan sekolah.'}</p>
            </div>
        </article>
    `).join('');
}

async function submitRegistration(event) {
    event.preventDefault();
    const form = event.currentTarget;
    setFeedback('#registration-feedback', 'Mengirim pendaftaran...');

    try {
        const response = await api.post('/registrations', payloadFromForm(form));
        const data = response.data?.data || response.data?.registration || response.data;
        setFeedback(
            '#registration-feedback',
            `Berhasil. Nomor: ${data.registration_number || '-'} | ID: ${data.id || '-'}`,
            'success',
        );
        form.reset();
        form.academic_year.value = '2026/2027';
        form.wave.value = '1';
    } catch (error) {
        setFeedback('#registration-feedback', readError(error), 'error');
    }
}

async function checkRegistrationStatus(event) {
    event.preventDefault();
    const number = new FormData(event.currentTarget).get('registration_number');
    $('#status-result').textContent = 'Memeriksa status...';

    try {
        const response = await api.get(`/registrations/${encodeURIComponent(number)}/status`);
        const data = response.data?.data || response.data;
        state.lastRegistrationStatus = data;
        const summary = data.payment_summary || {};
        const options = summary.installment_options || [];
        $('#status-result').innerHTML = `
            <strong>${data.student_name || data.registration_number || number}</strong><br>
            Status: ${data.status || '-'}<br>
            Pembayaran: ${summary.payment_status || data.payment?.status || '-'}<br>
            Total biaya: ${formatCurrency(summary.total_amount)}<br>
            Sudah dibayar: ${formatCurrency(summary.paid_amount)}<br>
            Sisa tagihan: ${formatCurrency(summary.remaining_amount)}
            ${options.length ? `
                <div class="installment-actions">
                    ${options.map((option) => `
                        <button class="secondary-button installment-button" type="button" data-installment-percent="${option.percent}">
                            Bayar ${option.percent}% (${formatCurrency(option.amount)})
                        </button>
                    `).join('')}
                </div>
            ` : '<p class="muted">Tagihan sudah selesai atau biaya PPDB gratis.</p>'}
        `;
    } catch (error) {
        $('#status-result').textContent = readError(error, 'Nomor pendaftaran tidak ditemukan.');
    }
}

async function uploadDocument(event) {
    event.preventDefault();
    const form = event.currentTarget;
    const data = new FormData(form);
    const registrationId = data.get('registration_id');
    const payload = new FormData();
    payload.append('documents[0][type]', data.get('document_type'));
    payload.append('documents[0][file]', data.get('document_file'));
    setFeedback('#document-feedback', 'Mengupload dokumen...');

    try {
        await api.post(`/registrations/${registrationId}/documents`, payload);
        setFeedback('#document-feedback', 'Dokumen berhasil diupload.', 'success');
        form.reset();
    } catch (error) {
        setFeedback('#document-feedback', readError(error), 'error');
    }
}

async function createPayment(eventOrRegistrationId, installmentPercent = null) {
    eventOrRegistrationId?.preventDefault?.();
    const registrationId = typeof eventOrRegistrationId === 'string'
        ? eventOrRegistrationId
        : new FormData(eventOrRegistrationId.currentTarget).get('registration_id');
    const payload = installmentPercent ? { installment_percent: Number(installmentPercent) } : {};
    setFeedback('#payment-feedback', 'Membuat pembayaran...');

    try {
        const response = await api.post(`/registrations/${registrationId}/payment`, payload);
        const data = response.data?.data || response.data?.payment || response.data;
        if (data.snap_token && window.snap) {
            window.snap.pay(data.snap_token, {
                onSuccess: () => setFeedback('#payment-feedback', 'Pembayaran berhasil. Cek status beberapa saat lagi.', 'success'),
                onPending: () => setFeedback('#payment-feedback', 'Pembayaran dibuat dan menunggu penyelesaian.', 'success'),
                onError: () => setFeedback('#payment-feedback', 'Pembayaran gagal diproses Midtrans.', 'error'),
                onClose: () => setFeedback('#payment-feedback', 'Popup pembayaran ditutup.', ''),
            });
        } else {
            setFeedback(
                '#payment-feedback',
                `Pembayaran dibuat. Nominal: ${formatCurrency(data.amount)} | Status: ${data.status || '-'}`,
                'success',
            );
        }

        return data;
    } catch (error) {
        setFeedback('#payment-feedback', readError(error), 'error');
        return null;
    }
}

async function adminLogin(event) {
    event.preventDefault();
    setFeedback('#admin-login-feedback', 'Login...');

    try {
        const response = await api.post('/auth/login', payloadFromForm(event.currentTarget));
        state.token = response.data?.token || response.data?.access_token;
        if (!state.token) {
            throw new Error('Token tidak ditemukan.');
        }

        localStorage.setItem('alghazaly_admin_token', state.token);
        api.defaults.headers.common.Authorization = `Bearer ${state.token}`;
        setFeedback('#admin-login-feedback', 'Login berhasil.', 'success');
        showAdminWorkspace();
        await loadAdminData();
    } catch (error) {
        setFeedback('#admin-login-feedback', readError(error, 'Login gagal.'), 'error');
    }
}

function showAdminWorkspace() {
    $('#admin-login-panel').classList.add('hidden');
    $('#admin-workspace').classList.remove('hidden');
    $('#logout-admin').classList.remove('hidden');
}

function showAdminLogin() {
    $('#admin-login-panel').classList.remove('hidden');
    $('#admin-workspace').classList.add('hidden');
    $('#logout-admin').classList.add('hidden');
}

function logoutAdmin() {
    state.token = null;
    localStorage.removeItem('alghazaly_admin_token');
    delete api.defaults.headers.common.Authorization;
    showAdminLogin();
}

async function loadAdminData() {
    if (!state.token) {
        showAdminLogin();
        return;
    }

    showAdminWorkspace();
    await Promise.allSettled([
        loadDashboard(),
        loadAdminProfile(),
        loadAdminPosts(),
        loadAdminTeachers(),
        loadAdminTestimonials(),
        loadAdminAlbums(),
        loadRegistrations(),
    ]);
}

async function loadDashboard() {
    try {
        const response = await api.get('/admin/dashboard');
        const data = response.data?.data || response.data || {};
        const metrics = [
            ['Pendaftaran', data.registrations_count ?? data.total_registrations ?? 0],
            ['Siswa', data.students_count ?? data.total_students ?? 0],
            ['Berita', data.posts_count ?? data.total_posts ?? 0],
            ['Pesan', data.messages_count ?? data.total_messages ?? 0],
        ];

        $('#admin-metrics').innerHTML = metrics.map(([label, value]) => `
            <article class="metric-card">
                <h3>${label}</h3>
                <strong>${value}</strong>
            </article>
        `).join('');
    } catch (error) {
        $('#admin-metrics').innerHTML = `<p class="muted">${readError(error, 'Dashboard belum bisa dimuat.')}</p>`;
    }
}

async function loadAdminProfile() {
    try {
        const response = await api.get('/admin/settings');
        const data = flattenSettings(response.data);
        const form = $('#profile-form');
        if (form) {
            [
                'school_name',
                'homepage_hero_title',
                'homepage_hero_subtitle',
                'school_address',
                'school_email',
                'school_phone',
                'school_whatsapp',
                'ppdb_status',
                'ppdb_academic_year',
                'ppdb_registration_fee',
            ].forEach((key) => {
                if (form.elements[key]) {
                    form.elements[key].value = data[key] ?? '';
                }
            });
        }

        $('#profile-preview').innerHTML = `
            <strong>${data.school_name || '-'}</strong><br>
            ${data.homepage_hero_title || '-'}<br>
            ${data.homepage_hero_subtitle || '-'}<br>
            ${data.school_address || '-'}<br>
            PPDB: ${data.ppdb_status || '-'} | ${data.ppdb_academic_year || '-'}
        `;
    } catch (error) {
        $('#profile-preview').textContent = readError(error, 'Profil belum bisa dimuat.');
    }
}

async function saveAdminProfile(event) {
    event.preventDefault();
    setFeedback('#profile-feedback', 'Menyimpan profil...');
    const data = payloadFromForm(event.currentTarget);
    const settings = [
        ['school_name', 'text', 'general'],
        ['homepage_hero_title', 'text', 'homepage'],
        ['homepage_hero_subtitle', 'textarea', 'homepage'],
        ['school_address', 'textarea', 'contact'],
        ['school_email', 'email', 'contact'],
        ['school_phone', 'phone', 'contact'],
        ['school_whatsapp', 'phone', 'contact'],
        ['ppdb_status', 'text', 'ppdb'],
        ['ppdb_academic_year', 'text', 'ppdb'],
        ['ppdb_registration_fee', 'number', 'ppdb'],
    ].map(([key, type, group]) => ({
        key,
        type,
        group,
        value: data[key] ?? '',
        is_public: true,
    }));

    try {
        await api.put('/admin/settings', { settings });
        setFeedback('#profile-feedback', 'Profil beranda tersimpan.', 'success');
        await Promise.allSettled([loadAdminProfile(), loadPublicData()]);
    } catch (error) {
        setFeedback('#profile-feedback', readError(error), 'error');
    }
}

async function loadAdminPosts() {
    try {
        const response = await api.get('/admin/posts');
        const posts = unwrapCollection(response);
        $('#admin-post-list').innerHTML = posts.slice(0, 10).map((post) => `
            <div class="list-row">
                <div>
                    <h3>${post.title}</h3>
                    <p>${post.type || 'news'} | ${post.is_published ? 'published' : 'draft'}</p>
                </div>
                ${badge(post.event_date ? formatDate(post.event_date) : post.status || 'konten')}
            </div>
        `).join('') || '<p class="muted">Belum ada konten.</p>';
    } catch (error) {
        $('#admin-post-list').innerHTML = `<p class="muted">${readError(error, 'Konten belum bisa dimuat.')}</p>`;
    }
}

async function loadAdminTeachers() {
    try {
        const response = await api.get('/admin/teachers');
        const teachers = unwrapCollection(response);
        $('#admin-teacher-list').innerHTML = teachers.map((teacher) => `
            <div class="list-row">
                <div>
                    <h3>${teacher.name}</h3>
                    <p>${teacher.position || '-'}${teacher.subject ? ` | ${teacher.subject}` : ''}</p>
                </div>
                <div class="row-actions">
                    ${badge(teacher.is_active ? 'tampil' : 'draft')}
                    <button class="mini-button" type="button" data-delete-teacher="${teacher.teacher_id || teacher.id}">Hapus</button>
                </div>
            </div>
        `).join('') || '<p class="muted">Belum ada data guru.</p>';

        $$('[data-delete-teacher]').forEach((button) => {
            button.addEventListener('click', () => deleteAdminItem('teachers', button.dataset.deleteTeacher, loadAdminTeachers));
        });
    } catch (error) {
        $('#admin-teacher-list').innerHTML = `<p class="muted">${readError(error, 'Data guru belum bisa dimuat.')}</p>`;
    }
}

async function createTeacher(event) {
    event.preventDefault();
    setFeedback('#teacher-feedback', 'Menyimpan guru...');
    const form = event.currentTarget;
    const payload = payloadFromForm(form);
    payload.nip = payload.nip || `TEMP-${Date.now()}`;
    payload.is_active = form.is_active.checked;
    payload.order = Number(payload.order || 0);

    try {
        await api.post('/admin/teachers', payload);
        setFeedback('#teacher-feedback', 'Data guru tersimpan dan siap tampil di beranda.', 'success');
        form.reset();
        form.is_active.checked = true;
        form.order.value = 0;
        await Promise.allSettled([loadAdminTeachers(), loadPublicData()]);
    } catch (error) {
        setFeedback('#teacher-feedback', readError(error), 'error');
    }
}

async function loadAdminTestimonials() {
    try {
        const response = await api.get('/admin/testimonials');
        const testimonials = unwrapCollection(response);
        $('#admin-testimonial-list').innerHTML = testimonials.map((testimonial) => `
            <div class="list-row">
                <div>
                    <h3>${testimonial.name}</h3>
                    <p>${testimonial.role || '-'} | rating ${testimonial.rating || '-'}</p>
                </div>
                <div class="row-actions">
                    ${badge(testimonial.is_published ? 'tampil' : 'draft')}
                    <button class="mini-button" type="button" data-delete-testimonial="${testimonial.testimonial_id || testimonial.id}">Hapus</button>
                </div>
            </div>
        `).join('') || '<p class="muted">Belum ada testimoni.</p>';

        $$('[data-delete-testimonial]').forEach((button) => {
            button.addEventListener('click', () => deleteAdminItem('testimonials', button.dataset.deleteTestimonial, loadAdminTestimonials));
        });
    } catch (error) {
        $('#admin-testimonial-list').innerHTML = `<p class="muted">${readError(error, 'Testimoni belum bisa dimuat.')}</p>`;
    }
}

async function createTestimonial(event) {
    event.preventDefault();
    setFeedback('#testimonial-feedback', 'Menyimpan testimoni...');
    const form = event.currentTarget;
    const payload = payloadFromForm(form);
    payload.is_published = form.is_published.checked;
    payload.rating = Number(payload.rating || 5);

    try {
        await api.post('/admin/testimonials', payload);
        setFeedback('#testimonial-feedback', 'Testimoni tersimpan dan siap tampil di beranda.', 'success');
        form.reset();
        form.is_published.checked = true;
        form.rating.value = 5;
        await Promise.allSettled([loadAdminTestimonials(), loadPublicData()]);
    } catch (error) {
        setFeedback('#testimonial-feedback', readError(error), 'error');
    }
}

async function loadAdminAlbums() {
    try {
        const response = await api.get('/admin/albums');
        const albums = unwrapCollection(response);
        $('#admin-album-list').innerHTML = albums.map((album) => `
            <div class="list-row">
                <div>
                    <h3>${album.title}</h3>
                    <p>${album.description || 'Tanpa deskripsi'}</p>
                </div>
                <div class="row-actions">
                    ${badge(album.is_published ? 'tampil' : 'draft')}
                    <button class="mini-button" type="button" data-delete-album="${album.album_id || album.id}">Hapus</button>
                </div>
            </div>
        `).join('') || '<p class="muted">Belum ada album.</p>';

        $$('[data-delete-album]').forEach((button) => {
            button.addEventListener('click', () => deleteAdminItem('albums', button.dataset.deleteAlbum, loadAdminAlbums));
        });
    } catch (error) {
        $('#admin-album-list').innerHTML = `<p class="muted">${readError(error, 'Album belum bisa dimuat.')}</p>`;
    }
}

async function createAlbum(event) {
    event.preventDefault();
    setFeedback('#album-feedback', 'Menyimpan album...');
    const form = event.currentTarget;
    const payload = payloadFromForm(form);
    payload.is_published = form.is_published.checked;
    payload.order = Number(payload.order || 0);

    try {
        await api.post('/admin/albums', payload);
        setFeedback('#album-feedback', 'Album tersimpan dan siap tampil di beranda.', 'success');
        form.reset();
        form.is_published.checked = true;
        form.cover.value = '/images/school-hero.png';
        form.order.value = 0;
        await Promise.allSettled([loadAdminAlbums(), loadPublicData()]);
    } catch (error) {
        setFeedback('#album-feedback', readError(error), 'error');
    }
}

async function deleteAdminItem(resource, id, reload) {
    if (!id) {
        return;
    }

    await api.delete(`/admin/${resource}/${id}`);
    await Promise.allSettled([reload(), loadPublicData(), loadDashboard()]);
}

async function createPost(event) {
    event.preventDefault();
    setFeedback('#post-feedback', 'Menyimpan konten...');
    const payload = payloadFromForm(event.currentTarget);
    payload.is_published = event.currentTarget.is_published.checked;

    try {
        await api.post('/admin/posts', payload);
        setFeedback('#post-feedback', 'Konten tersimpan.', 'success');
        event.currentTarget.reset();
        event.currentTarget.is_published.checked = true;
        await Promise.allSettled([loadAdminPosts(), loadPublicData(), loadDashboard()]);
    } catch (error) {
        setFeedback('#post-feedback', readError(error), 'error');
    }
}

async function loadRegistrations() {
    try {
        const response = await api.get('/admin/registrations');
        const registrations = unwrapCollection(response);
        $('#registration-list').innerHTML = registrations.slice(0, 15).map((registration) => `
            <div class="list-row">
                <div>
                    <h3>${registration.student_name || '-'}</h3>
                    <p>${registration.registration_number || '-'} | ${registration.status || '-'}</p>
                </div>
                <div class="row-actions">
                    <button class="mini-button" type="button" data-registration-id="${registration.id}">Detail</button>
                </div>
            </div>
        `).join('') || '<p class="muted">Belum ada pendaftaran.</p>';

        $$('#registration-list [data-registration-id]').forEach((button) => {
            button.addEventListener('click', () => showRegistrationDetail(registrations.find((item) => String(item.id) === button.dataset.registrationId)));
        });
    } catch (error) {
        $('#registration-list').innerHTML = `<p class="muted">${readError(error, 'Data pendaftaran belum bisa dimuat.')}</p>`;
    }
}

function showRegistrationDetail(registration) {
    if (!registration) {
        return;
    }

    state.selectedRegistration = registration;
    const documents = registration.documents || registration.registration_documents || [];
    $('#registration-detail').innerHTML = `
        <div class="panel-heading">
            <h2>${registration.student_name || '-'}</h2>
            ${badge(registration.status)}
        </div>
        <p class="muted">${registration.registration_number || '-'} | ${registration.parent_name || 'Orang tua belum diisi'}</p>
        <div class="form-grid">
            <label>Ubah status
                <select id="registration-status-select">
                    ${['submitted', 'document_review', 'verified', 'payment_pending', 'paid', 'accepted', 'rejected'].map((status) => `
                        <option value="${status}" ${registration.status === status ? 'selected' : ''}>${status}</option>
                    `).join('')}
                </select>
            </label>
            <button class="secondary-button" id="update-registration-status" type="button">Simpan status</button>
        </div>
        <h3>Dokumen</h3>
        <div class="list-table">
            ${documents.length ? documents.map((document) => `
                <div class="list-row">
                    <div>
                        <h3>${document.document_type || document.type || 'Dokumen'}</h3>
                        <p>${document.status || 'pending'}</p>
                    </div>
                    <div class="row-actions">
                        <button class="mini-button" data-document-id="${document.id}" data-document-status="verified" type="button">Terima</button>
                        <button class="mini-button" data-document-id="${document.id}" data-document-status="rejected" type="button">Tolak</button>
                    </div>
                </div>
            `).join('') : '<p class="muted">Belum ada dokumen.</p>'}
        </div>
    `;

    $('#update-registration-status').addEventListener('click', updateRegistrationStatus);
    $$('#registration-detail [data-document-id]').forEach((button) => {
        button.addEventListener('click', () => updateDocumentStatus(button.dataset.documentId, button.dataset.documentStatus));
    });
}

async function updateRegistrationStatus() {
    const status = $('#registration-status-select').value;
    await api.patch(`/admin/registrations/${state.selectedRegistration.id}/status`, { status });
    await loadRegistrations();
    await loadDashboard();
}

async function updateDocumentStatus(documentId, status) {
    await api.patch(`/admin/registrations/${state.selectedRegistration.id}/documents/${documentId}`, { status });
    await loadRegistrations();
}

function bindEvents() {
    $$('[data-view]').forEach((element) => {
        element.addEventListener('click', (event) => {
            const view = event.currentTarget.dataset.view;
            if (view) {
                switchView(view);
            }
        });
    });

    $$('.tab-button').forEach((button) => {
        button.addEventListener('click', () => switchAdminTab(button.dataset.adminTab));
    });

    $('#registration-form')?.addEventListener('submit', submitRegistration);
    $('#status-form')?.addEventListener('submit', checkRegistrationStatus);
    $('#document-form')?.addEventListener('submit', uploadDocument);
    $('#payment-form')?.addEventListener('submit', createPayment);
    $('#status-result')?.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-installment-percent]');
        if (!button || !state.lastRegistrationStatus?.registration_id) {
            return;
        }

        await createPayment(String(state.lastRegistrationStatus.registration_id), button.dataset.installmentPercent);
    });
    $('#admin-login-form')?.addEventListener('submit', adminLogin);
    $('#logout-admin')?.addEventListener('click', logoutAdmin);
    $('#profile-form')?.addEventListener('submit', saveAdminProfile);
    $('#post-form')?.addEventListener('submit', createPost);
    $('#teacher-form')?.addEventListener('submit', createTeacher);
    $('#testimonial-form')?.addEventListener('submit', createTestimonial);
    $('#album-form')?.addEventListener('submit', createAlbum);
    $('#refresh-public')?.addEventListener('click', loadPublicData);
    $('#refresh-profile')?.addEventListener('click', loadAdminProfile);
    $('#refresh-admin-posts')?.addEventListener('click', loadAdminPosts);
    $('#refresh-teachers')?.addEventListener('click', loadAdminTeachers);
    $('#refresh-testimonials')?.addEventListener('click', loadAdminTestimonials);
    $('#refresh-albums')?.addEventListener('click', loadAdminAlbums);
    $('#refresh-registrations')?.addEventListener('click', loadRegistrations);
}

bindEvents();
loadPublicData();

if (window.location.hash.replace('#', '')) {
    switchView(window.location.hash.replace('#', ''));
}

if (state.token) {
    loadAdminData();
}
