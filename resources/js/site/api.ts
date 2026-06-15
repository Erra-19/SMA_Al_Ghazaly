import { Announcement, AlumnusItem, EventActivity, Post, Testimonial } from './types';

type Paginated<T> = {
  data?: T[];
};

const jsonHeaders = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
};

async function request<T>(url: string, options: RequestInit = {}): Promise<T> {
  const response = await fetch(url, {
    ...options,
    headers: {
      ...jsonHeaders,
      ...(options.headers || {}),
    },
  });

  const payload = await response.json().catch(() => ({}));

  if (!response.ok) {
    const message = payload?.message || Object.values(payload?.errors || {})?.flat()?.[0] || 'Request gagal diproses.';
    throw new Error(String(message));
  }

  return payload as T;
}

function collection<T>(payload: Paginated<T> | T[]): T[] {
  return Array.isArray(payload) ? payload : payload.data || [];
}

function formatDate(value?: string): string {
  if (!value) return '';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function eventDate(value?: string): EventActivity['date'] {
  const date = value ? new Date(value) : new Date();
  if (Number.isNaN(date.getTime())) {
    return { day: '-', month: '-', year: '-' };
  }

  return {
    day: date.toLocaleDateString('id-ID', { day: '2-digit' }),
    month: date.toLocaleDateString('id-ID', { month: 'short' }).toUpperCase(),
    year: date.toLocaleDateString('id-ID', { year: 'numeric' }),
  };
}

function categoryForAnnouncement(value?: string): Announcement['category'] {
  const normalized = String(value || '').toLowerCase();
  if (normalized.includes('ppdb')) return 'PPDB';
  if (normalized.includes('akademik')) return 'Akademik';
  if (normalized.includes('libur')) return 'Libur';
  return 'Informasi';
}

function statusForPost(value?: string): Announcement['status'] {
  const normalized = String(value || '').toLowerCase();
  if (normalized.includes('penting')) return 'Penting';
  if (normalized.includes('selesai')) return 'Selesai';
  return 'Acara akan Datang';
}

export async function getAnnouncements(): Promise<Announcement[]> {
  const payload = await request<Paginated<any>>('/api/posts?type=news&per_page=12');

  return collection(payload).map((post) => ({
    id: String(post.post_id || post.id || post.slug),
    title: post.title,
    category: categoryForAnnouncement(post.category || post.categories?.[0]?.category_name),
    date: formatDate(post.published_at || post.created_at),
    summary: post.summary || post.excerpt || String(post.content || '').replace(/<[^>]*>/g, '').slice(0, 180),
    content: post.content || post.summary || post.excerpt || '',
    status: statusForPost(post.post_status),
    image: post.thumbnail || undefined,
    author: post.author?.name || 'Humas Sekolah',
  }));
}

export async function getEvents(): Promise<EventActivity[]> {
  const payload = await request<Paginated<any>>('/api/posts?type=event&per_page=8');

  return collection(payload).map((post) => ({
    id: String(post.post_id || post.id || post.slug),
    title: post.title,
    date: eventDate(post.event_start_at || post.published_at || post.created_at),
    category: post.category || post.categories?.[0]?.category_name || 'Kegiatan',
    location: post.event_location || 'SMA Al-Ghazaly Bogor',
    time: post.event_start_at ? new Date(post.event_start_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : 'Sesuai jadwal',
    description: post.summary || post.excerpt || post.content || '',
  }));
}

export async function getArticles(): Promise<Post[]> {
  const payload = await request<Paginated<any>>('/api/posts?type=article&per_page=6');

  return collection(payload).map((post) => ({
    id: String(post.post_id || post.id || post.slug),
    title: post.title,
    category: post.category || post.categories?.[0]?.category_name || 'Artikel',
    date: formatDate(post.published_at || post.created_at),
    excerpt: post.summary || post.excerpt || String(post.content || '').replace(/<[^>]*>/g, '').slice(0, 160),
    content: post.content || post.summary || post.excerpt || '',
    readTime: '5 Menit Baca',
    image: post.thumbnail || '/images/school-hero.png',
  }));
}

export async function getPosts(type?: string): Promise<Post[]> {
  const params = new URLSearchParams({ per_page: '30' });
  if (type) params.set('type', type);
  const payload = await request<Paginated<any>>(`/api/posts?${params.toString()}`);

  return collection(payload).map((post) => ({
    id: String(post.post_id || post.id || post.slug),
    title: post.title,
    category: post.category || post.categories?.[0]?.category_name || 'Berita',
    date: formatDate(post.published_at || post.created_at),
    excerpt: post.summary || post.excerpt || String(post.content || '').replace(/<[^>]*>/g, '').slice(0, 180),
    content: post.content || post.summary || post.excerpt || '',
    readTime: '5 Menit Baca',
    image: post.thumbnail || '',
  }));
}

export async function getAlumni(): Promise<AlumnusItem[]> {
  const payload = await request<any[] | Paginated<any>>('/api/alumni?per_page=50');

  return collection(payload).map((item) => ({
    id: String(item.alumnus_id || item.id),
    name: item.name,
    graduation_year: item.graduation_year,
    photo: item.photo || undefined,
    current_institution: item.current_institution || item.occupation || undefined,
    major: item.major || undefined,
    achievement: item.achievement || item.story || undefined,
    testimonial: item.testimonial
      ? {
          id: String(item.testimonial.testimonial_id || item.testimonial.id),
          quote: item.testimonial.content || item.testimonial.quote || '',
          rating: item.testimonial.rating ?? undefined,
        }
      : undefined,
  }));
}

export async function getTestimonials(): Promise<Testimonial[]> {
  const payload = await request<any[] | Paginated<any>>('/api/testimonials');

  return collection(payload).map((item) => ({
    id: String(item.testimonial_id || item.id),
    name: item.name,
    university: item.university || item.role || 'Alumni SMA Al-Ghazaly',
    major: item.major || 'Alumni',
    year: item.graduation_year ? `Alumni ${item.graduation_year}` : item.role || 'Alumni',
    quote: item.content || item.quote || '',
    avatar: item.photo || '/images/school-hero.png',
  }));
}

export async function getTeachers() {
  const payload = await request<any[] | Paginated<any>>('/api/teachers');

  return collection(payload).map((teacher) => ({
    id: String(teacher.teacher_id || teacher.id),
    name: teacher.name,
    role: teacher.position || teacher.subject || 'Pengajar SMA Al-Ghazaly',
    category: teacher.category || 'bk-staf',
    image: teacher.photo || '/images/school-hero.png',
    education: teacher.education || '-',
    philosophy: teacher.philosophy || teacher.bio || 'Mendidik dengan ilmu, adab, dan keteladanan.',
    experience: teacher.experience || '-',
    email: teacher.email || 'info@smaalghazaly.sch.id',
    tags: Array.isArray(teacher.tags) ? teacher.tags : (teacher.subject ? [teacher.subject] : []),
    isLeadership: Boolean(teacher.is_leadership),
  }));
}

export async function getPrograms(type?: string) {
  const query = type ? `?type=${encodeURIComponent(type)}` : '';
  const payload = await request<any[] | Paginated<any>>(`/api/programs${query}`);

  return collection(payload).map((item) => ({
    id: String(item.program_id || item.id),
    title: item.title,
    subtitle: item.subtitle || '',
    description: item.description || '',
    image: item.image || '',
    icon: item.icon || 'BookOpen',
    badge: item.badge || '',
    stats: item.stats || '',
    type: item.type || 'unggulan',
    features: Array.isArray(item.features) ? item.features : [],
  }));
}

export async function getFacilities() {
  const payload = await request<any[] | Paginated<any>>('/api/facilities');

  return collection(payload).map((item) => ({
    id: String(item.facility_id || item.id),
    name: item.name,
    category: item.category || 'akademik',
    image: item.image || '',
    iconName: item.icon_name || 'Fasilitas',
    shortDesc: item.short_desc || '',
    longDesc: item.long_desc || item.short_desc || '',
    capacity: item.capacity || '',
    specs: Array.isArray(item.specs) ? item.specs : [],
    operationalHours: item.operational_hours || '',
    location: item.location || '',
    isFeatured: Boolean(item.is_featured),
  }));
}

export async function getProfileData() {
  return request<any>('/api/profile');
}

export async function getAlbums() {
  const payload = await request<any[] | Paginated<any>>('/api/albums');

  return collection(payload).map((item) => ({
    id: String(item.album_id || item.id || item.slug),
    slug: item.slug || '',
    title: item.title,
    description: item.description || '',
    image: item.cover || item.cover_image || '',
    badge: 'Galeri',
  }));
}

export async function getAlbum(slug: string) {
  const data = await request<any>(`/api/albums/${encodeURIComponent(slug)}`);
  return {
    id: String(data.album_id || data.id),
    slug: data.slug || slug,
    title: data.title,
    description: data.description || '',
    cover: data.cover || data.cover_image || '',
    medias: (data.medias || []).map((m: any) => ({
      id: String(m.media_id || m.id),
      url: m.url || (m.path ? `/storage/${m.path}` : ''),
      filename: m.filename || m.name || '',
      mime_type: m.mime_type || 'image/jpeg',
    })),
  };
}

export async function getSettings(): Promise<Record<string, string>> {
  return request<Record<string, string>>('/api/settings');
}

export async function submitContact(payload: Record<string, string>) {
  return request('/api/contact', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

export async function getFormConfig(slug: string): Promise<any | null> {
  try {
    return await request<any>(`/api/forms/${slug}`);
  } catch {
    return null;
  }
}

export async function uploadFormFile(file: File, fieldKey: string, fieldLabel: string) {
  const fd = new FormData();
  fd.append('file', file);
  fd.append('field_key', fieldKey);
  fd.append('field_label', fieldLabel);
  const res = await fetch('/api/ppdb/upload-file', { method: 'POST', body: fd });
  if (!res.ok) {
    const err = await res.json().catch(() => ({})) as { message?: string };
    throw new Error(err.message || 'Gagal mengupload file.');
  }
  return res.json() as Promise<{
    url: string; path: string; field_key: string; field_label: string;
    original_name: string; mime_type: string; file_size: number;
  }>;
}

export async function submitRegistration(payload: Record<string, unknown>) {
  return request<{ registration_number: string; registration_id: number }>('/api/registrations', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

export async function checkRegistrationStatus(number: string) {
  return request<any>(`/api/registrations/${encodeURIComponent(number)}/status`);
}

export async function submitPaymentProof(regNumber: string, proof: File | null, notes: string) {
  const fd = new FormData();
  if (proof) fd.append('proof', proof);
  if (notes) fd.append('notes', notes);
  const response = await fetch(`/api/registrations/${encodeURIComponent(regNumber)}/payment-proof`, {
    method: 'POST',
    headers: { Accept: 'application/json' },
    body: fd,
  });
  if (!response.ok) {
    const data = await response.json().catch(() => ({}));
    throw new Error(data.message || 'Gagal mengirim bukti pembayaran.');
  }
  return response.json();
}

export async function getPublicSettings(): Promise<Record<string, string>> {
  try {
    const data = await request<any>('/api/settings');
    if (Array.isArray(data)) return Object.fromEntries(data.map((s: any) => [s.key, s.value ?? '']));
    if (data && typeof data === 'object') {
      const flat: Record<string, string> = {};
      Object.values(data).forEach((group: any) => {
        if (Array.isArray(group)) group.forEach((s: any) => { flat[s.key] = s.value ?? ''; });
      });
      return flat;
    }
    return {};
  } catch { return {}; }
}

export interface SiteStats {
  accepted_registrations: number;
  active_teachers: number;
  ekskul_count: number;
}

export async function getStats(): Promise<SiteStats> {
  return request<SiteStats>('/api/stats');
}

function calendarDate(value?: string) {
  const date = value ? new Date(value) : new Date();
  return {
    day:   date.toLocaleDateString('id-ID', { day: '2-digit' }),
    month: date.toLocaleDateString('id-ID', { month: 'short' }).toUpperCase(),
    year:  date.toLocaleDateString('id-ID', { year: 'numeric' }),
  };
}

export async function getAcademicCalendars(academicYear?: string) {
  const query = academicYear ? `?academic_year=${encodeURIComponent(academicYear)}` : '';
  const payload = await request<any[]>(`/api/academic-calendars${query}`);
  const items = Array.isArray(payload) ? payload : [];

  return items.map((item) => {
    // API returns full ISO datetime ("2025-03-28T00:00:00.000000Z") — strip to date-only
    const startIso: string | null = item.start_date ? String(item.start_date).substring(0, 10) : null;
    const endIso: string | null   = item.end_date   ? String(item.end_date).substring(0, 10)   : null;
    const isRange = startIso && endIso && endIso !== startIso;

    return {
      id:          String(item.calendar_id || item.id),
      title:       item.title,
      date:        calendarDate(startIso || item.start_date),
      endDate:     isRange ? calendarDate(endIso!) : null,
      startIso,
      endIso:      isRange ? endIso : null,
      category:    item.category || 'Akademik',
      color:       item.color || 'green',
      location:    'SMA Al-Ghazaly Bogor',
      time:        'Sesuai jadwal',
      description: item.description || '',
    };
  });
}
