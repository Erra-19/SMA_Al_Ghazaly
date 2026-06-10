import { Announcement, EventActivity, Post, Testimonial, ProgramUnggulan, FaqItem } from './types';

export const ANNOUNCEMENTS: Announcement[] = [
  {
    id: "ann-1",
    title: "Penerimaan Peserta Didik Baru (PPDB) Online Tahun Pelajaran 2026/2027",
    category: "PPDB",
    date: "10 Juni 2026",
    summary: "Pendaftaran siswa baru SMA Al-Ghazaly Bogor gelombang pertama resmi dibuka secara online mulai tanggal 1 Juni sampai akhir Juli 2026.",
    content: "SMA Al-Ghazaly Bogor membuka pendaftaran untuk calon siswa baru tahun ajaran 2026/2027. Tersedia Program Peminatan MIPA dan IPS dengan kurikulum terintegrasi nilai-nilai keislaman dan teknologi modern. Keunggulan kami meliputi kurikulum Merdeka Belajar, program tahfidz super-intensif, pembinaan akhlak mulia, serta bimbingan masuk PTN favorit secara intensif. Silakan lengkapi berkas pendaftaran online Anda di portal PPDB kami.",
    status: "Penting",
    image: "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=600&auto=format&fit=crop",
    author: "Panitia PPDB 2026"
  },
  {
    id: "ann-2",
    title: "Pengumuman Libur Idul Adha 1447 H & Libur Akhir Semester Ganjil",
    category: "Libur",
    date: "8 Juni 2026",
    summary: "Sehubungan dengan perayaan Idul Adha 1447 H dan berakhirnya kalender akademik semester, kegiatan KBM diliburkan sementara.",
    content: "Diberitahukan kepada seluruh siswa, guru, dan staf SMA Al-Ghazaly Bogor bahwa kegiatan pembelajaran ditiadakan dalam rangka memperingati Hari Raya Idul Adha 1447 H dan Libur Kelulusan Semester. Siswa diharapkan tetap melaksanakan tugas mandiri dan muroja'ah hafalan Al-Qur'an di rumah masing-masing. Kegiatan sekolah akan aktif kembali seperti semula sesuai kalender akademik.",
    status: "Acara akan Datang",
    image: "https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=600&auto=format&fit=crop",
    author: "Humas Sekolah"
  },
  {
    id: "ann-3",
    title: "Kelulusan Seleksi Nasional Berdasarkan Prestasi (SNBP) PTN Favorit 2026",
    category: "Akademik",
    date: "25 Mei 2026",
    summary: "Selamat kepada 45 siswa-siswi SMA Al-Ghazaly yang dinyatakan LULUS dalam seleksi raport SNBP di ITB, UI, IPB, dan PTN Favorit lainnya.",
    content: "Alhamdulillahirabbil'alamin, keluarga besar SMA Al-Ghazaly Bogor berbangga atas pencapaian luar biasa siswa-siswi kelas XII pada SNBP tahun ini. Peningkatan persentase kelulusan tahun ini melonjak sebesar 15% dibanding tahun lalu. Ini membuktikan dedikasi program intensif bimbingan PTN yang diterapkan sejak kelas XI membuahkan hasil optimal. Semoga santri sekalian sukses menempuh jenjang pendidikan yang lebih tinggi.",
    status: "Penting",
    image: "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=600&auto=format&fit=crop",
    author: "Wakasek Kurikulum"
  },
  {
    id: "ann-4",
    title: "Alur Pembayaran Registrasi Akhir Gelombang I Calon Siswa Baru",
    category: "PPDB",
    date: "5 Juni 2026",
    summary: "Bagi calon peserta didik yang telah dinyatakan lulus seleksi administrasi PPDB Gelombang I, diharap segera melakukan registrasi ulang.",
    content: "Tata cara registrasi ulang calon siswa baru meliputi pembayaran uang pangkal sekolah dan seragam. Pembayaran dilakukan secara transfer virtual account Bank Syariah Indonesia (BSI) atau melalui loket pembayaran langsung di Yayasan Islamic Centre Al-Ghazaly dengan mematuhi protokol layanan sekolah yang berlaku.",
    status: "Acara akan Datang",
    image: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=600&auto=format&fit=crop",
    author: "Bagian Keuangan"
  }
];

export const EVENTS: EventActivity[] = [
  {
    id: "evt-1",
    title: "Sosialisasi & Simulasi PPDB Online Gelombang 1",
    date: { day: "22", month: "JUN", year: "2026" },
    category: "PPDB",
    location: "Aula Pertemuan Al-Ghazaly & Live Zoom",
    time: "08:00 - 11:30 WIB",
    description: "Pertemuan interaktif bagi calon orang tua murid untuk memandu langkah demi langkah pendaftaran, pengenalan sistem asrama maupun program non-asrama, serta kurikulum islami Al-Ghazaly."
  },
  {
    id: "evt-2",
    title: "Ujian Tahfidzul Qur'an Akhir Semester (Imtihan)",
    date: { day: "15", month: "JUL", year: "2026" },
    category: "Kurikulum",
    location: "Masjid Islamic Centre Al-Ghazaly",
    time: "07:30 - 15:00 WIB",
    description: "Evaluasi tahunan hafalan Al-Qur'an (Juz 30, Juz 1-5, dst) yang diuji langsung oleh dewan asatidzah pondok pesantren dan pakar qiraah Bogor."
  },
  {
    id: "evt-3",
    title: "Asesmen Sumatif Akhir Semester (ASAS) Ganjil",
    date: { day: "18", month: "NOV", year: "2026" },
    category: "Akademik",
    location: "Ruang Kelas SMA Al-Ghazaly",
    time: "07:00 - 12:30 WIB",
    description: "Pelaksanaan ujian akhir semester tertulis berbasis komputer & perangkat pintar lokal SMA Al-Ghazaly (CBT System) untuk mengevaluasi kompetensi kurikulum merdeka belajar."
  },
  {
    id: "evt-4",
    title: "Lomba Kompetensi Siswa Madani & Pameran Karya",
    date: { day: "20", month: "DES", year: "2026" },
    category: "Kegiatan",
    location: "Halaman Kompleks Al-Ghazaly",
    time: "08:00 - selesai",
    description: "Ajang unjuk kemampuan seni islami, stand-up comedy dakwah, robotik sains sederhana, serta bazar kewirausahaan siswa-siswi terpadu Al-Ghazaly."
  }
];

export const POSTS: Post[] = [
  {
    id: "post-1",
    title: "Menyiapkan Generasi Rabbani Di Era Digital: Tantangan & Solusi Orang Tua",
    category: "Opini & Artikel",
    date: "3 Juni 2026",
    excerpt: "Bagaimana cara menyeimbangkan penggunaan teknologi dengan penanaman budi pekerti luhur (Akhlakul Karimah) pada anak usia SMA? Simak kajian mendalam ini.",
    content: "Di era modern, teknologi laksana pisau bermata dua. SMA Al-Ghazaly Bogor menyelesaikannya dengan mengintegrasikan nilai mulia akhlak dan literasi teknologi islami yang membekali siswa dengan pemahaman dasar agama kuat sekaligus keterampilan analitik abad-21.",
    readTime: "5 Menit Baca",
    image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop"
  },
  {
    id: "post-2",
    title: "Kunjungan Ilmiah ke Laboratorium Antariksa dan Observatorium Bosscha",
    category: "Dokumentasi",
    date: "14 Mei 2026",
    excerpt: "Siswa kelas XI program MIPA melakukan observasi perbintangan langsung guna menyinergikan ilmu fisika astronomi modern dengan tafsir ayat Semesta.",
    content: "Kegiatan rutin luar kelas ini bertujuan melatih nalar kritis santri dalam membedah fenomena hisab dan astronomi praktis. Mengharmonisasikan firman Allah di Al-Qur'an tentang gugusan galaksi dengan penemuan teleskop terkini.",
    readTime: "4 Menit Baca",
    image: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=600&auto=format&fit=crop"
  },
  {
    id: "post-3",
    title: "Tips Lulus Seleksi PTN Melalui Jalur SNBT Bersama Guru BK Al-Ghazaly",
    category: "Tips & Trik",
    date: "10 April 2026",
    excerpt: "Langkah terprogram dan materi yang wajib dikuasai dari jauh hari agar santri siap menghadapi UTBK SNBT secara matang.",
    content: "Bimbingan konseling di SMA Al-Ghazaly didukung dengan program tryout intensif mingguan, sistem ranking adaptif, dan konseling personal pemilihan jurusan berbasis minat-bakat.",
    readTime: "8 Menit Baca",
    image: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=600&auto=format&fit=crop"
  }
];

export const TESTIMONIALS: Testimonial[] = [
  {
    id: "test-1",
    name: "Muhammad Fadhil Al-Fatih",
    university: "Institut Teknologi Bandung (ITB)",
    major: "Teknik Informatika",
    year: "Alumni 2024",
    quote: "Belajar di SMA Al-Ghazaly bukan sekadar mengejar nilai akademik berkualitas, tapi benar-benar diajarkan memiliki integritas moral. Hafalan tahfidz saya sangat membantu fokus mental saya saat kuliah.",
    avatar: "https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=200&auto=format&fit=crop"
  },
  {
    id: "test-2",
    name: "Aisyah Humaira S.Tr",
    university: "Universitas Indonesia (UI)",
    major: "Pendidikan Dokter",
    year: "Alumni 2023",
    quote: "Suasana kekeluargaan di Al-Ghazaly sangat hangat. Para pengajar tulus membimbing kami baik materi UTBK sains maupun akhlak pergaulan islami sehari-hari. Sangat mempersiapkan saya untuk berkiprah di dunia medis.",
    avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop"
  },
  {
    id: "test-3",
    name: "Ahmad Rayhan, S.H.",
    university: "Universitas Gadjah Mada (UGM)",
    major: "Ilmu Hukum",
    year: "Alumni 2022",
    quote: "Pondasi kedisiplinan dan nalar hukum islam yang diajarkan ustadz-ustadzah Al-Ghazaly menjadi modal berharga saya memenangkan kompetisi peradilan semu tingkat internasional di UGM.",
    avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop"
  },
  {
    id: "test-4",
    name: "Siti Sarah Maulida, S.Pd",
    university: "Universitas Negeri Jakarta (UNJ)",
    major: "Pendidikan Bahasa Arab",
    year: "Alumni 2021",
    quote: "Kemampuan bahasa asing terutama Bahasa Arab yang dilatih harian di SMA Al-Ghazaly mempermudah saya lulus seleksi beasiswa penuh kuliah dan lulus lebih cepat dengan predikat terbaik.",
    avatar: "https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?q=80&w=200&auto=format&fit=crop"
  }
];

export const PROGRAMS: ProgramUnggulan[] = [
  {
    id: "prog-1",
    title: "Tahfidzul Qur'an & Tafsir",
    description: "Kelompok bimbingan menghafal Al-Qur'an terprogram dengan target terukur, dilengkapi pendalaman nilai tafsir amaliyah.",
    icon: "BookOpen",
    color: "from-emerald-500 to-teal-600"
  },
  {
    id: "prog-2",
    title: "Akselerasi Bimbingan PTN",
    description: "Pendampingan persiapan Masuk Perguruan Tinggi Negeri sejak jenjang kelas X melatih kesiapan taktis ujian SNBP, SNBT, dan Ujian Mandiri.",
    icon: "GraduationCap",
    color: "from-green-600 to-emerald-700"
  },
  {
    id: "prog-3",
    title: "Sains Terapan & Robotika",
    description: "Pengembangan bakat sains modern melalui klub robotik kreatif, coding dasar, dan riset praktis di laboratorium modern.",
    icon: "Cpu",
    color: "from-emerald-600 to-emerald-800"
  },
  {
    id: "prog-4",
    title: "Leadership & Da'wah Training",
    description: "Melatih kepemimpinan berwibawa melalui panggung pidato multibahasa (Arab, Inggris, Indo) serta organisasi siswa (OSIS & Pramuka terpadu).",
    icon: "Users",
    color: "from-teal-500 to-green-600"
  }
];

export const FAQS: FaqItem[] = [
  {
    category: "PPDB",
    question: "Bagaimana cara melakukan pendaftaran online PPDB?",
    answer: "Sangat mudah! Buka portal pendaftaran klik 'Gabung PPDB 2026', isi formulir data calon siswa, lalu unggah salinan digital kartu keluarga, akta lahir, dan rapor SMP semester 1-5. Anda akan dipandu otomatis oleh sistem WhatsApp sekolah setelah mengisi formulir."
  },
  {
    category: "PPDB",
    question: "Apakah tersedia beasiswa untuk calon siswa baru?",
    answer: "Ya, kami menyediakan beasiswa prestasi berupa pemotongan biaya SPP / uang pangkal bagi calon siswa yang memiliki hafalan Al-Qur'an minimal 3 Juz, atau memiliki prestasi kejuaraan minimal tingkat Kota/Kabupaten di bidang akademik dan olahraga."
  },
  {
    category: "Akademik",
    question: "Kurikulum apa yang diterapkan di SMA Al-Ghazaly?",
    answer: "Kami menggunakan Kurikulum Merdeka Belajar dari Kementerian Pendidikan yang disinergikan secara adaptif dengan Kurikulum Pesantren dan Pendidikan Islami Yayasan Al-Ghazaly Bogor guna mencetak profil pelajar pancasila yang rabbani."
  },
  {
    category: "Umum",
    question: "Apakah asrama bersifat wajib bagi seluruh siswa?",
    answer: "Tidak. SMA Al-Ghazaly Bogor menyediakan dua program jalur pendaftaran: Jalur Fullday School (Pulang-Pergi) dan Jalur Boarding School (Mondok/Asrama terpadu) dengan fasilitas asrama putra dan putri secara terpisah."
  }
];
