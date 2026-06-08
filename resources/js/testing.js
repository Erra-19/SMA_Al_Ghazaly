import './bootstrap';

const api = window.axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
    },
});

const state = {
    token: localStorage.getItem('alghazaly_admin_token'),
};

if (state.token) {
    api.defaults.headers.common.Authorization = `Bearer ${state.token}`;
}

const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => Array.from(document.querySelectorAll(selector));

function setToken(token) {
    state.token = token;
    if (token) {
        localStorage.setItem('alghazaly_admin_token', token);
        api.defaults.headers.common.Authorization = `Bearer ${token}`;
    } else {
        localStorage.removeItem('alghazaly_admin_token');
        delete api.defaults.headers.common.Authorization;
    }
    $('#token-state').textContent = token ? 'Token aktif' : 'Belum login';
}

function formPayload(form) {
    return Object.fromEntries(new FormData(form).entries());
}

function stamp() {
    return new Date().toISOString().replace(/[-:.TZ]/g, '').slice(0, 14);
}

function boolPayload(payload, form, keys) {
    keys.forEach((key) => {
        payload[key] = Boolean(form.elements[key]?.checked);
    });
    return payload;
}

function output(selector, data) {
    const element = $(selector);
    element.textContent = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
}

function summarizeError(error) {
    return {
        status: error?.response?.status || null,
        message: error?.response?.data?.message || error.message,
        errors: error?.response?.data?.errors || null,
        data: error?.response?.data || null,
    };
}

async function run(selector, callback) {
    output(selector, 'Loading...');
    try {
        const result = await callback();
        output(selector, result);
        return result;
    } catch (error) {
        const summary = summarizeError(error);
        output(selector, summary);
        throw error;
    }
}

function collection(data) {
    if (Array.isArray(data)) return data;
    if (Array.isArray(data?.data)) return data.data;
    if (Array.isArray(data?.registrations)) return data.registrations;
    return [];
}

async function login(payload = null) {
    const body = payload || formPayload($('#test-login-form'));
    const { data } = await api.post('/auth/login', body);
    setToken(data.token);
    return {
        token_preview: `${data.token.slice(0, 18)}...`,
        user: data.user,
    };
}

async function createSettings() {
    const payload = formPayload($('#test-settings-form'));
    const settings = [
        ['school_name', payload.school_name, 'text', 'general'],
        ['homepage_hero_title', payload.school_name, 'text', 'homepage'],
        ['homepage_hero_subtitle', payload.homepage_hero_subtitle, 'textarea', 'homepage'],
        ['school_email', payload.school_email, 'email', 'contact'],
        ['ppdb_status', payload.ppdb_status, 'text', 'ppdb'],
        ['ppdb_academic_year', '2026/2027', 'text', 'ppdb'],
        ['ppdb_registration_fee', '0', 'number', 'ppdb'],
    ].map(([key, value, type, group]) => ({ key, value, type, group, is_public: true }));

    const { data } = await api.put('/admin/settings', { settings });
    const profile = await api.get('/profile');
    return { saved: data, public_profile: profile.data };
}

async function createPost() {
    const form = $('#test-post-form');
    const payload = boolPayload(formPayload(form), form, ['is_published']);
    payload.title = `${payload.title} ${stamp()}`;
    const { data } = await api.post('/admin/posts', payload);
    const publicPosts = await api.get('/posts');
    return { created: data, public_posts_count: collection(publicPosts.data).length };
}

async function createTeacher() {
    const form = $('#test-teacher-form');
    const payload = boolPayload(formPayload(form), form, ['is_active']);
    payload.nip = payload.nip || `TEST-${stamp()}`;
    payload.name = `${payload.name} ${stamp()}`;
    payload.order = 0;
    const { data } = await api.post('/admin/teachers', payload);
    const publicTeachers = await api.get('/teachers');
    return { created: data, public_found: collection(publicTeachers.data).some((item) => item.name === payload.name) };
}

async function createTestimonial() {
    const form = $('#test-testimonial-form');
    const payload = boolPayload(formPayload(form), form, ['is_published']);
    payload.name = `${payload.name} ${stamp()}`;
    payload.rating = Number(payload.rating || 5);
    const { data } = await api.post('/admin/testimonials', payload);
    const publicTestimonials = await api.get('/testimonials');
    return { created: data, public_found: collection(publicTestimonials.data).some((item) => item.name === payload.name) };
}

async function createAlbum() {
    const form = $('#test-album-form');
    const payload = boolPayload(formPayload(form), form, ['is_published']);
    payload.title = `${payload.title} ${stamp()}`;
    payload.order = 0;
    const { data } = await api.post('/admin/albums', payload);
    const publicAlbums = await api.get('/albums');
    return { created: data, public_found: collection(publicAlbums.data).some((item) => item.title === payload.title) };
}

async function createRegistration() {
    const payload = formPayload($('#test-registration-form'));
    payload.student_name = `${payload.student_name} ${stamp()}`;
    const { data } = await api.post('/registrations', payload);
    $('#test-registration-id').value = data.registration_id;
    $('#admin-registration-id').value = data.registration_id;
    $('#test-registration-number').value = data.registration_number;
    return data;
}

async function checkStatus() {
    const number = $('#test-registration-number').value;
    const { data } = await api.get(`/registrations/${encodeURIComponent(number)}/status`);
    return data;
}

async function createPayment() {
    const id = $('#test-registration-id').value;
    const installmentPercent = Number($('#test-installment-percent')?.value || 100);
    const { data } = await api.post(`/registrations/${id}/payment`, { installment_percent: installmentPercent });
    if (data.payment_id) {
        $('#admin-payment-id').value = data.payment_id;
    }
    return data;
}

async function uploadDocument() {
    const id = $('#test-registration-id').value;
    const file = $('#test-document-file').files[0];
    if (!file) {
        return { message: 'Pilih file jpg/png/pdf dulu.' };
    }

    const payload = new FormData();
    payload.append('documents[0][type]', 'test_document');
    payload.append('documents[0][file]', file);
    const { data } = await api.post(`/registrations/${id}/documents`, payload);
    return data;
}

async function loadAdminRegistrations() {
    const { data } = await api.get('/admin/registrations');
    const items = collection(data);
    if (items[0]?.registration_id) {
        $('#admin-registration-id').value = items[0].registration_id;
    }
    return data;
}

async function updateRegistrationStatus() {
    const id = $('#admin-registration-id').value;
    const status = $('#admin-registration-status').value;
    const { data } = await api.patch(`/admin/registrations/${id}/status`, { status });
    return data;
}

async function updatePayment() {
    const id = $('#admin-payment-id').value;
    const payload = {
        paid_amount: Number($('#admin-paid-amount').value || 0),
        payment_type: 'manual_test',
    };
    const status = $('#admin-payment-status').value;
    if (status) {
        payload.status = status;
    }

    const { data } = await api.patch(`/admin/payments/${id}`, payload);
    return data;
}

async function runSmokeTest() {
    const result = {};
    result.login = await login({ email: 'admin@alghazaly.test', password: 'password' });
    result.settings = await createSettings();
    result.post = await createPost();
    result.teacher = await createTeacher();
    result.testimonial = await createTestimonial();
    result.album = await createAlbum();
    result.registration = await createRegistration();
    result.payment = await createPayment();
    result.status = await checkStatus();
    result.dashboard = (await api.get('/admin/dashboard')).data;
    return result;
}

function bindEvents() {
    $('#test-login-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#auth-output', () => login());
    });

    $('#test-logout').addEventListener('click', () => {
        setToken(null);
        output('#auth-output', 'Token cleared.');
    });

    $('#load-public-test').addEventListener('click', () => {
        run('#public-output', async () => ({
            profile: (await api.get('/profile')).data,
            posts: (await api.get('/posts')).data,
            teachers: (await api.get('/teachers')).data,
            testimonials: (await api.get('/testimonials')).data,
            albums: (await api.get('/albums')).data,
        }));
    });

    $$('[data-public-endpoint]').forEach((button) => {
        button.addEventListener('click', () => {
            run('#public-output', async () => (await api.get(button.dataset.publicEndpoint)).data);
        });
    });

    $('#test-settings-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#settings-output', createSettings);
    });
    $('#test-post-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#post-output', createPost);
    });
    $('#test-teacher-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#teacher-output', createTeacher);
    });
    $('#test-testimonial-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#testimonial-output', createTestimonial);
    });
    $('#test-album-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#album-output', createAlbum);
    });
    $('#test-registration-form').addEventListener('submit', (event) => {
        event.preventDefault();
        run('#registration-output', createRegistration);
    });

    $('#test-check-status').addEventListener('click', () => run('#ppdb-output', checkStatus));
    $('#test-create-payment').addEventListener('click', () => run('#ppdb-output', createPayment));
    $('#test-upload-document').addEventListener('click', () => run('#ppdb-output', uploadDocument));
    $('#load-admin-registrations').addEventListener('click', () => run('#admin-ppdb-output', loadAdminRegistrations));
    $('#test-update-registration-status').addEventListener('click', () => run('#admin-ppdb-output', updateRegistrationStatus));
    $('#test-update-payment').addEventListener('click', () => run('#admin-ppdb-output', updatePayment));
    $('#load-admin-dashboard').addEventListener('click', () => run('#admin-ppdb-output', async () => (await api.get('/admin/dashboard')).data));
    $('#run-smoke-test').addEventListener('click', () => run('#admin-ppdb-output', runSmokeTest));
}

setToken(state.token);
bindEvents();
