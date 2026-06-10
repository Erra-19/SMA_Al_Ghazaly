import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  BookOpen, 
  GraduationCap, 
  Cpu, 
  Users, 
  CheckCircle, 
  Sparkles, 
  ArrowRight,
  TrendingUp,
  Award,
  BookMarked,
  Layers,
  Heart,
  Calendar,
  Compass,
  Laptop,
  Check
} from 'lucide-react';
import { getPrograms } from '../api';

const iconMap = { BookOpen, GraduationCap, Cpu, Users };

export default function SchoolPrograms() {
  const [activeTab, setActiveTab] = useState<'akademik' | 'unggulan' | 'ekskul'>('akademik');
  const [programs, setPrograms] = useState<any[]>([]);

  useEffect(() => {
    getPrograms().then(setPrograms).catch(() => setPrograms([]));
  }, []);

  // Academic programs details (MIPA, IPS, Boarding)
  const academicPrograms = [
    {
      id: "mipa",
      title: "Peminatan MIPA (Matematika & Ilmu Pengetahuan Alam)",
      description: "Program yang dirancang khusus untuk memfasilitasi minat siswa di bidang sains, riset ilmiah, kedokteran, teknik, dan teknologi. Mengedepankan nalar kritis melalui eksperimen laboratorium serta integrasi sains dengan ayat semesta.",
      highlights: [
        "Kurikulum Merdeka dengan pendalaman Fisika, Kimia, Biologi & Matematika Tingkat Lanjut",
        "Praktikum intensif di Laboratorium Sains Modern",
        "Pembinaan Olimpiade Sains Nasional (OSN) secara berkala dan terstruktur",
        "Proyek riset ilmiah sederhana siswa (Scientific Project) sebagai syarat kelulusan"
      ],
      image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop",
      badge: "Sains & Teknologi",
      stats: "Persentase Lulus PTN: 88%"
    },
    {
      id: "ips",
      title: "Peminatan IPS (Ilmu Pengetahuan Sosial)",
      description: "Program yang dirancang untuk membekali calon pemimpin masa depan mumpuni di bidang sosial-kemasyarakatan, ilmu hukum, ekonomi pembangunan, hubungan internasional, geopolitik, dan kewirausahaan berbasis moralitas islami yang tepercaya.",
      highlights: [
        "Pendalaman Sosiologi, Ekonomi Akuntansi, Geografi, dan Sejarah Nusantara",
        "Pelatihan Debat Hukum & Sosialisasi Politik Kewarganegaraan",
        "Program Entrepreneurship (Kewirausahaan Siswa) & Business Plan Competition",
        "Kunjungan studi lapangan (Fieldwork Study) ke lembaga pemerintahan dan bursa efek"
      ],
      image: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop",
      badge: "Sosial & Ekonomi",
      stats: "Persentase Lulus PTN: 82%"
    },
    {
      id: "keagamaan",
      title: "Kurikulum Keagamaan Al-Ghazaly (Khas Pesantren)",
      description: "Kurikulum tambahan integral khas Yayasan Islamic Center Al-Ghazaly Bogor guna mencetak asisten asatidzah, ulama intelektual, dan praktisi dakwah bersahaja yang adaptif terhadap era dinamis tanpa kehilangan pegangan pokok syariat.",
      highlights: [
        "Kajian Kitab Kuning Dasar (Fiqih, Aqidah, Akhlak, Nahwu & Shorof)",
        "Metode Praktis Baca Kitab Guna Memahami Tafsir Al-Qur'an secara benar",
        "Praktik Pengabdian Masyarakat Terintegrasi (Dakwah Lapangan)",
        "Tahfidz Bersanad (Opsional dengan bimbingan ustadz mutakhassish)"
      ],
      image: "https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=800&auto=format&fit=crop",
      badge: "Adab & Dirosah Islamiyah",
      stats: "Target: Karakter Rabbani & Mandiri"
    }
  ];

  // Core Flagship Program (Program Unggulan)
  const flagshipPrograms = [
    {
      id: "tahfidz",
      title: "Tahfidzul Qur'an & Tafsir Intensif",
      subtitle: "Membentuk Penghafal Al-Qur'an Berjiwa Rabbani",
      description: "Program pembinaan menghafal Al-Qur'an terprogram semenjak kelas X dengan target kelulusan minimal hafalan 3 sampai 5 Juz (Atau 10-15 Juz bagi kelas Program Khusus), didukung metode muraja'ah mutqin teruji, serta sertifikasi dan wisuda Al-Qur'an akbar setiap tahunnya.",
      icon: BookOpen,
      color: "from-emerald-500 to-teal-600",
      accentColor: "bg-emerald-50 text-[#019342]",
      features: ["Sabaq, Sabqi, dan Manzil secara reguler", "Halaqah khusus berasrama & fullday", "Sertifikat hafalan resmi Madrasah Al-Ghazaly", "Ujian terbuka (Imtihan) langsung di depan masyayikh"]
    },
    {
      id: "ptn",
      title: "Akselerasi Sukses UTBK & Bimbingan PTN",
      subtitle: "Jembatan Menuju Gerbang Kampus Terbaik",
      description: "Sistem pendampingan khusus siswa-siswi kelas XI dan XII dalam menembus Perguruan Tinggi Negeri impian (UI, ITB, IPB, UGM, Unpad, dll.). Meliputi tryout komputer mingguan, pendampingan pemilihan jurusan sesuai bakat (psikotes), pembahasan kilat trik UTBK-SNBT, serta konsultasi portofolio SNBP.",
      icon: GraduationCap,
      color: "from-[#191654] to-blue-700",
      accentColor: "bg-blue-50 text-[#191654]",
      features: ["Pembahasan Soal Kognitif & Literasi harian", "Bank soal SNBT terupdate berkala", "Kerjasama dengan lembaga konsultan pendidikan ternama", "Sesi motivasi sukses & doa bersama (Khatman)"]
    },
    {
      id: "robotiks",
      title: "Sains Terapan, Coding & Robotika",
      subtitle: "Mempersiapkan Ahli Teknologi Masa Depan",
      description: "Membekali siswa berpikir komputasional melalui ekstrakurikuler sains aplikatif dan teknik elektro praktis. Siswa dilatih merakit mikrokontroler Arduino, dasar-dasar coding (Python & Scratch), robot line follower, serta diajarkan memanfaatkan AI secara positif-kreatif dalam pembelajaran.",
      icon: Cpu,
      color: "from-emerald-600 to-emerald-800",
      accentColor: "bg-teal-50 text-teal-700",
      features: ["Kelompok Riset & Kompetisi robotik regional", "Fasilitas Lab Komputer Modern berspesifikasi tinggi", "Workshop IoT (Internet of Things) berkala", "Sertifikasi keikutsertaan kompetisi kementerian"]
    },
    {
      id: "leadership",
      title: "Leadership & Da'wah Multilingual Training",
      subtitle: "Kader Pemimpin Rabbani Penguasa Bahasa",
      description: "Mengasah kesiapan berwibawa tampil di muka umum dan memimpin orasi dakwah di era global. Siswa dilatih berpidato dalam 3 bahasa (Arab, Inggris, Indonesia) secara bergantian setiap pekan, manajemen organisasi kepengurusan OSIS-MPK, serta kepedulian sosial luhur.",
      icon: Users,
      color: "from-[#191654] to-emerald-700",
      accentColor: "bg-indigo-50 text-[#191654]",
      features: ["Panggung Syiar Pidato 3 Bahasa mingguan", "Kaderisasi kepemimpinan LDKS berstandar", "Bimbingan debat berbahasa asing", "Pengabdian masyarakat santri berkhidmat (Bakti Sosial)"]
    }
  ];

  // Extracurriculars programs (Ekskul)
  const extracurriculars = [
    { name: "Pramuka Terpadu Al-Ghazaly", category: "Umum & Wajib", icon: "✓", desc: "Melatih kemandirian, kedisiplinan, tanggap darurat, dan kebersamaan organisasi islami." },
    { name: "Paskibraka & PMR", category: "Kedisplinan & Sosial", icon: "✓", desc: "Pelatihan baris-berbaris yang presisi dan unit kesehatan tanggap cepat kemanusiaan." },
    { name: "Futsal, Basket & Badminton", category: "Olahraga", icon: "✓", desc: "Uji kecerdasan kinestetik, kesehatan jasmani, serta prestasi kejuaraan antar sekolah." },
    { name: "Pencak Silat Pagar Nusa", category: "Beladiri", icon: "✓", desc: "Seni beladiri warisan ulama nusantara guna proteksi diri dan membela kebenaran akhlaki." },
    { name: "Seni Hadroh, Marawis & Kaligrafi", category: "Seni Islami", icon: "✓", desc: "Meresapi keindahan syiar islam lewat lantunan sholawat merdu dan goresan khat indah." },
    { name: "Klub Jurnalistik, Fotografi & IT", category: "Kreativitas", icon: "✓", desc: "Mengembangkan bakat menyunting tulisan mading, dokumentasi sekolah, dan literasi web." }
  ];

  const dynamicAcademicPrograms = programs.filter((program) => program.type === 'akademik');
  const dynamicFlagshipPrograms = programs.filter((program) => program.type === 'unggulan');
  const dynamicExtracurriculars = programs.filter((program) => program.type === 'ekskul');

  return (
    <div id="school-programs-page" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-16">
      
      {/* Hero Banner Header: Rich Gradient & Modern Visual Frame */}
      <div 
        id="programs-hero-banner" 
        className="relative overflow-hidden bg-gradient-to-br from-[#191654] to-[#019342] text-white py-24 px-4 sm:px-6 lg:px-8 mb-16 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
      >
        <div className="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:24px_24px]" />
        
        {/* Floating background blur elements */}
        <div className="absolute -top-12 right-12 w-80 h-80 bg-white/10 rounded-full blur-3xl opacity-50" />
        <div className="absolute -bottom-16 -left-10 w-96 h-96 bg-[#019342]/40 rounded-full blur-3xl opacity-60" />

        <div className="relative mx-auto max-w-7xl">
          <motion.div
            initial={{ opacity: 0, y: -15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-[10px] font-black tracking-widest uppercase mb-4"
          >
            <Sparkles className="h-3.5 w-3.5 text-yellow-300 animate-pulse" />
            <span>Islamic &amp; Modern Education System</span>
          </motion.div>

          <motion.h1 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-none text-white0"
          >
            Program Sekolah
          </motion.h1>

          <motion.div 
            initial={{ width: 0 }}
            animate={{ width: 80 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="mt-4 h-1.5 bg-[#019342] rounded-full" 
          />

          <motion.p 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.25 }}
            className="mt-6 max-w-2xl text-sm md:text-base text-white/90 leading-relaxed font-medium"
          >
            Mendidik generasi tangguh berakhlak mulia melalui sinkronisasi kurikulum nasional, pendalaman ilmu keislaman, akselerasi PTN, serta penguasaan sains digital.
          </motion.p>
        </div>
      </div>

      {/* Main Container */}
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {/* Navigation Tab Pills */}
        <div className="flex justify-center mb-16">
          <div className="inline-flex p-1.5 bg-slate-100/80 backdrop-blur border border-slate-200/55 rounded-2xl md:rounded-full">
            <div className="flex flex-col md:flex-row gap-1">
              <button
                id="tab-btn-akademik"
                onClick={() => setActiveTab('akademik')}
                className={`px-6 py-3 rounded-xl md:rounded-full text-xs font-black uppercase tracking-wider transition-all cursor-pointer ${
                  activeTab === 'akademik'
                    ? 'bg-[#019342] text-white shadow-md'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'
                }`}
              >
                <div className="flex items-center gap-2 justify-center">
                  <BookMarked className="h-4 w-4" />
                  <span>Kurikulum Akademik</span>
                </div>
              </button>
              
              <button
                id="tab-btn-unggulan"
                onClick={() => setActiveTab('unggulan')}
                className={`px-6 py-3 rounded-xl md:rounded-full text-xs font-black uppercase tracking-wider transition-all cursor-pointer ${
                  activeTab === 'unggulan'
                    ? 'bg-[#019342] text-white shadow-md'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-300/60'
                }`}
              >
                <div className="flex items-center gap-2 justify-center">
                  <Award className="h-4 w-4" />
                  <span>Program Unggulan Plus</span>
                </div>
              </button>

              <button
                id="tab-btn-ekskul"
                onClick={() => setActiveTab('ekskul')}
                className={`px-6 py-3 rounded-xl md:rounded-full text-xs font-black uppercase tracking-wider transition-all cursor-pointer ${
                  activeTab === 'ekskul'
                    ? 'bg-[#019342] text-white shadow-md'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-300/60'
                }`}
              >
                <div className="flex items-center gap-2 justify-center">
                  <Compass className="h-4 w-4" />
                  <span>Ekstrakurikuler</span>
                </div>
              </button>
            </div>
          </div>
        </div>

        {/* Tab content renderer with animations */}
        <AnimatePresence mode="wait">
          {activeTab === 'akademik' && (
            <motion.div
              key="akademik-panel"
              initial={{ opacity: 0, y: 15 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -15 }}
              transition={{ duration: 0.4 }}
              className="space-y-12"
            >
              <div className="text-center max-w-xl mx-auto space-y-2">
                <span className="text-[10px] font-black uppercase tracking-widest text-[#019342]">Struktur Pembelajaran Resmi</span>
                <h3 className="text-2xl font-extrabold text-[#191654]">Kurikulum Merdeka Belajar</h3>
                <p className="text-xs text-slate-500 font-medium leading-relaxed">
                  Menawarkan program peminatan terpadu yang membebaskan eksplorasi siswa berfokus pada kekuatan minat, penguasaan sains empiris, dan pembinaan batiniah mendalam.
                </p>
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {dynamicAcademicPrograms.map((prog) => (
                  <div 
                    key={prog.id}
                    className="bg-white border border-slate-150 rounded-3xl overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.015)] hover:shadow-[0_12px_24px_rgba(0,0,0,0.03)] transition-all flex flex-col justify-between"
                  >
                    <div>
                      {/* Image header part */}
                      <div className="relative aspect-video overflow-hidden">
                        <img 
                          src={prog.image} 
                          alt={prog.title} 
                          className="w-full h-full object-cover"
                          referrerPolicy="no-referrer"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
                        <span className="absolute bottom-3 left-3 inline-block px-2.5 py-1 rounded-full bg-[#019342] text-white text-[9px] font-black uppercase tracking-wider">
                          {prog.badge}
                        </span>
                      </div>

                      <div className="p-6 space-y-4">
                        <h4 className="text-base font-black text-slate-900 leading-snug">
                          {prog.title}
                        </h4>
                        <p className="text-xs text-slate-500 leading-relaxed font-medium">
                          {prog.description}
                        </p>

                        <div className="h-px bg-slate-100" />

                        {/* Program highlights Checklist */}
                        <div className="space-y-2.5">
                          <span className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Materi &amp; Fokus Utama:</span>
                          <ul className="space-y-2">
                            {(prog.features || []).map((hlt, idx) => (
                              <li key={idx} className="flex gap-2 items-start">
                                <span className="h-4 w-4 shrink-0 rounded-full bg-emerald-50 text-[#019342] flex items-center justify-center text-[10px]">✓</span>
                                <span className="text-[11px] text-slate-600 font-semibold leading-relaxed">{hlt}</span>
                              </li>
                            ))}
                          </ul>
                        </div>
                      </div>
                    </div>

                    {/* Bottom stat footer banner */}
                    <div className="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                      <span className="text-[10px] font-black uppercase text-[#191654] tracking-wider">
                        SMA Al-Ghazaly Bogor
                      </span>
                      <span className="text-[10px] font-bold text-[#019342] bg-[#019342]/5 px-2.5 py-1 rounded">
                        {prog.stats}
                      </span>
                    </div>
                  </div>
                ))}
              </div>
              {!dynamicAcademicPrograms.length && (
                <div className="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-xs font-bold text-slate-400">
                  Belum ada program akademik yang dipublikasikan.
                </div>
              )}
            </motion.div>
          )}

          {activeTab === 'unggulan' && (
            <motion.div
              key="unggulan-panel"
              initial={{ opacity: 0, y: 15 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -15 }}
              transition={{ duration: 0.4 }}
              className="space-y-12"
            >
              <div className="text-center max-w-xl mx-auto space-y-2">
                <span className="text-[10px] font-black uppercase tracking-widest text-[#019342]">Program Unggulan Istimewa</span>
                <h3 className="text-2xl font-extrabold text-[#191654]">Program Plus &amp; Pengembangan Karakter</h3>
                <p className="text-xs text-slate-500 font-medium leading-relaxed">
                  Kami mengaplikasikan program khusus bersertifikat di luar kurikulum standar nasional demi menghasilkan generasi yang tangguh spiritualitasnya, berdaya saing global, dan cerdas sosial janjinya.
                </p>
              </div>

              {/* Grid 2x2 Flagship programs */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {dynamicFlagshipPrograms.map((flag) => {
                  const IconComp = iconMap[flag.icon as keyof typeof iconMap] || BookOpen;
                  return (
                    <div 
                      key={flag.id}
                      className="bg-white border border-slate-150 rounded-3xl p-6 md:p-8 shadow-[0_10px_30px_rgba(0,0,0,0.015)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group"
                    >
                      <div className="space-y-6">
                        {/* Header icon block */}
                        <div className="flex gap-4 items-center">
                          <div className={`h-12 w-12 rounded-2xl flex items-center justify-center border border-emerald-500/10 ${flag.accentColor} group-hover:bg-[#019342] group-hover:text-white transition-all duration-300 shrink-0`}>
                            <IconComp className="h-6 w-6" />
                          </div>
                          <div>
                            <span className="text-[9px] font-bold tracking-widest uppercase text-slate-400">PROGRAM PLUS</span>
                            <h4 className="text-md font-black text-slate-900 leading-none mt-0.5">{flag.title}</h4>
                          </div>
                        </div>

                        {/* Subtitle */}
                        <div className="space-y-2">
                          <span className="text-xs font-bold text-[#019342] italic block">
                            "{flag.subtitle}"
                          </span>
                          <p className="text-xs text-slate-500 leading-relaxed font-semibold">
                            {flag.description}
                          </p>
                        </div>
                      </div>

                      {/* Line break & lists */}
                      <div className="space-y-4 pt-6 mt-6 border-t border-slate-100">
                        <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Metode Pembelajaran / Konten:</span>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                          {flag.features.map((feat, f_idx) => (
                            <div key={f_idx} className="flex gap-1.5 items-center">
                              <Check className="h-3.5 w-3.5 text-[#019342] shrink-0" />
                              <span className="text-[10.5px] font-semibold text-slate-650 tracking-tight leading-tight">{feat}</span>
                            </div>
                          ))}
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
              {!dynamicFlagshipPrograms.length && (
                <div className="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-xs font-bold text-slate-400">
                  Belum ada program unggulan yang dipublikasikan.
                </div>
              )}
            </motion.div>
          )}

          {activeTab === 'ekskul' && (
            <motion.div
              key="ekskul-panel"
              initial={{ opacity: 0, y: 15 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -15 }}
              transition={{ duration: 0.4 }}
              className="space-y-12"
            >
              <div className="text-center max-w-xl mx-auto space-y-2">
                <span className="text-[10px] font-black uppercase tracking-widest text-[#019342]">Saluran Bakat &amp; Potensi Siswa</span>
                <h3 className="text-2xl font-extrabold text-[#191654]">Ekstrakurikuler Unggulan</h3>
                <p className="text-xs text-slate-500 font-medium leading-relaxed">
                  Menawarkan berbagai wadah kreatif luar kelas bagi para siswa untuk mengasah kecakapan hidup, kesehatan raga, nilai luhur kerja sama tim, dan kepemimpinan.
                </p>
              </div>

              {/* Grid bento layout for extracurricular list */}
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {dynamicExtracurriculars.map((ek, idx) => (
                  <div 
                    key={idx}
                    className="bg-white border border-slate-150 p-6 rounded-3xl shadow-[0_5px_15px_rgba(0,0,0,0.01)] hover:border-[#019342]/20 hover:shadow-[0_12px_24px_rgba(0,0,0,0.02)] transition-all duration-300"
                  >
                    <div className="flex justify-between items-start mb-4">
                      <span className="inline-block px-2.5 py-1 rounded-full bg-slate-100 text-[#191654] text-[9px] font-black uppercase tracking-wider">
                      {ek.badge || 'Ekstrakurikuler'}
                      </span>
                      <span className="text-xs font-black text-emerald-500">★</span>
                    </div>

                    <h4 className="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wide mb-1.5">
                      {ek.title}
                    </h4>
                    <p className="text-[11px] text-slate-500 leading-relaxed font-semibold">
                      {ek.description}
                    </p>
                  </div>
                ))}
              </div>
              {!dynamicExtracurriculars.length && (
                <div className="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-xs font-bold text-slate-400">
                  Belum ada ekstrakurikuler yang dipublikasikan.
                </div>
              )}
            </motion.div>
          )}
        </AnimatePresence>

        {/* Section CTA: Form Registration */}
        <motion.div
          initial={{ opacity: 0, scale: 0.98 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          className="relative overflow-hidden bg-gradient-to-br from-[#191654] to-[#019342] text-white rounded-3xl p-8 sm:p-12 mb-12 shadow-xl border border-slate-100/10 mt-20"
        >
          {/* Subtle Background Elements */}
          <div className="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full blur-3xl opacity-30" />
          <div className="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-2xl opacity-20" />

          <div className="relative grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div className="lg:col-span-8 space-y-4">
              <span className="inline-block px-3 py-1 bg-white/10 rounded-full text-white text-[9px] font-black uppercase tracking-widest">
                Penerimaan Peserta Didik Baru (PPDB) 2026/2027
              </span>
              <h4 className="text-2xl sm:text-3xl font-black tracking-tight leading-none text-white">
                Siap Bergabung dengan Keluarga Rabbani SMA Al-Ghazaly?
              </h4>
              <p className="text-sm text-white/80 max-w-xl font-medium leading-relaxed">
                Kuotanya terbatas di setiap gelombang penerimaan. Jadilah bagian dari generasi unggul sains berhati mulia tahfidz Al-Qur'an.
              </p>
            </div>
            
            <div className="lg:col-span-4 lg:text-right">
              <a 
                href="https://wa.me/6281381008834"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex w-full md:w-auto items-center justify-center gap-2 px-8 py-4 bg-white text-[#191654] hover:bg-[#019342] hover:text-white hover:scale-102 transition-all duration-300 font-extrabold text-xs uppercase tracking-widest rounded-full shadow-lg"
              >
                <span>Daftar Sekarang Melalui WA</span>
                <ArrowRight className="h-4 w-4" />
              </a>
            </div>
          </div>
        </motion.div>

      </div>
    </div>
  );
}
