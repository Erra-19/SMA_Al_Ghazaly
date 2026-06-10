{{-- ═══ PROFIL ═════════════════════════════════════════════════════════════════ --}}

{{-- Hero --}}
<div class="py-16 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Tentang Kami</p>
        <h1 class="text-3xl font-black mb-3" x-text="$store.pub.schoolName || 'SMA Al-Ghazaly Bogor'"></h1>
        <p class="text-gray-300 text-base leading-relaxed"
           x-text="$store.pub.settings.tagline || 'Membentuk generasi Islam yang cerdas, berakhlak mulia, dan berdaya saing global sejak 1985.'">
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Sejarah Singkat --}}
    <section class="mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#019342">Sejarah</p>
                <h2 class="text-2xl font-black text-gray-900 mb-4">Sekolah Islam Unggulan Kota Bogor</h2>
                <div class="prose prose-sm text-gray-600 leading-relaxed space-y-3">
                    <p x-text="$store.pub.settings.history || 'SMA Al-Ghazaly Bogor berdiri dengan visi membentuk insan Islami yang tidak hanya unggul secara akademik, tetapi juga kuat dalam keimanan dan akhlak. Sejak berdiri, sekolah ini telah melahirkan ribuan alumni yang kini mengabdi di berbagai bidang sebagai kontribusi nyata bagi agama, bangsa, dan masyarakat.'">
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['NSS','Nomor Statistik Sekolah'],['NPSN','Nomor Pokok Sekolah Nasional'],['Akreditasi','Status Akreditasi'],['Tahun Berdiri','Sejak']] as $i => $info)
                <div class="rounded-2xl p-5 text-center bg-gray-50 border border-gray-100">
                    <p class="text-2xl font-black mb-1"
                       style="color:#019342"
                       x-text="['nss','npsn','accreditation','year_founded'][$i] !== undefined
                           ? ($store.pub.settings[['nss','npsn','accreditation','year_founded'][$i]] || ['—','—','A','1985'][$i])
                           : '—'">
                    </p>
                    <p class="text-gray-500 text-xs">{{ $info[0] }}</p>
                    <p class="text-gray-400 text-xs">{{ $info[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Visi & Misi --}}
    <section class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-2xl p-8 text-white" style="background:linear-gradient(135deg,#019342,#027a35)">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">Visi</h3>
                </div>
                <p class="text-white/90 leading-relaxed text-sm"
                   x-text="$store.pub.settings.vision || 'Menjadi sekolah Islam unggulan yang melahirkan generasi beriman, berilmu, dan berakhlak karimah, serta mampu berkontribusi positif bagi masyarakat dan bangsa.'">
                </p>
            </div>
            <div class="rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background:#f0fdf4">
                        <svg class="h-5 w-5" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Misi</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    @foreach(['Menyelenggarakan pendidikan Islam berkualitas tinggi','Mengintegrasikan ilmu pengetahuan dengan nilai-nilai Islam','Membina akhlak dan karakter Islami yang kuat','Memfasilitasi pengembangan potensi akademik dan non-akademik','Mempersiapkan lulusan siap PTN dan dunia kerja'] as $m)
                    <li class="flex items-start gap-2">
                        <svg class="h-4 w-4 mt-0.5 shrink-0" style="color:#019342" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $m }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- Nilai-Nilai --}}
    <section>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-gray-900">Nilai-Nilai Kami</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach([['🕌','Keislaman','Berlandaskan nilai-nilai Al-Quran dan Sunnah'],['📚','Ilmu','Mengutamakan keunggulan akademik dan intelektual'],['🤲','Akhlak','Membentuk karakter mulia dan berintegritas'],['🚀','Prestasi','Mendorong pencapaian terbaik di setiap bidang']] as $v)
            <div class="rounded-2xl p-5 text-center bg-gray-50 border border-gray-100 hover:border-green-200 transition-colors">
                <div class="text-4xl mb-3">{{ $v[0] }}</div>
                <p class="font-bold text-gray-900 text-sm mb-1">{{ $v[1] }}</p>
                <p class="text-gray-500 text-xs">{{ $v[2] }}</p>
            </div>
            @endforeach
        </div>
    </section>

</div>
