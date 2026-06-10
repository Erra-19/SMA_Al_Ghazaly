import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  Eye, 
  Target, 
  ChevronLeft, 
  ChevronRight, 
  Users, 
  GraduationCap, 
  BookOpen, 
  Award, 
  Quote, 
  Compass, 
  Flag,
  Heart,
  Calendar
} from 'lucide-react';
import { getAlbums, getProfileData } from '../api';

export default function SchoolProfile() {
  // Activity Slider State
  const [currentSlide, setCurrentSlide] = useState(0);
  const [profilePage, setProfilePage] = useState<any>(null);
  const [siteSettings, setSiteSettings] = useState<Record<string, string>>({});
  const [galleryItems, setGalleryItems] = useState<any[]>([]);

  useEffect(() => {
    getProfileData()
      .then((data) => {
        setProfilePage(data?.pages?.['profil-sekolah'] || null);
        setSiteSettings(data?.settings_flat || {});
      })
      .catch(() => {});
    getAlbums().then(setGalleryItems).catch(() => setGalleryItems([]));
  }, []);

  const gallerySlides = [
    {
      id: 1,
      title: "Kegiatan Kepemimpinan OSIS & MPK SMA Al-Ghazaly",
      description: "Membentuk kader kepemimpinan rabbani yang terampil berorganisasi, berakhlak mulia, dan siap mengabdi di masyarakat.",
      image: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop",
      badge: "Karakter"
    },
    {
      id: 2,
      title: "Proses KBM Pembelajaran Mandiri & IT",
      description: "Pendidikan interaktif berbasis digital laboratoy untuk mempersiapkan masa depan IPTEK berkelas.",
      image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop",
      badge: "Teknologi"
    },
    {
      id: 3,
      title: "Ujian Tahfidz & Wisuda Al-Qur'an Tahunan",
      description: "Syukur atas kelulusan hafalan juz santriwan dan santriwati dengan target hafalan terprogram secara intensif.",
      image: "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&auto=format&fit=crop",
      badge: "Al-Qur'an"
    },
    {
      id: 4,
      title: "Kegiatan Ekstrakurikuler & Kebersamaan",
      description: "Mengembangkan minat bakat secara seimbang demi menciptakan lulusan berkualitas tinggi dan sehat jasmani.",
      image: "https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop",
      badge: "Ekskul"
    }
  ];

  const handleNextSlide = () => {
    setCurrentSlide((prev) => galleryItems.length ? (prev + 1) % galleryItems.length : 0);
  };

  const handlePrevSlide = () => {
    setCurrentSlide((prev) => galleryItems.length ? (prev - 1 + galleryItems.length) % galleryItems.length : 0);
  };

  // Visi & Misi dari settings (diisi admin), fallback ke teks default
  const visionText = siteSettings.school_vision || 'Visi sekolah belum diisi dari admin.';
  const missions: string[] = siteSettings.school_missions
    ? siteSettings.school_missions.split('\n').map(s => s.trim()).filter(Boolean)
    : [
        "Memberikan keteladanan Etika dan Moral agar siswa bertingkah laku sesuai dengan ciri khas Islami dan perilaku Akhlaqul Karimah.",
        "Melaksanakan proses pembelajaran yang mengarah kepada pembentukan Pribadi Mandiri.",
        "Memberikan pelayanan dan bimbingan kepada para siswa dalam upaya peningkatan Kreatifitas siswa baik secara Akademik maupun Non-Akademik.",
        "Meningkatkan jumlah lulusan yang diterima di Perguruan Tinggi Negeri (PTN) dan Perguruan Tinggi Swasta (PTS) Favorit, baik dalam negeri maupun luar negeri."
      ];

  const stats = [
    {
      id: "stat-pengajar",
      value: "35+",
      label: "Total Pengajar",
      desc: "Guru tersertifikasi & berkompeten",
      icon: Users,
      color: "border-emerald-500/20 text-[#019342] bg-emerald-500/5"
    },
    {
      id: "stat-siswa-baru",
      value: "160+",
      label: "Total Siswa Baru",
      desc: "Kuota pendaftaran per tahun",
      icon: GraduationCap,
      color: "border-blue-500/20 text-[#191654] bg-blue-500/5"
    },
    {
      id: "stat-siswa-existing",
      value: "480+",
      label: "Total Siswa Existing",
      desc: "Siswa aktif reguler & boarding",
      icon: BookOpen,
      color: "border-teal-500/20 text-teal-600 bg-teal-500/5"
    },
    {
      id: "stat-alumni",
      value: "1.200+",
      label: "Total Alumni",
      desc: "Tersebar di PTN & PTS Favorit",
      icon: Award,
      color: "border-amber-500/20 text-amber-600 bg-amber-500/5"
    }
  ];

  const profileContent = profilePage?.content?.replace(/<[^>]*>/g, '') || '';

  return (
    <div id="school-profile-page" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-16">
      
      {/* 1. Header Hero Banner: Modern Glassmorphism Accent */}
      <div 
        id="profile-hero-banner" 
        className="relative overflow-hidden bg-gradient-to-br from-[#019342] to-[#191654] text-white py-24 px-4 sm:px-6 lg:px-8 mb-16 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
      >
        <div className="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:24px_24px]" />
        
        {/* Decorative Floating Blurs */}
        <div className="absolute -top-12 -left-12 w-80 h-80 bg-white/10 rounded-full blur-3xl" />
        <div className="absolute -bottom-16 right-10 w-96 h-96 bg-[#019342]/40 rounded-full blur-3xl opacity-60" />

        <div className="relative mx-auto max-w-7xl">
          <motion.div
            initial={{ opacity: 0, y: -15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-[10px] font-black tracking-widest uppercase mb-4"
          >
            <Compass className="h-3.5 w-3.5 text-emerald-300 animate-spin-slow" />
            <span>Yayasan Islamic Center Al-Ghazaly</span>
          </motion.div>

          <motion.h1 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-none"
          >
            Profil Sekolah
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
            {profilePage?.meta_description || profileContent || 'Informasi profil sekolah akan tampil setelah diisi dari admin.'}
          </motion.p>
        </div>
      </div>

      {/* 2. Brand Identity & Visi Misi Core Grid */}
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-16 space-y-3">
          <h2 id="section-main-title" className="text-3xl font-extrabold text-[#191654] tracking-tight">
            {profilePage?.title || 'Profil Sekolah'}
          </h2>
          <div className="h-1 w-14 bg-[#019342] mx-auto rounded-full" />
          <p className="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">
            Mewujudkan Insan Kualitas Unggul IMTAK &amp; IPTEK
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-24">
          
          {/* Left Column: Campus Image Frame Card */}
          <motion.div 
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="lg:col-span-12 xl:col-span-5 space-y-6"
          >
            <div className="relative group overflow-hidden rounded-3xl border border-slate-200 bg-white p-4 shadow-[0_8px_30px_rgba(0,0,0,0.015)] transition-all">
              <div className="overflow-hidden rounded-2xl relative aspect-[4/3]">
                <img
                  src={siteSettings.profile_image || profilePage?.thumbnail || '/images/school-hero.png'}
                  alt="SMA AlGhazaly School Campus"
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                  referrerPolicy="no-referrer"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-80" />
                <span className="absolute bottom-4 left-4 inline-flex items-center gap-1.5 rounded-full bg-[#019342] text-white px-3 py-1 text-[10px] font-bold uppercase tracking-wider">
                  Gedung Pembelajaran
                </span>
              </div>
              
              <div className="pt-5 pb-2 text-center">
                <p className="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">PROGRAM UNGGULAN</p>
                <div className="flex justify-center text-[#019342] mb-3">
                  <Quote className="h-6 w-6 opacity-30 fill-current rotate-180" />
                </div>
                <p className="text-xs sm:text-sm font-bold text-slate-700 italic px-4 leading-relaxed">
                  "{profileContent || 'Konten profil sekolah belum diisi.'}"
                </p>
              </div>
            </div>
          </motion.div>

          {/* Right Column: Visi & Misi Modern Cards */}
          <motion.div 
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="lg:col-span-12 xl:col-span-7 space-y-8"
          >
            {/* Visi Segment Panel */}
            <div className="relative overflow-hidden bg-white border border-slate-150 rounded-3xl p-6 sm:p-8 shadow-[0_12px_32px_rgba(0,0,0,0.015)]">
              <div className="absolute top-0 left-0 w-2.5 h-full bg-[#019342]" />
              
              <div className="flex items-center gap-3.5 mb-5 pl-2">
                <div className="h-10 w-10 rounded-2xl bg-emerald-500/10 text-[#019342] flex items-center justify-center">
                  <Eye className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="text-lg font-black text-slate-900 leading-none">Visi Sekolah</h3>
                  <span className="text-[9px] text-[#94a3b8] uppercase font-bold tracking-widest mt-1.5 block">Core Vision &amp; Direction</span>
                </div>
              </div>

              <div className="pl-2">
                <p className="text-sm sm:text-base font-extrabold text-slate-800 leading-relaxed md:max-w-xl">
                  {visionText}
                </p>
              </div>
            </div>

            {/* Misi Segment Cards List */}
            <div className="bg-white border border-slate-150 rounded-3xl p-6 sm:p-8 shadow-[0_12px_32px_rgba(0,0,0,0.015)] relative">
              <div className="absolute top-0 left-0 w-2.5 h-full bg-[#191654]" />

              <div className="flex items-center gap-3.5 mb-6 pl-2">
                <div className="h-10 w-10 rounded-2xl bg-blue-500/10 text-[#191654] flex items-center justify-center">
                  <Target className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="text-lg font-black text-slate-900 leading-none">Misi Sekolah</h3>
                  <span className="text-[9px] text-[#94a3b8] uppercase font-bold tracking-widest mt-1.5 block">Mission Directives &amp; Action Plan</span>
                </div>
              </div>

              {/* Staggered Misi List */}
              <div className="space-y-4 pl-2">
                {missions.map((misi, i) => (
                  <div 
                    key={i} 
                    className="flex gap-4 p-4 rounded-2xl bg-[#f8fafc] border border-slate-100 hover:border-slate-200 hover:bg-white transition-all duration-300"
                  >
                    <div className="h-7 w-7 shrink-0 rounded-lg bg-white border border-slate-250/60 shadow-sm text-xs font-black text-[#019342] flex items-center justify-center">
                      {i + 1}
                    </div>
                    <p className="text-xs sm:text-[13px] font-semibold text-slate-600 leading-relaxed">
                      {misi}
                    </p>
                  </div>
                ))}
              </div>
            </div>
          </motion.div>

        </div>

        {/* 3. Interactive Photo & Activities Carousel */}
        <div className="mb-24">
          <div className="text-center max-w-2xl mx-auto mb-10 space-y-2">
            <h3 id="gallery-carousel-title" className="text-2xl font-black text-slate-900">
              Galeri Kegiatan Al-Ghazaly
            </h3>
            <p className="text-xs text-slate-500 font-semibold max-w-md mx-auto leading-relaxed">
              Momen penting, kepemimpinan siswa terpadu OSIS &amp; MPK, serta fasilitas pembelajaran representatif.
            </p>
          </div>

          {galleryItems.length > 0 ? (
          <div className="relative max-w-4xl mx-auto">
            {/* Slider frame Wrapper */}
            <div className="overflow-hidden rounded-3xl border border-slate-150 bg-white shadow-xl relative aspect-[16/9] md:aspect-[21/9]">
              <AnimatePresence mode="wait">
                <motion.div
                  key={currentSlide}
                  initial={{ opacity: 0, scale: 0.98 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.98 }}
                  transition={{ duration: 0.4 }}
                  className="w-full h-full relative"
                >
                  <img 
                    src={galleryItems[currentSlide]?.image} 
                    alt={galleryItems[currentSlide]?.title} 
                    className="w-full h-full object-cover"
                    referrerPolicy="no-referrer"
                  />
                  
                  {/* Subtle black overlay shadow */}
                  <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/35 to-transparent" />
                  
                  {/* Bottom Text Captions */}
                  <div className="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white space-y-2">
                    <span className="inline-block px-2.5 py-1 rounded bg-[#019342] text-[9px] font-black uppercase tracking-widest mb-1 text-white">
                      {galleryItems[currentSlide]?.badge}
                    </span>
                    <h4 className="text-sm md:text-lg font-black tracking-tight leading-snug text-white">
                      {galleryItems[currentSlide]?.title}
                    </h4>
                    <p className="text-[11px] md:text-xs text-white/80 leading-relaxed font-semibold max-w-xl line-clamp-2 md:line-clamp-none">
                      {galleryItems[currentSlide]?.description}
                    </p>
                  </div>
                </motion.div>
              </AnimatePresence>

              {/* Slider Pill Indicators for Navigation */}
              <div className="absolute top-4 right-4 flex gap-1.5 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-full z-10">
                {galleryItems.map((_, i) => (
                  <button 
                    key={i}
                    onClick={() => setCurrentSlide(i)}
                    className={`h-1.5 rounded-full transition-all duration-300 ${
                      currentSlide === i ? 'w-4 bg-[#019342]' : 'w-1.5 bg-white/60'
                    }`}
                  />
                ))}
              </div>
            </div>

            {/* Float left & right button triggers */}
            <div className="absolute top-1/2 -translate-y-1/2 -left-4 md:-left-6 z-10">
              <button 
                id="btn-prev-slide"
                onClick={handlePrevSlide}
                className="h-10 w-10 rounded-full bg-white hover:bg-slate-100 hover:scale-105 active:scale-95 transition-all text-slate-800 shadow-md flex items-center justify-center border border-slate-200"
              >
                <ChevronLeft className="h-5 w-5" />
              </button>
            </div>

            <div className="absolute top-1/2 -translate-y-1/2 -right-4 md:-right-6 z-10">
              <button 
                id="btn-next-slide"
                onClick={handleNextSlide}
                className="h-10 w-10 rounded-full bg-white hover:bg-slate-100 hover:scale-105 active:scale-95 transition-all text-slate-800 shadow-md flex items-center justify-center border border-slate-200"
              >
                <ChevronRight className="h-5 w-5" />
              </button>
            </div>

            <div className="text-center mt-3">
              <span className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                OSIS &amp; MPK SMA Al-Ghazaly • {currentSlide + 1} dari {gallerySlides.length}
              </span>
            </div>
          </div>
          ) : (
            <div className="mx-auto max-w-4xl rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-xs font-bold text-slate-400">
              Belum ada album kegiatan yang dipublikasikan.
            </div>
          )}
        </div>

        {/* 4. "Tentang Kami" Statistics section */}
        <div id="school-statistics-section" className="mb-12">
          <div className="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <h3 className="text-2xl font-black text-slate-900 tracking-tight">
              Tentang Kami
            </h3>
            <div className="h-0.5 w-10 bg-[#019342] mx-auto rounded-full" />
            <p className="text-xs text-slate-500 font-semibold uppercase tracking-widest mt-1.5">
              Informasi Statistik SMA Al-Ghazaly Bogor
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {stats.map((st) => {
              const IconComp = st.icon;
              return (
                <div 
                  key={st.id}
                  id={st.id}
                  className="bg-white border border-slate-150 rounded-3xl p-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)] hover:shadow-[0_12px_24px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 relative group"
                >
                  <div className="flex justify-between items-start mb-4">
                    <div className={`h-11 w-11 rounded-2xl flex items-center justify-center border ${st.color}`}>
                      <IconComp className="h-5 w-5" />
                    </div>
                    <span className="text-[9px] uppercase font-black text-slate-300 tracking-wider">CIVITAS</span>
                  </div>

                  <div className="space-y-1">
                    <span className="block text-3xl font-black text-slate-900 tracking-tight">
                      {st.value}
                    </span>
                    <h4 className="text-xs font-black text-slate-900 uppercase tracking-wide">
                      {st.label}
                    </h4>
                    <p className="text-[11px] text-[#94a3b8] font-bold">
                      {st.desc}
                    </p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

      </div>
    </div>
  );
}
