{{-- ═══ STUDENTS ═══════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'students'" x-data="studentsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Data Murid</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} murid`"></p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="downloadTemplate()" class="adm-btn adm-btn-secondary adm-btn-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Template Excel
            </button>
            <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
                Upload Excel
                <input type="file" class="hidden" accept=".xlsx,.xls" @change="importFile($event.target.files); $event.target.value=null">
            </label>
            <button @click="openAdd()" class="adm-btn adm-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Murid
            </button>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="adm-card p-4 mb-4 flex flex-wrap gap-3">
        <input type="text" x-model="filters.search" @input.debounce.400ms="load(1)"
            class="adm-input w-52 text-sm" placeholder="Cari nama / NIS / NISN...">
        <select x-model="filters.grade_level" @change="load(1)" class="adm-input w-32 text-sm">
            <option value="">Semua Kelas</option>
            <option value="X">Kelas X</option>
            <option value="XI">Kelas XI</option>
            <option value="XII">Kelas XII</option>
        </select>
        <select x-model="filters.status" @change="load(1)" class="adm-input w-36 text-sm">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
            <option value="graduated">Lulus</option>
            <option value="transferred">Pindah</option>
            <option value="dropped_out">Keluar</option>
        </select>
        <input type="text" x-model="filters.academic_year" @input.debounce.500ms="load(1)"
            class="adm-input w-32 text-sm" placeholder="Tahun ajaran">
    </div>

    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="p-10 flex justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="adm-th">Murid</th>
                        <th class="adm-th">NISN / NIS</th>
                        <th class="adm-th">Kelas</th>
                        <th class="adm-th">Tahun Ajaran</th>
                        <th class="adm-th">Status</th>
                        <th class="adm-th"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="items.length === 0">
                        <tr><td colspan="6" class="adm-td text-center text-gray-400 py-10">Belum ada data murid.</td></tr>
                    </template>
                    <template x-for="item in items" :key="item.student_id">
                        <tr class="adm-tr">
                            <td class="adm-td">
                                <div class="flex items-center gap-3">
                                    <template x-if="item.photo">
                                        <img :src="item.photo" class="h-9 w-9 rounded-full object-cover bg-gray-100 shrink-0">
                                    </template>
                                    <template x-if="!item.photo">
                                        <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0"
                                            x-text="(item.name||'?').split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                                    </template>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm" x-text="item.name"></p>
                                        <p class="text-xs text-gray-400" x-text="item.gender ?? ''"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="adm-td">
                                <p class="text-xs text-gray-700" x-text="item.nisn ?? '-'"></p>
                                <p class="text-xs text-gray-400" x-text="item.nis ? 'NIS: '+item.nis : ''"></p>
                            </td>
                            <td class="adm-td">
                                <span class="font-semibold text-sm text-gray-800" x-text="item.grade_level ?? '-'"></span>
                                <span class="text-xs text-gray-400 ml-1" x-text="item.major ?? ''"></span>
                            </td>
                            <td class="adm-td text-gray-500 text-sm" x-text="item.academic_year ?? '-'"></td>
                            <td class="adm-td">
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-700': item.status === 'active',
                                        'bg-gray-100 text-gray-500':  item.status === 'inactive',
                                        'bg-blue-100 text-blue-700':  item.status === 'graduated',
                                        'bg-yellow-100 text-yellow-700': item.status === 'transferred',
                                        'bg-red-100 text-red-700':   item.status === 'dropped_out',
                                    }"
                                    x-text="{active:'Aktif',inactive:'Nonaktif',graduated:'Lulus',transferred:'Pindah',dropped_out:'Keluar'}[item.status] ?? item.status">
                                </span>
                            </td>
                            <td class="adm-td">
                                <div class="flex items-center gap-1">
                                    <button @click="edit(item)" class="adm-btn-ghost text-blue-500 hover:bg-blue-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <template x-if="confirmId !== item.student_id">
                                        <button @click="confirmId = item.student_id" class="adm-btn-ghost text-red-400 hover:bg-red-50">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
                                    <template x-if="confirmId === item.student_id">
                                        <div class="flex gap-1">
                                            <button @click="remove(item.student_id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus?</button>
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

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Murid' : 'Edit Data Murid'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Identitas --}}
                        <div class="col-span-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Identitas</p>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="adm-input" placeholder="Nama lengkap sesuai akta lahir">
                        </div>
                        <div>
                            <label class="adm-label">NIS</label>
                            <input type="text" x-model="form.nis" class="adm-input" placeholder="Nomor Induk Siswa">
                        </div>
                        <div>
                            <label class="adm-label">NISN</label>
                            <input type="text" x-model="form.nisn" class="adm-input" placeholder="10 digit">
                        </div>
                        <div>
                            <label class="adm-label">NIK</label>
                            <input type="text" x-model="form.nik" class="adm-input" placeholder="16 digit">
                        </div>
                        <div>
                            <label class="adm-label">Jenis Kelamin</label>
                            <select x-model="form.gender" class="adm-select">
                                <option value="">Pilih...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Tempat Lahir</label>
                            <input type="text" x-model="form.birth_place" class="adm-input" placeholder="Bogor">
                        </div>
                        <div>
                            <label class="adm-label">Tanggal Lahir</label>
                            <input type="date" x-model="form.birth_date" class="adm-input">
                        </div>

                        {{-- Kontak --}}
                        <div class="col-span-2 mt-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Kontak</p>
                        </div>
                        <div>
                            <label class="adm-label">No. HP / WA</label>
                            <input type="text" x-model="form.phone" class="adm-input" placeholder="0812...">
                        </div>
                        <div>
                            <label class="adm-label">Email</label>
                            <input type="email" x-model="form.email" class="adm-input" placeholder="nama@email.com">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Alamat</label>
                            <textarea x-model="form.address" class="adm-textarea" rows="2" placeholder="Alamat lengkap..."></textarea>
                        </div>

                        {{-- Foto --}}
                        <div class="col-span-2">
                            <label class="adm-label">Foto</label>
                            <div class="space-y-2">
                                <input type="text" x-model="form.photo" class="adm-input" placeholder="https://... atau /storage/uploads/foto.jpg">
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
                                <img :src="form.photo" class="mt-2 h-16 w-16 rounded-lg border border-gray-100 object-cover">
                            </template>
                        </div>

                        {{-- Akademik --}}
                        <div class="col-span-2 mt-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Data Akademik</p>
                        </div>
                        <div>
                            <label class="adm-label">Kelas</label>
                            <select x-model="form.grade_level" class="adm-select">
                                <option value="">Pilih kelas...</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Jurusan</label>
                            <select x-model="form.major" class="adm-select">
                                <option value="">Pilih jurusan...</option>
                                <option value="IPA">IPA</option>
                                <option value="IPS">IPS</option>
                                <option value="Bahasa">Bahasa</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Tahun Ajaran</label>
                            <input type="text" x-model="form.academic_year" class="adm-input" placeholder="2025/2026">
                        </div>
                        <div>
                            <label class="adm-label">Status</label>
                            <select x-model="form.status" class="adm-select">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                                <option value="graduated">Lulus</option>
                                <option value="transferred">Pindah</option>
                                <option value="dropped_out">Keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Asal Sekolah Sebelumnya</label>
                            <input type="text" x-model="form.previous_school" class="adm-input" placeholder="SMP Negeri 1 Bogor">
                        </div>
                        <div>
                            <label class="adm-label">Urutan Tampil</label>
                            <input type="number" x-model="form.order" class="adm-input" placeholder="0">
                        </div>

                        {{-- Orang Tua --}}
                        <div class="col-span-2 mt-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Orang Tua / Wali</p>
                        </div>
                        <div>
                            <label class="adm-label">Nama Orang Tua / Wali</label>
                            <input type="text" x-model="form.parent_name" class="adm-input" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="adm-label">No. HP Orang Tua</label>
                            <input type="text" x-model="form.parent_phone" class="adm-input" placeholder="0812...">
                        </div>

                        {{-- Catatan --}}
                        <div class="col-span-2 mt-2">
                            <label class="adm-label">Catatan</label>
                            <textarea x-model="form.notes" class="adm-textarea" rows="2" placeholder="Catatan internal..."></textarea>
                        </div>
                    </div>
                    <div x-show="formError" x-text="formError" class="mt-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-2"></div>
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
