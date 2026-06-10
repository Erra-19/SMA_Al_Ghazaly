{{-- ═══ PAYMENTS ════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'payments'" x-data="paymentsPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Pembayaran PPDB</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} transaksi`"></p>
        </div>
        <div class="flex gap-2">
            <select x-model="statusFilter" @change="load()" class="adm-select text-xs py-1.5 w-36">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Lunas</option>
                <option value="failed">Gagal</option>
                <option value="expired">Expired</option>
            </select>
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
            <p class="text-xs text-gray-500">Menunggu</p>
            <p class="text-xl font-bold text-yellow-500 mt-0.5" x-text="summary.pending ?? 0"></p>
        </div>
        <div class="adm-card p-4">
            <p class="text-xs text-gray-500">Total Pemasukan</p>
            <p class="text-lg font-bold text-gray-900 mt-0.5" x-text="'Rp ' + (summary.total_amount ?? 0).toLocaleString('id-ID')"></p>
        </div>
    </div>

    <div class="adm-card overflow-hidden">
        <div x-show="loading" class="p-10 flex justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="adm-th">Order ID</th>
                        <th class="adm-th">Pendaftar</th>
                        <th class="adm-th">Jumlah</th>
                        <th class="adm-th">Metode</th>
                        <th class="adm-th">Status</th>
                        <th class="adm-th">Tanggal</th>
                        <th class="adm-th"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="items.length === 0">
                        <tr><td colspan="7" class="td text-center text-gray-400 py-10">Belum ada data pembayaran.</td></tr>
                    </template>
                    <template x-for="item in items" :key="item.id">
                        <tr class="adm-tr">
                            <td class="adm-td">
                                <p class="font-mono text-xs text-gray-700" x-text="item.order_id ?? item.transaction_id ?? '-'"></p>
                            </td>
                            <td class="adm-td">
                                <p class="font-medium text-gray-900" x-text="item.registration?.full_name ?? item.student_name ?? '-'"></p>
                            </td>
                            <td class="td font-semibold text-gray-900" x-text="'Rp ' + (item.amount ?? 0).toLocaleString('id-ID')"></td>
                            <td class="td text-gray-600 text-xs capitalize" x-text="item.payment_method ?? item.method ?? '-'"></td>
                            <td class="adm-td">
                                <span class="badge"
                                    :class="{'badge-yellow':item.status==='pending','badge-green':item.status==='paid','badge-red':item.status==='failed','badge-gray':item.status==='expired'}"
                                    x-text="item.status === 'paid' ? 'Lunas' : item.status === 'pending' ? 'Menunggu' : item.status === 'failed' ? 'Gagal' : 'Expired'">
                                </span>
                            </td>
                            <td class="td text-xs text-gray-500" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'"></td>
                            <td class="adm-td">
                                <template x-if="item.status === 'pending'">
                                    <button @click="confirmPaid(item.id)" class="adm-btn text-white adm-btn-sm" style="background:#019342;" onmouseover="this.style.background='#191654'" onmouseout="this.style.background='#019342'">Konfirmasi Lunas</button>
                                </template>
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
</div>
