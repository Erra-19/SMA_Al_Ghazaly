{{-- ═══ TESTIMONIALS ════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'testimonials'" x-data="testimonialsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Testimoni</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} testimoni`"></p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
    </div>

    <div x-show="loading" class="p-10 flex justify-center">
        <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <template x-if="items.length === 0">
            <div class="col-span-2 adm-card p-10 text-center text-gray-400">Belum ada testimoni.</div>
        </template>
        <template x-for="item in items" :key="item.id">
            <div class="adm-card p-5">
                <div class="flex items-start gap-3 mb-3">
                    <template x-if="item.photo">
                        <img :src="item.photo" class="h-10 w-10 rounded-full object-cover bg-gray-100 shrink-0">
                    </template>
                    <template x-if="!item.photo">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-sm font-bold text-green-700 shrink-0"
                            x-text="item.name.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                        <p class="text-xs text-gray-500" x-text="[item.university, item.major].filter(Boolean).join(' — ') || item.role || ''"></p>
                        <p class="text-xs text-gray-400" x-text="item.graduation_year ? `Alumni ${item.graduation_year}` : item.role ?? ''"></p>
                        <div class="flex gap-0.5 mt-1">
                            <template x-for="i in [1,2,3,4,5]">
                                <svg class="h-3.5 w-3.5" :class="i <= (item.rating ?? 5) ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </template>
                        </div>
                    </div>
                    <span class="badge shrink-0" :class="item.is_published ? 'badge-green' : 'badge-gray'" x-text="item.is_published ? 'Aktif' : 'Draft'"></span>
                </div>
                <p class="text-sm text-gray-600 line-clamp-3 italic" x-text="`"${item.content ?? item.message ?? ''}"`"></p>
                <div class="flex items-center justify-end gap-1 mt-3 pt-3 border-t border-gray-100">
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
            </div>
        </template>
    </div>

    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Testimoni' : 'Edit Testimoni'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Alumnus Picker --}}
                        <div class="col-span-2">
                            <label class="adm-label">Hubungkan ke Alumni</label>
                            <select x-model="form.alumnus_id" @change="onAlumnusChange()" class="adm-select">
                                <option :value="null">— Tidak ditautkan ke alumni —</option>
                                <template x-for="a in alumniList" :key="a.alumnus_id ?? a.id">
                                    <option :value="a.alumnus_id ?? a.id"
                                        x-text="`${a.name} (${a.graduation_year ?? '-'})`"></option>
                                </template>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Pilih alumni untuk auto-isi nama, foto, kampus, jurusan, dan tahun lulus.</p>
                        </div>

                        <div>
                            <label class="adm-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="adm-input" placeholder="Nama alumni">
                        </div>
                        <div>
                            <label class="adm-label">Jabatan / Role</label>
                            <input type="text" x-model="form.role" class="adm-input" placeholder="Alumni, Orang Tua...">
                        </div>

                        {{-- Data Alumni (baru) --}}
                        <div>
                            <label class="adm-label">Universitas / Perguruan Tinggi</label>
                            <input type="text" x-model="form.university" class="adm-input" placeholder="Institut Teknologi Bandung (ITB)">
                        </div>
                        <div>
                            <label class="adm-label">Jurusan</label>
                            <input type="text" x-model="form.major" class="adm-input" placeholder="Teknik Informatika">
                        </div>
                        <div>
                            <label class="adm-label">Tahun Lulus SMA</label>
                            <input type="number" x-model="form.graduation_year" class="adm-input" min="2000" max="2030" placeholder="2024">
                        </div>
                        <div>
                            <label class="adm-label">Rating (1–5)</label>
                            <input type="number" x-model="form.rating" class="adm-input" min="1" max="5" placeholder="5">
                        </div>

                        <div class="col-span-2">
                            <label class="adm-label">Foto</label>
                            <div class="space-y-2">
                                <input type="text" x-model="form.photo" class="adm-input" placeholder="https://... atau /storage/uploads/foto-testimoni.jpg">
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
                            <label class="adm-label">Kutipan Testimoni <span class="text-red-500">*</span></label>
                            <textarea x-model="form.content" class="adm-textarea" rows="4" placeholder="Tuliskan pengalaman atau kesan alumni..."></textarea>
                        </div>
                        <div>
                            <label class="adm-label">Urutan</label>
                            <input type="number" x-model="form.order" class="adm-input" placeholder="0">
                        </div>
                        <div>
                            <label class="adm-label">Status</label>
                            <select x-model="form.is_published" class="adm-select">
                                <option value="1">Aktif</option>
                                <option value="0">Draft</option>
                            </select>
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
