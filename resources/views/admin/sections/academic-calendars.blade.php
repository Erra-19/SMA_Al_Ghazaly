{{-- ═══ KALENDER AKADEMIK ══════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'academic-calendars'" x-data="academicCalendarPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Kalender Akademik</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola agenda & kegiatan sekolah per tanggal</p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Agenda
        </button>
    </div>

    {{-- Filter Tahun Ajaran --}}
    <div class="flex items-center gap-3 mb-4">
        <select x-model="yearFilter" @change="load()" class="adm-input w-48">
            <option value="">Semua Tahun Ajaran</option>
            <option>2024/2025</option>
            <option>2025/2026</option>
            <option>2026/2027</option>
        </select>
        <span class="text-xs text-gray-400 font-medium" x-text="`${items.length} agenda`"></span>
    </div>

    <div x-show="loading" class="p-10 flex justify-center">
        <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    <div x-show="!loading" class="adm-card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="th">Judul Agenda</th>
                    <th class="th">Tanggal</th>
                    <th class="th">Kategori</th>
                    <th class="th">Tahun Ajaran</th>
                    <th class="th">Status</th>
                    <th class="th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="items.length === 0">
                    <tr><td colspan="6" class="td text-center text-gray-400 py-10">Belum ada agenda.</td></tr>
                </template>
                <template x-for="item in items" :key="item.calendar_id">
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="td">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0"
                                    :class="{
                                        'bg-green-500': item.color === 'green',
                                        'bg-blue-500': item.color === 'blue',
                                        'bg-red-500': item.color === 'red',
                                        'bg-amber-500': item.color === 'amber',
                                        'bg-purple-500': item.color === 'purple',
                                    }"></span>
                                <span class="font-medium text-gray-900" x-text="item.title"></span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 pl-4" x-text="item.description || '—'"></p>
                        </td>
                        <td class="td text-xs text-gray-600 whitespace-nowrap">
                            <span x-text="fmt.date(item.start_date)"></span>
                            <template x-if="item.end_date && item.end_date !== item.start_date">
                                <span class="text-gray-400"> — <span x-text="fmt.date(item.end_date)"></span></span>
                            </template>
                        </td>
                        <td class="td">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700" x-text="item.category || 'Akademik'"></span>
                        </td>
                        <td class="td text-xs text-gray-500" x-text="item.academic_year || '—'"></td>
                        <td class="td">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                :class="item.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                x-text="item.is_published ? 'Publish' : 'Draft'"></span>
                        </td>
                        <td class="td text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEdit(item)" class="adm-btn adm-btn-secondary adm-btn-sm">Edit</button>
                                <button @click="remove(item.calendar_id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah / Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div @click.outside="showModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900" x-text="editId ? 'Edit Agenda' : 'Tambah Agenda'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <label class="adm-label">Judul Agenda <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.title" class="adm-input" placeholder="Ujian Tengah Semester Ganjil...">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" x-model="form.start_date" class="adm-input">
                    </div>
                    <div>
                        <label class="adm-label">Tanggal Selesai</label>
                        <input type="date" x-model="form.end_date" class="adm-input">
                        <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika 1 hari</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Kategori</label>
                        <select x-model="form.category" class="adm-input">
                            <option value="Akademik">Akademik</option>
                            <option value="Ujian">Ujian</option>
                            <option value="Libur">Libur</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Ekstrakurikuler">Ekstrakurikuler</option>
                            <option value="PPDB">PPDB</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="adm-label">Warna</label>
                        <select x-model="form.color" class="adm-input">
                            <option value="green">🟢 Hijau</option>
                            <option value="blue">🔵 Biru</option>
                            <option value="red">🔴 Merah</option>
                            <option value="amber">🟡 Kuning</option>
                            <option value="purple">🟣 Ungu</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="adm-label">Tahun Ajaran</label>
                    <select x-model="form.academic_year" class="adm-input">
                        <option value="">— Pilih —</option>
                        <option>2024/2025</option>
                        <option>2025/2026</option>
                        <option>2026/2027</option>
                    </select>
                </div>

                <div>
                    <label class="adm-label">Deskripsi</label>
                    <textarea x-model="form.description" class="adm-textarea" rows="2" placeholder="Keterangan tambahan..."></textarea>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="adm-label mb-0">Publish</label>
                    <button type="button" @click="form.is_published = form.is_published ? 0 : 1"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                        :class="form.is_published ? 'bg-green-600' : 'bg-gray-200'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                            :class="form.is_published ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100">
                <button @click="showModal = false" class="adm-btn adm-btn-secondary">Batal</button>
                <button @click="save()" :disabled="saving" class="adm-btn adm-btn-primary">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>

</div>
