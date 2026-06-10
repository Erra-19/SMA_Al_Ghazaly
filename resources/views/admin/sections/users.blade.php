{{-- ═══ USERS ════════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'users'" x-data="usersPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Manajemen Pengguna</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} pengguna`"></p>
        </div>
        <button @click="openAdd()" class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </button>
    </div>

    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="p-10 flex justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <table x-show="!loading" class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr><th class="adm-th">Pengguna</th><th class="adm-th">Email</th><th class="adm-th">Role</th><th class="adm-th">Bergabung</th><th class="adm-th"></th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-if="items.length === 0">
                    <tr><td colspan="5" class="td text-center text-gray-400 py-10">Belum ada pengguna.</td></tr>
                </template>
                <template x-for="item in items" :key="item.id">
                    <tr class="adm-tr">
                        <td class="adm-td">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 shrink-0"
                                    x-text="item.name.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                                <span class="font-medium text-gray-900" x-text="item.name"></span>
                            </div>
                        </td>
                        <td class="td text-gray-600" x-text="item.email"></td>
                        <td class="adm-td">
                            <span class="badge" :class="item.role === 'super_admin' ? 'badge-red' : item.role === 'admin' ? 'badge-blue' : 'badge-gray'"
                                x-text="item.role === 'super_admin' ? 'Super Admin' : item.role === 'admin' ? 'Admin' : (item.role ?? 'User')"></span>
                        </td>
                        <td class="td text-xs text-gray-500" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
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
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna'"></h3>
                    <button @click="showModal = false" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="adm-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.name" class="adm-input" placeholder="Nama pengguna">
                    </div>
                    <div>
                        <label class="adm-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" x-model="form.email" class="adm-input" placeholder="email@domain.com">
                    </div>
                    <div>
                        <label class="adm-label">Role <span class="text-red-500">*</span></label>
                        <select x-model="form.role" class="adm-select">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div>
                        <label class="adm-label" x-text="modalMode === 'add' ? 'Password' : 'Password Baru (kosongkan jika tidak diubah)'"></label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" x-model="form.password" class="adm-input pr-10"
                                :placeholder="modalMode === 'add' ? 'Min. 8 karakter' : '(tidak diubah)'">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
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
