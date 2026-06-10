{{-- ═══ REGISTRATIONS ═══════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'registrations'" x-data="registrationsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Data Pendaftar PPDB</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} pendaftar`"></p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Status filter --}}
            <select x-model="statusFilter" @change="load()" class="adm-select text-xs py-1.5 w-36">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="verified">Terverifikasi</option>
                <option value="approved">Diterima</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <div class="flex gap-5">
        {{-- Table --}}
        <div class="flex-1 adm-card overflow-hidden">
            <div x-show="loading" class="p-10 flex justify-center">
                <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="adm-th">Nama Siswa</th>
                            <th class="adm-th">Orang Tua</th>
                            <th class="adm-th">Tanggal</th>
                            <th class="adm-th">Status</th>
                            <th class="adm-th"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="items.length === 0">
                            <tr><td colspan="5" class="td text-center text-gray-400 py-10">Belum ada pendaftar.</td></tr>
                        </template>
                        <template x-for="item in items" :key="item.id">
                            <tr class="adm-tr" :class="detail?.id === item.id && 'bg-green-50'">
                                <td class="adm-td">
                                    <p class="font-medium text-gray-900" x-text="item.full_name ?? item.student_name ?? '-'"></p>
                                    <p class="text-xs text-gray-400" x-text="item.registration_number ?? item.no_reg ?? ''"></p>
                                </td>
                                <td class="td text-gray-600" x-text="item.parent_name ?? item.father_name ?? '-'"></td>
                                <td class="td text-xs text-gray-500" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
                                <td class="adm-td">
                                    <span class="badge"
                                        :class="{'badge-yellow':item.status==='pending','badge-blue':item.status==='verified','badge-green':item.status==='approved','badge-red':item.status==='rejected'}"
                                        x-text="item.status === 'pending' ? 'Pending' : item.status === 'verified' ? 'Terverifikasi' : item.status === 'approved' ? 'Diterima' : 'Ditolak'">
                                    </span>
                                </td>
                                <td class="adm-td">
                                    <button @click="openDetail(item)" class="adm-btn-ghost adm-btn-sm hover:bg-blue-50" style="color:#019342;">Detail</button>
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

        {{-- Detail Panel --}}
        <template x-if="detail">
            <div class="w-80 shrink-0 adm-card overflow-hidden flex flex-col max-h-[calc(100vh-8rem)] sticky top-20">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Detail Pendaftar</h3>
                    <button @click="detail = null" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">
                    {{-- Biodata --}}
                    <section>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:#019342;">Biodata Siswa</p>
                        <dl class="space-y-1.5 text-sm">
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Nama Lengkap</dt><dd class="text-gray-800 font-medium" x-text="detail.full_name ?? detail.student_name ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">No. Reg</dt><dd class="text-gray-800" x-text="detail.registration_number ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Tgl. Lahir</dt><dd class="text-gray-800" x-text="detail.birth_date ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Jenis Kelamin</dt><dd class="text-gray-800" x-text="detail.gender === 'M' ? 'Laki-laki' : detail.gender === 'F' ? 'Perempuan' : (detail.gender ?? '-')"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Asal Sekolah</dt><dd class="text-gray-800" x-text="detail.prev_school ?? '-'"></dd></div>
                        </dl>
                    </section>
                    <section>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:#019342;">Data Orang Tua</p>
                        <dl class="space-y-1.5 text-sm">
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Nama Ayah</dt><dd class="text-gray-800" x-text="detail.father_name ?? detail.parent_name ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Nama Ibu</dt><dd class="text-gray-800" x-text="detail.mother_name ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">No. HP</dt><dd class="text-gray-800" x-text="detail.phone ?? detail.parent_phone ?? '-'"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0">Alamat</dt><dd class="text-gray-800" x-text="detail.address ?? '-'"></dd></div>
                        </dl>
                    </section>

                    {{-- Dokumen --}}
                    <template x-if="detail.documents && detail.documents.length > 0">
                        <section>
                            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:#019342;">Dokumen</p>
                            <div class="space-y-1.5">
                                <template x-for="doc in (detail.documents ?? [])" :key="doc.id">
                                    <a :href="doc.url" target="_blank" class="flex items-center gap-2 text-xs text-blue-600 hover:text-blue-700">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span x-text="doc.type ?? doc.name ?? 'Dokumen'"></span>
                                    </a>
                                </template>
                            </div>
                        </section>
                    </template>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-100 p-4 space-y-2">
                    <p class="text-xs text-gray-400 font-medium mb-2">Ubah Status</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="updateStatus('verified')" :disabled="detail.status === 'verified'" class="adm-btn adm-btn adm-btn-secondary adm-btn-sm justify-center disabled:opacity-40">Verifikasi</button>
                        <button @click="updateStatus('approved')" :disabled="detail.status === 'approved'" class="adm-btn text-white adm-btn-sm justify-center disabled:opacity-40" style="background:#019342;" onmouseover="this.style.background='#191654'" onmouseout="this.style.background='#019342'">Terima</button>
                        <button @click="updateStatus('pending')" :disabled="detail.status === 'pending'" class="adm-btn adm-btn adm-btn-secondary adm-btn-sm justify-center disabled:opacity-40">Reset</button>
                        <button @click="updateStatus('rejected')" :disabled="detail.status === 'rejected'" class="adm-btn adm-btn adm-btn-danger adm-btn-sm justify-center disabled:opacity-40">Tolak</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
