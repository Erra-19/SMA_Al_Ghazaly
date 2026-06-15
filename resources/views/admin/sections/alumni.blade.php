{{-- ═══ ALUMNI ══════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'alumni'" x-data="alumniPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Data Alumni</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} alumni`"></p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Alumni
        </button>
    </div>

    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="p-10 flex justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr><th class="adm-th">Nama</th><th class="adm-th">Angkatan</th><th class="adm-th">Pekerjaan / Kampus</th><th class="adm-th">Testimoni</th><th class="adm-th"></th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="items.length === 0">
                        <tr><td colspan="5" class="td text-center text-gray-400 py-10">Belum ada data alumni.</td></tr>
                    </template>
                    <template x-for="item in items" :key="item.alumnus_id ?? item.id">
                        <tr class="adm-tr">
                            <td class="adm-td">
                                <div class="flex items-center gap-3">
                                    <template x-if="item.photo">
                                        <img :src="item.photo" class="h-9 w-9 rounded-full object-cover bg-gray-100 shrink-0">
                                    </template>
                                    <template x-if="!item.photo">
                                        <div class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 shrink-0"
                                            x-text="item.name.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                                    </template>
                                    <div>
                                        <span class="font-medium text-gray-900" x-text="item.name"></span>
                                        <template x-if="!item.is_published">
                                            <span class="ml-1.5 badge badge-gray text-[9px]">Tersembunyi</span>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="adm-td" x-text="item.graduation_year ?? item.year ?? '-'"></td>
                            <td class="td text-gray-600" x-text="item.occupation ?? item.current_institution ?? '-'"></td>
                            <td class="adm-td">
                                <template x-if="item.testimonial">
                                    <span class="badge badge-green text-[9px]">
                                        <svg class="h-2.5 w-2.5 mr-0.5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Ada
                                    </span>
                                </template>
                                <template x-if="!item.testimonial">
                                    <span class="text-[10px] text-gray-300">—</span>
                                </template>
                            </td>
                            <td class="adm-td">
                                <div class="flex items-center gap-1">
                                    <button @click="edit(item)" class="adm-btn-ghost adm-btn-ghost text-blue-500 hover:bg-blue-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <template x-if="confirmId !== item.id">
                                        <button @click="confirmId = item.id" class="adm-btn-ghost adm-btn-ghost text-red-400 hover:bg-red-50">
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
        <div x-show="meta.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
            <p class="text-xs text-gray-500" x-text="`Halaman ${meta.current_page} dari ${meta.last_page}`"></p>
            <div class="flex gap-1">
                <button @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">← Prev</button>
                <button @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm disabled:opacity-40">Next →</button>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Alumni' : 'Edit Alumni'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="adm-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="adm-input" placeholder="Nama alumni">
                        </div>
                        <div>
                            <label class="adm-label">Tahun Lulus</label>
                            <input type="number" x-model="form.graduation_year" class="adm-input" placeholder="2023">
                        </div>
                        <div>
                            <label class="adm-label">Lokasi / Kota</label>
                            <input type="text" x-model="form.location" class="adm-input" placeholder="Jakarta">
                        </div>
                        <div>
                            <label class="adm-label">Pekerjaan / Kampus</label>
                            <input type="text" x-model="form.occupation" class="adm-input" placeholder="UI, ITB, PT XYZ...">
                        </div>
                        <div>
                            <label class="adm-label">Foto</label>
                            <div class="space-y-2">
                                <input type="text" x-model="form.photo" class="adm-input" placeholder="https://... atau /storage/uploads/foto-alumni.jpg">
                                <div class="flex flex-wrap gap-2">
                                    <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                        Upload
                                        <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'photo', $event.target.files); $event.target.value = null">
                                    </label>
                                    <button type="button" @click="openMediaPickerFor($data, 'photo')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                    <button type="button" x-show="form.photo" @click="form.photo = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                                </div>
                            </div>
                            <template x-if="form.photo">
                                <img :src="form.photo" class="mt-2 h-16 w-16 rounded-lg border border-gray-100 object-cover p-1">
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Cerita / Prestasi</label>
                            <textarea x-model="form.story" class="adm-textarea" rows="3" placeholder="Cerita singkat..."></textarea>
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
