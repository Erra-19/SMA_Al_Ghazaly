{{-- ═══ MEDIA ════════════════════════════════════════════════════════════════ --}}
<div x-show="$store.adm.page === 'media'" x-data="mediaPage()">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Manajemen Media</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="`${items.length} file tersimpan`"></p>
        </div>
        <label class="adm-btn adm-btn-primary cursor-pointer">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Upload File
            <input type="file" class="hidden" multiple accept="image/*,video/*,application/pdf"
                @change="upload($event.target.files)">
        </label>
    </div>

    {{-- Upload progress --}}
    <div x-show="uploading" class="mb-4">
        <div class="adm-card p-4 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700">Mengupload...</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                    <div class="bg-green-600 h-1.5 rounded-full transition-all" :style="`width:${uploadProgress}%`"></div>
                </div>
            </div>
            <span class="text-xs text-gray-500" x-text="`${uploadProgress}%`"></span>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex items-center gap-3 mb-4">
        <div class="flex gap-1 bg-white border border-gray-200 rounded-lg p-1">
            <template x-for="f in ['all','image','video','document']">
                <button @click="filterType = f; filterItems()"
                    class="px-3 py-1 rounded-md text-xs font-medium transition"
                    :class="filterType === f ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    x-text="f === 'all' ? 'Semua' : f.charAt(0).toUpperCase() + f.slice(1)"></button>
            </template>
        </div>
    </div>

    <div x-show="loading" class="p-10 flex justify-center">
        <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    {{-- Grid --}}
    <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        <template x-if="filtered.length === 0">
            <div class="col-span-5 adm-card p-10 text-center text-gray-400">Belum ada media.</div>
        </template>
        <template x-for="file in filtered" :key="file.id ?? file.url">
            <div class="adm-card overflow-hidden group relative cursor-pointer" @click="selectFile(file)">
                {{-- Image preview --}}
                <template x-if="file.mime_type && file.mime_type.startsWith('image')">
                    <div class="aspect-square bg-gray-100">
                        <img :src="file.url" class="w-full h-full object-cover">
                    </div>
                </template>
                {{-- Non-image --}}
                <template x-if="!file.mime_type || !file.mime_type.startsWith('image')">
                    <div class="aspect-square bg-gray-50 flex flex-col items-center justify-center gap-1">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs text-gray-400 uppercase" x-text="(file.name ?? file.url ?? '').split('.').pop()"></span>
                    </div>
                </template>
                {{-- Hover overlay --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-end">
                    <div class="w-full opacity-0 group-hover:opacity-100 transition p-2 bg-gradient-to-t from-black/60 to-transparent">
                        <p class="text-white text-xs truncate" x-text="file.name ?? file.url.split('/').pop()"></p>
                    </div>
                </div>
                {{-- Selected indicator --}}
                <template x-if="selected && selected.id === file.id">
                    <div class="absolute top-2 right-2 h-5 w-5 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </template>
            </div>
        </template>
    </div>

    {{-- File detail panel --}}
    <template x-if="selected">
        <div class="fixed inset-y-0 right-0 z-40 w-72 bg-white border-l border-gray-200 shadow-xl flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Detail File</h3>
                <button @click="selected = null" class="adm-btn-ghost adm-btn-ghost"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="flex-1 p-5 overflow-y-auto">
                <template x-if="selected.mime_type && selected.mime_type.startsWith('image')">
                    <img :src="selected.url" class="w-full rounded-lg object-cover mb-4 max-h-48">
                </template>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-400 text-xs font-medium">Nama file</dt>
                        <dd class="text-gray-700 break-all" x-text="selected.name ?? selected.url.split('/').pop()"></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs font-medium">URL</dt>
                        <dd class="text-gray-700 break-all text-xs" x-text="selected.url"></dd>
                    </div>
                    <template x-if="selected.size">
                        <div>
                            <dt class="text-gray-400 text-xs font-medium">Ukuran</dt>
                            <dd class="text-gray-700" x-text="selected.size > 1048576 ? (selected.size/1048576).toFixed(1)+' MB' : (selected.size/1024).toFixed(0)+' KB'"></dd>
                        </div>
                    </template>
                </dl>
            </div>
            <div class="border-t border-gray-100 p-5 space-y-2">
                <button @click="copyUrl(selected.url)" class="adm-btn adm-btn-secondary w-full justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    Salin URL
                </button>
                <template x-if="confirmId !== selected.id">
                    <button @click="confirmId = selected.id" class="adm-btn adm-btn-danger w-full justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus File
                    </button>
                </template>
                <template x-if="confirmId === selected.id">
                    <div class="flex gap-2">
                        <button @click="remove(selected.id)" class="adm-btn adm-btn-danger flex-1 justify-center">Ya, Hapus</button>
                        <button @click="confirmId = null" class="adm-btn adm-btn-secondary flex-1 justify-center">Batal</button>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
