{{-- ═══ REGISTRATIONS ═══════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'registrations'" x-data="registrationsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Data Pendaftar PPDB</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} pendaftar`"></p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Status filter --}}
            <select x-model="statusFilter" @change="load()" class="adm-select text-xs py-1.5 w-44">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="submitted">Baru Mendaftar</option>
                <option value="document_review">Review Dokumen</option>
                <option value="verified">Terverifikasi</option>
                <option value="accepted">Diterima</option>
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
                        <template x-for="item in items" :key="item.registration_id ?? item.id">
                            <tr class="adm-tr" :class="detail?.id === item.id && 'bg-green-50'">
                                <td class="adm-td">
                                    <p class="font-medium text-gray-900" x-text="item.full_name ?? item.student_name ?? '-'"></p>
                                    <p class="text-xs text-gray-400" x-text="item.registration_number ?? item.no_reg ?? ''"></p>
                                </td>
                                <td class="td text-gray-600" x-text="item.parent_name ?? item.father_name ?? '-'"></td>
                                <td class="td text-xs text-gray-500" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
                                <td class="adm-td">
                                    <span class="badge"
                                        :class="{
                                            'badge-gray':   item.status === 'draft',
                                            'badge-yellow': item.status === 'submitted',
                                            'badge-blue':   item.status === 'document_review',
                                            'badge-indigo': item.status === 'verified',
                                            'badge-green':  item.status === 'accepted',
                                            'badge-red':    item.status === 'rejected'
                                        }"
                                        x-text="{
                                            draft:           'Draft',
                                            submitted:       'Baru Mendaftar',
                                            document_review: 'Review Dokumen',
                                            verified:        'Terverifikasi',
                                            accepted:        'Diterima',
                                            rejected:        'Ditolak'
                                        }[item.status] ?? item.status">
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

                    {{-- Isian Formulir PPDB (form_data dinamis) --}}
                    <template x-if="detail.form_data && Object.keys(detail.form_data).length > 0">
                        <section>
                            <div class="flex items-center gap-2 mb-2">
                                <p class="text-xs font-semibold uppercase tracking-wide" style="color:#019342;">Isian Formulir PPDB</p>
                                <span class="text-[10px] text-gray-400 font-medium"
                                    x-text="`(${Object.keys(detail.form_data).length} field)`"></span>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                <template x-for="entry in Object.entries(detail.form_data || {})" :key="entry[0]">
                                    <div class="flex gap-2 text-xs"
                                        x-show="entry[1] !== null && entry[1] !== '' && entry[1] !== undefined">
                                        <dt class="text-gray-400 shrink-0 w-28 leading-relaxed capitalize"
                                            x-text="entry[0].replace(/_/g, ' ')">
                                        </dt>
                                        <dd class="text-gray-800 leading-relaxed break-words min-w-0"
                                            x-text="Array.isArray(entry[1]) ? entry[1].join(', ') : (typeof entry[1] === 'object' && entry[1] !== null ? JSON.stringify(entry[1]) : String(entry[1] ?? ''))">
                                        </dd>
                                    </div>
                                </template>
                            </div>
                        </section>
                    </template>

                    {{-- Dokumen --}}
                    <template x-if="detail.documents && detail.documents.length > 0">
                        <section>
                            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:#019342;">Dokumen</p>
                            <div class="space-y-1.5">
                                <template x-for="doc in (detail.documents ?? [])" :key="doc.document_id ?? doc.id">
                                    <div class="flex items-center justify-between gap-2 py-1">
                                        <a :href="doc.file_path ? `/storage/${doc.file_path}` : '#'" target="_blank"
                                            class="flex items-center gap-2 text-xs text-blue-600 hover:text-blue-700 min-w-0">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span class="truncate" x-text="doc.document_type ?? doc.type ?? doc.original_name ?? 'Dokumen'"></span>
                                        </a>
                                        <span class="badge text-[9px] shrink-0"
                                            :class="{'badge-yellow':doc.status==='pending','badge-green':doc.status==='verified','badge-red':doc.status==='rejected'}"
                                            x-text="{pending:'Pending',verified:'Terverifikasi',rejected:'Ditolak'}[doc.status] ?? doc.status ?? '-'">
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </section>
                    </template>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-100 p-4 space-y-2">
                    <p class="text-xs text-gray-400 font-medium mb-2">Ubah Status</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="updateStatus('document_review')"
                            :disabled="['document_review','verified','accepted','rejected'].includes(detail.status)"
                            class="adm-btn adm-btn-secondary adm-btn-sm justify-center disabled:opacity-40">
                            Review Dok.
                        </button>
                        <button @click="updateStatus('verified')"
                            :disabled="['verified','accepted','rejected'].includes(detail.status)"
                            class="adm-btn adm-btn-secondary adm-btn-sm justify-center disabled:opacity-40">
                            Verifikasi
                        </button>
                        <button @click="updateStatus('accepted')"
                            :disabled="detail.status === 'accepted' || !['paid','partial'].includes(detail.payment_status ?? '')"
                            class="adm-btn text-white adm-btn-sm justify-center col-span-2 disabled:opacity-40 disabled:cursor-not-allowed"
                            style="background:#019342;" onmouseover="if(!this.disabled)this.style.background='#191654'" onmouseout="this.style.background='#019342'"
                            :title="!['paid','partial'].includes(detail.payment_status ?? '') ? 'Pembayaran belum dikonfirmasi' : 'Terima pendaftar'">
                            ✓ Terima Pendaftar
                        </button>
                        <template x-if="!['paid','partial'].includes(detail.payment_status ?? '') && detail.status !== 'accepted'">
                            <p class="col-span-2 text-[10px] text-amber-600 text-center -mt-1">
                                ⚠ Konfirmasi pembayaran dulu di menu Pembayaran
                            </p>
                        </template>
                        <button @click="updateStatus('submitted')"
                            :disabled="detail.status === 'submitted'"
                            class="adm-btn adm-btn-secondary adm-btn-sm justify-center disabled:opacity-40">
                            Reset ke Awal
                        </button>
                        <button @click="updateStatus('rejected')"
                            :disabled="detail.status === 'rejected'"
                            class="adm-btn adm-btn-danger adm-btn-sm justify-center disabled:opacity-40">
                            Tolak
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
