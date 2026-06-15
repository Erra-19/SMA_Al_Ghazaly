{{-- ═══ DASHBOARD ═══════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'dashboard'" x-data="dashboardPage()" x-init="load()">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <template x-for="s in stats" :key="s.label">
            <div class="adm-card p-5 cursor-pointer hover:shadow-md transition-shadow group"
                 @click="$store.adm.go(s.action)">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 font-medium truncate" x-text="s.label"></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1 leading-none" x-text="s.value"></p>
                        <p class="text-xs mt-1.5 text-gray-400 truncate" x-text="s.sub"></p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition-transform group-hover:scale-110"
                         :class="s.bg" x-html="s.icon"></div>
                </div>
            </div>
        </template>
        <template x-if="loading && stats.length === 0">
            <template x-for="i in [1,2,3,4]">
                <div class="adm-card p-5 animate-pulse">
                    <div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div>
                    <div class="h-7 bg-gray-200 rounded w-1/3 mb-2"></div>
                    <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                </div>
            </template>
        </template>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-2 mb-5">
        <button @click="$store.adm.go('posts')"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-xs font-medium text-gray-700 transition-colors shadow-sm">
            <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Post
        </button>
        <button @click="$store.adm.go('teachers')"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-xs font-medium text-gray-700 transition-colors shadow-sm">
            <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Kelola Guru
        </button>
        <button @click="$store.adm.go('registrations')"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-xs font-medium text-gray-700 transition-colors shadow-sm">
            <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Semua Pendaftar
        </button>
        <button @click="$store.adm.go('payments')"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-orange-400 hover:bg-orange-50 text-xs font-medium text-gray-700 transition-colors shadow-sm">
            <svg class="h-3.5 w-3.5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Verifikasi Bayar
        </button>
        <button @click="$store.adm.go('messages')"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:bg-blue-50 text-xs font-medium text-gray-700 transition-colors shadow-sm">
            <svg class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Lihat Pesan
        </button>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">

        {{-- Bar Chart: Pendaftar per Bulan --}}
        <div class="adm-card p-5 lg:col-span-3">
            <p class="text-sm font-semibold text-gray-800 mb-1">Pendaftar per Bulan</p>
            <p class="text-xs text-gray-400 mb-4">6 bulan terakhir</p>
            <div x-show="loading" class="h-32 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading">
                {{-- Bars --}}
                <div class="flex items-end gap-2 h-28 px-1">
                    <template x-for="(m, idx) in monthlyData" :key="m.label">
                        <div class="flex-1 flex flex-col items-center justify-end gap-0.5">
                            <span class="text-[9px] text-gray-500 tabular-nums leading-none"
                                  x-show="m.count > 0" x-text="m.count"></span>
                            <div class="w-full rounded-t-sm transition-all duration-700"
                                 :class="idx === monthlyData.length - 1 ? 'bg-green-600' : 'bg-green-200'"
                                 :style="`height:${maxMonth > 0 ? Math.max(4, Math.round(m.count / maxMonth * 96)) : 4}px`">
                            </div>
                        </div>
                    </template>
                </div>
                {{-- Labels --}}
                <div class="flex gap-2 mt-1.5 px-1">
                    <template x-for="m in monthlyData" :key="m.label + '_l'">
                        <span class="flex-1 text-center text-[9px] text-gray-400" x-text="m.label"></span>
                    </template>
                </div>
            </div>
        </div>

        {{-- Donut: Status Pendaftar --}}
        <div class="adm-card p-5 lg:col-span-2">
            <p class="text-sm font-semibold text-gray-800 mb-1">Status Pendaftar</p>
            <p class="text-xs text-gray-400 mb-4">Keseluruhan</p>
            <div x-show="loading" class="h-32 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="flex items-center gap-5">
                {{-- Donut --}}
                <div class="relative shrink-0 w-24 h-24">
                    <div class="w-24 h-24 rounded-full" :style="'background: ' + donutGradient"></div>
                    <div class="absolute inset-[22%] rounded-full bg-white flex flex-col items-center justify-center">
                        <span class="text-sm font-bold text-gray-900 leading-none" x-text="regStatus.total ?? 0"></span>
                        <span class="text-[9px] text-gray-400 mt-0.5">total</span>
                    </div>
                </div>
                {{-- Legend --}}
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-green-500 shrink-0"></div>
                            <span class="text-xs text-gray-600">Diterima</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800" x-text="regStatus.accepted ?? 0"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></div>
                            <span class="text-xs text-gray-600">Submit</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800" x-text="regStatus.submitted ?? 0"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-blue-400 shrink-0"></div>
                            <span class="text-xs text-gray-600">Review</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800"
                              x-text="(regStatus.document_review ?? 0) + (regStatus.verified ?? 0)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-red-400 shrink-0"></div>
                            <span class="text-xs text-gray-600">Ditolak</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800" x-text="regStatus.rejected ?? 0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Pendaftar Terbaru --}}
        <div class="adm-card">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Pendaftar Terbaru</h3>
                <button @click="$store.adm.go('registrations')"
                    class="text-xs text-green-600 hover:text-green-700 font-medium">Lihat semua →</button>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="divide-y divide-gray-50">
                <template x-if="recentRegs.length === 0">
                    <p class="px-5 py-8 text-sm text-gray-400 text-center">Belum ada pendaftar.</p>
                </template>
                <template x-for="r in recentRegs" :key="r.registration_id">
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-[11px] font-bold text-green-700 shrink-0"
                             x-text="(r.student_name ?? 'X').split(' ').slice(0,2).map(n=>n[0]??'').join('').toUpperCase()">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-text="r.student_name ?? '—'"></p>
                            <p class="text-xs text-gray-400"
                               x-text="r.created_at ? new Date(r.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : ''"></p>
                        </div>
                        <span class="badge"
                            :class="{
                                'badge-yellow': ['pending','submitted'].includes(r.status),
                                'badge-blue':   ['document_review','verified'].includes(r.status),
                                'badge-green':  ['accepted','approved'].includes(r.status),
                                'badge-red':    r.status === 'rejected',
                            }"
                            x-text="r.status === 'submitted'       ? 'Submit'   :
                                    r.status === 'document_review' ? 'Review'   :
                                    r.status === 'verified'        ? 'Verif'    :
                                    r.status === 'accepted'        ? 'Diterima' :
                                    r.status === 'rejected'        ? 'Ditolak'  : (r.status ?? '—')">
                        </span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Perlu Aksi: Bukti Bayar Pending --}}
        <div class="adm-card">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Perlu Aksi</h3>
                <button @click="$store.adm.go('payments')"
                    class="text-xs text-green-600 hover:text-green-700 font-medium">Lihat semua →</button>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="divide-y divide-gray-50">
                <template x-if="pendingPayments.length === 0">
                    <div class="px-5 py-8 text-center">
                        <svg class="h-8 w-8 text-green-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400">Tidak ada yang perlu diverifikasi.</p>
                    </div>
                </template>
                <template x-for="p in pendingPayments" :key="p.payment_id">
                    <div class="flex items-center gap-3 px-5 py-3 cursor-pointer hover:bg-orange-50/60 transition-colors"
                         @click="$store.adm.go('payments')">
                        <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate"
                               x-text="p.registration?.student_name ?? 'Pendaftar'"></p>
                            <p class="text-xs text-gray-400" x-text="fmt.currency(p.amount)"></p>
                        </div>
                        <span class="badge badge-yellow text-[9px]">Verifikasi</span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Agenda Terdekat --}}
        <div class="adm-card">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Agenda Terdekat</h3>
                <button @click="$store.adm.go('academic-calendars')"
                    class="text-xs text-green-600 hover:text-green-700 font-medium">Lihat semua →</button>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="divide-y divide-gray-50">
                <template x-if="upcomingEvents.length === 0">
                    <p class="px-5 py-8 text-sm text-gray-400 text-center">Tidak ada agenda mendatang.</p>
                </template>
                <template x-for="e in upcomingEvents" :key="e.calendar_id">
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="shrink-0 w-9 text-center">
                            <p class="text-base font-bold text-gray-800 leading-none"
                               x-text="new Date(e.start_date).getDate()"></p>
                            <p class="text-[9px] text-gray-400 uppercase"
                               x-text="new Date(e.start_date).toLocaleDateString('id-ID',{month:'short'})"></p>
                        </div>
                        <div class="w-0.5 h-8 rounded-full shrink-0"
                            :class="{
                                'bg-green-400':  e.color === 'green',
                                'bg-blue-400':   e.color === 'blue',
                                'bg-yellow-400': e.color === 'yellow',
                                'bg-red-400':    e.color === 'red',
                                'bg-purple-400': e.color === 'purple',
                                'bg-orange-400': e.color === 'orange',
                                'bg-gray-300':   !e.color || e.color === 'gray',
                            }">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-text="e.title"></p>
                            <p class="text-xs text-gray-400" x-text="e.category ?? ''"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
