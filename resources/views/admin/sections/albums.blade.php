{{-- ═══ ALBUMS ══════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'albums'" x-data="albumsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Album Foto</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} album`"></p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Album
        </button>
    </div>

    <div x-show="loading" class="p-10 flex justify-center">
        <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-if="items.length === 0">
            <div class="col-span-3 adm-card p-10 text-center text-gray-400">Belum ada album.</div>
        </template>
        <template x-for="item in items" :key="item.album_id ?? item.id">
            <div class="adm-card overflow-hidden group">
                <div class="relative aspect-video bg-gray-100">
                    <template x-if="item.cover_image">
                        <img :src="item.cover_image" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!item.cover_image">
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </template>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                        <button @click.stop="edit(item)" class="adm-btn bg-white text-gray-800 px-3 py-1.5 text-xs shadow">Edit</button>
                        <button @click.stop="openPhotos(item)" class="adm-btn text-white px-3 py-1.5 text-xs shadow" style="background:#019342;">
                            <svg class="h-3.5 w-3.5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Kelola Foto
                        </button>
                        <template x-if="confirmId !== item.id">
                            <button @click.stop="confirmId = item.id" class="adm-btn bg-red-500 text-white px-3 py-1.5 text-xs shadow">Hapus</button>
                        </template>
                        <template x-if="confirmId === item.id">
                            <button @click.stop="remove(item.id)" class="adm-btn bg-red-700 text-white px-3 py-1.5 text-xs shadow">Konfirmasi?</button>
                        </template>
                    </div>
                    {{-- Photo count badge --}}
                    <template x-if="item.medias_count > 0">
                        <div class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">
                            <span x-text="item.medias_count"></span> foto
                        </div>
                    </template>
                </div>
                <div class="p-4">
                    <p class="font-medium text-gray-900 text-sm truncate" x-text="item.title"></p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-400" x-text="item.date ? new Date(item.date).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : ''"></p>
                        <span class="badge" :class="item.is_published ? 'badge-green' : 'badge-gray'" x-text="item.is_published ? 'Published' : 'Draft'"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="meta.last_page > 1" class="flex items-center justify-center gap-2 mt-5">
        <button @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">← Prev</button>
        <span class="text-xs text-gray-500" x-text="`${meta.current_page} / ${meta.last_page}`"></span>
        <button @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">Next →</button>
    </div>

    {{-- Edit/Add Album Modal --}}
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Album' : 'Edit Album'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="adm-label">Judul Album <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.title" class="adm-input" placeholder="Judul album">
                    </div>
                    <div>
                        <label class="adm-label">Deskripsi</label>
                        <textarea x-model="form.description" class="adm-textarea" rows="3" placeholder="Deskripsi album..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="adm-label">Cover</label>
                            <div class="space-y-2">
                                <input type="text" x-model="form.cover_image" class="adm-input" placeholder="https://...">
                                <div class="flex flex-wrap gap-2">
                                    <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                        Upload
                                        <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'cover_image', $event.target.files); $event.target.value = null">
                                    </label>
                                    <button type="button" @click="openMediaPickerFor($data, 'cover_image')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                    <button type="button" x-show="form.cover_image" @click="form.cover_image = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                                </div>
                            </div>
                            <template x-if="form.cover_image">
                                <img :src="form.cover_image" class="mt-2 h-16 max-w-[180px] rounded-lg border border-gray-100 object-cover p-1">
                            </template>
                        </div>
                        <div>
                            <label class="adm-label">Tanggal</label>
                            <input type="date" x-model="form.date" class="adm-input">
                        </div>
                    </div>
                    <div>
                        <label class="adm-label">Status</label>
                        <select x-model="form.is_published" class="adm-select">
                            <option value="0">Draft</option>
                            <option value="1">Published</option>
                        </select>
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

    {{-- Kelola Foto Modal --}}
    <template x-teleport="body">
        <div x-show="photosModal.show" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="photosModal.show = false"></div>
            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh]" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                    <div>
                        <h3 class="font-semibold text-gray-900" x-text="`Kelola Foto — ${photosModal.albumTitle}`"></h3>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="`${photosModal.medias.length} foto dalam album ini`"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="openPhotosPicker()"
                            class="adm-btn adm-btn-sm text-white"
                            style="background:#019342;" onmouseover="this.style.background='#191654'" onmouseout="this.style.background='#019342'">
                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah dari Media
                        </button>
                        <button @click="photosModal.show = false" class="adm-btn-ghost p-1 rounded hover:bg-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-5">
                    <template x-if="photosModal.medias.length === 0">
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <svg class="h-12 w-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-semibold">Belum ada foto. Klik "Tambah dari Media" untuk memulai.</p>
                        </div>
                    </template>
                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                        <template x-for="media in photosModal.medias" :key="media.media_id ?? media.id">
                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                <img :src="media.url || (media.path ? `/storage/${media.path}` : '')"
                                    class="w-full h-full object-cover"
                                    :alt="media.filename ?? ''">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <button @click="removePhoto(media.media_id ?? media.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between shrink-0">
                    <span x-show="photosModal.saving" class="text-xs text-gray-400 flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        Menyimpan...
                    </span>
                    <span x-show="!photosModal.saving" class="text-xs text-gray-400">Perubahan langsung disimpan.</span>
                    <button @click="photosModal.show = false" class="adm-btn adm-btn-secondary adm-btn-sm">Selesai</button>
                </div>
            </div>
        </div>
    </template>
</div>
