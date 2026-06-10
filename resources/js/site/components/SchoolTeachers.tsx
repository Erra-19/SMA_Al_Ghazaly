import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  GraduationCap, 
  BookOpen, 
  Award, 
  Mail, 
  Search, 
  Heart, 
  Sparkles, 
  Quote, 
  ArrowRight,
  ShieldAlert,
  Compass,
  CheckCircle,
  HelpCircle,
  UserCheck
} from 'lucide-react';
import { getTeachers } from '../api';

interface Teacher {
  id: string;
  name: string;
  role: string;
  category: 'imtak' | 'mipa' | 'social-english' | 'bk-staf';
  image: string;
  education: string;
  philosophy: string;
  experience: string;
  email: string;
  tags: string[];
  isLeadership?: boolean;
}

export default function SchoolTeachers() {
  const [activeFilter, setActiveFilter] = useState<'all' | 'imtak' | 'mipa' | 'social-english' | 'bk-staf'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedTeacher, setSelectedTeacher] = useState<Teacher | null>(null);

  const filterTabs = [
    { id: 'all', label: 'Semua Pengajar' },
    { id: 'imtak', label: 'Imtak & Keagamaan' },
    { id: 'mipa', label: 'MIPA & Teknologi' },
    { id: 'social-english', label: 'IPS & Bahasa' },
    { id: 'bk-staf', label: 'Konseling & Staf' },
  ] as const;

  const fallbackTeachers: Teacher[] = [
    // --- LEADERSHIP & DEWAN DIREKSI ---
    {
      id: 'tc-1',
      name: 'K.H. Dr. Muhammad Syarif, Lc., M.A.',
      role: 'Kepala Sekolah & Guru Utama Tafsir Qur\'an',
      category: 'imtak',
      image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop',
      education: 'S3 Tafsir Al-Qur\'an, Al-Azhar University, Cairo',
      philosophy: 'Membentuk generasi emas umat dengan memadukan sains modern dan kedalaman mutiara Al-Qur\'an.',
      experience: '22 Tahun Mengabdi',
      email: 'm.syarif@alghazalybogor.sch.id',
      tags: ['Tafsir', 'Bahasa Arab', 'Kepemimpinan'],
      isLeadership: true,
    },
    {
      id: 'tc-2',
      name: 'Ustadzah Dra. Hj. Fatimah Az-Zahra, M.Pd.',
      role: 'Wakil Kepala Sekolah Bidang Kurikulum & Guru Matematika',
      category: 'mipa',
      image: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
      education: 'S2 Evaluasi Kurikulum, Universitas Negeri Jakarta',
      philosophy: 'Matematika melatih keadilan berpikir. Dengan menanamkan kejujuran logika, siswa siap menyelesaikan masalah global.',
      experience: '18 Tahun Mengabdi',
      email: 'fatimah.zahra@alghazalybogor.sch.id',
      tags: ['Kurikulum', 'Matematika', 'Inovasi'],
      isLeadership: true,
    },
    {
      id: 'tc-3',
      name: 'Ustadz Ahmad Fauzi, S.Pd.I.',
      role: 'Wakil Kepala Sekolah Bidang Kesiswaan & Guru Aqidah Akhlak',
      category: 'imtak',
      image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Pendidikan Agama Islam, UIN Syarif Hidayatullah',
      philosophy: 'Akhlakul karimah adalah mahkota ilmu. Pintar tanpa adab laksana pohon tanpa buah yang menyejukkan.',
      experience: '15 Tahun Mengabdi',
      email: 'ahmad.fauzi@alghazalybogor.sch.id',
      tags: ['Aqidah Akhlak', 'Mentor Karakter', 'Konseling'],
      isLeadership: true,
    },
    {
      id: 'tc-4',
      name: 'Ustadz H. M. Ridwan, Lc.',
      role: 'Kepala Asrama & Dewan Pengampu Program Tahfidz Intensif',
      category: 'imtak',
      image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Ushuluddin, Jamia Islamia Madinah, KSA',
      philosophy: 'Al-Qur\'an dijaga di dada, diamalkan di nyata. Asrama adalah kawah candradimuka kedisiplinan santri.',
      experience: '12 Tahun Mengabdi',
      email: 'm.ridwan@alghazalybogor.sch.id',
      tags: ['Tahfidz', 'Hadits', 'Boarding'],
      isLeadership: true,
    },

    // --- IMTAK & KEAGAMAAN ---
    {
      id: 'tc-5',
      name: 'Ustadz Wildan Baihaqi, S.S.',
      role: 'Guru Utama Bahasa Arab & Sharaf',
      category: 'imtak',
      image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Sastra Arab, Universitas Indonesia',
      philosophy: 'Bahasa Arab adalah kunci memahami khazanah hukum Islam dan keindahan sastra samawi.',
      experience: '9 Tahun Mengabdi',
      email: 'wildan.b@alghazalybogor.sch.id',
      tags: ['Bahasa Arab', 'Sastra', 'Ushul Fiqih'],
    },
    {
      id: 'tc-6',
      name: 'Ustadzah Sarah Rafifah, S.Ag.',
      role: 'Guru Fiqih & Sejarah Kebudayaan Islam (SKI)',
      category: 'imtak',
      image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Syariah, UIN Sunan Gunung Djati, Bandung',
      philosophy: 'Menghidupkan kejayaan sejarah Islam di benak milenial untuk memicu kebangkitan sains madani selanjutnya.',
      experience: '7 Tahun Mengabdi',
      email: 'sarah.rafifah@alghazalybogor.sch.id',
      tags: ['Fiqih', 'SKI', 'Feminisme Islam'],
    },

    // --- MIPA & TEKNOLOGI ---
    {
      id: 'tc-7',
      name: 'Andri Wijaya, M.Si.',
      role: 'Guru Utama Fisika & Pembimbing Utama Olimpiade Sains',
      category: 'mipa',
      image: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=300&auto=format&fit=crop',
      education: 'S2 Fisika Teoretis, Institut Teknologi Bandung (ITB)',
      philosophy: 'Setiap rumus fisika adalah bukti keteraturan penciptaan alam semesta oleh Sang Khaliq.',
      experience: '11 Tahun Mengabdi',
      email: 'andri.w@alghazalybogor.sch.id',
      tags: ['Fisika', 'Sains Komputasi', 'Astronomi'],
    },
    {
      id: 'tc-8',
      name: 'Dr. Rina Hermawan, M.T.',
      role: 'Guru Kimia & Koordinator Laboratorium Terpadu',
      category: 'mipa',
      image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop',
      education: 'S3 Teknik Kimia, Institut Teknologi Sepuluh Nopember (ITS)',
      philosophy: 'Penelitian praktis memupuk ketelitian intelektual. Kimia adalah seni menghubungkan makro dan mikro.',
      experience: '8 Tahun Mengabdi',
      email: 'rina.h@alghazalybogor.sch.id',
      tags: ['Kimia', 'Riset Lab', 'Nanoteknologi'],
    },
    {
      id: 'tc-9',
      name: 'Rahmat Hidayat, S.Pd.',
      role: 'Guru Informatika, Desain Grafis & Pembina Klub Robotika',
      category: 'mipa',
      image: 'https://images.unsplash.com/photo-1542909168-82c3e7fdca5c?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Pendidikan Teknologi & Jaringan, Universitas Pakuan',
      philosophy: 'Siswa hari ini harus menjadi kreator teknologi masa depan, bukan sekadar konsumen teknologi barat.',
      experience: '6 Tahun Mengabdi',
      email: 'rahmat.h@alghazalybogor.sch.id',
      tags: ['IoT', 'Coding', 'Robotika'],
    },
    {
      id: 'tc-10',
      name: 'Siti Aminah, S.Si.',
      role: 'Guru Biologi & Koordinator Ekologi Hijau Mandiri',
      category: 'mipa',
      image: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Ilmu Biologi, Universitas Padjadjaran (UNPAD)',
      philosophy: 'Mempelajari sistem kehidupan hayati menumbuhkan kepedulian mendalam terhadap kelestarian bumi amanah Allah.',
      experience: '10 Tahun Mengabdi',
      email: 'siti.aminah@alghazalybogor.sch.id',
      tags: ['Biologi', 'Botani', 'Hidroponik'],
    },

    // --- IPS & BAHASA ---
    {
      id: 'tc-11',
      name: 'Farhan Al-Ghifari, S.S., M.A.',
      role: 'Guru Utama Bahasa Inggris & Pembimbing English Debate',
      category: 'social-english',
      image: 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?q=80&w=300&auto=format&fit=crop',
      education: 'S2 Applied Linguistics, University of Manchester, UK',
      philosophy: 'English is not just a language; it is our primary international bridge to convey the message of peace and Islamic science globally.',
      experience: '11 Tahun Mengabdi',
      email: 'farhan.ag@alghazalybogor.sch.id',
      tags: ['Bahasa Inggris', 'Linguistik', 'Debate'],
    },
    {
      id: 'tc-12',
      name: 'Diana Lestari, M.Pd.',
      role: 'Guru Bahasa Indonesia & Penulis Karya Sastra',
      category: 'social-english',
      image: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?q=80&w=300&auto=format&fit=crop',
      education: 'S2 Pendidikan Sastra & Bahasa Indonesia, Universitas Indonesia',
      philosophy: 'Bahasa Indonesia mengikat persatuan rasa. Sastra mempertajam sensibilitas hati dan empati sosial.',
      experience: '14 Tahun Mengabdi',
      email: 'diana.lestari@alghazalybogor.sch.id',
      tags: ['Sastra', 'Jurnalistik', 'Retorika Deskriptif'],
    },
    {
      id: 'tc-13',
      name: 'Bambang Susilo, S.Pd.',
      role: 'Guru Sejarah & Pembina Pramuka Terpadu',
      category: 'social-english',
      image: 'https://images.unsplash.com/photo-1500048993953-d23a436266cf?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Pendidikan Sejarah, Universitas Negeri Jakarta',
      philosophy: 'Tanpa sejarah, kita buta arah. Memahami masa lampau adalah penunjuk jalan meraih kejayaan peradaban masa depan.',
      experience: '13 Tahun Mengabdi',
      email: 'bambang.s@alghazalybogor.sch.id',
      tags: ['Sejarah', 'Geopolitik', 'Pramuka'],
    },

    // --- BK & STAF ---
    {
      id: 'tc-14',
      name: 'Annisa Fitriani, S.Psi., M.Psi.',
      role: 'Konselor Utama Bimbingan Konseling (BK)',
      category: 'bk-staf',
      image: 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=300&auto=format&fit=crop',
      education: 'S2 Psikologi Pendidikan, Universitas Indonesia',
      philosophy: 'Setiap anak memiliki keunikan kecerdasan sendiri. Membimbing mereka menemukan jati diri adalah kebahagiaan sejati.',
      experience: '9 Tahun Mengabdi',
      email: 'annisa.f@alghazalybogor.sch.id',
      tags: ['Konseling', 'Bakat & Minat', 'Mental Health'],
    },
    {
      id: 'tc-15',
      name: 'Dani Ramdani, S.Kom.',
      role: 'Kepala Bidang IT, Infrastruktur & Server Lokal',
      category: 'bk-staf',
      image: 'https://images.unsplash.com/photo-1549351512-c5e12b75266d?q=80&w=300&auto=format&fit=crop',
      education: 'S1 Teknik Informatika, IPB University',
      philosophy: 'Mendukung kelancaran akademik dengan sistem cloud sekolah yang aman, cepat, dan terpusat.',
      experience: '10 Tahun Mengabdi',
      email: 'dani.ramdani@alghazalybogor.sch.id',
      tags: ['IT Admin', 'Fullstack', 'Web Server'],
    }
  ];

  // Filtering Logic
  const [teachersList, setTeachersList] = useState<Teacher[]>([]);

  useEffect(() => {
    getTeachers()
      .then((items) => setTeachersList(items as Teacher[]))
      .catch(() => {});
  }, []);

  const filteredTeachers = teachersList.filter(teacher => {
    const matchesFilter = activeFilter === 'all' || teacher.category === activeFilter;
    const matchesSearch = teacher.name.toLowerCase().includes(searchQuery.toLowerCase()) || 
                          teacher.role.toLowerCase().includes(searchQuery.toLowerCase()) ||
                          teacher.tags.some(tag => tag.toLowerCase().includes(searchQuery.toLowerCase()));
    
    // Leadership is highlighted separately at the top of the page, so we only list them if it matches filters
    return matchesFilter && matchesSearch;
  });

  const leadershipList = teachersList.filter(t => t.isLeadership);

  return (
    <div id="teachers-page-root" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-16">
      
      {/* 1. Header Hero Banner with Luxury Gradient & Floats */}
      <div 
        id="teachers-hero-banner" 
        className="relative overflow-hidden bg-gradient-to-br from-[#019342] to-[#191654] text-white py-24 px-4 sm:px-6 lg:px-8 mb-16 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
      >
        <div className="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:24px_24px]" />
        
        {/* Floating Blurs for Depth */}
        <div className="absolute -top-12 -left-12 w-80 h-80 bg-white/10 rounded-full blur-3xl" />
        <div className="absolute -bottom-16 right-10 w-96 h-96 bg-[#019342]/40 rounded-full blur-3xl opacity-60 animate-pulse-slow" />

        <div className="relative mx-auto max-w-7xl">
          <motion.div
            initial={{ opacity: 0, y: -15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-[10px] font-black tracking-widest uppercase mb-4"
          >
            <Sparkles className="h-4 w-4 text-emerald-300" />
            <span>Pendidik Berkelas Rabbani &amp; Bersahabat</span>
          </motion.div>

          <motion.h1 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-none"
          >
            Dewan Pengajar
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
            Berkenalan dengan dewan pengajar handal SMA Al-Ghazaly Bogor yang berdedikasi tinggi menggabungkan sains, iman, kepemimpinan karakter, dan teknologi.
          </motion.p>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {/* 2. Top Management / School Leadership Segment */}
        <section id="school-leadership" className="mb-24">
          <div className="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span className="text-[10px] font-black tracking-widest text-[#019342] uppercase bg-[#019342]/10 px-4 py-1.5 rounded-full inline-block">
              Kepemimpinan Sekolah
            </span>
            <h2 id="leadership-title" className="text-3xl font-extrabold text-[#191654] tracking-tight">
              Dewan Direksi &amp; Pimpinan Akademik
            </h2>
            <div className="h-1 w-12 bg-[#019342] mx-auto rounded-full" />
            <p className="text-xs text-slate-500 font-bold max-w-md mx-auto leading-relaxed">
              Mengarahkan kebijakan strategis sekolah demi menghasilkan lulusan berintegritas moral madani dan berdaya saing global tinggi.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {leadershipList.map((leader, i) => (
              <motion.div
                key={leader.id}
                id={`leader-card-${leader.id}`}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                className="bg-white border-2 border-slate-100/80 rounded-3xl overflow-hidden shadow-[0_12px_32px_rgba(0,0,0,0.015)] group hover:border-[#019342]/40 transition-all duration-300 flex flex-col h-full"
              >
                {/* Image Section */}
                <div className="relative aspect-[4/5] overflow-hidden bg-slate-100">
                  <img
                    src={leader.image}
                    alt={leader.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                    referrerPolicy=" referrer"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-90" />
                  
                  {/* Floating Badges */}
                  <div className="absolute top-4 left-4 inline-flex items-center gap-1.5 rounded-full bg-[#191654] text-white px-3 py-1 text-[9px] font-extrabold uppercase tracking-widest shadow-md">
                    <UserCheck className="h-3 w-3 text-emerald-400" />
                    <span>DIREKSI</span>
                  </div>

                  {/* Position Detail in Image Bottom */}
                  <div className="absolute bottom-4 left-4 right-4 text-white">
                    <p className="text-[10px] font-black uppercase text-emerald-400 tracking-wider mb-1 line-clamp-1">{leader.experience}</p>
                    <p className="text-xs text-white/90 font-semibold line-clamp-2">{leader.role}</p>
                  </div>
                </div>

                {/* Info Text Body Section */}
                <div className="p-6 flex flex-col flex-grow">
                  <h3 className="text-sm font-black text-slate-900 leading-snug group-hover:text-[#019342] transition-colors line-clamp-2 min-h-12 mb-2">
                    {leader.name}
                  </h3>
                  
                  <div className="flex items-start gap-2 text-xs text-slate-500 mb-4 bg-slate-50 p-2.5 rounded-xl border border-slate-100/60 grow">
                    <GraduationCap className="h-4 w-4 text-[#019342] shrink-0 mt-0.5" />
                    <span className="font-semibold leading-relaxed line-clamp-2">{leader.education}</span>
                  </div>

                  {/* Bottom Trigger */}
                  <button
                    id={`btn-view-${leader.id}`}
                    onClick={() => setSelectedTeacher(leader)}
                    className="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-[#019342] hover:text-white hover:border-[#019342] transition-all duration-200 flex items-center justify-center gap-2 group/btn"
                  >
                    <span>Filsafat &amp; Kontak</span>
                    <ArrowRight className="h-3.5 w-3.5 group-hover/btn:translate-x-1 transition-transform" />
                  </button>
                </div>
              </motion.div>
            ))}
          </div>
        </section>

        {/* 3. Interactive Main Directory of Teachers */}
        <section id="teachers-directory" className="mb-12">
          
          {/* Header Segment info */}
          <div className="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-100">
            <div>
              <h2 id="directory-title" className="text-2xl font-black text-[#191654] tracking-tight">
                Direktori Guru &amp; Staf
              </h2>
              <p className="text-xs font-semibold text-slate-400 mt-1">
                Menyajikan informasi akurat kompetensi guru bidang pelajaran.
              </p>
            </div>

            {/* Live Search bar layout */}
            <div className="relative w-full md:w-80 shrink-0">
              <input
                id="teacher-search-field"
                type="text"
                placeholder="Cari guru, subjek, atau keilmuan..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 rounded-2xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-bold shadow-inner"
              />
              <Search className="absolute left-3.5 top-3.5 h-4 w-4 text-slate-400" />
            </div>
          </div>

          {/* Filtering Tabs Controls */}
          <div className="flex flex-wrap items-center gap-2 mb-10 overflow-x-auto pb-2 scrollbar-none">
            {filterTabs.map((tab) => (
              <button
                key={tab.id}
                id={`tab-filter-${tab.id}`}
                onClick={() => setActiveFilter(tab.id)}
                className={`px-5 py-2.5 rounded-full text-xs font-bold tracking-wide transition relative cursor-pointer ${
                  activeFilter === tab.id
                    ? 'bg-[#019342] text-white shadow-md'
                    : 'bg-white text-slate-500 border border-slate-150 hover:bg-slate-50 hover:text-slate-700'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>

          {/* Teacher Directory Cards Grid list */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <AnimatePresence mode="popLayout">
              {filteredTeachers.map((tc, index) => (
                <motion.div
                  layout
                  key={tc.id}
                  id={`dir-card-${tc.id}`}
                  initial={{ opacity: 0, scale: 0.95 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.9 }}
                  transition={{ duration: 0.3 }}
                  className="bg-white border border-slate-150 rounded-2xl p-5 shadow-[0_4px_24px_rgba(0,0,0,0.01)] hover:shadow-xl hover:-translate-y-1 hover:border-[#019342]/20 transition-all duration-300 flex flex-col h-full group"
                >
                  <div className="flex items-center gap-4 mb-4">
                    {/* Small Rounded Avatar Frame */}
                    <div className="relative h-14 w-14 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                      <img
                        src={tc.image}
                        alt={tc.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-all duration-500"
                        referrerPolicy="no-referrer"
                      />
                    </div>

                    <div className="overflow-hidden">
                      <h4 className="text-xs font-black text-slate-900 leading-tight group-hover:text-[#019342] transition-colors truncate">
                        {tc.name}
                      </h4>
                      <p className="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider truncate">
                        {tc.experience}
                      </p>
                    </div>
                  </div>

                  {/* Role descriptor text */}
                  <p className="text-[11px] font-semibold text-slate-500 line-clamp-2 min-h-8 mb-4">
                    {tc.role}
                  </p>

                  {/* Core Tags */}
                  <div className="flex flex-wrap gap-1.5 mb-4 mt-auto">
                    {tc.tags.map((tag, i) => (
                      <span
                        key={i}
                        className="px-2 py-0.5 rounded-md bg-[#019342]/5 text-[#019342] text-[9px] font-black uppercase tracking-wider"
                      >
                        {tag}
                      </span>
                    ))}
                  </div>

                  <div className="border-t border-slate-100 pt-4 flex items-center justify-between">
                    <button
                      id={`btn-open-detail-${tc.id}`}
                      onClick={() => setSelectedTeacher(tc)}
                      className="text-[10px] font-black text-[#019342] uppercase tracking-wider group-hover:text-[#191654] transition-all flex items-center gap-1.5"
                    >
                      <span>Lihat Detail</span>
                      <ArrowRight className="h-3 w-3 translate-x-0 group-hover:translate-x-1 transition-transform" />
                    </button>

                    <a
                      id={`link-email-${tc.id}`}
                      href={`mailto:${tc.email}`}
                      title={tc.email}
                      className="text-slate-400 hover:text-[#019342] transition-colors"
                    >
                      <Mail className="h-4 w-4" />
                    </a>
                  </div>
                </motion.div>
              ))}
            </AnimatePresence>

            {/* Zero results container */}
            {filteredTeachers.length === 0 && (
              <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                className="col-span-full py-16 text-center bg-white border border-slate-150 rounded-3xl"
              >
                <div className="h-12 w-12 rounded-full bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-4">
                  <BookOpen className="h-5 w-5" />
                </div>
                <h3 className="text-sm font-extrabold text-slate-800">
                  Pengajar Tidak Ditemukan
                </h3>
                <p className="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                  Maaf, tidak ada guru atau bidang pelajaran yang cocok dengan kriteria pencarian &quot;{searchQuery}&quot;. Silakan coba frasa pencarian lain.
                </p>
              </motion.div>
            )}
          </div>
        </section>

      </div>

      {/* 4. Highly Polished Teacher Detail Modal Window */}
      <AnimatePresence>
        {selectedTeacher && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
          >
            {/* Modal Body Container */}
            <motion.div
              initial={{ scale: 0.95, y: 15 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 15 }}
              transition={{ type: 'spring', damping: 25, stiffness: 350 }}
              className="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 max-w-xl w-full relative"
            >
              {/* Header block height color */}
              <div className="h-2.5 bg-[#019342]" />

              <div className="p-6 md:p-8">
                
                {/* Visual Bio Header */}
                <div className="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                  <div className="h-24 w-24 shrink-0 rounded-2xl overflow-hidden border-2 border-[#019342]/10 bg-slate-50">
                    <img
                      src={selectedTeacher.image}
                      alt={selectedTeacher.name}
                      className="w-full h-full object-cover"
                    />
                  </div>

                  <div className="text-center sm:text-left overflow-hidden">
                    <span className="px-2.5 py-1 rounded bg-[#019342]/10 text-[#019342] text-[9.5px] font-black uppercase tracking-wider inline-block mb-2">
                      {selectedTeacher.experience}
                    </span>
                    <h3 className="text-lg font-black text-slate-900 leading-tight">
                      {selectedTeacher.name}
                    </h3>
                    <p className="text-xs text-slate-500 font-semibold mt-1">
                      {selectedTeacher.role}
                    </p>
                  </div>
                </div>

                {/* Info List Fields */}
                <div className="space-y-4">
                  
                  {/* Ed */}
                  <div className="bg-[#f8fafc] border border-slate-100 p-4 rounded-xl">
                    <div className="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                      <GraduationCap className="h-4 w-4 text-[#019342]" />
                      <span>Alumnus &amp; Riwayat Akademik</span>
                    </div>
                    <p className="text-xs font-bold text-slate-700 leading-relaxed">
                      {selectedTeacher.education}
                    </p>
                  </div>

                  {/* Philosophy Quote */}
                  <div className="bg-emerald-500/[0.02] border border-emerald-500/10 p-4 rounded-xl relative">
                    <div className="absolute top-4 right-4 text-[#019342]/10">
                      <Quote className="h-8 w-8 fill-current" />
                    </div>
                    
                    <div className="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                      <Heart className="h-4 w-4 text-rose-500 fill-current animate-pulse-slow" />
                      <span>Filsafat Mengajar</span>
                    </div>
                    <p className="text-xs sm:text-[13px] font-semibold text-slate-600 italic leading-relaxed">
                      &quot;{selectedTeacher.philosophy}&quot;
                    </p>
                  </div>

                  {/* Technical Tags & Expertise areas */}
                  <div>
                    <span className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Spesialisasi Kunci</span>
                    <div className="flex flex-wrap gap-2 pl-1">
                      {selectedTeacher.tags.map((tag, i) => (
                        <span
                          key={i}
                          className="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold pb-1"
                        >
                          #{tag}
                        </span>
                      ))}
                    </div>
                  </div>

                </div>

                {/* Footer Controls / Close Actions */}
                <div className="mt-8 border-t border-slate-100 pt-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                  <a
                    id="modal-email-btn"
                    href={`mailto:${selectedTeacher.email}`}
                    className="w-full sm:w-auto text-xs font-black tracking-widest uppercase inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#191654] text-white hover:bg-[#019342] transition-colors shadow-sm"
                  >
                    <Mail className="h-4 w-4" />
                    <span>Hubungi Guru Via Email</span>
                  </a>

                  <button
                    id="modal-close-btn"
                    onClick={() => setSelectedTeacher(null)}
                    className="w-full sm:w-auto py-2.5 px-6 rounded-none text-xs font-black text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-widest text-center"
                  >
                    Tutup
                  </button>
                </div>

              </div>

            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

    </div>
  );
}
