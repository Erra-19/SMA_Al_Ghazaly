{{-- ═══ FORM BUILDER ══════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'form-builder'" x-data="formBuilderPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Form Builder</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kustomisasi formulir PPDB — langkah & field per langkah</p>
        </div>
        <button @click="openAddForm()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Form
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Daftar Form --}}
        <div class="adm-card divide-y divide-gray-100 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50/60 border-b border-gray-100">
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">Daftar Form</p>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <template x-if="!loading && forms.length === 0">
                <p class="text-xs text-gray-400 text-center py-8">Belum ada form.</p>
            </template>
            <template x-for="f in forms" :key="f.form_id">
                <div class="px-4 py-3 hover:bg-green-50/60 transition-colors flex items-center justify-between gap-2"
                    :class="activeForm?.form_id === f.form_id ? 'bg-green-50 border-l-2 border-green-600' : 'border-l-2 border-transparent'">
                    {{-- Klik nama untuk edit --}}
                    <div class="flex-1 min-w-0 cursor-pointer" @click="selectForm(f)">
                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="f.name"></p>
                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                            <span class="text-[10px] text-gray-400 truncate" x-text="f.slug"></span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full shrink-0"
                                :class="f.type === 'ppdb' ? 'bg-green-100 text-green-700' : f.type === 'contact' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'"
                                x-text="f.type?.toUpperCase()"></span>
                        </div>
                    </div>
                    {{-- Toggle aktif --}}
                    <div class="shrink-0 flex flex-col items-center gap-1">
                        <button @click.stop="activateForm(f)" :disabled="f.is_active == 1"
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors disabled:cursor-default"
                            :class="f.is_active ? 'bg-green-500' : 'bg-gray-200 hover:bg-gray-300'"
                            :title="f.is_active ? 'Aktif (klik form lain untuk ganti)' : 'Klik untuk aktifkan'">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform"
                                :class="f.is_active ? 'translate-x-4' : 'translate-x-1'"></span>
                        </button>
                        <span class="text-[9px] font-bold"
                            :class="f.is_active ? 'text-green-600' : 'text-gray-400'"
                            x-text="f.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Editor Form --}}
        <div class="lg:col-span-2 space-y-4">
            <template x-if="!activeForm">
                <div class="adm-card p-10 flex items-center justify-center text-sm text-gray-400">
                    ← Pilih form untuk diedit
                </div>
            </template>

            <template x-if="activeForm">
                <div class="space-y-4">
                    {{-- Info Form --}}
                    <div class="adm-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-gray-800">Info Form</p>
                            <div class="flex gap-2">
                                <button @click="saveFormInfo()" class="adm-btn adm-btn-primary adm-btn-sm">Simpan Info</button>
                                <button @click="deleteForm()" class="adm-btn adm-btn-danger adm-btn-sm">Hapus Form</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="adm-label">Nama Form</label>
                                <input type="text" x-model="activeForm.name" class="adm-input">
                            </div>
                            <div>
                                <label class="adm-label">Slug</label>
                                <input type="text" x-model="activeForm.slug" class="adm-input" placeholder="ppdb-registration">
                            </div>
                            <div>
                                <label class="adm-label">Tipe</label>
                                <select x-model="activeForm.type" class="adm-input">
                                    <option value="general">General</option>
                                    <option value="ppdb">PPDB</option>
                                    <option value="contact">Kontak</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 pt-4">
                                <label class="adm-label mb-0">Aktif</label>
                                <button type="button" @click="activeForm.is_active = activeForm.is_active ? 0 : 1"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="activeForm.is_active ? 'bg-green-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                        :class="activeForm.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                            <div class="col-span-2">
                                <label class="adm-label">Deskripsi / Instruksi</label>
                                <textarea x-model="activeForm.description" class="adm-textarea" rows="2" placeholder="Instruksi untuk pendaftar..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Steps --}}
                    <div class="adm-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-bold text-gray-800">
                                Langkah-langkah
                                <span class="ml-1 text-xs font-normal text-gray-400" x-text="`(${(activeForm.steps || []).length} langkah)`"></span>
                            </p>
                            <button @click="addStep()" class="adm-btn adm-btn-secondary adm-btn-sm">+ Tambah Langkah</button>
                        </div>

                        <template x-if="(activeForm.steps || []).length === 0">
                            <p class="text-xs text-gray-400 py-4 text-center">Belum ada langkah. Klik "Tambah Langkah".</p>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(step, si) in (activeForm.steps || [])" :key="si">
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    {{-- Step Header --}}
                                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50/80"
                                        :class="step._open ? 'border-b border-gray-100' : ''">
                                        {{-- Accordion toggle --}}
                                        <button @click="step._open = !step._open"
                                            class="h-6 w-6 rounded-full bg-green-600 text-white text-[10px] font-black flex items-center justify-center shrink-0 hover:bg-green-700 transition-colors"
                                            :title="step._open ? 'Klik untuk lipat' : 'Klik untuk buka'">
                                            <span x-text="step._open ? '▾' : '▸'" style="font-size:10px"></span>
                                        </button>
                                        <input type="text" x-model="step.label" class="flex-1 text-sm font-semibold bg-transparent border-0 outline-none focus:ring-0 text-gray-900" placeholder="Nama langkah (misal: Data Pribadi)">
                                        <span class="text-[10px] text-gray-400 shrink-0" x-text="`${(step.fields||[]).length} field`"></span>
                                        <input type="text" x-model="step.short_label" class="w-20 text-xs bg-transparent border border-gray-200 rounded px-2 py-1 outline-none" placeholder="Label pendek">
                                        <button @click="moveStep(si, -1)" :disabled="si === 0" class="text-gray-400 hover:text-gray-600 disabled:opacity-30 text-xs">↑</button>
                                        <button @click="moveStep(si, 1)" :disabled="si === (activeForm.steps.length - 1)" class="text-gray-400 hover:text-gray-600 disabled:opacity-30 text-xs">↓</button>
                                        <button @click="removeStep(si)" class="text-red-400 hover:text-red-600 text-xs font-bold">Hapus</button>
                                    </div>

                                    {{-- Fields in Step (accordion) --}}
                                    <div class="p-3 space-y-2" x-show="step._open"
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0">
                                        <template x-if="(step.fields || []).length === 0">
                                            <p class="text-[11px] text-gray-400 text-center py-2">Belum ada field di langkah ini.</p>
                                        </template>

                                        <template x-for="(field, fi) in (step.fields || [])" :key="fi">
                                            <div class="p-2.5 bg-white border border-gray-100 rounded-lg hover:border-gray-200 group space-y-1.5">
                                                {{-- Row 1: index + 4 inputs + actions --}}
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-bold text-gray-400 w-5 text-center shrink-0" x-text="fi + 1"></span>
                                                    <div class="flex-1 grid grid-cols-4 gap-2 min-w-0">
                                                        <input type="text" x-model="field.key" class="adm-input text-xs py-1 min-w-0" placeholder="key (misal: student_name)">
                                                        <input type="text" x-model="field.label" class="adm-input text-xs py-1 min-w-0" placeholder="Label">
                                                        <select x-model="field.type" class="adm-input text-xs py-1 min-w-0">
                                                            <option value="text">Text</option>
                                                            <option value="textarea">Textarea</option>
                                                            <option value="number">Number</option>
                                                            <option value="date">Date</option>
                                                            <option value="tel">Telepon</option>
                                                            <option value="email">Email</option>
                                                            <option value="select">Dropdown</option>
                                                            <option value="radio">Radio</option>
                                                            <option value="file">Upload File</option>
                                                        </select>
                                                        <input type="text" x-model="field.placeholder" class="adm-input text-xs py-1 min-w-0" placeholder="Placeholder...">
                                                    </div>
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        <label class="flex items-center gap-1 cursor-pointer select-none">
                                                            <input type="checkbox" x-model="field.required"
                                                                style="width:14px;height:14px;accent-color:#16a34a;cursor:pointer;flex-shrink:0">
                                                            <span class="text-[10px] text-gray-500 font-medium">Wajib</span>
                                                        </label>
                                                        <button @click="moveField(step, fi, -1)" :disabled="fi === 0"
                                                            class="text-gray-300 hover:text-gray-600 disabled:opacity-20 text-xs leading-none px-0.5" title="Naik">↑</button>
                                                        <button @click="moveField(step, fi, 1)" :disabled="fi === (step.fields.length - 1)"
                                                            class="text-gray-300 hover:text-gray-600 disabled:opacity-20 text-xs leading-none px-0.5" title="Turun">↓</button>
                                                        <button @click="removeField(step, fi)"
                                                            class="text-red-300 hover:text-red-500 text-xs px-0.5 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus">✕</button>
                                                    </div>
                                                </div>
                                                {{-- Row 2: Options (hanya tampil jika select/radio) --}}
                                                <div x-show="field.type === 'select' || field.type === 'radio'"
                                                    class="pl-7">
                                                    <input type="text" x-model="field.options_raw"
                                                        @input="field.options = (field.options_raw || '').split(',').map(s => s.trim()).filter(s => s)"
                                                        class="adm-input text-xs py-1 w-full"
                                                        placeholder="Opsi, dipisah koma — misal: Laki-laki, Perempuan">
                                                    <p class="text-[10px] text-gray-400 mt-0.5">Pisahkan tiap pilihan dengan koma</p>
                                                </div>
                                            </div>
                                        </template>

                                        <button @click="addField(step)"
                                            class="w-full text-xs text-green-600 border border-dashed border-green-200 rounded-lg py-2 hover:bg-green-50 transition-colors font-semibold">
                                            + Tambah Field
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                            <button @click="saveSteps()" :disabled="saving" class="adm-btn adm-btn-primary">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-show="!saving">Simpan Semua Langkah</span>
                                <span x-show="saving">Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal Tambah Form --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div @click.outside="showAddModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
            <h3 class="font-semibold text-gray-900">Tambah Form Baru</h3>
            <div>
                <label class="adm-label">Nama Form <span class="text-red-500">*</span></label>
                <input type="text" x-model="newForm.name" @input="newForm.slug = newForm.name.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'')" class="adm-input" placeholder="Formulir PPDB 2026/2027">
            </div>
            <div>
                <label class="adm-label">Slug <span class="text-red-500">*</span></label>
                <input type="text" x-model="newForm.slug" class="adm-input" placeholder="ppdb-registration">
                <p class="text-[10px] text-gray-400 mt-1">Gunakan <strong>ppdb-registration</strong> agar terhubung otomatis ke form PPDB</p>
            </div>
            <div>
                <label class="adm-label">Tipe</label>
                <select x-model="newForm.type" class="adm-input">
                    <option value="ppdb">PPDB</option>
                    <option value="contact">Kontak</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button @click="showAddModal = false" class="adm-btn adm-btn-secondary">Batal</button>
                <button @click="createForm()" class="adm-btn adm-btn-primary">Buat Form</button>
            </div>
        </div>
    </div>

</div>
