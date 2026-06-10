import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  Building, 
  BookOpen, 
  Sparkles, 
  ArrowRight,
  ShieldAlert,
  Compass,
  CheckCircle,
  Clock,
  MapPin,
  Maximize2,
  Bookmark,
  Waves,
  Heart,
  Calendar,
  Layers,
  Search,
  Check
} from 'lucide-react';
import { getFacilities } from '../api';

interface Facility {
  id: string;
  name: string;
  category: 'akademik' | 'ibadah-sosial' | 'olahraga-seni' | 'kesejahteraan';
  image: string;
  iconName: string;
  shortDesc: string;
  longDesc: string;
  capacity?: string;
  specs: string[];
  operationalHours: string;
  location: string;
  isFeatured?: boolean;
}

export default function SchoolFacilities() {
  const [activeFilter, setActiveFilter] = useState<'all' | 'akademik' | 'ibadah-sosial' | 'olahraga-seni' | 'kesejahteraan'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedFacility, setSelectedFacility] = useState<Facility | null>(null);
  const [facilitiesList, setFacilitiesList] = useState<Facility[]>([]);

  useEffect(() => {
    getFacilities().then((items) => setFacilitiesList(items as Facility[])).catch(() => setFacilitiesList([]));
  }, []);

  const filterTabs = [
    { id: 'all', label: 'Semua Fasilitas' },
    { id: 'akademik', label: 'Pusat Akademik & Riset' },
    { id: 'ibadah-sosial', label: 'Spiritual & Sosial' },
    { id: 'olahraga-seni', label: 'Olahraga & Kreativitas' },
    { id: 'kesejahteraan', label: 'Kesejahteraan Siswa' },
  ] as const;

  const fallbackFacilities: Facility[] = [
    {
      id: 'fac-1',
      name: 'Masjid Jami\' Al-Ghazaly',
      category: 'ibadah-sosial',
      image: 'https://images.unsplash.com/photo-1597935258735-e254c1839512?q=80&w=800&auto=format&fit=crop',
      iconName: 'Ibadah',
      shortDesc: 'Pusat kegiatan rohani, shalat berjamaah, halaqah tahfidz, dan kajian keagamaan seluruh civitas akademika.',
      longDesc: 'Masjid Jami\' Al-Ghazaly merupakan jantung spiritual dari SMA Al-Ghazaly Bogor. Senantiasa digunakan secara intensif untuk membiasakan shalat fardhu berjamaah tepat waktu, shalat dhuha bersama, ujian terbuka (imtihan) hafalan Al-Qur\'an, serta kajian kitab kuning pimpinan para kyai. Dilengkapi dengan pendingin udara yang sejuk dan arsitektur kubah bernuansa islami modern yang damai.',
      capacity: '600 Jamaah',
      specs: [
        'Sistem Audio & Sound Terkini',
        'Area Wudhu Luas & Higienis',
        'Koleksi Mushaf Al-Qur\'an Santri',
        'Gedung Terpisah Jamaah Ikhwan & Akhwat'
      ],
      operationalHours: '24 Jam Aktif',
      location: 'Samping Timur Kompleks Utama',
      isFeatured: true,
    },
    {
      id: 'fac-2',
      name: 'Perpustakaan Pintar & Digital',
      category: 'akademik',
      image: 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=800&auto=format&fit=crop',
      iconName: 'Buku',
      shortDesc: 'Ruang literasi modern dengan ribuan buku akademik, kitab-kitab khazanah islam classic, dan komputer e-library.',
      longDesc: 'Perpustakaan SMA Al-Ghazaly menyediakan atmosfer belajar yang tenang dan inspiratif. Memadukan koleksi literatur kurikulum nasional terlengkap, rujukan sains global, jurnal, fiksi bermutu, hingga kitab-kitab aseli warisan intelektual islam klasik (Kitab Kuning). Tempat ini juga dilengkapi dengan e-Library (Komputer Riset) guna menyambungkan nalar santri ke pangkalan data riset dunia modern.',
      capacity: '80 Siswa',
      specs: [
        'Katalog Digital Opac Terkomputerisasi',
        'Fasilitas Hotspot Internet Kecepatan Tinggi',
        'Ruang Baca Lesehan Ber-AC nyaman',
        'Koleksi Referensi Internasional'
      ],
      operationalHours: '07:00 - 16:00 WIB',
      location: 'Lantai 2 Koridor Utara',
      isFeatured: true,
    },
    {
      id: 'fac-3',
      name: 'Laboratorium Biologi, Fisika & Kimia Terpadu',
      category: 'akademik',
      image: 'https://images.unsplash.com/photo-1517976487492-5750f3195933?q=80&w=800&auto=format&fit=crop',
      iconName: 'Lab',
      shortDesc: 'Laboratorium sains modern dengan peralatan analisis praktikum lengkap untuk mendukung riset siswa.',
      longDesc: 'Sebagai pilar utama kurikulum MIPA, Laboratorium Terpadu kami dirancang memenuhi standar keselamatan tinggi dan riset modern. Siswa dilatih melakukan observasi mikroskopis, analisis reaksi kimia aman, dan eksperimen rekayasa fisika dasar secara real-time. Mempersiapkan mereka memahami rahasia alam semesta kreasi Ilahi melalui kacamata ilmu pengetahuan eksak modern.',
      capacity: '40 Praktikan',
      specs: [
        'Mikroskop Digital & Monitor Interaktif',
        'Peralatan Destilasi & Titrasi Lengkap',
        'Lemari Asam & Sistem Aliran Khusus',
        'Kit Eksperimen Mekanika & Listrik'
      ],
      operationalHours: '07:30 - 15:30 WIB',
      location: 'Gedung Barat lantai 1',
      isFeatured: true,
    },
    {
      id: 'fac-4',
      name: 'Lapangan Olahraga Hijau Mandiri',
      category: 'olahraga-seni',
      image: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=800&auto=format&fit=crop',
      iconName: 'Olahraga',
      shortDesc: 'Lapangan outdoor serbaguna untuk olahraga futsal, basket, voli, bulutangkis, serta inspeksi upacara bendera.',
      longDesc: 'Lapangan olahraga SMA Al-Ghazaly dibalut warna hijau-terang laksana kesegaran fisik peserta didik. Didesain secara serbaguna (multipurpose) guna memfasilitasi klub olahraga futsal, bola basket, bola voli, bulutangkis, latihan bela diri pencak silat, program pramuka, serta menjadi tempat utama pelaksanaan upacara bendera hari senin dan apel santri pagi yang khidmat.',
      capacity: '800 Siswa untuk Upacara',
      specs: [
        'Sistem Cat Antilicin Standar Kompetensi',
        'Ring Basket Hidrolik Portabel',
        'Gawang Futsal Aman Berserat Kokoh',
        'Sistem Drainase Air Hujan Cepat Kering'
      ],
      operationalHours: '06:00 - 17:30 WIB',
      location: 'Tengah Kompleks Kampus Al-Ghazaly',
      isFeatured: true,
    },
    {
      id: 'fac-5',
      name: 'Gazebo Diskusi & Pojok Hafalan',
      category: 'ibadah-sosial',
      image: 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=800&auto=format&fit=crop',
      iconName: 'Taman',
      shortDesc: 'Pondok asri luar kelas yang diteduhi tanaman penyaring udara untuk diskusi kelompok dan hafalan Al-Qur\'an santri.',
      longDesc: 'Didesain menyerupai konsep taman santri yang teduh dan asri. Memiliki susunan tiang kayu kokoh dengan ventilasi angin segar alami maksimal. Siswa memanfaatkannya di waktu istirahat maupun jam pelajaran luar kelas untuk murojaah hafalan mandiri, menyelesaikan tugas kelompok dengan bersahabat, atau mendiskusikan gagasan proyek sosial madani.',
      capacity: '10-15 Siswa per Gazebo',
      specs: [
        'Sumber Pengisian Daya Listrik di Tiap Sisi',
        'Akses Tercepat Wi-Fi Eksternal Kampus',
        'Taman Rerumputan Hijau Penyejuk Mata',
        'Dekat dengan Area Masjid Utama'
      ],
      operationalHours: '06:30 - 17:00 WIB',
      location: 'Taman Samping Masjid',
    },
    {
      id: 'fac-6',
      name: 'Koperasi & Toko Atribut Siswa',
      category: 'kesejahteraan',
      image: 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?q=80&w=800&auto=format&fit=crop',
      iconName: 'Koperasi',
      shortDesc: 'Gerai terpusat penyedia buku pelajaran, seragam resmi, alat tulis, dan aksesoris resmi identitas Al-Ghazaly.',
      longDesc: 'Mempermudah kebutuhan harian siswa tanpa perlu keluar lingkungan sekolah. Menyediakan lengkap mulai dari baju seragam batik Al-Ghazaly, jas almamater, baju olahraga, kebutuhan pramuka terpadu, buku paket pendukung pelajaran kementerian, hingga aneka kreasi inovatif hasil wirausaha pengurus OSIS sekolah.',
      capacity: '30 Pelanggan sekaligus',
      specs: [
        'Sistem Pembayaran Cashless & Cash terpadu',
        'Fasilitas Mesin Foto Copy Mini Siswa',
        'Sediaan Seragam Cadangan Semua Ukuran',
        'Dikelola mandiri berazaskan kejujuran'
      ],
      operationalHours: '07:00 - 15:30 WIB',
      location: 'Gedung Kesejahteraan Sisi Selatan',
    },
    {
      id: 'fac-7',
      name: 'Kantin Berkah Sehat & Higienis',
      category: 'kesejahteraan',
      image: 'https://images.unsplash.com/photo-1563245372-f21724e3856d?q=80&w=800&auto=format&fit=crop',
      iconName: 'Kantin',
      shortDesc: 'Pusat kuliner bernutrisi seimbang bagi siswa dengan penjaminan kebersihan dan menu sehat bersertifikasi.',
      longDesc: 'Kantin SMA Al-Ghazaly meletakkan prioritas pada aspek kebersihan dan gizi. Sajian sayuran segar, protein berkualitas, jus buah asli, serta camilan sehat dikoordinasi secara mandiri dengan pemeriksaan kelayakan higienitas harian. Membantu menjaga asupan nutrisi santri agar tetap prima dalam berpikir keras menimba ilmu.',
      capacity: '120 Tempat Duduk',
      specs: [
        'Wastafel Cuci Tangan Setiap Sudut Meja',
        'Daftar Menu Bersertifikat Halal MUI',
        'Pengelolaan sisa sampah ramah lingkungan',
        'Konsep sirkulasi udara luar ruangan yang asri'
      ],
      operationalHours: '09:00 - 14:00 WIB',
      location: 'Samping Lab IPA Lantai 1',
    },
    {
      id: 'fac-8',
      name: 'Toilet Bersih, Sehat & Higienis',
      category: 'kesejahteraan',
      image: 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=800&auto=format&fit=crop',
      iconName: 'Toilet',
      shortDesc: 'Fasilitas sanitasi yang dirawat ketat tiga kali sehari, dipisahkan mandiri untuk santri ikhwan dan akhwat.',
      longDesc: 'Sanitasi yang mulia merupakan refleksi keimanan yang luhur (An-Nazhafatu minal Iman). Toilet kami dijaga dengan kedisiplinan jadwal pembersihan yang tinggi. Terbagi atas dua blok bangunan terpisah yang amat aman menjaga privasi masing-masing gender. Mengalirkan air bersih berlimpah bebas endapan mineral keruh.',
      capacity: 'Terdiri atas 12 Bilik Bersih',
      specs: [
        'Pemberian Pewangi Otomatis Menyegarkan',
        'Wastafel Marmer & Mirror Portrait',
        'Gantungan Pakaian & Keran Wudhu Internal',
        'Sistem Aliran Limbah Modern Eco-friendly'
      ],
      operationalHours: '24 Jam Aktif',
      location: 'Ujung Tiap Koridor Pembelajaran',
    },
    {
      id: 'fac-9',
      name: 'Kompleks Parkir Aman Terpadu',
      category: 'kesejahteraan',
      image: 'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?q=80&w=800&auto=format&fit=crop',
      iconName: 'Parkir',
      shortDesc: 'Area parkir aman berpengaman penjaga sekolah dan CCTV 24 jam untuk sepeda motor dan mobil tamu.',
      longDesc: 'Kompleks pelataran parkir dirancang dengan pembetonan rata bebas genangan. Memberikan kenyamanan bagi para guru, staf administrasi, serta orang tua/wali siswa yang sedang bertamu untuk menjemput putra-putrinya. Diawasi ketat oleh petugas ramah dan terlindungi kamera pengintai CCTV secara non-stop.',
      capacity: '80 Sepeda Motor & 20 Mobil',
      specs: [
        'Pos Penjagaan Pintu Gerbang Utama',
        'Sistem Kamera Keamanan CCTV Terintegrasi',
        'Atap Kanopi Teduh Pelindung Cuaca Panas',
        'Garis Batas Parkir yang Rapi dan Tertib'
      ],
      operationalHours: '05:30 - 18:00 WIB',
      location: 'Dekat Pintu Gerbang Masuk Utama',
    }
  ];

  // Filtering list based on select tabs and live search keywords
  const filteredFacilities = facilitiesList.filter(facility => {
    const matchesFilter = activeFilter === 'all' || facility.category === activeFilter;
    const matchesSearch = facility.name.toLowerCase().includes(searchQuery.toLowerCase()) || 
                          facility.shortDesc.toLowerCase().includes(searchQuery.toLowerCase()) ||
                          facility.specs.some(spec => spec.toLowerCase().includes(searchQuery.toLowerCase()));
    
    return matchesFilter && matchesSearch;
  });

  return (
    <div id="facilities-page-root" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-16">
      
      {/* 1. Header Banner */}
      <div 
        id="facilities-hero-banner" 
        className="relative overflow-hidden bg-gradient-to-br from-[#019342] to-[#191654] text-white py-24 px-4 sm:px-6 lg:px-8 mb-16 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
      >
        <div className="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:24px_24px]" />
        
        {/* Decorative Floating Components */}
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
            <span>Infra dan Fasilitas Pembelajaran Kondusif</span>
          </motion.div>

          <motion.h1 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-none"
          >
            Fasilitas Sekolah
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
            className="mt-6 max-w-2xl text-sm md:text-base text-white/90 leading-relaxed font-semibold"
          >
            Kenyamanan fisik dan kelengkapan infrastruktur modern di SMA Al-Ghazaly Bogor disinergikan harmonis guna menghasilkan ekosistem ilmu yang tepercaya dan rukun.
          </motion.p>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {/* 2. Headline with Quick Facility Grid */}
        <section id="facilities-intro" className="mb-16">
          <div className="text-center max-w-3xl mx-auto mb-12 space-y-4">
            <span className="text-[10px] font-black tracking-widest text-[#019342] uppercase bg-[#019342]/10 px-4 py-1.5 rounded-full inline-block">
              Keunggulan Sarana
            </span>
            <h2 id="intro-title" className="text-3xl font-extrabold text-[#191654] tracking-tight">
              Fasilitas Utama Penunjang Prestasi
            </h2>
            <div className="h-1 w-12 bg-[#019342] mx-auto rounded-full" />
            <p className="text-xs sm:text-sm text-slate-500 font-semibold max-w-2xl mx-auto leading-relaxed">
              Kami meyakini lingkungan yang bersih, lengkap, dan asri akan melipatgandakan semangat belajar para santri serta mengoptimalkan tumbuh-kembang minat bakat sains maupun agama.
            </p>
          </div>
        </section>

        {/* 3. Interactive Main Directory of Facilities */}
        <section id="facilities-directory" className="mb-12">
          
          {/* Header Segment info */}
          <div className="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-100">
            <div>
              <h2 id="directory-title" className="text-2xl font-black text-[#191654] tracking-tight">
                Jelajahi Fasilitas Kami
              </h2>
              <p className="text-xs font-semibold text-slate-400 mt-1">
                Gunakan saringan kategori atau pencarian instan sarana sekolah.
              </p>
            </div>

            {/* Live Search bar layout */}
            <div className="relative w-full md:w-80 shrink-0">
              <input
                id="facility-search-field"
                type="text"
                placeholder="Cari koordinat sarana, spesifikasi..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 rounded-2xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold shadow-inner"
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
                className={`px-5 py-2.5 rounded-full text-xs font-bold tracking-wide transition relative cursor-pointer whitespace-nowrap ${
                  activeFilter === tab.id
                    ? 'bg-[#019342] text-white shadow-md'
                    : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 hover:text-slate-750'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>

          {/* Facilities Cards Grid list */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <AnimatePresence mode="popLayout">
              {filteredFacilities.map((fac, idx) => (
                <motion.div
                  layout
                  key={fac.id}
                  id={`facility-card-${fac.id}`}
                  initial={{ opacity: 0, scale: 0.95 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.9 }}
                  transition={{ duration: 0.3 }}
                  className="bg-white border-2 border-slate-100/80 rounded-3xl overflow-hidden shadow-[0_12px_24px_rgba(0,0,0,0.01)] group hover:border-[#019342]/40 hover:shadow-xl transition-all duration-300 flex flex-col h-full"
                >
                  {/* Photo area with category float */}
                  <div className="relative aspect-[16/10] overflow-hidden bg-slate-150">
                    <img
                      src={fac.image}
                      alt={fac.name}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                      referrerPolicy="no-referrer"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent" />
                    
                    {/* Category Label Float */}
                    <div className="absolute top-4 left-4 bg-[#191654]/90 text-white backdrop-blur-sm shadow-md rounded-full px-3 py-1 text-[9px] font-extrabold uppercase tracking-wider">
                      {fac.category === 'akademik' && 'Pusat Riset'}
                      {fac.category === 'ibadah-sosial' && 'Karakter & Qur\'an'}
                      {fac.category === 'olahraga-seni' && 'Kesehatan & Seni'}
                      {fac.category === 'kesejahteraan' && 'Layanan Murid'}
                    </div>

                    {/* Featured Star Badge */}
                    {fac.isFeatured && (
                      <div className="absolute top-4 right-4 bg-amber-500 text-white rounded-full p-1.5 shadow-md" title="Mewah & Komplet">
                        <Sparkles className="h-3.5 w-3.5" />
                      </div>
                    )}
                  </div>

                  {/* Body textual list */}
                  <div className="p-6 flex flex-col flex-grow">
                    <h3 className="text-base font-black text-slate-900 mb-2 leading-snug group-hover:text-[#019342] transition-colors truncate">
                      {fac.name}
                    </h3>

                    <p className="text-xs text-slate-500 leading-relaxed font-semibold mb-6 flex-grow line-clamp-3">
                      {fac.shortDesc}
                    </p>

                    <div className="border-t border-slate-100 pt-4 flex items-center justify-between text-[11px] font-bold text-slate-400">
                      <span className="flex items-center gap-1.5">
                        <MapPin className="h-3.5 w-3.5 text-[#019342]" />
                        {fac.location}
                      </span>
                    </div>

                    <div className="mt-5">
                      <button
                        id={`btn-open-facility-${fac.id}`}
                        onClick={() => setSelectedFacility(fac)}
                        className="w-full py-2.5 px-4 rounded-xl border-2 border-slate-100 text-xs font-bold text-slate-700 hover:bg-[#019342] hover:text-white hover:border-[#019342] transition-all duration-200 flex items-center justify-center gap-2 group/btn"
                      >
                        <span>Cek Detil & Sfesifikasi</span>
                        <ArrowRight className="h-3.5 w-3.5 group-hover/btn:translate-x-1 transition-transform" />
                      </button>
                    </div>
                  </div>
                </motion.div>
              ))}
            </AnimatePresence>

            {/* Empty filter result display */}
            {filteredFacilities.length === 0 && (
              <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                className="col-span-full py-16 text-center bg-white border border-slate-150 rounded-3xl"
              >
                <div className="h-12 w-12 rounded-full bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-4">
                  <Building className="h-5 w-5" />
                </div>
                <h3 className="text-sm font-extrabold text-slate-800">
                  Sarana Tidak Ditemukan
                </h3>
                <p className="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-medium">
                  Maaf, tidak ada fasilitas atau sarana sekolah yang ramah pencarian &quot;{searchQuery}&quot;. Coba cek kata kunci lain.
                </p>
              </motion.div>
            )}
          </div>
        </section>

      </div>

      {/* 4. Highly Polished Detail Modal Window */}
      <AnimatePresence>
        {selectedFacility && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/55 backdrop-blur-sm"
          >
            {/* Modal Body Container */}
            <motion.div
              initial={{ scale: 0.95, y: 15 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 15 }}
              transition={{ type: 'spring', damping: 25, stiffness: 350 }}
              className="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-150 max-w-xl w-full relative"
            >
              {/* Header block height color */}
              <div className="h-2.5 bg-[#019342]" />

              <div className="p-6 md:p-8">
                
                {/* Visual Bio Header */}
                <div className="relative aspect-[16/9] overflow-hidden rounded-2xl bg-slate-50 mb-6 shadow-sm">
                  <img
                    src={selectedFacility.image}
                    alt={selectedFacility.name}
                    className="w-full h-full object-cover"
                    referrerPolicy="no-referrer"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent" />
                  <div className="absolute bottom-4 left-4 text-white">
                    <span className="text-[10px] bg-emerald-500 font-extrabold px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-1.5 shadow-sm">
                      {selectedFacility. operationalHours}
                    </span>
                    <h3 className="text-lg md:text-xl font-black text-white leading-tight">
                      {selectedFacility.name}
                    </h3>
                  </div>
                </div>

                {/* Info List Fields */}
                <div className="space-y-4">
                  
                  {/* Long descriptive text */}
                  <div>
                    <h4 className="text-[10px] font-black text-[#191654] uppercase tracking-widest mb-1.5">Penjelasan &amp; Manfaat</h4>
                    <p className="text-xs sm:text-[13px] font-semibold text-slate-600 leading-relaxed">
                      {selectedFacility.longDesc}
                    </p>
                  </div>

                  {/* Technical Tags & Specs */}
                  <div className="bg-[#f8fafc] border border-slate-100 p-4 rounded-2xl">
                    <span className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 pl-1">Spesifikasi Standard &amp; Kelebihan</span>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      {selectedFacility.specs.map((item, i) => (
                        <div key={i} className="flex items-start gap-2 text-xs font-semibold text-slate-700 leading-tight">
                          <Check className="h-4 w-4 text-[#019342] shrink-0 mt-0.5" />
                          <span>{item}</span>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Capacity, Hours & Locations stats grid */}
                  <div className="grid grid-cols-3 gap-3 pt-2">
                    <div className="border border-slate-100 p-3 rounded-xl text-center">
                      <span className="block text-[9px] text-[#94a3b8] font-bold uppercase tracking-wider">Kapasitas</span>
                      <span className="text-xs font-extrabold text-[#191654] block mt-1 leading-none">{selectedFacility.capacity || 'Kondisional'}</span>
                    </div>
                    
                    <div className="border border-slate-100 p-3 rounded-xl text-center">
                      <span className="block text-[9px] text-[#94a3b8] font-bold uppercase tracking-wider">Jam Buka</span>
                      <span className="text-xs font-extrabold text-[#191654] block mt-1 leading-none">{selectedFacility.operationalHours}</span>
                    </div>

                    <div className="border border-slate-100 p-3 rounded-xl text-center">
                      <span className="block text-[9px] text-[#94a3b8] font-bold uppercase tracking-wider">Lokasi</span>
                      <span className="text-xs font-extrabold text-[#191654] block mt-1 leading-none truncate" title={selectedFacility.location}>{selectedFacility.location}</span>
                    </div>
                  </div>

                </div>

                {/* Footer Controls / Close Actions */}
                <div className="mt-8 border-t border-slate-100 pt-5 flex items-center justify-end gap-4">
                  <button
                    id="modal-close-facility-btn"
                    onClick={() => setSelectedFacility(null)}
                    className="w-full sm:w-auto py-2.5 px-6 rounded-xl bg-[#191654] text-white hover:bg-[#019342] transition-colors text-xs font-black uppercase tracking-widest text-center"
                  >
                    Tutup Sarana
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
