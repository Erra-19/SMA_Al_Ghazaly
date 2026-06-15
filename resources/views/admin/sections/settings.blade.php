{{-- ═══ SETTINGS ════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'settings'" x-data="settingsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Pengaturan Website</h2>
            <p class="text-xs text-gray-500 mt-0.5">Informasi umum dan konfigurasi sekolah</p>
        </div>
        <button @click="save()" :disabled="saving" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span x-show="!saving">Simpan Pengaturan</span>
            <span x-show="saving">Menyimpan...</span>
        </button>
    </div>

    <div x-show="loading" class="p-10 flex justify-center">
        <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    <div x-show="!loading" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Informasi Sekolah --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Informasi Sekolah</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="adm-label">Nama Sekolah</label>
                            <input type="text" x-model="form.school_name" class="adm-input" placeholder="Al-Ghazaly Islamic School">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Tagline / Motto</label>
                            <input type="text" x-model="form.tagline" class="adm-input" placeholder="Motto sekolah...">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Visi Sekolah</label>
                            <textarea x-model="form.school_vision" class="adm-textarea" rows="2" placeholder="Tulis visi sekolah..."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Misi Sekolah</label>
                            <p class="text-[11px] text-gray-400 -mt-2 mb-1">Satu baris = satu poin misi</p>
                            <textarea x-model="form.school_missions" class="adm-textarea" rows="5" placeholder="Memberikan keteladanan etika dan moral...&#10;Melaksanakan proses pembelajaran mandiri...&#10;Meningkatkan jumlah lulusan PTN/PTS..."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Alamat</label>
                            <textarea x-model="form.address" class="adm-textarea" rows="2" placeholder="Alamat lengkap sekolah"></textarea>
                        </div>
                        <div>
                            <label class="adm-label">No. Telepon</label>
                            <input type="text" x-model="form.phone" class="adm-input" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div>
                            <label class="adm-label">Email</label>
                            <input type="email" x-model="form.email" class="adm-input" placeholder="info@alghazaly.sch.id">
                        </div>
                        <div>
                            <label class="adm-label">WhatsApp</label>
                            <input type="text" x-model="form.whatsapp" class="adm-input" placeholder="628xx...">
                        </div>
                        <div>
                            <label class="adm-label">NPSN</label>
                            <input type="text" x-model="form.npsn" class="adm-input" placeholder="12345678">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Deskripsi & SEO</h3>
                <div class="space-y-4">
                    <div>
                        <label class="adm-label">Deskripsi Singkat</label>
                        <textarea x-model="form.description" class="adm-textarea" rows="3" placeholder="Deskripsi singkat tentang sekolah..."></textarea>
                    </div>
                    <div>
                        <label class="adm-label">Meta Description (SEO)</label>
                        <textarea x-model="form.meta_description" class="adm-textarea" rows="2" placeholder="Deskripsi untuk mesin pencari..."></textarea>
                    </div>
                    <div>
                        <label class="adm-label">Meta Keywords (SEO)</label>
                        <input type="text" x-model="form.meta_keywords" class="adm-input" placeholder="sekolah islam, sd it, alghazaly...">
                    </div>
                </div>
            </div>

            {{-- Rekening Bank --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Rekening Pembayaran PPDB</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="adm-label">Nama Bank</label>
                            <input type="text" x-model="form.bank_name" class="adm-input" placeholder="BCA / Mandiri / BNI...">
                        </div>
                        <div>
                            <label class="adm-label">Nomor Rekening</label>
                            <input type="text" x-model="form.bank_account_number" class="adm-input" placeholder="1234567890">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Atas Nama</label>
                            <input type="text" x-model="form.bank_account_name" class="adm-input" placeholder="Yayasan Islamic Centre Al-Ghazaly">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biaya PPDB --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-1 pb-3 border-b border-gray-100">Biaya PPDB</h3>
                <p class="text-[11px] text-gray-400 mb-4">Rincian biaya yang ditampilkan ke calon siswa setelah mendaftar.</p>

                {{-- Daftar Item Biaya --}}
                <div class="mb-4 space-y-2">
                    <template x-if="ppdbFees.length === 0">
                        <p class="text-xs text-gray-400 italic py-2">Belum ada item biaya.</p>
                    </template>
                    <template x-for="(fee, idx) in ppdbFees" :key="idx">
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="fee.name" class="adm-input flex-1" placeholder="Nama biaya (mis. Biaya Pendaftaran)">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-green-300 w-40 bg-white shrink-0">
                                <span class="px-2.5 text-xs text-gray-500 bg-gray-50 border-r border-gray-200 self-stretch flex items-center shrink-0 select-none">Rp</span>
                                <input type="number" x-model="fee.amount" class="flex-1 px-2 py-2 text-sm outline-none min-w-0" placeholder="500000" min="0" step="1000">
                            </div>
                            <button type="button" @click="removeFee(idx)"
                                class="h-8 w-8 shrink-0 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between mb-5">
                    <button type="button" @click="addFee()"
                        class="flex items-center gap-1.5 text-xs font-semibold text-green-700 hover:text-green-800 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Item Biaya
                    </button>
                    <template x-if="ppdbFees.length > 0">
                        <p class="text-xs font-bold text-gray-700">
                            Total: <span class="text-green-700" x-text="'Rp ' + fmtRp(ppdbFeesTotal)"></span>
                        </p>
                    </template>
                </div>

                {{-- Pilihan Pembayaran --}}
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <div>
                        <label class="adm-label">Mode Pembayaran</label>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 mt-2">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" x-model="form.ppdb_payment_mode" value="full" class="accent-green-600 cursor-pointer" style="width:14px;height:14px;">
                                <span class="text-sm text-gray-700">Lunas saja</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" x-model="form.ppdb_payment_mode" value="installment" class="accent-green-600 cursor-pointer" style="width:14px;height:14px;">
                                <span class="text-sm text-gray-700">Cicil saja</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" x-model="form.ppdb_payment_mode" value="both" class="accent-green-600 cursor-pointer" style="width:14px;height:14px;">
                                <span class="text-sm text-gray-700">Lunas atau Cicil (pilihan siswa)</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="form.ppdb_payment_mode === 'installment' || form.ppdb_payment_mode === 'both'"
                         class="grid grid-cols-2 gap-4 bg-gray-50 rounded-xl p-4">
                        <div>
                            <label class="adm-label">Jumlah Cicilan</label>
                            <input type="number" x-model="form.ppdb_installment_count" class="adm-input" min="2" max="12" placeholder="3">
                            <p class="text-[10px] text-gray-400 mt-0.5">Termasuk DP sebagai cicilan pertama</p>
                        </div>
                        <div>
                            <label class="adm-label">Uang Muka / DP</label>
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-green-300 bg-white">
                                <span class="px-2.5 text-xs text-gray-500 bg-gray-50 border-r border-gray-200 self-stretch flex items-center shrink-0 select-none">Rp</span>
                                <input type="number" x-model="form.ppdb_installment_dp" class="flex-1 px-2 py-2 text-sm outline-none min-w-0" min="0" step="1000" placeholder="500000">
                            </div>
                        </div>
                        <div class="col-span-2" x-show="ppdbFeesTotal > 0 && form.ppdb_installment_dp > 0 && form.ppdb_installment_count > 1">
                            <p class="text-[11px] text-blue-600 bg-blue-50 rounded-lg px-3 py-2">
                                Preview: DP <strong x-text="'Rp ' + fmtRp(form.ppdb_installment_dp)"></strong> +
                                <span x-text="ppdbInstallmentTerms"></span>× cicilan
                                <strong x-text="'Rp ' + fmtRp(ppdbInstallmentAmount)"></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sosial Media --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Sosial Media</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Instagram</label>
                        <input type="text" x-model="form.instagram" class="adm-input" placeholder="@username">
                    </div>
                    <div>
                        <label class="adm-label">Facebook</label>
                        <input type="text" x-model="form.facebook" class="adm-input" placeholder="facebook.com/...">
                    </div>
                    <div>
                        <label class="adm-label">YouTube</label>
                        <input type="text" x-model="form.youtube" class="adm-input" placeholder="youtube.com/...">
                    </div>
                    <div>
                        <label class="adm-label">Twitter / X</label>
                        <input type="text" x-model="form.twitter" class="adm-input" placeholder="@username">
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-5">

            {{-- Logo & Favicon --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Logo & Aset Visual</h3>
                <div class="space-y-4">
                    <div>
                        <label class="adm-label">Logo</label>
                        <div class="space-y-2">
                            <input type="text" x-model="form.logo" class="adm-input" placeholder="https://... atau /storage/uploads/logo.png">
                            <div class="flex flex-wrap gap-2">
                                <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                    Upload
                                    <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'logo', $event.target.files); $event.target.value = null">
                                </label>
                                <button type="button" @click="openMediaPickerFor($data, 'logo')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                <button type="button" x-show="form.logo" @click="form.logo = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                            </div>
                        </div>
                        <template x-if="form.logo">
                            <img :src="form.logo" class="mt-2 h-12 object-contain rounded border border-gray-100 p-1">
                        </template>
                    </div>
                    <div>
                        <label class="adm-label">Favicon</label>
                        <div class="space-y-2">
                            <input type="text" x-model="form.favicon" class="adm-input" placeholder="https://... atau /storage/uploads/favicon.png">
                            <div class="flex flex-wrap gap-2">
                                <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                    Upload
                                    <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'favicon', $event.target.files); $event.target.value = null">
                                </label>
                                <button type="button" @click="openMediaPickerFor($data, 'favicon')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                <button type="button" x-show="form.favicon" @click="form.favicon = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                            </div>
                        </div>
                        <template x-if="form.favicon">
                            <img :src="form.favicon" class="mt-2 h-10 object-contain rounded border border-gray-100 p-1">
                        </template>
                    </div>
                    <div>
                        <label class="adm-label">Foto Hero/Banner</label>
                        <div class="space-y-2">
                            <input type="text" x-model="form.hero_image" class="adm-input" placeholder="https://... atau /storage/uploads/hero.jpg">
                            <div class="flex flex-wrap gap-2">
                                <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                    Upload
                                    <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'hero_image', $event.target.files); $event.target.value = null">
                                </label>
                                <button type="button" @click="openMediaPickerFor($data, 'hero_image')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                <button type="button" x-show="form.hero_image" @click="form.hero_image = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                            </div>
                        </div>
                        <template x-if="form.hero_image">
                            <img :src="form.hero_image" class="mt-2 h-20 w-full rounded border border-gray-100 object-cover p-1">
                        </template>
                    </div>
                    <div>
                        <label class="adm-label">Foto Profil Sekolah</label>
                        <p class="text-[11px] text-gray-400 -mt-2 mb-2">Gambar yang tampil di halaman Profil Sekolah</p>
                        <div class="space-y-2">
                            <input type="text" x-model="form.profile_image" class="adm-input" placeholder="https://... atau /storage/uploads/profil.jpg">
                            <div class="flex flex-wrap gap-2">
                                <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">
                                    Upload
                                    <input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'profile_image', $event.target.files); $event.target.value = null">
                                </label>
                                <button type="button" @click="openMediaPickerFor($data, 'profile_image')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                <button type="button" x-show="form.profile_image" @click="form.profile_image = ''" class="adm-btn adm-btn-secondary adm-btn-sm">Kosongkan</button>
                            </div>
                        </div>
                        <template x-if="form.profile_image">
                            <img :src="form.profile_image" class="mt-2 h-20 w-full rounded border border-gray-100 object-cover p-1">
                        </template>
                    </div>
                </div>
            </div>

            {{-- PPDB --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Pengaturan PPDB</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Status PPDB</p>
                            <p class="text-xs text-gray-400">Buka/tutup penerimaan</p>
                        </div>
                        <button type="button" @click="form.ppdb_open = form.ppdb_open ? 0 : 1"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                            :class="form.ppdb_open ? 'bg-green-600' : 'bg-gray-200'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                :class="form.ppdb_open ? 'translate-x-6' : 'translate-x-1'"></span>
                        </button>
                    </div>
                    <div>
                        <label class="adm-label">Tahun Ajaran</label>
                        <input type="text" x-model="form.academic_year" class="adm-input" placeholder="2025/2026">
                    </div>
                    <div>
                        <label class="adm-label">Biaya Pendaftaran (Rp)</label>
                        <input type="number" x-model="form.registration_fee" class="adm-input" placeholder="0">
                    </div>
                    <div>
                        <label class="adm-label">Kuota Penerimaan</label>
                        <input type="number" x-model="form.quota" class="adm-input" placeholder="100">
                    </div>
                </div>
            </div>

            {{-- Statistik / KPI --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Statistik Sekolah (KPI)</h3>
                <p class="text-xs text-gray-400 mb-4">Angka yang ditampilkan di halaman Profil Sekolah. Contoh: "35+" atau "160+".</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Total Pengajar</label>
                        <input type="text" x-model="form.stat_total_teachers" class="adm-input" placeholder="35+">
                    </div>
                    <div>
                        <label class="adm-label">Total Siswa Baru (per tahun)</label>
                        <input type="text" x-model="form.stat_total_new_students" class="adm-input" placeholder="160+">
                    </div>
                    <div>
                        <label class="adm-label">Total Siswa Aktif</label>
                        <input type="text" x-model="form.stat_total_students" class="adm-input" placeholder="480+">
                    </div>
                    <div>
                        <label class="adm-label">Total Alumni</label>
                        <input type="text" x-model="form.stat_total_alumni" class="adm-input" placeholder="1.200+">
                    </div>
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="adm-card p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">Lokasi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="adm-label">Google Maps Embed URL (untuk iframe peta)</label>
                        <input type="text" x-model="form.maps_embed_url" class="adm-input" placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="adm-label">Latitude</label>
                            <input type="text" x-model="form.latitude" class="adm-input" placeholder="-6.xxx">
                        </div>
                        <div>
                            <label class="adm-label">Longitude</label>
                            <input type="text" x-model="form.longitude" class="adm-input" placeholder="106.xxx">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
