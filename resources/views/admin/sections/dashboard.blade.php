{{-- ═══ DASHBOARD ═══════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'dashboard'" x-data="dashboardPage()" x-init="load()">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <template x-for="s in stats" :key="s.label">
            <div class="adm-card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 font-medium truncate" x-text="s.label"></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1 leading-none" x-text="s.value"></p>
                        <p class="text-xs mt-1.5 font-medium" :class="s.trendUp ? 'text-green-600' : 'text-gray-400'" x-text="s.sub"></p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" :class="s.bg"
                        x-html="s.icon"></div>
                </div>
            </div>
        </template>
        {{-- Loading shimmer --}}
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

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Pendaftar Terbaru --}}
        <div class="adm-card">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Pendaftar Terbaru</h3>
                <a @click.prevent="$store.adm.go('registrations')" href="#"
                    class="text-xs text-green-600 hover:text-green-700 font-medium">Lihat semua →</a>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="divide-y divide-gray-50">
                <template x-if="recentRegs.length === 0">
                    <p class="px-5 py-8 text-sm text-gray-400 text-center">Belum ada pendaftar.</p>
                </template>
                <template x-for="r in recentRegs" :key="r.id ?? r.registration_id">
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-[11px] font-bold text-green-700 shrink-0"
                            x-text="(r.student_name ?? r.full_name ?? 'X').split(' ').slice(0,2).map(n=>n[0]??'').join('').toUpperCase()"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate"
                                x-text="r.student_name ?? r.full_name ?? '—'"></p>
                            <p class="text-xs text-gray-400"
                                x-text="r.registration_number ?? r.created_at ? new Date(r.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : ''"></p>
                        </div>
                        <span class="badge"
                            :class="{
                                'badge-yellow': ['pending','submitted'].includes(r.status),
                                'badge-blue':   ['document_review','verified'].includes(r.status),
                                'badge-green':  ['accepted','approved'].includes(r.status),
                                'badge-red':    r.status === 'rejected',
                                'badge-gray':   !r.status
                            }"
                            x-text="r.status === 'submitted' ? 'Submit' :
                                    r.status === 'document_review' ? 'Review' :
                                    r.status === 'verified' ? 'Verif' :
                                    r.status === 'accepted' ? 'Diterima' :
                                    r.status === 'rejected' ? 'Ditolak' : (r.status ?? '—')">
                        </span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Pesan Terbaru --}}
        <div class="adm-card">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Pesan Terbaru</h3>
                <a @click.prevent="$store.adm.go('messages')" href="#"
                    class="text-xs text-green-600 hover:text-green-700 font-medium">Lihat semua →</a>
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="divide-y divide-gray-50">
                <template x-if="recentMsgs.length === 0">
                    <p class="px-5 py-8 text-sm text-gray-400 text-center">Belum ada pesan.</p>
                </template>
                <template x-for="m in recentMsgs" :key="m.id">
                    <div class="flex items-center gap-3 px-5 py-3 cursor-pointer hover:bg-gray-50"
                        @click="$store.adm.go('messages')">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-[11px] font-bold text-blue-700 shrink-0"
                            x-text="(m.name ?? 'X').charAt(0).toUpperCase()"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-text="m.name"></p>
                            <p class="text-xs text-gray-400 truncate" x-text="m.message ?? m.content ?? ''"></p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-[10px] text-gray-400"
                                x-text="m.created_at ? new Date(m.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short'}) : ''"></p>
                            <span x-show="!m.is_read" class="inline-block h-1.5 w-1.5 rounded-full bg-green-500 mt-1"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
