{{-- ═══ PROGRAM ════════════════════════════════════════════════════════════════ --}}

<div class="py-16 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Keunggulan Kami</p>
        <h1 class="text-3xl font-black mb-3">Program Unggulan</h1>
        <p class="text-gray-300">Empat program inti yang menjadi kebanggaan SMA Al-Ghazaly Bogor</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- 4 Program Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
        <template x-for="prog in programs" :key="prog.id">
            <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm card-hover cursor-pointer"
                 @click="activeProgram = (activeProgram === prog.id ? null : prog.id)">
                <div class="p-6 flex items-start gap-4">
                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 bg-gray-50">
                        <span x-text="prog.icon"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <h3 class="font-bold text-gray-900" x-text="prog.title"></h3>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium text-white shrink-0"
                                  :style="'background:' + prog.color"
                                  x-text="prog.tag"></span>
                        </div>
                        <p class="text-sm text-gray-500 mb-3" x-text="prog.desc"></p>
                        <ul class="space-y-1.5">
                            <template x-for="feat in prog.features">
                                <li class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#019342"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span x-text="feat"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Kurikulum Info --}}
    <section class="mb-16 rounded-2xl p-8 text-white" style="background:linear-gradient(135deg,#0d1035,#191654)">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            @foreach([['Kurikulum Merdeka','Mengikuti kebijakan Kemendikbud dengan adaptasi nilai-nilai Islami'],['Integrasi Imtak','Setiap mata pelajaran diintegrasikan dengan nilai-nilai keislaman'],['Bilingual Program','Pembelajaran berbasis Bahasa Indonesia dan Inggris secara terpadu']] as $k)
            <div>
                <h4 class="font-bold text-white mb-2">{{ $k[0] }}</h4>
                <p class="text-gray-400 text-sm">{{ $k[1] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Ekstrakurikuler --}}
    <section class="mb-16">
        <h2 class="text-xl font-black text-gray-900 mb-6">Ekstrakurikuler</h2>
        <div class="flex flex-wrap gap-3">
            @foreach(['Pramuka','PMR','Rohis','OSIS','English Club','Jurnalistik','Basket','Futsal','Badminton','Seni Tari','Paduan Suara','Tahfidz Club','Robotika','Fotografi','Teater'] as $ekskul)
            <span class="px-4 py-2 rounded-full text-sm font-medium bg-gray-50 border border-gray-200 text-gray-700 hover:border-green-300 hover:bg-green-50 transition-colors cursor-default">
                {{ $ekskul }}
            </span>
            @endforeach
        </div>
    </section>

    {{-- FAQ --}}
    <section>
        <h2 class="text-xl font-black text-gray-900 mb-6">Pertanyaan Umum</h2>
        <div class="space-y-3">
            <template x-for="(faq, i) in faqs" :key="i">
                <div class="rounded-2xl border border-gray-100 overflow-hidden">
                    <button class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
                            @click="openFaq = openFaq === i ? null : i">
                        <span class="font-semibold text-gray-900 text-sm" x-text="faq.q"></span>
                        <svg class="h-4 w-4 text-gray-400 shrink-0 transition-transform" :class="openFaq === i ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === i" x-transition class="px-5 pb-4 text-sm text-gray-600 leading-relaxed" x-text="faq.a"></div>
                </div>
            </template>
        </div>
    </section>

    {{-- CTA --}}
    <div class="mt-16 text-center">
        <h3 class="text-xl font-black text-gray-900 mb-2">Tertarik dengan Program Kami?</h3>
        <p class="text-gray-500 text-sm mb-6">Daftarkan putra-putri Anda sekarang di PPDB SMA Al-Ghazaly Bogor.</p>
        <button @click="$store.pub.go('ppdb')"
            class="px-8 py-3 rounded-full text-white font-semibold text-sm hover:opacity-90 transition-all"
            style="background:#019342">
            Daftar PPDB Sekarang →
        </button>
    </div>

</div>
