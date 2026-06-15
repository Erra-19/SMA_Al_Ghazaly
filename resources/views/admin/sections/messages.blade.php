{{-- ═══ MESSAGES ════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'messages'" x-data="messagesPage()">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Pesan</h2>
            <p class="text-xs text-gray-500 mt-0.5"
                x-text="tab === 'public'
                    ? `${pubMeta.total ?? 0} pesan masuk publik`
                    : `${intMeta.total ?? 0} pesan ${internalTab === 'inbox' ? 'diterima' : 'terkirim'}`"></p>
        </div>
        <button x-show="tab === 'internal'" @click="openCompose()"
            class="adm-btn adm-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tulis Pesan
        </button>
    </div>

    {{-- Main Tabs --}}
    <div class="flex items-center gap-1 mb-5 border-b border-gray-200">
        <button @click="tab='public'; pubDetail=null"
            class="px-4 py-2 text-xs font-semibold border-b-2 transition"
            :class="tab==='public' ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700'">
            Pesan Publik
        </button>
        <button @click="tab='internal'; intDetail=null"
            class="relative px-4 py-2 text-xs font-semibold border-b-2 transition"
            :class="tab==='internal' ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700'">
            Pesan Internal
            <template x-if="$store.adm.internalUnread > 0">
                <span class="absolute -top-0.5 -right-1 min-w-[16px] h-4 rounded-full bg-rose-500 text-white text-[8px] font-bold flex items-center justify-center px-1"
                    x-text="$store.adm.internalUnread > 99 ? '99+' : $store.adm.internalUnread"></span>
            </template>
        </button>
    </div>

    {{-- ═══ TAB: PESAN PUBLIK ═══ --}}
    <div x-show="tab === 'public'" class="flex gap-5">
        {{-- List --}}
        <div class="w-80 shrink-0 adm-card overflow-hidden flex flex-col max-h-[calc(100vh-12rem)]">
            <div class="px-4 py-3 border-b border-gray-100">
                <input type="text" x-model="pubSearch" @input="filterPub()"
                    class="adm-input text-xs py-1.5" placeholder="Cari pesan...">
            </div>
            <div x-show="pubLoading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!pubLoading" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <template x-if="pubItems.length === 0">
                    <p class="p-6 text-sm text-gray-400 text-center">Tidak ada pesan masuk.</p>
                </template>
                <template x-for="m in pubItems" :key="m.id">
                    <button @click="openPubDetail(m)" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition"
                        :class="pubDetail?.id === m.id && 'bg-green-50 border-l-2 border-green-600'">
                        <div class="flex items-start gap-2">
                            <div class="h-7 w-7 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 shrink-0 mt-0.5"
                                x-text="(m.name ?? 'X').charAt(0).toUpperCase()"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-sm font-medium text-gray-900 truncate" :class="!m.is_read && 'font-bold'" x-text="m.name"></p>
                                    <span x-show="!m.is_read" class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5 truncate" x-text="m.message ?? ''"></p>
                                <p class="text-[10px] text-gray-300 mt-0.5" x-text="fmtMsgDate(m.created_at)"></p>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
            <div x-show="pubMeta.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex gap-1">
                <button @click="loadPublic(pubMeta.current_page - 1)" :disabled="pubMeta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">←</button>
                <button @click="loadPublic(pubMeta.current_page + 1)" :disabled="pubMeta.current_page >= pubMeta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">→</button>
            </div>
        </div>

        {{-- Detail publik --}}
        <div class="flex-1">
            <template x-if="!pubDetail">
                <div class="adm-card h-52 flex items-center justify-center text-gray-300">
                    <div class="text-center">
                        <svg class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-gray-400">Pilih pesan untuk dibaca</p>
                    </div>
                </div>
            </template>
            <template x-if="pubDetail">
                <div class="adm-card">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900" x-text="pubDetail.name"></h3>
                                <p class="text-sm text-gray-500 mt-0.5" x-text="pubDetail.email"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="pubDetail.phone ?? ''"></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-400" x-text="pubDetail.created_at ? new Date(pubDetail.created_at).toLocaleString('id-ID',{day:'2-digit',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit'}) : ''"></p>
                                <template x-if="pubDetail.subject">
                                    <span class="badge badge-blue mt-1" x-text="pubDetail.subject"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed" x-text="pubDetail.message ?? ''"></p>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="pubConfirmId !== pubDetail.id">
                                <button @click="pubConfirmId = pubDetail.id" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </template>
                            <template x-if="pubConfirmId === pubDetail.id">
                                <div class="flex gap-2">
                                    <button @click="removePub(pubDetail.id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus Sekarang</button>
                                    <button @click="pubConfirmId = null" class="adm-btn adm-btn-secondary adm-btn-sm">Batal</button>
                                </div>
                            </template>
                        </div>
                        <a :href="`mailto:${pubDetail.email}`" class="adm-btn adm-btn-secondary adm-btn-sm">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Balas via Email
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══ TAB: PESAN INTERNAL ═══ --}}
    <div x-show="tab === 'internal'" class="flex gap-5">
        {{-- List --}}
        <div class="w-80 shrink-0 adm-card overflow-hidden flex flex-col max-h-[calc(100vh-12rem)]">
            {{-- Sub-tabs: Masuk / Terkirim --}}
            <div class="flex border-b border-gray-100">
                <button @click="internalTab='inbox'"
                    class="flex-1 py-2.5 text-xs font-semibold transition"
                    :class="internalTab==='inbox' ? 'text-green-700 bg-green-50' : 'text-gray-500 hover:bg-gray-50'">
                    <span class="relative inline-flex items-center gap-1">
                        Masuk
                        <template x-if="$store.adm.internalUnread > 0">
                            <span class="min-w-[16px] h-4 rounded-full bg-rose-500 text-white text-[8px] font-bold inline-flex items-center justify-center px-1"
                                x-text="$store.adm.internalUnread > 99 ? '99+' : $store.adm.internalUnread"></span>
                        </template>
                    </span>
                </button>
                <button @click="internalTab='sent'"
                    class="flex-1 py-2.5 text-xs font-semibold transition"
                    :class="internalTab==='sent' ? 'text-green-700 bg-green-50' : 'text-gray-500 hover:bg-gray-50'">
                    Terkirim
                </button>
            </div>
            <div x-show="intLoading" class="p-8 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!intLoading" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <template x-if="intItems.length === 0">
                    <p class="p-6 text-sm text-gray-400 text-center" x-text="internalTab==='inbox' ? 'Tidak ada pesan masuk.' : 'Belum ada pesan terkirim.'"></p>
                </template>
                <template x-for="m in intItems" :key="m.id">
                    <button @click="openIntDetail(m)" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition"
                        :class="intDetail?.id === m.id && 'bg-green-50 border-l-2 border-green-600'">
                        <div class="flex items-start gap-2">
                            <div class="h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5"
                                :class="internalTab==='inbox' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600'"
                                x-text="((internalTab==='inbox' ? m.sender?.name : m.receiver?.name) ?? 'Semua').charAt(0).toUpperCase()"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-xs font-semibold text-gray-800 truncate"
                                        :class="!m.is_read && internalTab==='inbox' && 'font-bold text-gray-900'"
                                        x-text="internalTab==='inbox' ? (m.sender?.name ?? 'Admin') : (m.receiver?.name ?? 'Semua Admin')"></p>
                                    <span x-show="!m.is_read && internalTab==='inbox'" class="h-2 w-2 rounded-full bg-rose-500 shrink-0"></span>
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-0.5 font-medium" x-text="m.subject || '(Tanpa subjek)'"></p>
                                <p class="text-[10px] text-gray-300 mt-0.5 truncate" x-text="m.body"></p>
                                <p class="text-[9px] text-gray-300 mt-0.5" x-text="fmtMsgDate(m.created_at)"></p>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
            <div x-show="intMeta.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex gap-1">
                <button @click="loadInternal(intMeta.current_page - 1)" :disabled="intMeta.current_page <= 1" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">←</button>
                <button @click="loadInternal(intMeta.current_page + 1)" :disabled="intMeta.current_page >= intMeta.last_page" class="adm-btn adm-btn-secondary adm-btn-sm flex-1 justify-center disabled:opacity-40">→</button>
            </div>
        </div>

        {{-- Detail internal --}}
        <div class="flex-1">
            <template x-if="!intDetail">
                <div class="adm-card h-52 flex items-center justify-center text-gray-300">
                    <div class="text-center">
                        <svg class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"/></svg>
                        <p class="text-sm text-gray-400">Pilih pesan untuk dibaca</p>
                    </div>
                </div>
            </template>
            <template x-if="intDetail">
                <div class="adm-card">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-indigo-100 flex items-center justify-center text-sm font-bold text-indigo-700 shrink-0"
                                    x-text="(intDetail.sender?.name ?? 'A').charAt(0).toUpperCase()"></div>
                                <div>
                                    <p class="font-semibold text-gray-900" x-text="intDetail.sender?.name ?? 'Admin'"></p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Kepada:
                                        <span class="font-medium" x-text="intDetail.receiver?.name ?? 'Semua Admin'"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 space-y-1">
                                <p class="text-xs text-gray-400"
                                    x-text="intDetail.created_at ? new Date(intDetail.created_at).toLocaleString('id-ID',{day:'2-digit',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit'}) : ''"></p>
                                <template x-if="intDetail.is_read">
                                    <span class="badge badge-green text-[9px]">Sudah dibaca</span>
                                </template>
                                <template x-if="!intDetail.is_read">
                                    <span class="badge badge-gray text-[9px]">Belum dibaca</span>
                                </template>
                            </div>
                        </div>
                        <template x-if="intDetail.subject">
                            <p class="mt-3 text-sm font-semibold text-gray-800" x-text="intDetail.subject"></p>
                        </template>
                    </div>

                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed" x-text="intDetail.body"></p>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="intConfirmId !== intDetail.id">
                                <button @click="intConfirmId = intDetail.id" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </template>
                            <template x-if="intConfirmId === intDetail.id">
                                <div class="flex gap-2">
                                    <button @click="removeInt(intDetail.id)" class="adm-btn adm-btn-danger adm-btn-sm">Hapus Sekarang</button>
                                    <button @click="intConfirmId = null" class="adm-btn adm-btn-secondary adm-btn-sm">Batal</button>
                                </div>
                            </template>
                        </div>
                        {{-- Balas --}}
                        <button @click="composeForm.receiver_id = intDetail.sender?.id ?? ''; composeForm.subject = intDetail.subject ? 'Re: ' + intDetail.subject : ''; composeForm.body = ''; composeError = null; showCompose = true"
                            class="adm-btn adm-btn-secondary adm-btn-sm">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            Balas
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══ COMPOSE MODAL ═══ --}}
    <template x-teleport="body">
        <div x-show="showCompose" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showCompose = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Tulis Pesan Baru</h3>
                    <button @click="showCompose = false" class="adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Penerima --}}
                    <div>
                        <label class="adm-label">Kepada</label>
                        <select x-model="composeForm.receiver_id" class="adm-select">
                            <option value="">— Broadcast ke semua admin —</option>
                            <template x-for="a in adminList" :key="a.id">
                                <option :value="a.id"
                                    x-text="`${a.name} (${a.role === 'super_admin' ? 'Super Admin' : 'Admin'})`"></option>
                            </template>
                        </select>
                    </div>
                    {{-- Subjek --}}
                    <div>
                        <label class="adm-label">Subjek <span class="text-gray-300">(opsional)</span></label>
                        <input type="text" x-model="composeForm.subject" class="adm-input" placeholder="Perihal pesan...">
                    </div>
                    {{-- Isi --}}
                    <div>
                        <label class="adm-label">Isi Pesan <span class="text-red-500">*</span></label>
                        <textarea x-model="composeForm.body" class="adm-textarea" rows="5"
                            placeholder="Tulis isi pesan..."></textarea>
                    </div>
                    <div x-show="composeError" x-text="composeError"
                        class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-2"></div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button @click="showCompose = false" class="adm-btn adm-btn-secondary">Batal</button>
                    <button @click="sendMessage()" :disabled="composeSaving" class="adm-btn adm-btn-primary">
                        <svg x-show="!composeSaving" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span x-show="!composeSaving">Kirim</span>
                        <span x-show="composeSaving">Mengirim...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
