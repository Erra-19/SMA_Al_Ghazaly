<div x-show="$store.adm.page === 'facilities'" x-data="facilitiesPage()">
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Fasilitas</h2>
            <p class="mt-0.5 text-xs text-gray-500">Kelola sarana dan prasarana yang tampil di halaman fasilitas.</p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">Tambah Fasilitas</button>
    </div>

    <div class="adm-card overflow-hidden">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr><th class="adm-th">Nama</th><th class="adm-th">Kategori</th><th class="adm-th">Urutan</th><th class="adm-th">Status</th><th class="adm-th"></th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-if="items.length === 0"><tr><td colspan="5" class="td py-10 text-center text-gray-400">Belum ada fasilitas.</td></tr></template>
                <template x-for="item in items" :key="item.facility_id ?? item.id">
                    <tr class="adm-tr">
                        <td class="adm-td"><p class="font-medium text-gray-900" x-text="item.name"></p><p class="text-xs text-gray-400" x-text="item.short_desc || '-'"></p></td>
                        <td class="adm-td" x-text="item.category"></td>
                        <td class="adm-td" x-text="item.order ?? 0"></td>
                        <td class="adm-td"><span class="badge" :class="item.is_published ? 'badge-green' : 'badge-gray'" x-text="item.is_published ? 'Published' : 'Draft'"></span></td>
                        <td class="adm-td">
                            <button @click="edit(item)" class="adm-btn-ghost text-blue-500">Edit</button>
                            <button x-show="confirmId !== item.id" @click="confirmId = item.id" class="adm-btn-ghost text-red-500">Hapus</button>
                            <button x-show="confirmId === item.id" @click="remove(item.id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus?</button>
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
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Fasilitas' : 'Edit Fasilitas'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost">Tutup</button>
                </div>
                <div class="max-h-[75vh] space-y-4 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2"><label class="adm-label">Nama</label><input x-model="form.name" class="adm-input" type="text"></div>
                        <div><label class="adm-label">Kategori</label><select x-model="form.category" class="adm-select"><option value="akademik">Akademik</option><option value="ibadah-sosial">Ibadah & Sosial</option><option value="olahraga-seni">Olahraga & Seni</option><option value="kesejahteraan">Kesejahteraan</option></select></div>
                        <div><label class="adm-label">Icon Label</label><input x-model="form.icon_name" class="adm-input" type="text"></div>
                        <div class="col-span-2"><label class="adm-label">Deskripsi singkat</label><input x-model="form.short_desc" class="adm-input" type="text"></div>
                        <div class="col-span-2"><label class="adm-label">Deskripsi lengkap</label><textarea x-model="form.long_desc" class="adm-textarea" rows="4"></textarea></div>
                        <div class="col-span-2">
                            <label class="adm-label">Gambar</label>
                            <input x-model="form.image" class="adm-input" type="text" placeholder="URL atau /storage/...">
                            <div class="mt-2 flex gap-2">
                                <label class="adm-btn adm-btn-secondary adm-btn-sm cursor-pointer">Upload<input type="file" class="hidden" accept="image/*" @change="uploadImageFor($data, 'image', $event.target.files); $event.target.value = null"></label>
                                <button type="button" @click="openMediaPickerFor($data, 'image')" class="adm-btn adm-btn-secondary adm-btn-sm">Pilih Media</button>
                            </div>
                        </div>
                        <div><label class="adm-label">Kapasitas</label><input x-model="form.capacity" class="adm-input" type="text"></div>
                        <div><label class="adm-label">Jam Operasional</label><input x-model="form.operational_hours" class="adm-input" type="text"></div>
                        <div class="col-span-2"><label class="adm-label">Lokasi</label><input x-model="form.location" class="adm-input" type="text"></div>
                        <div class="col-span-2"><label class="adm-label">Spesifikasi, satu baris satu item</label><textarea x-model="form.specs" class="adm-textarea" rows="5"></textarea></div>
                        <div><label class="adm-label">Urutan</label><input x-model="form.order" class="adm-input" type="number"></div>
                        <div class="flex items-end gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" x-model="form.is_featured"> Featured</label>
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
