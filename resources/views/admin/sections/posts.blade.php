{{-- ═══ POSTS ═══════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'posts'" x-data="postsPage()">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Berita & Artikel</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} post`"></p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Post
        </button>
    </div>

    {{-- Table --}}
    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="p-10 flex justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="adm-th">Judul</th>
                        <th class="adm-th">Kategori</th>
                        <th class="adm-th">Status</th>
                        <th class="adm-th">Tanggal</th>
                        <th class="adm-th"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="items.length === 0">
                        <tr><td colspan="5" class="td text-center text-gray-400 py-10">Belum ada post.</td></tr>
                    </template>
                    <template x-for="item in items" :key="item.id">
                        <tr class="adm-tr">
                            <td class="adm-td">
                                <div class="flex items-center gap-3">
                                    <template x-if="item.image">
                                        <img :src="item.image" class="h-10 w-10 rounded-lg object-cover bg-gray-100 shrink-0">
                                    </template>
                                    <div>
                                        <p class="font-medium text-gray-900 line-clamp-1" x-text="item.title"></p>
                                        <p class="text-xs text-gray-400" x-text="item.slug"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="adm-td" x-text="item.category?.name ?? '-'"></td>
                            <td class="adm-td">
                                <span class="badge" :class="item.is_published ? 'badge-green' : 'badge-gray'"
                                    x-text="item.is_published ? 'Published' : 'Draft'"></span>
                            </td>
                            <td class="adm-td" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
                            <td class="adm-td">
                                <div class="flex items-center gap-1">
                                    <button @click="edit(item)" class="adm-btn-ghost adm-btn-ghost text-blue-500 hover:bg-blue-50" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <template x-if="confirmId !== item.id">
                                        <button @click="confirmId = item.id" class="adm-btn-ghost adm-btn-ghost text-red-400 hover:bg-red-50" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
                                    <template x-if="confirmId === item.id">
                                        <div class="flex gap-1">
                                            <button @click="remove(item.id)" class="adm-btn adm-btn adm-btn-danger adm-btn-sm">Hapus?</button>
                                            <button @click="confirmId = null" class="adm-btn adm-btn-secondary adm-btn-sm">Batal</button>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        <div x-show="meta.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
            <p class="text-xs text-gray-500" x-text="`Halaman ${meta.current_page} dari ${meta.last_page}`"></p>
            <div class="flex gap-1">
                <button @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">← Prev</button>
                <button @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">Next →</button>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Post' : 'Edit Post'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Judul --}}
                        <div class="col-span-2">
                            <label class="adm-label">Judul <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.title" class="adm-input" placeholder="Judul post">
                        </div>

                        {{-- Jenis Konten + Kategori Topik --}}
                        <div>
                            <label class="adm-label">Jenis Konten</label>
                            <select x-model="form.type" class="adm-select">
                                <option value="news">Berita</option>
                                <option value="article">Artikel</option>
                                <option value="event">Kegiatan / Acara</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Kategori Topik</label>
                            <select x-model="form.category" class="adm-select">
                                <option value="">— Pilih —</option>
                                <option value="PPDB">PPDB</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Informasi">Informasi</option>
                                <option value="Libur">Libur</option>
                            </select>
                        </div>

                        {{-- Status Publish + Badge Status --}}
                        <div>
                            <label class="adm-label">Status Publish</label>
                            <select x-model="form.is_published" class="adm-select">
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Badge Status</label>
                            <select x-model="form.post_status" class="adm-select">
                                <option value="Penting">Penting</option>
                                <option value="Acara akan Datang">Acara akan Datang</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="col-span-2">
                            <label class="adm-label">Thumbnail</label>
                            <div class="space-y-2">
                                <input type="text" x-model="form.thumbnail" class="adm-input" placeholder="https://... atau /storage/uploads/thumbnail.jpg">
                                <div class="flex flex-wrap gap-2">
                                    <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                        Upload
                                        <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'thumbnail', $event.target.files); $event.target.value = null">
                                    </label>
                                    <button type="button" @click="openMediaPickerFor($data, 'thumbnail')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                    <button type="button" x-show="form.thumbnail" @click="form.thumbnail = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                                </div>
                            </div>
                            <template x-if="form.thumbnail">
                                <img :src="form.thumbnail" class="mt-2 h-16 max-w-[180px] rounded-lg border border-gray-100 object-cover p-1">
                            </template>
                        </div>

                        {{-- Event fields — tampil hanya saat type=event --}}
                        <template x-if="form.type === 'event'">
                            <div class="col-span-2 grid grid-cols-2 gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <div>
                                    <label class="adm-label">Tanggal Mulai Acara</label>
                                    <input type="datetime-local" x-model="form.event_start_at" class="adm-input">
                                </div>
                                <div>
                                    <label class="adm-label">Tanggal Selesai</label>
                                    <input type="datetime-local" x-model="form.event_end_at" class="adm-input">
                                </div>
                                <div class="col-span-2">
                                    <label class="adm-label">Lokasi Acara</label>
                                    <input type="text" x-model="form.event_location" class="adm-input" placeholder="Contoh: Aula SMA Al-Ghazaly">
                                </div>
                            </div>
                        </template>

                        {{-- Ringkasan / Excerpt --}}
                        <div class="col-span-2">
                            <label class="adm-label">Ringkasan Singkat</label>
                            <textarea x-model="form.summary" class="adm-textarea" rows="2" placeholder="Deskripsi singkat yang tampil di card / listing..."></textarea>
                        </div>

                        {{-- Konten --}}
                        <div class="col-span-2">
                            <label class="adm-label">Konten Lengkap <span class="text-red-500">*</span></label>
                            <textarea x-model="form.content" class="adm-textarea" rows="8" placeholder="Isi konten lengkap..."></textarea>
                        </div>
                    </div>
                    <div x-show="formError" x-text="formError" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-2"></div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button @click="showModal = false" class="adm-btn adm-btn-secondary">Batal</button>
                    <button @click="save()" :disabled="saving" class="adm-btn adm-btn-primary">
                        <span x-show="!saving" x-text="modalMode === 'add' ? 'Simpan' : 'Update'"></span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
