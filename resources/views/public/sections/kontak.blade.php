{{-- ═══ KONTAK ══════════════════════════════════════════════════════════════════ --}}

<div class="py-14 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Hubungi Kami</p>
        <h1 class="text-3xl font-black mb-3">Kontak & Lokasi</h1>
        <p class="text-gray-300">Kami siap membantu Anda. Jangan ragu untuk menghubungi kami.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        {{-- Contact Info --}}
        <div>
            <h2 class="text-xl font-black text-gray-900 mb-6">Informasi Kontak</h2>
            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdf4">
                        <svg class="h-5 w-5" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Alamat</p>
                        <p class="text-gray-500 text-sm mt-0.5" x-text="$store.pub.settings.address || 'Jl. Raya Bogor No. 1, Kota Bogor, Jawa Barat 16111'"></p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdf4">
                        <svg class="h-5 w-5" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Telepon / WhatsApp</p>
                        <p class="text-gray-500 text-sm mt-0.5" x-text="$store.pub.settings.phone || '(0251) 123-4567'"></p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdf4">
                        <svg class="h-5 w-5" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Email</p>
                        <p class="text-gray-500 text-sm mt-0.5" x-text="$store.pub.settings.email || 'info@alghazaly.sch.id'"></p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdf4">
                        <svg class="h-5 w-5" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Jam Operasional</p>
                        <p class="text-gray-500 text-sm mt-0.5">Senin – Jumat: 07.00 – 16.00 WIB</p>
                        <p class="text-gray-500 text-sm">Sabtu: 07.00 – 12.00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- Map placeholder --}}
            <div class="mt-8 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200" style="height:260px">
                <template x-if="$store.pub.settings.maps_embed">
                    <iframe :src="$store.pub.settings.maps_embed"
                        class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </template>
                <template x-if="!$store.pub.settings.maps_embed">
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                        <svg class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <p class="text-sm">Peta lokasi tersedia di Google Maps</p>
                    </div>
                </template>
            </div>
        </div>

        {{-- Contact Form --}}
        <div>
            <h2 class="text-xl font-black text-gray-900 mb-6">Kirim Pesan</h2>

            {{-- Success --}}
            <template x-if="sent">
                <div class="rounded-2xl p-8 text-center" style="background:#f0fdf4; border: 1px solid #bbf7d0">
                    <div class="h-14 w-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#019342">
                        <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Pesan Terkirim!</h3>
                    <p class="text-gray-600 text-sm mb-4">Terima kasih. Kami akan menghubungi Anda dalam 1×24 jam kerja.</p>
                    <button @click="reset()" class="px-5 py-2 rounded-xl text-white text-sm font-medium" style="background:#019342">
                        Kirim Pesan Lain
                    </button>
                </div>
            </template>

            {{-- Form --}}
            <template x-if="!sent">
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input x-model="form.name" type="text" placeholder="Nama Anda"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input x-model="form.email" type="email" placeholder="nama@email.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#019342">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP / WA</label>
                        <input x-model="form.phone" type="text" placeholder="08xx-xxxx-xxxx"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#019342">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                        <select x-model="form.subject"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#019342">
                            <template x-for="s in subjects" :key="s.value">
                                <option :value="s.value" x-text="s.label"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan <span class="text-red-500">*</span></label>
                    <textarea x-model="form.message" rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent resize-none"
                        style="--tw-ring-color:#019342"></textarea>
                </div>

                <div x-show="error" x-text="error" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-2"></div>

                <button @click="send()" :disabled="sending"
                    class="w-full py-3 rounded-xl text-white font-semibold text-sm transition-all hover:opacity-90 disabled:opacity-60"
                    style="background:#019342">
                    <span x-show="!sending">Kirim Pesan →</span>
                    <span x-show="sending" class="flex items-center justify-center gap-2">
                        <svg class="spinner h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        Mengirim...
                    </span>
                </button>
            </div>
            </template>
        </div>

    </div>
</div>
