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
        <template x-for="item in items" :key="item.id">
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
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                        <button @click="edit(item)" class="adm-btn bg-white text-gray-800 px-3 py-1.5 text-xs shadow">Edit</button>
                        <template x-if="confirmId !== item.id">
                            <button @click="confirmId = item.id" class="adm-btn bg-red-500 text-white px-3 py-1.5 text-xs shadow">Hapus</button>
                        </template>
                        <template x-if="confirmId === item.id">
                            <button @click="remove(item.id)" class="adm-btn bg-red-700 text-white px-3 py-1.5 text-xs shadow">Konfirmasi?</button>
                        </template>
                    </div>
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

    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Album' : 'Edit Album'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
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
                                <input type="text" x-model="form.cover_image" class="adm-input" placeholder="https://... atau /storage/uploads/cover-album.jpg">
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
</div>
