<div x-show="$store.adm.page === 'programs'" x-data="programsPage()">
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Program Sekolah</h2>
            <p class="mt-0.5 text-xs text-gray-500">Kelola program akademik, unggulan, dan ekstrakurikuler.</p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">Tambah Program</button>
    </div>

    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="flex justify-center p-10">
            <svg class="h-6 w-6 animate-spin text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <table x-show="!loading" class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr><th class="adm-th">Judul</th><th class="adm-th">Tipe</th><th class="adm-th">Urutan</th><th class="adm-th">Status</th><th class="adm-th"></th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-if="items.length === 0">
                    <tr><td colspan="5" class="td py-10 text-center text-gray-400">Belum ada program.</td></tr>
                </template>
                <template x-for="item in items" :key="item.program_id ?? item.id">
                    <tr class="adm-tr">
                        <td class="adm-td">
                            <p class="font-medium text-gray-900" x-text="item.title"></p>
                            <p class="text-xs text-gray-400" x-text="item.subtitle || item.badge || '-'"></p>
                        </td>
                        <td class="adm-td capitalize" x-text="item.type"></td>
                        <td class="adm-td" x-text="item.order ?? 0"></td>
                        <td class="adm-td"><span class="badge" :class="item.is_published ? 'badge-green' : 'badge-gray'" x-text="item.is_published ? 'Published' : 'Draft'"></span></td>
                        <td class="adm-td">
                            <div class="flex items-center gap-1">
                                <button @click="edit(item)" class="adm-btn-ghost text-blue-500">Edit</button>
                                <template x-if="confirmId !== item.id">
                                    <button @click="confirmId = item.id" class="adm-btn-ghost text-red-500">Hapus</button>
                                </template>
                                <template x-if="confirmId === item.id">
                                    <button @click="remove(item.id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus?</button>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Program' : 'Edit Program'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost">Tutup</button>
                </div>
                <div class="max-h-[75vh] space-y-4 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="adm-label">Judul</label>
                            <input x-model="form.title" class="adm-input" type="text">
                        </div>
                        <div>
                            <label class="adm-label">Tipe</label>
                            <select x-model="form.type" class="adm-select">
                                <option value="akademik">Akademik</option>
                                <option value="unggulan">Unggulan</option>
                                <option value="ekskul">Ekstrakurikuler</option>
                            </select>
                        </div>
                        <div>
                            <label class="adm-label">Icon</label>
                            <select x-model="form.icon" class="adm-select">
                                <option value="BookOpen">BookOpen</option>
                                <option value="GraduationCap">GraduationCap</option>
                                <option value="Cpu">Cpu</option>
                                <option value="Users">Users</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Subtitle / Ringkasan pendek</label>
                            <input x-model="form.subtitle" class="adm-input" type="text">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Deskripsi</label>
                            <textarea x-model="form.description" class="adm-textarea" rows="4"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Gambar</label>
                            <div class="space-y-2">
                                <input x-model="form.image" class="adm-input" type="text" placeholder="URL atau /storage/...">
                                <div class="flex gap-2">
                                    <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">Upload<input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'image', $event.target.files); $event.target.value = null"></label>
                                    <button type="button" @click="openMediaPickerFor($data, 'image')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="adm-label">Badge</label>
                            <input x-model="form.badge" class="adm-input" type="text">
                        </div>
                        <div>
                            <label class="adm-label">Statistik/Label bawah</label>
                            <input x-model="form.stats" class="adm-input" type="text">
                        </div>
                        <div class="col-span-2">
                            <label class="adm-label">Fitur / poin utama, satu baris satu item</label>
                            <textarea x-model="form.features" class="adm-textarea" rows="5"></textarea>
                        </div>
                        <div>
                            <label class="adm-label">Urutan</label>
                            <input x-model="form.order" class="adm-input" type="number">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" x-model="form.is_published"> Published</label>
                        </div>
                    </div>
                    <div x-show="formError" x-text="formError" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600"></div>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4">
                    <button @click="showModal = false" class="adm-btn adm-btn-secondary">Batal</button>
                    <button @click="save()" :disabled="saving" class="adm-btn adm-btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </template>
</div>
