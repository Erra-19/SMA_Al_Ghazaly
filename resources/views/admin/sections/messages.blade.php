{{-- ═══ MESSAGES ════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'messages'" x-data="messagesPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Pesan Masuk</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`Total: ${meta.total ?? 0} pesan`"></p>
        </div>
    </div>

    <div class="flex gap-5">
        {{-- List --}}
        <div class="w-80 shrink-0 adm-card overflow-hidden flex flex-col max-h-[calc(100vh-8rem)]">
            <div class="px-4 py-3 border-b border-gray-100">
                <input type="text" x-model="search" @input="filterMessages()"
                    class="adm-input text-xs py-1.5" placeholder="Cari pesan...">
            </div>
            <div x-show="loading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!loading" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <template x-if="items.length === 0">
                    <p class="p-6 text-sm text-gray-400 text-center">Tidak ada pesan.</p>
                </template>
                <template x-for="m in items" :key="m.id">
                    <button @click="openDetail(m)"
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 transition"
                        :class="detail?.id === m.id && 'bg-green-50 border-l-2 border-green-600'">
                        <div class="flex items-start gap-2">
                            <div class="h-7 w-7 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 shrink-0 mt-0.5"
                                x-text="(m.name ?? 'X').charAt(0).toUpperCase()"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="m.name"></p>
                                    <span x-show="!m.is_read" class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5 truncate" x-text="m.message ?? m.content ?? ''"></p>
                                <p class="text-[10px] text-gray-300 mt-0.5" x-text="m.created_at ? new Date(m.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short'}) : ''"></p>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
            <div x-show="meta.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex gap-1">
                <button @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">←</button>
                <button @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">→</button>
            </div>
        </div>

        {{-- Detail --}}
        <div class="flex-1">
            <template x-if="!detail">
                <div class="adm-card h-48 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="h-10 w-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-sm">Pilih pesan untuk dibaca</p>
                    </div>
                </div>
            </template>
            <template x-if="detail">
                <div class="adm-card">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900" x-text="detail.name"></h3>
                                <p class="text-sm text-gray-500 mt-0.5" x-text="detail.email"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="detail.phone ?? ''"></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-400" x-text="detail.created_at ? new Date(detail.created_at).toLocaleString('id-ID',{day:'2-digit',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit'}) : ''"></p>
                                <template x-if="detail.subject">
                                    <p class="text-sm font-medium text-gray-700 mt-1" x-text="`Perihal: ${detail.subject}`"></p>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed" x-text="detail.message ?? detail.content ?? ''"></p>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="confirmId !== detail.id">
                                <button @click="confirmId = detail.id" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </template>
                            <template x-if="confirmId === detail.id">
                                <div class="flex gap-2">
                                    <button @click="remove(detail.id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus Sekarang</button>
                                    <button @click="confirmId = null" class="adm-btn adm-btn-secondary adm-btn-sm">Batal</button>
                                </div>
                            </template>
                        </div>
                        <a :href="`mailto:${detail.email}`" class="adm-btn adm-btn-secondary adm-btn-sm">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Balas via Email
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
