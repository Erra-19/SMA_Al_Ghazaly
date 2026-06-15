{{-- ═══ PAYMENTS ════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'payments'" x-data="paymentsPage()">

    <div class="flex items-center justify-between mb-5 gap-3">
        <div class="min-w-0">
            <h2 class="text-base font-bold text-gray-900">Pembayaran PPDB</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} transaksi`"></p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" x-model="search" @input="onSearch()"
                    class="adm-input pl-9 text-xs py-1.5 w-44" placeholder="Cari nama pendaftar...">
            </div>
            {{-- Status filter --}}
            <select x-model="statusFilter" @change="proofFilter = false; load(1)" class="adm-select text-xs py-1.5 w-32">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="partial">Sebagian</option>
                <option value="paid">Lunas</option>
                <option value="failed">Ditolak</option>
                <option value="expired">Expired</option>
            </select>
            {{-- Quick filter: butuh verifikasi --}}
            <button @click="proofFilter = !proofFilter; statusFilter = proofFilter ? 'pending' : ''; load(1)"
                class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border transition-colors whitespace-nowrap"
                :class="proofFilter ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-amber-600 border-amber-300 hover:bg-amber-50'">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Butuh Verifikasi
            </button>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="adm-card p-4">
            <p class="text-xs text-gray-500">Total Transaksi</p>
            <p class="text-xl font-bold text-gray-900 mt-0.5" x-text="meta.total ?? 0"></p>
        </div>
        <div class="adm-card p-4">
            <p class="text-xs text-gray-500">Lunas</p>
            <p class="text-xl font-bold mt-0.5" style="color:#019342;" x-text="summary.paid ?? 0"></p>
        </div>
        <div class="adm-card p-4">
            <p class="text-xs text-gray-500">Sebagian / Menunggu</p>
            <p class="text-xl font-bold text-amber-500 mt-0.5"
                x-text="(summary.partial ?? 0) + ' / ' + (summary.pending ?? 0)"></p>
        </div>
        <div class="adm-card p-4">
            <p class="text-xs text-gray-500">Total Pemasukan</p>
            <p class="text-lg font-bold text-gray-900 mt-0.5"
                x-text="'Rp ' + Number(summary.total_amount ?? 0).toLocaleString('id-ID')"></p>
        </div>
    </div>

    <div class="flex gap-5 items-start">
        {{-- Table --}}
        <div class="flex-1 adm-card overflow-hidden">
            <div x-show="loading" class="p-10 flex justify-center">
                <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="adm-th">Pendaftar</th>
                            <th class="adm-th">Jumlah</th>
                            <th class="adm-th">Status</th>
                            <th class="adm-th">Bukti</th>
                            <th class="adm-th">Tanggal</th>
                            <th class="adm-th"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="items.length === 0">
                            <tr><td colspan="6" class="td text-center text-gray-400 py-10">Belum ada data pembayaran.</td></tr>
                        </template>
                        <template x-for="item in items" :key="item.payment_id ?? item.id">
                            <tr class="adm-tr cursor-pointer"
                                :class="detail?.payment_id === item.payment_id ? 'bg-amber-50' : ''"
                                @click="openDetail(item)">
                                <td class="adm-td">
                                    <p class="font-medium text-gray-900" x-text="item.registration?.student_name ?? item.student_name ?? '-'"></p>
                                    <p class="text-[10px] text-gray-400 font-mono" x-text="item.order_id ?? '-'"></p>
                                </td>
                                <td class="td text-sm text-gray-900">
                                    <span class="font-semibold" x-text="'Rp ' + Number(item.amount ?? 0).toLocaleString('id-ID')"></span>
                                    <template x-if="item.paid_amount > 0 && item.status !== 'paid'">
                                        <span class="block text-[10px] text-amber-600 font-medium"
                                            x-text="'Terbayar Rp ' + Number(item.paid_amount ?? 0).toLocaleString('id-ID')">
                                        </span>
                                    </template>
                                </td>
                                <td class="adm-td">
                                    <span class="badge"
                                        :class="{'badge-yellow':item.status==='pending','badge-green':item.status==='paid','badge-orange':item.status==='partial','badge-red':item.status==='failed','badge-gray':item.status==='expired'}"
                                        x-text="{paid:'Lunas',pending:'Menunggu',partial:'Sebagian',failed:'Ditolak',expired:'Expired'}[item.status] ?? item.status">
                                    </span>
                                </td>
                                <td class="adm-td">
                                    <template x-if="item.proof_url">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            Ada Bukti
                                        </span>
                                    </template>
                                    <template x-if="!item.proof_url">
                                        <span class="text-[10px] text-gray-300">—</span>
                                    </template>
                                </td>
                                <td class="td text-xs text-gray-500" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
                                <td class="adm-td" @click.stop>
                                    <button @click="openDetail(item)"
                                        class="text-xs font-medium text-green-700 hover:text-white border border-green-300 hover:bg-green-600 hover:border-green-600 px-3 py-1 rounded-lg transition-colors whitespace-nowrap">
                                        Lihat Detail
                                    </button>
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

        {{-- ═══ Detail Panel ═══════════════════════════════════════════════════ --}}
        <template x-if="detail">
            <div class="w-80 shrink-0 adm-card overflow-hidden flex flex-col" style="max-height:calc(100vh - 8rem); position:sticky; top:5rem;">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Detail Pembayaran</h3>
                    <button @click="detail = null" class="adm-btn-ghost p-1 rounded hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">

                    {{-- Pendaftar info --}}
                    <dl class="space-y-2">
                        <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0 text-xs">Pendaftar</dt><dd class="font-semibold text-gray-800 text-xs" x-text="detail.registration?.student_name ?? '-'"></dd></div>
                        <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0 text-xs">No. Daftar</dt><dd class="font-mono text-xs text-gray-600" x-text="detail.registration?.registration_number ?? '-'"></dd></div>
                        <div class="flex gap-2"><dt class="text-gray-400 w-28 shrink-0 text-xs">Order ID</dt><dd class="font-mono text-[10px] text-gray-500" x-text="detail.order_id ?? '-'"></dd></div>
                        <div class="flex gap-2">
                            <dt class="text-gray-400 w-28 shrink-0 text-xs">Status Reg.</dt>
                            <dd>
                                <span class="badge text-[10px]"
                                    :class="{'badge-gray':detail.registration?.status==='draft','badge-yellow':detail.registration?.status==='submitted','badge-blue':detail.registration?.status==='document_review','badge-indigo':detail.registration?.status==='verified','badge-green':detail.registration?.status==='accepted','badge-red':detail.registration?.status==='rejected'}"
                                    x-text="{draft:'Draft',submitted:'Mendaftar',document_review:'Review Dok.',verified:'Terverifikasi',accepted:'Diterima',rejected:'Ditolak'}[detail.registration?.status] ?? '-'">
                                </span>
                            </dd>
                        </div>
                    </dl>

                    <hr class="border-gray-100">

                    {{-- ═══ STEP 1 — Verifikasi Pembayaran (pending + ada bukti) ═══ --}}
                    <template x-if="detail.status === 'pending' && detail.proof_url">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full bg-amber-500 text-white text-[10px] font-black flex items-center justify-center shrink-0">1</span>
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">Verifikasi Pembayaran</p>
                            </div>

                            {{-- Bukti --}}
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium mb-1.5">Bukti Transfer</p>
                                <template x-if="!detail.proof_url.endsWith('.pdf')">
                                    <a :href="detail.proof_url" target="_blank" class="block">
                                        <img :src="detail.proof_url" class="rounded-xl border border-gray-200 w-full object-cover max-h-52 hover:opacity-90 transition cursor-zoom-in">
                                    </a>
                                </template>
                                <template x-if="detail.proof_url.endsWith('.pdf')">
                                    <a :href="detail.proof_url" target="_blank"
                                        class="flex items-center gap-2 text-blue-600 text-xs font-semibold border border-blue-100 bg-blue-50 rounded-xl px-4 py-3 hover:bg-blue-100 transition">
                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        Lihat PDF Bukti
                                    </a>
                                </template>
                            </div>

                            {{-- Rincian pembayaran + input --}}
                            <div class="bg-gray-50 rounded-xl p-3.5 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Tagihan Total</span>
                                    <span class="text-xs font-bold text-gray-900"
                                        x-text="'Rp ' + Number(detail.amount ?? 0).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="space-y-1.5">
                                    <p class="text-xs text-gray-500 font-medium">Jumlah Terbayar <span class="text-gray-400 font-normal">(sesuai bukti)</span></p>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-400 font-medium shrink-0">Rp</span>
                                        <input type="number" x-model.number="paidInput" min="0"
                                            :max="Number(detail.amount ?? 0)"
                                            class="adm-input flex-1 text-xs text-right font-semibold"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="flex justify-between items-center pt-1 border-t border-gray-200">
                                    <span class="text-xs text-gray-500">Sisa Bayar</span>
                                    <span class="text-xs font-bold"
                                        :class="Math.max(0, Number(detail.amount ?? 0) - paidInput) > 0 ? 'text-red-500' : 'text-green-600'"
                                        x-text="'Rp ' + Math.max(0, Number(detail.amount ?? 0) - paidInput).toLocaleString('id-ID')">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Ringkasan read-only (sudah diverifikasi / belum ada bukti) --}}
                    <template x-if="!(detail.status === 'pending' && detail.proof_url)">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full flex items-center justify-center shrink-0 text-[10px] font-black"
                                    :class="detail.status === 'paid' ? 'bg-green-600 text-white' : detail.status === 'partial' ? 'bg-amber-500 text-white' : 'bg-gray-300 text-white'">1</span>
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">Pembayaran</p>
                                <span class="badge text-[10px]"
                                    :class="{'badge-yellow':detail.status==='pending','badge-green':detail.status==='paid','badge-orange':detail.status==='partial','badge-red':detail.status==='failed','badge-gray':detail.status==='expired'}"
                                    x-text="{paid:'Lunas',pending:'Belum Bayar',partial:'Sebagian',failed:'Ditolak',expired:'Expired'}[detail.status] ?? detail.status">
                                </span>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3.5 space-y-2.5">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Tagihan</span>
                                    <span class="text-xs font-bold text-gray-900"
                                        x-text="'Rp ' + Number(detail.amount ?? 0).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Terbayar</span>
                                    <span class="text-xs font-bold" :class="detail.status==='paid' ? 'text-green-600' : 'text-amber-600'"
                                        x-text="'Rp ' + Number(detail.paid_amount ?? 0).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between pt-1 border-t border-gray-200">
                                    <span class="text-xs text-gray-500">Sisa</span>
                                    <span class="text-xs font-bold"
                                        :class="(Number(detail.amount??0) - Number(detail.paid_amount??0)) > 0 ? 'text-red-500' : 'text-green-600'"
                                        x-text="'Rp ' + Math.max(0, Number(detail.amount ?? 0) - Number(detail.paid_amount ?? 0)).toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                            <template x-if="detail.rejected_reason">
                                <div class="text-xs text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2" x-text="'Ditolak: ' + detail.rejected_reason"></div>
                            </template>
                            {{-- Show proof for paid/partial --}}
                            <template x-if="detail.proof_url && (detail.status === 'paid' || detail.status === 'partial')">
                                <div>
                                    <p class="text-[10px] text-gray-400 mb-1.5">Bukti Pembayaran</p>
                                    <template x-if="!detail.proof_url.endsWith('.pdf')">
                                        <a :href="detail.proof_url" target="_blank">
                                            <img :src="detail.proof_url" class="rounded-xl border border-gray-200 w-full object-cover max-h-40 hover:opacity-90 transition cursor-zoom-in">
                                        </a>
                                    </template>
                                    <template x-if="detail.proof_url.endsWith('.pdf')">
                                        <a :href="detail.proof_url" target="_blank"
                                            class="flex items-center gap-2 text-blue-600 text-xs font-semibold border border-blue-100 bg-blue-50 rounded-xl px-4 py-3">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            Lihat PDF Bukti
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- ═══ Riwayat Pembayaran ════════════════════════════════ --}}
                    <template x-if="detail.histories && detail.histories.length > 0">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Riwayat Pembayaran</p>
                            <div class="space-y-0">
                                <template x-for="(h, idx) in detail.histories" :key="h.payment_history_id ?? idx">
                                    <div class="flex gap-2.5">
                                        {{-- Dot + line --}}
                                        <div class="flex flex-col items-center shrink-0">
                                            <span class="h-5 w-5 rounded-full border-2 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5"
                                                :class="{
                                                    'border-green-500 bg-green-50 text-green-600': h.new_status === 'paid',
                                                    'border-amber-400 bg-amber-50 text-amber-600': h.new_status === 'partial',
                                                    'border-red-400 bg-red-50 text-red-500':       h.new_status === 'failed',
                                                    'border-blue-400 bg-blue-50 text-blue-500':    h.new_status === 'pending',
                                                    'border-purple-400 bg-purple-50 text-purple-500': h.event_type === 'student_accepted',
                                                    'border-gray-300 bg-white text-gray-400':      !['paid','partial','failed','pending'].includes(h.new_status) && h.event_type !== 'student_accepted',
                                                }">
                                                <template x-if="h.new_status === 'paid' && h.event_type !== 'student_accepted'">
                                                    <span>✓</span>
                                                </template>
                                                <template x-if="h.new_status === 'partial'">
                                                    <span>½</span>
                                                </template>
                                                <template x-if="h.new_status === 'failed'">
                                                    <span>✕</span>
                                                </template>
                                                <template x-if="h.event_type === 'student_accepted'">
                                                    <span>🎓</span>
                                                </template>
                                                <template x-if="!['paid','partial','failed','student_accepted'].includes(h.new_status) && h.event_type !== 'student_accepted'">
                                                    <span>·</span>
                                                </template>
                                            </span>
                                            <div class="w-px flex-1 bg-gray-100 mt-1"
                                                :class="idx === detail.histories.length - 1 ? 'opacity-0' : ''"></div>
                                        </div>
                                        {{-- Content --}}
                                        <div class="pb-3 min-w-0">
                                            <p class="text-[11px] font-bold text-gray-700 leading-snug"
                                                x-text="{
                                                    'created':          'Pembayaran dibuat',
                                                    'admin_update':     h.new_status === 'paid' ? 'Dikonfirmasi lunas' : 'Dikonfirmasi sebagian',
                                                    'admin_reject':     'Bukti ditolak admin',
                                                    'midtrans_webhook': 'Dikonfirmasi Midtrans',
                                                    'student_accepted': 'Pendaftar diterima sebagai murid',
                                                }[h.event_type] ?? (h.event_type ?? 'Update status')">
                                            </p>
                                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                <template x-if="h.old_status">
                                                    <span class="flex items-center gap-1 text-[9px] text-gray-400">
                                                        <span x-text="{paid:'Lunas',partial:'Sebagian',pending:'Menunggu',failed:'Ditolak',expired:'Expired'}[h.old_status] ?? h.old_status"></span>
                                                        <span>→</span>
                                                        <span class="font-bold"
                                                            :class="{'text-green-600':h.new_status==='paid','text-amber-500':h.new_status==='partial','text-red-500':h.new_status==='failed'}"
                                                            x-text="{paid:'Lunas',partial:'Sebagian',pending:'Menunggu',failed:'Ditolak',expired:'Expired'}[h.new_status] ?? h.new_status">
                                                        </span>
                                                    </span>
                                                </template>
                                                <template x-if="h.payload?.paid_amount">
                                                    <span class="text-[9px] font-semibold text-gray-500"
                                                        x-text="'Rp ' + Number(h.payload.paid_amount).toLocaleString('id-ID')">
                                                    </span>
                                                </template>
                                            </div>
                                            <template x-if="h.payload?.note">
                                                <p class="text-[9px] text-gray-400 mt-0.5 italic leading-relaxed"
                                                    x-text="h.payload.note"></p>
                                            </template>
                                            <p class="text-[9px] text-gray-300 mt-0.5"
                                                x-text="h.created_at ? new Date(h.created_at).toLocaleString('id-ID',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : ''">
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>

                {{-- ═══ Action Footer ═══════════════════════════════════════════════ --}}

                {{-- STEP 1 ACTIONS: pending + ada bukti → verifikasi --}}
                <template x-if="detail.status === 'pending' && detail.proof_url">
                    <div class="border-t border-gray-100 p-4 space-y-2">
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-2">Tindakan Verifikasi</p>
                        {{-- Lunas: aktif jika paidInput >= tagihan --}}
                        <button @click="confirmPaid(detail.payment_id ?? detail.id)"
                            :disabled="paidInput <= 0 || paidInput < (detail.amount ?? 0)"
                            class="w-full adm-btn text-white justify-center adm-btn-sm transition disabled:opacity-40 disabled:cursor-not-allowed"
                            style="background:#019342;"
                            onmouseover="if(!this.disabled)this.style.background='#191654'" onmouseout="this.style.background='#019342'">
                            ✓ Konfirmasi Lunas
                        </button>
                        {{-- Sebagian: aktif jika 0 < paidInput < tagihan --}}
                        <button @click="confirmPartial(detail.payment_id ?? detail.id)"
                            :disabled="paidInput <= 0 || paidInput >= (detail.amount ?? 0)"
                            class="w-full adm-btn adm-btn-sm justify-center bg-amber-500 text-white hover:bg-amber-600 disabled:opacity-40 disabled:cursor-not-allowed transition">
                            ⟳ Konfirmasi Sebagian
                        </button>
                        <button @click="rejectPayment(detail.payment_id ?? detail.id)"
                            class="w-full adm-btn adm-btn-danger adm-btn-sm justify-center">
                            ✕ Tolak Bukti Pembayaran
                        </button>
                    </div>
                </template>

                {{-- STEP 2 ACTIONS: payment lunas → terima murid --}}
                <template x-if="detail.status === 'paid'">
                    <div class="border-t border-gray-100 p-4 space-y-2">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="h-5 w-5 rounded-full bg-green-600 text-white text-[10px] font-black flex items-center justify-center shrink-0">2</span>
                            <p class="text-[10px] text-gray-500 font-semibold">Pembayaran lunas. Terima sebagai murid baru.</p>
                        </div>
                        <template x-if="detail.registration?.status !== 'accepted'">
                            <button @click="acceptStudent()"
                                class="w-full adm-btn text-white justify-center adm-btn-sm"
                                style="background:#019342;"
                                onmouseover="this.style.background='#191654'" onmouseout="this.style.background='#019342'">
                                🎓 Terima Murid
                            </button>
                        </template>
                        <template x-if="detail.registration?.status === 'accepted'">
                            <div class="text-center py-2">
                                <span class="badge badge-green text-xs">✓ Pendaftar sudah diterima sebagai murid</span>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Pending tanpa bukti --}}
                <template x-if="detail.status === 'pending' && !detail.proof_url">
                    <div class="border-t border-gray-100 p-4">
                        <p class="text-xs text-gray-400 italic text-center">Menunggu pendaftar mengirim bukti pembayaran.</p>
                    </div>
                </template>

            </div>
        </template>
    </div>
</div>
