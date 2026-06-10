{{-- ═══ PPDB ════════════════════════════════════════════════════════════════════ --}}

<div class="py-14 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Penerimaan Peserta Didik Baru</p>
        <h1 class="text-3xl font-black mb-2">PPDB 2026/2027</h1>
        <p class="text-gray-300 text-sm">Daftar sekarang dan bergabunglah bersama SMA Al-Ghazaly Bogor</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">

    {{-- Tab --}}
    <div class="flex rounded-2xl bg-gray-100 p-1 mb-8">
        <button @click="activeTab = 'daftar'"
            :class="activeTab === 'daftar' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all">
            Formulir Pendaftaran
        </button>
        <button @click="activeTab = 'status'"
            :class="activeTab === 'status' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all">
            Cek Status Pendaftaran
        </button>
    </div>

    {{-- ══ STATUS CHECK TAB ══════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'status'">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="font-bold text-gray-900 text-lg mb-1">Cek Status Pendaftaran</h2>
            <p class="text-gray-500 text-sm mb-6">Masukkan nomor pendaftaran yang Anda terima saat mendaftar.</p>
            <div class="flex gap-2">
                <input x-model="statusCode" @keydown.enter="checkStatus()"
                    type="text" placeholder="Contoh: PPDB-2026-XXXXXX"
                    class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color:#019342">
                <button @click="checkStatus()" :disabled="statusLoading"
                    class="px-6 py-3 rounded-xl text-white text-sm font-medium transition-all hover:opacity-90 disabled:opacity-60"
                    style="background:#019342">
                    <span x-show="!statusLoading">Cek</span>
                    <svg x-show="statusLoading" class="spinner h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                </button>
            </div>

            <template x-if="statusResult">
                <div class="mt-6">
                    <template x-if="statusResult.found">
                        <div class="rounded-2xl border p-5" style="border-color:#bbf7d0; background:#f0fdf4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-bold text-gray-900" x-text="statusResult.student_name"></h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                      :class="statusColor(statusResult.status)"
                                      x-text="statusBadge(statusResult.status)"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs">No. Pendaftaran</p>
                                    <p class="font-mono font-semibold text-gray-900" x-text="statusResult.registration_number"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Tahun Pelajaran</p>
                                    <p class="font-semibold text-gray-900" x-text="statusResult.academic_year"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Gelombang</p>
                                    <p class="font-semibold text-gray-900" x-text="statusResult.wave"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Jurusan Pilihan</p>
                                    <p class="font-semibold text-gray-900" x-text="statusResult.major_choice"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Tanggal Daftar</p>
                                    <p class="font-semibold text-gray-900" x-text="fmtDate(statusResult.submitted_at || statusResult.created_at)"></p>
                                </div>
                            </div>
                            <template x-if="statusResult.documents && statusResult.documents.length > 0">
                                <div class="mt-4 pt-4 border-t border-green-200">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">Status Dokumen</p>
                                    <div class="space-y-1.5">
                                        <template x-for="doc in statusResult.documents" :key="doc.document_id">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-600" x-text="doc.document_type"></span>
                                                <span class="px-2 py-0.5 rounded-full font-medium"
                                                      :class="doc.status === 'verified' ? 'bg-green-100 text-green-700' : doc.status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'"
                                                      x-text="doc.status === 'verified' ? 'Terverifikasi' : doc.status === 'rejected' ? 'Ditolak' : 'Menunggu'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!statusResult.found">
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-center text-sm text-red-600">
                            Nomor pendaftaran tidak ditemukan. Pastikan nomor sudah benar atau
                            <button @click="activeTab = 'daftar'" class="underline font-medium">daftar sekarang</button>.
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- ══ REGISTRATION FORM TAB ═════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'daftar'">

        {{-- Success State --}}
        <template x-if="submitted">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
                <div class="h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-5" style="background:#019342">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Pendaftaran Berhasil!</h2>
                <p class="text-gray-600 mb-4">Selamat! Nomor pendaftaran Anda adalah:</p>
                <div class="inline-block px-6 py-3 rounded-2xl text-white font-black text-xl mb-6" style="background:#019342">
                    <span x-text="registrationNumber"></span>
                </div>
                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                    Simpan nomor ini. Gunakan untuk memantau status pendaftaran Anda di tab <strong>Cek Status</strong>.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button @click="activeTab = 'status'; statusCode = registrationNumber"
                        class="px-6 py-2.5 rounded-xl text-white font-medium text-sm" style="background:#191654">
                        Cek Status Pendaftaran
                    </button>
                    <button @click="reset()"
                        class="px-6 py-2.5 rounded-xl border-2 font-medium text-sm" style="border-color:#019342; color:#019342">
                        Daftar Lagi
                    </button>
                </div>
            </div>
        </template>

        {{-- Form --}}
        <template x-if="!submitted">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Step Indicator --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-500">Langkah <span x-text="step"></span> dari <span x-text="totalSteps"></span></p>
                    <p class="text-xs font-semibold" style="color:#019342" x-text="currentStepTitle"></p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all" style="background:#019342"
                         :style="`width:${(step / totalSteps) * 100}%`"></div>
                </div>
            </div>

            <div class="p-6">

            {{-- ── STEP 1: Jenis & Pilihan ─────────────────────────────── --}}
            <div x-show="step === 1" class="space-y-5">
                <h3 class="font-bold text-gray-900">Jenis & Pilihan Sekolah</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pendaftaran</label>
                        <select x-model="form.jenis_pendaftaran" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>Siswa Baru</option>
                            <option>Pindahan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan Pilihan</label>
                        <select x-model="form.major_choice" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>MIPA</option>
                            <option>IPS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pelajaran</label>
                        <select x-model="form.academic_year" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>2026/2027</option>
                            <option>2027/2028</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gelombang</label>
                        <select x-model="form.wave" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>Gelombang 1</option>
                            <option>Gelombang 2</option>
                            <option>Gelombang 3</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah (SMP/MTs)</label>
                        <input x-model="form.previous_school" type="text" placeholder="Nama sekolah asal"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Peserta UN/USBN</label>
                        <input x-model="form.no_peserta_un" type="text" placeholder="(opsional)"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. SKHUN</label>
                        <input x-model="form.no_skhun" type="text" placeholder="(opsional)"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                </div>
            </div>

            {{-- ── STEP 2: Data Pribadi ─────────────────────────────────── --}}
            <div x-show="step === 2" class="space-y-5">
                <h3 class="font-bold text-gray-900">Data Pribadi Calon Siswa</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input x-model="form.student_name" type="text" placeholder="Nama lengkap sesuai akta lahir"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input x-model="form.nisn" type="text" placeholder="10 digit NISN"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input x-model="form.nik" type="text" placeholder="16 digit NIK KTP"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select x-model="form.gender" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option value="">— Pilih —</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <select x-model="form.agama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>Islam</option><option>Kristen</option><option>Katolik</option>
                            <option>Hindu</option><option>Budha</option><option>Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input x-model="form.birth_place" type="text" placeholder="Kota tempat lahir"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input x-model="form.birth_date" type="date"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP / WA <span class="text-red-500">*</span></label>
                        <input x-model="form.phone" type="text" placeholder="08xx-xxxx-xxxx"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input x-model="form.email" type="email" placeholder="email@contoh.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <input x-model="form.address" type="text" placeholder="Nama jalan, no. rumah"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RT / RW</label>
                        <div class="flex gap-2">
                            <input x-model="form.rt" type="text" placeholder="RT" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <input x-model="form.rw" type="text" placeholder="RW" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input x-model="form.kode_pos" type="text" placeholder="16xxx"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                        <input x-model="form.nama_kelurahan" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input x-model="form.kecamatan" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tinggal Bersama</label>
                        <select x-model="form.tinggal_bersama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>Orang Tua</option><option>Wali</option><option>Kos / Kontrak</option><option>Asrama</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transportasi ke Sekolah</label>
                        <select x-model="form.transportasi" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <option>Kendaraan Pribadi</option><option>Angkutan Umum</option>
                            <option>Jalan Kaki</option><option>Antar Jemput Sekolah</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── STEP 3: Ayah ─────────────────────────────────────────── --}}
            <div x-show="step === 3" class="space-y-5">
                <h3 class="font-bold text-gray-900">Data Orang Tua — Ayah</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                        <input x-model="form.nama_ayah" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lahir</label>
                        <input x-model="form.tahun_lahir_ayah" type="number" min="1940" max="2000" placeholder="1975"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                        <select x-model="form.pendidikan_ayah" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <template x-for="opt in pendidikanOptions"><option x-text="opt" :value="opt"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input x-model="form.pekerjaan_ayah" type="text" placeholder="Wiraswasta, PNS, dsb."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan</label>
                        <select x-model="form.penghasilan_ayah" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <template x-for="opt in penghasilanOptions"><option x-text="opt" :value="opt"></option></template>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── STEP 4: Ibu ──────────────────────────────────────────── --}}
            <div x-show="step === 4" class="space-y-5">
                <h3 class="font-bold text-gray-900">Data Orang Tua — Ibu</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                        <input x-model="form.nama_ibu" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lahir</label>
                        <input x-model="form.tahun_lahir_ibu" type="number" min="1940" max="2000" placeholder="1978"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                        <select x-model="form.pendidikan_ibu" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <template x-for="opt in pendidikanOptions"><option x-text="opt" :value="opt"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input x-model="form.pekerjaan_ibu" type="text" placeholder="Ibu Rumah Tangga, dsb."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan</label>
                        <select x-model="form.penghasilan_ibu" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <template x-for="opt in penghasilanOptions"><option x-text="opt" :value="opt"></option></template>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── STEP 5: Wali ─────────────────────────────────────────── --}}
            <div x-show="step === 5" class="space-y-5">
                <h3 class="font-bold text-gray-900">Data Wali <span class="text-sm font-normal text-gray-400">(opsional)</span></h3>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-200 hover:bg-gray-50">
                    <input type="checkbox" x-model="form.mempunyai_wali" :value="1" :checked="form.mempunyai_wali == 1"
                        @change="form.mempunyai_wali = $event.target.checked ? 1 : 0"
                        class="h-4 w-4 rounded">
                    <span class="text-sm font-medium text-gray-700">Siswa memiliki wali (bukan orang tua kandung)</span>
                </label>
                <template x-if="form.mempunyai_wali == 1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wali</label>
                        <input x-model="form.nama_wali" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lahir Wali</label>
                        <input x-model="form.tahun_lahir_wali" type="number" min="1940" max="2000"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Wali</label>
                        <input x-model="form.pekerjaan_wali" type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                </div>
                </template>
                <template x-if="form.mempunyai_wali != 1">
                    <p class="text-center text-gray-400 text-sm py-8">Centang kotak di atas jika ada data wali yang perlu diisi.</p>
                </template>
            </div>

            {{-- ── STEP 6: Periodik & Review ────────────────────────────── --}}
            <div x-show="step === 6" class="space-y-5">
                <h3 class="font-bold text-gray-900">Data Periodik & Tinjauan</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                        <input x-model="form.tinggi_badan" type="number" placeholder="160"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                        <input x-model="form.berat_badan" type="number" placeholder="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jml. Saudara Kandung</label>
                        <input x-model="form.jumlah_saudara_kandung" type="number" min="0"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Tempuh (mnt)</label>
                        <input x-model="form.waktu_tempuh" type="number" min="1"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jarak ke Sekolah</label>
                        <select x-model="form.jarak_sekolah" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#019342">
                            <template x-for="j in jarakOptions"><option x-text="j" :value="j"></option></template>
                        </select>
                    </div>
                </div>

                {{-- Review Summary --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2 text-sm">
                    <h4 class="font-semibold text-gray-900 mb-3">Ringkasan Pendaftaran</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <p class="text-gray-500">Nama</p><p class="font-medium text-gray-900" x-text="form.student_name || '—'"></p>
                        <p class="text-gray-500">Jenis Kelamin</p><p class="font-medium text-gray-900" x-text="form.gender || '—'"></p>
                        <p class="text-gray-500">Tgl. Lahir</p><p class="font-medium text-gray-900" x-text="form.birth_date || '—'"></p>
                        <p class="text-gray-500">Jurusan</p><p class="font-medium text-gray-900" x-text="form.major_choice"></p>
                        <p class="text-gray-500">Gelombang</p><p class="font-medium text-gray-900" x-text="form.wave"></p>
                        <p class="text-gray-500">Asal Sekolah</p><p class="font-medium text-gray-900" x-text="form.previous_school || '—'"></p>
                        <p class="text-gray-500">Nama Ayah</p><p class="font-medium text-gray-900" x-text="form.nama_ayah || '—'"></p>
                        <p class="text-gray-500">Nama Ibu</p><p class="font-medium text-gray-900" x-text="form.nama_ibu || '—'"></p>
                    </div>
                </div>
            </div>

            {{-- Error --}}
            <div x-show="formError" x-text="formError"
                 class="mt-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3"></div>

            {{-- Navigation --}}
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                <button x-show="step > 1" @click="prev()"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                    ← Kembali
                </button>
                <div x-show="step === 1" class="w-4"></div>

                <template x-if="step < totalSteps">
                    <button @click="next()"
                        class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition-all"
                        style="background:#019342">
                        Lanjut →
                    </button>
                </template>
                <template x-if="step === totalSteps">
                    <button @click="submit()" :disabled="submitting"
                        class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition-all disabled:opacity-60"
                        style="background:#019342">
                        <span x-show="!submitting">Kirim Pendaftaran ✓</span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <svg class="spinner h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                            Mengirim...
                        </span>
                    </button>
                </template>
            </div>

            </div>{{-- /p-6 --}}
        </div>
        </template>

    </div>{{-- /activeTab daftar --}}

    {{-- Info PPDB --}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([['📋','Persyaratan','Fotocopy ijazah, rapor, akta lahir, dan KK'],['📅','Timeline','Gel. 1: Jan–Mar | Gel. 2: Apr–Jun'],['📞','Info Lebih Lanjut','Hubungi kami di nomor yang tertera']] as $info)
        <div class="bg-white rounded-2xl p-4 border border-gray-100">
            <div class="text-2xl mb-2">{{ $info[0] }}</div>
            <p class="font-semibold text-gray-900 text-sm">{{ $info[1] }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $info[2] }}</p>
        </div>
        @endforeach
    </div>

</div>
