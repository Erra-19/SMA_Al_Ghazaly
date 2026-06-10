import { useState, FormEvent, ChangeEvent } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  CheckCircle, 
  ArrowRight, 
  ArrowLeft, 
  User, 
  BookOpen, 
  Users, 
  Heart, 
  Activity, 
  Send, 
  FileText, 
  Camera, 
  Download,
  AlertCircle,
  Clock,
  QrCode,
  Building
} from 'lucide-react';
import { submitRegistration } from '../api';

// Form interface definition
interface RegistrationData {
  // Step 1: Registrasi Peserta Didik
  jenisPendaftaran: string;
  noPesertaUn: string;
  noSkhun: string;
  noIjazah: string;
  
  // Step 2: Data Pribadi
  namaLengkap: string;
  jenisKelamin: string;
  nisn: string;
  nik: string;
  tempatLahir: string;
  tanggalLahir: string;
  agama: string;
  kebutuhanKhusus: string;
  alamatJalan: string;
  rt: string;
  rw: string;
  namaDusun: string;
  namaKelurahan: string;
  kecamatan: string;
  kodePos: string;
  tinggalBersama: string;
  transportasi: string;
  noHp: string;
  email: string;

  // Step 3: Data Ayah Kandung
  namaAyah: string;
  tahunLahirAyah: string;
  pendidikanAyah: string;
  pekerjaanAyah: string;
  penghasilanAyah: string;
  berkebutuhanKhususAyah: string;

  // Step 4: Data Ibu Kandung
  namaIbu: string;
  tahunLahirIbu: string;
  pendidikanIbu: string;
  pekerjaanIbu: string;
  penghasilanIbu: string;
  berkebutuhanKhususIbu: string;

  // Step 5: Data Wali (Optional)
  mempunyaiWali: string; // 'ya' | 'tidak'
  namaWali: string;
  tahunLahirWali: string;
  pendidikanWali: string;
  pekerjaanWali: string;
  penghasilanWali: string;

  // Step 6: Data Periodik
  tinggiBadan: string;
  beratBadan: string;
  jarakSekolah: string; // '<= 1 KM' | '> 1 KM'
  jarakSekolahKm: string;
  waktuTempuh: string;
  jumlahSaudaraKandung: string;
}

const initialFormData: RegistrationData = {
  jenisPendaftaran: 'Siswa Baru',
  noPesertaUn: '',
  noSkhun: '',
  noIjazah: '',

  namaLengkap: '',
  jenisKelamin: '',
  nisn: '',
  nik: '',
  tempatLahir: '',
  tanggalLahir: '',
  agama: 'Islam',
  kebutuhanKhusus: 'Tidak Ada',
  alamatJalan: '',
  rt: '',
  rw: '',
  namaDusun: '',
  namaKelurahan: '',
  kecamatan: 'Bogor Tengah',
  kodePos: '',
  tinggalBersama: 'Orang Tua',
  transportasi: 'Kendaraan Pribadi',
  noHp: '',
  email: '',

  namaAyah: '',
  tahunLahirAyah: '',
  pendidikanAyah: 'SLTA / Sederajat',
  pekerjaanAyah: 'Karyawan Swasta',
  penghasilanAyah: 'Rp. 3.000.000 - Rp. 5.000.000',
  berkebutuhanKhususAyah: 'Tidak Ada',

  namaIbu: '',
  tahunLahirIbu: '',
  pendidikanIbu: 'SLTA / Sederajat',
  pekerjaanIbu: 'Ibu Rumah Tangga',
  penghasilanIbu: 'Tidak Berpenghasilan',
  berkebutuhanKhususIbu: 'Tidak Ada',

  mempunyaiWali: 'tidak',
  namaWali: '',
  tahunLahirWali: '',
  pendidikanWali: '',
  pekerjaanWali: '',
  penghasilanWali: '',

  tinggiBadan: '',
  beratBadan: '',
  jarakSekolah: '<= 1 KM',
  jarakSekolahKm: '1',
  waktuTempuh: '10',
  jumlahSaudaraKandung: '0',
};

const STEPS = [
  { id: 1, label: 'REGISTRASI PESERTA DIDIK', shortLabel: 'Registrasi' },
  { id: 2, label: 'DATA PRIBADI', shortLabel: 'Pribadi' },
  { id: 3, label: 'DATA AYAH KANDUNG', shortLabel: 'Ayah' },
  { id: 4, label: 'DATA IBU KANDUNG', shortLabel: 'Ibu' },
  { id: 5, label: 'DATA WALI', shortLabel: 'Wali' },
  { id: 6, label: 'DATA PERIODIK', shortLabel: 'Periodik' },
  { id: 7, label: 'SELESAI', shortLabel: 'Selesai' },
];

export default function SchoolRegistrationForm() {
  const [currentStep, setCurrentStep] = useState(1);
  const [formData, setFormData] = useState<RegistrationData>(initialFormData);
  const [registrationCode, setRegistrationCode] = useState<string>('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [validationError, setValidationError] = useState<string | null>(null);

  const handleInputChange = (
    e: ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setValidationError(null);
  };

  const validateStep = (step: number): boolean => {
    switch (step) {
      case 1:
        if (!formData.jenisPendaftaran) {
          setValidationError('Jenis Pendaftaran wajib ditentukan.');
          return false;
        }
        if (!formData.noPesertaUn) {
          setValidationError('Nomor Peserta UN Sebelumnya wajib diisi.');
          return false;
        }
        if (!formData.noSkhun) {
          setValidationError('Nomor Seri SKHUN Sebelumnya wajib diisi.');
          return false;
        }
        if (!formData.noIjazah) {
          setValidationError('Nomor Seri Ijazah Sebelumnya wajib diisi.');
          return false;
        }
        break;
      case 2:
        if (!formData.namaLengkap.trim()) {
          setValidationError('Nama Lengkap wajib diisi.');
          return false;
        }
        if (!formData.jenisKelamin) {
          setValidationError('Jenis Kelamin wajib dipilih.');
          return false;
        }
        if (!formData.nisn || formData.nisn.length < 10) {
          setValidationError('NISN wajib diisi dengan format 10 digit angka.');
          return false;
        }
        if (!formData.nik || formData.nik.length < 16) {
          setValidationError('NIK wajib diisi dengan format 16 digit angka sesuai KTP/KK.');
          return false;
        }
        if (!formData.tempatLahir.trim()) {
          setValidationError('Tempat Lahir wajib diisi.');
          return false;
        }
        if (!formData.tanggalLahir) {
          setValidationError('Tanggal Lahir wajib ditentukan.');
          return false;
        }
        if (!formData.alamatJalan.trim()) {
          setValidationError('Alamat Jalan wajib diisi.');
          return false;
        }
        if (!formData.noHp.trim()) {
          setValidationError('Nomor Handphone yang dapat dihubungi wajib diisi.');
          return false;
        }
        break;
      case 3:
        if (!formData.namaAyah.trim()) {
          setValidationError('Nama Ayah Kandung wajib diisi.');
          return false;
        }
        break;
      case 4:
        if (!formData.namaIbu.trim()) {
          setValidationError('Nama Ibu Kandung wajib diisi.');
          return false;
        }
        break;
      case 5:
        if (formData.mempunyaiWali === 'ya') {
          if (!formData.namaWali.trim()) {
            setValidationError('Nama Wali harus diisi jika Anda memilih mempunyai Wali.');
            return false;
          }
        }
        break;
      case 6:
        if (!formData.tinggiBadan || isNaN(Number(formData.tinggiBadan))) {
          setValidationError('Tinggi Badan harus berupa angka valid (cm).');
          return false;
        }
        if (!formData.beratBadan || isNaN(Number(formData.beratBadan))) {
          setValidationError('Berat Badan harus berupa angka valid (kg).');
          return false;
        }
        break;
    }
    return true;
  };

  const handleNext = () => {
    if (validateStep(currentStep)) {
      setValidationError(null);
      setCurrentStep((prev) => Math.min(prev + 1, 7));
      window.scrollTo({ top: 300, behavior: 'smooth' });
    }
  };

  const handlePrev = () => {
    setValidationError(null);
    setCurrentStep((prev) => Math.max(prev - 1, 1));
    window.scrollTo({ top: 300, behavior: 'smooth' });
  };

  const registrationPayload = () => ({
    jenis_pendaftaran: formData.jenisPendaftaran,
    no_peserta_un: formData.noPesertaUn,
    no_skhun: formData.noSkhun,
    no_ijazah: formData.noIjazah,
    student_name: formData.namaLengkap,
    gender: formData.jenisKelamin,
    nisn: formData.nisn,
    nik: formData.nik,
    birth_place: formData.tempatLahir,
    birth_date: formData.tanggalLahir,
    agama: formData.agama,
    kebutuhan_khusus: formData.kebutuhanKhusus,
    address: formData.alamatJalan,
    rt: formData.rt,
    rw: formData.rw,
    nama_dusun: formData.namaDusun,
    nama_kelurahan: formData.namaKelurahan,
    kecamatan: formData.kecamatan,
    kode_pos: formData.kodePos,
    tinggal_bersama: formData.tinggalBersama,
    transportasi: formData.transportasi,
    phone: formData.noHp,
    email: formData.email,
    previous_school: 'Belum diisi',
    academic_year: '2026/2027',
    wave: 'Gelombang 1',
    major_choice: 'Belum memilih',
    nama_ayah: formData.namaAyah,
    tahun_lahir_ayah: formData.tahunLahirAyah,
    pendidikan_ayah: formData.pendidikanAyah,
    pekerjaan_ayah: formData.pekerjaanAyah,
    penghasilan_ayah: formData.penghasilanAyah,
    nama_ibu: formData.namaIbu,
    tahun_lahir_ibu: formData.tahunLahirIbu,
    pendidikan_ibu: formData.pendidikanIbu,
    pekerjaan_ibu: formData.pekerjaanIbu,
    penghasilan_ibu: formData.penghasilanIbu,
    mempunyai_wali: formData.mempunyaiWali === 'ya',
    nama_wali: formData.namaWali,
    tahun_lahir_wali: formData.tahunLahirWali,
    pendidikan_wali: formData.pendidikanWali,
    pekerjaan_wali: formData.pekerjaanWali,
    penghasilan_wali: formData.penghasilanWali,
    tinggi_badan: formData.tinggiBadan,
    berat_badan: formData.beratBadan,
    jarak_sekolah: formData.jarakSekolah,
    jarak_sekolah_km: formData.jarakSekolahKm,
    waktu_tempuh: formData.waktuTempuh,
    jumlah_saudara_kandung: formData.jumlahSaudaraKandung,
    parent_name: formData.namaAyah || formData.namaIbu || formData.namaWali,
    parent_phone: formData.noHp,
  });

  const handleSubmitRegistration = async (e: FormEvent) => {
    e.preventDefault();
    if (!validateStep(6)) return;

    setIsSubmitting(true);
    setValidationError(null);

    try {
      const response = await submitRegistration(registrationPayload());
      setRegistrationCode(response.registration_number);
      setIsSubmitting(false);
      setCurrentStep(7);
      window.scrollTo({ top: 200, behavior: 'smooth' });
    } catch (error) {
      setValidationError(error instanceof Error ? error.message : 'Pendaftaran belum bisa dikirim. Coba lagi sebentar lagi.');
      setIsSubmitting(false);
    }
  };

  const handleReset = () => {
    setFormData(initialFormData);
    setRegistrationCode('');
    setCurrentStep(1);
    setValidationError(null);
  };

  return (
    <div id="ppdb-registration-root" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-20">
      
      {/* 1. PPDB Online Hero Banner */}
      <div 
        id="ppdb-hero-section" 
        className="relative overflow-hidden bg-gradient-to-br from-[#019342] to-[#120f3a] text-white py-14 px-4 sm:px-6 lg:px-8 mb-12 shadow-sm"
      >
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]" opacity-10="" />
        
        <div className="relative mx-auto max-w-7xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
          <div className="bg-[#019342] border-l-4 border-emerald-400 p-6 md:p-8 max-w-xl shadow-lg rounded-2xl">
            <h1 className="text-3xl md:text-4xl font-black tracking-tight leading-none text-white">
              PPDB Online
            </h1>
            <p className="mt-2 text-xs md:text-sm text-emerald-50 font-bold leading-relaxed">
              Penerimaan Peserta Didik Baru (PPDB) Sekolah Menengah Atas Al-Ghazaly Berbasis Sistem Informasi Terintegrasi Yayasan Islamic Center Al-Ghazaly Bogor.
            </p>
          </div>
          
          <div className="text-right shrink-0 bg-white/10 backdrop-blur border border-white/20 p-4 rounded-xl hidden lg:block">
            <span className="text-[10px] uppercase font-black tracking-wider text-emerald-300 block">Tahun Ajaran Aktif</span>
            <span className="text-lg font-black block text-white">2026 / 2027</span>
            <span className="text-[9px] text-zinc-300 font-semibold mt-1 block">Kuota Terbatas &bull; Seleksi Administrasi</span>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-5xl px-4 sm:px-6">
        
        {/* 2. Wizard Breadcrumb Step Navigation */}
        <div id="ppdb-wizard-steps-container" className="mb-12 bg-white border border-slate-150 rounded-2xl p-6 shadow-[0_5px_15px_rgba(0,0,0,0.01)]">
          
          <div className="relative flex items-center justify-between">
            {/* Background line behind circles */}
            <div className="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-100 z-0" />
            
            {/* Dynamic Step Rings */}
            {STEPS.map((step, index) => {
              const isCompleted = currentStep > step.id;
              const isActive = currentStep === step.id;
              
              return (
                <div key={step.id} className="relative z-10 flex flex-col items-center">
                  <div
                    id={`wizard-step-indicator-${step.id}`}
                    className={`h-9 w-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border ${
                      isActive 
                        ? 'bg-[#019342] text-white border-[#019342] ring-4 ring-emerald-50 scale-110'
                        : isCompleted
                        ? 'bg-emerald-50 text-[#019342] border-emerald-200'
                        : 'bg-white text-slate-400 border-slate-200'
                    }`}
                  >
                    {isCompleted ? <CheckCircle className="h-4 w-4" /> : step.id}
                  </div>
                  
                  <span className="text-[8.5px] font-black tracking-wider text-slate-400 uppercase mt-2 text-center max-w-[90px] hidden md:block select-none leading-tight">
                    {step.shortLabel}
                  </span>
                </div>
              );
            })}
          </div>

          <div className="text-center mt-6 pt-4 border-t border-slate-50">
            <span id="wizard-current-title" className="text-xs font-black tracking-wider text-emerald-700 uppercase bg-emerald-50 px-4 py-1.5 rounded-full inline-block">
              Langkah {currentStep} dari 7: {STEPS[currentStep - 1].label}
            </span>
          </div>
        </div>

        {/* 3. Main Form Area */}
        <div className="bg-white border border-slate-150 rounded-3xl p-6 sm:p-10 shadow-[0_15px_35px_rgba(0,0,0,0.015)] relative">
          
          {/* Validation Alert Box */}
          {validationError && (
            <div className="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center gap-2">
              <AlertCircle className="h-5 w-5 text-rose-500 shrink-0" />
              <span>{validationError}</span>
            </div>
          )}

          {/* Steps Contents */}
          <form onSubmit={handleSubmitRegistration} className="space-y-8">
            
            {/* STEP 1: Registrasi Peserta Didik */}
            {currentStep === 1 && (
              <div id="step-content-1" className="space-y-6 animate-fade-in">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <BookOpen className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 1: Hubungan &amp; Registrasi Sekolah</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Sediakan informasi jenis pendaftaran dan nomor seri dokumen pendidikan sebelumnya.</p>
                </div>

                <div className="space-y-5">
                  <div>
                    <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Jenis Pendaftaran <span className="text-rose-500">*</span></label>
                    <select
                      name="jenisPendaftaran"
                      value={formData.jenisPendaftaran}
                      onChange={handleInputChange}
                      className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-bold"
                    >
                      <option value="Siswa Baru">Siswa Baru (Lulusan SMP/MTs)</option>
                      <option value="Pindahan">Siswa Pindahan (Mutasi)</option>
                      <option value="Kembali Bersekolah">Kembali Bersekolah</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nomor Peserta UN Sebelumnya <span className="text-rose-500">*</span></label>
                    <input
                      type="text"
                      name="noPesertaUn"
                      required
                      value={formData.noPesertaUn}
                      onChange={handleInputChange}
                      placeholder="Contoh: 2-19-02-05-001-102-3"
                      className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                    />
                    <p className="text-[10px] text-slate-400 font-bold mt-1">Input 20 digit nomor ujian nasional SMP/MTs Anda.</p>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nomor Seri SKHUN Sebelumnya <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="noSkhun"
                        required
                        value={formData.noSkhun}
                        onChange={handleInputChange}
                        placeholder="Contoh: DN-01 DI 1029302"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nomor Seri Ijazah Sebelumnya <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="noIjazah"
                        required
                        value={formData.noIjazah}
                        onChange={handleInputChange}
                        placeholder="Contoh: DN-01/D-SMP/06/10294"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* STEP 2: Data Pribadi */}
            {currentStep === 2 && (
              <div id="step-content-2" className="space-y-6 animate-fade-in block">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <User className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 2: Data Pribadi Calon Siswa</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Sediakan data kependudukan dan identitas diri lengkap Anda sesuai dengan Akta Kelahiran.</p>
                </div>

                <div className="space-y-5">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="namaLengkap"
                        required
                        value={formData.namaLengkap}
                        onChange={handleInputChange}
                        placeholder="Sesuai Akta Kelahiran"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Jenis Kelamin <span className="text-rose-500">*</span></label>
                      <select
                        name="jenisKelamin"
                        required
                        value={formData.jenisKelamin}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-bold"
                      >
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">NISN (10 Digit) <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="nisn"
                        required
                        maxLength={10}
                        value={formData.nisn}
                        onChange={handleInputChange}
                        placeholder="Nomor Induk Siswa Nasional"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">NIK / No. KTP (16 Digit) <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="nik"
                        required
                        maxLength={16}
                        value={formData.nik}
                        onChange={handleInputChange}
                        placeholder="Nomor Induk Kependudukan"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div className="sm:col-span-2">
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tempat Lahir <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="tempatLahir"
                        required
                        value={formData.tempatLahir}
                        onChange={handleInputChange}
                        placeholder="Contoh: Bogor"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tanggal Lahir <span className="text-rose-500">*</span></label>
                      <input
                        type="date"
                        name="tanggalLahir"
                        required
                        value={formData.tanggalLahir}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Alamat Email Aktif <span className="text-rose-500">*</span></label>
                      <input
                        type="email"
                        name="email"
                        required
                        value={formData.email}
                        onChange={handleInputChange}
                        placeholder="nama@domain.com"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">No. HP / WhatsApp <span className="text-rose-500">*</span></label>
                      <input
                        type="tel"
                        name="noHp"
                        required
                        value={formData.noHp}
                        onChange={handleInputChange}
                        placeholder="Contoh: 0812XXXXXXXX"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Alamat Rumah Lengkap (Beserta No. Rumah / Gang) <span className="text-rose-500">*</span></label>
                    <input
                      type="text"
                      name="alamatJalan"
                      required
                      value={formData.alamatJalan}
                      onChange={handleInputChange}
                      placeholder="Contoh: Jl. Merdeka No 12, Kelurahan Ciwaringin"
                      className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                    />
                  </div>

                  <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">RT</label>
                      <input
                        type="text"
                        name="rt"
                        value={formData.rt}
                        onChange={handleInputChange}
                        placeholder="001"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none text-slate-800"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">RW</label>
                      <input
                        type="text"
                        name="rw"
                        value={formData.rw}
                        onChange={handleInputChange}
                        placeholder="004"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Kecamatan</label>
                      <input
                        type="text"
                        name="kecamatan"
                        value={formData.kecamatan}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Kode Pos</label>
                      <input
                        type="text"
                        name="kodePos"
                        value={formData.kodePos}
                        onChange={handleInputChange}
                        placeholder="16125"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800"
                      />
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* STEP 3: Data Ayah Kandung */}
            {currentStep === 3 && (
              <div id="step-content-3" className="space-y-6 animate-fade-in block">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <Users className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 3: Identitas Ayah Kandung</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Sediakan informasi kependudukan dan pekerjaan wali ayah kandung.</p>
                </div>

                <div className="space-y-5">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap Ayah Kandung <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="namaAyah"
                        required
                        value={formData.namaAyah}
                        onChange={handleInputChange}
                        placeholder="Sesuai Akta Kelahiran Siswa / KK"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tahun Lahir Ayah</label>
                      <input
                        type="text"
                        name="tahunLahirAyah"
                        maxLength={4}
                        value={formData.tahunLahirAyah}
                        onChange={handleInputChange}
                        placeholder="Contoh: 1975"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pendidikan Terakhir</label>
                      <select
                        name="pendidikanAyah"
                        value={formData.pendidikanAyah}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                      >
                        <option value="Tidak Sekolah">Tidak Sekolah</option>
                        <option value="SD / Sederajat">SD / Sederajat</option>
                        <option value="SMP / Sederajat">SMP / Sederajat</option>
                        <option value="SLTA / Sederajat">SLTA / Sederajat</option>
                        <option value="Diploma (D1 - D4)">Diploma (D1 - D4)</option>
                        <option value="Sarjana S1">Sarjana S1</option>
                        <option value="Magister S2 / S3">Magister S2 / S3</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pekerjaan Utama</label>
                      <select
                        name="pekerjaanAyah"
                        value={formData.pekerjaanAyah}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                      >
                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                        <option value="PNS / TNI / POLRI">PNS / TNI / POLRI</option>
                        <option value="Wiraswasta / Pedagang">Wiraswasta / Pedagang</option>
                        <option value="Petani / Nelayan / Buruh">Petani / Nelayan / Buruh</option>
                        <option value="Pensiunan">Pensiunan</option>
                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Penghasilan Bulanan Ayah</label>
                    <select
                      name="penghasilanAyah"
                      value={formData.penghasilanAyah}
                      onChange={handleInputChange}
                      className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                    >
                      <option value="Kurang dari Rp. 1.000.000">Kurang dari Rp. 1.000.000</option>
                      <option value="Rp. 1.000.000 - Rp. 3.000.000">Rp. 1.000.000 - Rp. 3.000.000</option>
                      <option value="Rp. 3.000.000 - Rp. 5.000.000">Rp. 3.000.000 - Rp. 5.000.000</option>
                      <option value="Rp. 5.050.000 - Rp. 20.000.000">Rp. 5.000.000 - Rp. 20.000.000</option>
                      <option value="Lebih dari Rp. 20.000.000">Lebih dari Rp. 20.000.000</option>
                    </select>
                  </div>
                </div>
              </div>
            )}

            {/* STEP 4: Data Ibu Kandung */}
            {currentStep === 4 && (
              <div id="step-content-4" className="space-y-6 animate-fade-in block">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <Heart className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 4: Identitas Ibu Kandung</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Nama Ibu Kandung wajib disi lengkap dan akurat sesuai Kartu Keluarga untuk verifikasi pusat Dapodik.</p>
                </div>

                <div className="space-y-5">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap Ibu Kandung <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="namaIbu"
                        required
                        value={formData.namaIbu}
                        onChange={handleInputChange}
                        placeholder="Sesuai Kartu Keluarga"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tahun Lahir Ibu</label>
                      <input
                        type="text"
                        name="tahunLahirIbu"
                        maxLength={4}
                        value={formData.tahunLahirIbu}
                        onChange={handleInputChange}
                        placeholder="Contoh: 1980"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pendidikan Terakhir</label>
                      <select
                        name="pendidikanIbu"
                        value={formData.pendidikanIbu}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                      >
                        <option value="Tidak Sekolah">Tidak Sekolah</option>
                        <option value="SD / Sederajat">SD / Sederajat</option>
                        <option value="SMP / Sederajat">SMP / Sederajat</option>
                        <option value="SLTA / Sederajat">SLTA / Sederajat</option>
                        <option value="Diploma (D1 - D4)">Diploma (D1 - D4)</option>
                        <option value="Sarjana S1">Sarjana S1</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pekerjaan Utama</label>
                      <select
                        name="pekerjaanIbu"
                        value={formData.pekerjaanIbu}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                      >
                        <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                        <option value="PNS / POLRI">PNS / POLRI</option>
                        <option value="Wiraswasta / Pedagang">Wiraswasta / Pedagang</option>
                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Penghasilan Bulanan Ibu</label>
                    <select
                      name="penghasilanIbu"
                      value={formData.penghasilanIbu}
                      onChange={handleInputChange}
                      className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                    >
                      <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                      <option value="Kurang dari Rp. 1.000.000">Kurang dari Rp. 1.000.000</option>
                      <option value="Rp. 1.000.000 - Rp. 3.000.000">Rp. 1.000.000 - Rp. 3.000.000</option>
                      <option value="Rp. 3.000.000 - Rp. 5.000.000">Rp. 3.000.000 - Rp. 5.000.000</option>
                      <option value="Lebih dari Rp. 5.000.000">Lebih dari Rp. 5.000.000</option>
                    </select>
                  </div>
                </div>
              </div>
            )}

            {/* STEP 5: Data Wali (Optional) */}
            {currentStep === 5 && (
              <div id="step-content-5" className="space-y-6 animate-fade-in block">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <Users className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 5: Data Wali (Jika Ada)</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Dapat dikosongkan jika Calon Siswa tinggal bersama orang tua kandung biologisnya.</p>
                </div>

                <div className="space-y-5">
                  <div className="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                    <label className="block text-[10px] font-black uppercase text-[#191654] tracking-wider mb-3">Apakah Anda diwakili oleh Wali?</label>
                    <div className="flex items-center gap-6">
                      <label className="flex items-center gap-2 text-xs font-black text-slate-800 cursor-pointer">
                        <input
                          type="radio"
                          name="mempunyaiWali"
                          value="tidak"
                          checked={formData.mempunyaiWali === 'tidak'}
                          onChange={handleInputChange}
                          className="h-4 w-4 text-[#019342] focus:ring-[#019342]"
                        />
                        <span>Tidak (Tinggal dengan Orang Tua)</span>
                      </label>
                      <label className="flex items-center gap-2 text-xs font-black text-slate-800 cursor-pointer">
                        <input
                          type="radio"
                          name="mempunyaiWali"
                          value="ya"
                          checked={formData.mempunyaiWali === 'ya'}
                          onChange={handleInputChange}
                          className="h-4 w-4 text-[#019342] focus:ring-[#019342]"
                        />
                        <span>Ya, Diwakili Wali</span>
                      </label>
                    </div>
                  </div>

                  {formData.mempunyaiWali === 'ya' && (
                    <div className="space-y-5 p-5 bg-emerald-50/20 border border-emerald-100 rounded-2xl animate-fade-in">
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                          <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap Wali <span className="text-rose-500">*</span></label>
                          <input
                            type="text"
                            name="namaWali"
                            required={formData.mempunyaiWali === 'ya'}
                            value={formData.namaWali}
                            onChange={handleInputChange}
                            placeholder="Contoh: Muhammad Jafar"
                            className="w-full px-4 py-3 rounded-xl text-xs bg-white border border-slate-200 text-slate-800 font-semibold"
                          />
                        </div>
                        <div>
                          <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tahun Lahir Wali</label>
                          <input
                            type="text"
                            name="tahunLahirWali"
                            maxLength={4}
                            value={formData.tahunLahirWali}
                            onChange={handleInputChange}
                            placeholder="Contoh: 1982"
                            className="w-full px-4 py-3 rounded-xl text-xs bg-white border border-slate-200 text-slate-800"
                          />
                        </div>
                      </div>

                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                          <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pendidikan Terakhir Wali</label>
                          <input
                            type="text"
                            name="pendidikanWali"
                            value={formData.pendidikanWali}
                            onChange={handleInputChange}
                            placeholder="Contoh: S1 Sosial"
                            className="w-full px-4 py-3 rounded-xl text-xs bg-white border border-slate-200 text-slate-800"
                          />
                        </div>
                        <div>
                          <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Pekerjaan Wali</label>
                          <input
                            type="text"
                            name="pekerjaanWali"
                            value={formData.pekerjaanWali}
                            onChange={handleInputChange}
                            placeholder="Contoh: Wiraswasta"
                            className="w-full px-4 py-3 rounded-xl text-xs bg-white border border-slate-200 text-slate-800"
                          />
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}

            {/* STEP 6: Data Periodik */}
            {currentStep === 6 && (
              <div id="step-content-6" className="space-y-6 animate-fade-in block">
                <div className="border-b border-slate-100 pb-3">
                  <h3 className="text-base font-black text-[#191654] flex items-center gap-2">
                    <Activity className="h-4.5 w-4.5 text-[#019342]" />
                    <span>Langkah 6: Data Periodik Fisik Calon Siswa</span>
                  </h3>
                  <p className="subtitle-text text-[11px] text-slate-400 font-bold mt-1">Ukur fisik berkala siswa untuk pendataan sosiometri dan statistik sekolah.</p>
                </div>

                <div className="space-y-5">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Tinggi Badan (cm) <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="tinggiBadan"
                        required
                        value={formData.tinggiBadan}
                        onChange={handleInputChange}
                        placeholder="Contoh: 168"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Berat Badan (kg) <span className="text-rose-500">*</span></label>
                      <input
                        type="text"
                        name="beratBadan"
                        required
                        value={formData.beratBadan}
                        onChange={handleInputChange}
                        placeholder="Contoh: 58"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Jarak Tempat Tinggal ke Sekolah</label>
                      <select
                        name="jarakSekolah"
                        value={formData.jarakSekolah}
                        onChange={handleInputChange}
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-bold"
                      >
                        <option value="<= 1 KM">Kurang dari atau sama dengan 1 Kilometer (&lt;= 1 KM)</option>
                        <option value="> 1 KM">Lebih dari 1 Kilometer (&gt; 1 KM)</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Jumlah Saudara Kandung</label>
                      <input
                        type="number"
                        name="jumlahSaudaraKandung"
                        min={0}
                        value={formData.jumlahSaudaraKandung}
                        onChange={handleInputChange}
                        placeholder="Contoh: 2"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 font-semibold"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Estimasi Jarak Detail (KM)</label>
                      <input
                        type="text"
                        name="jarakSekolahKm"
                        value={formData.jarakSekolahKm}
                        onChange={handleInputChange}
                        placeholder="Contoh: 2"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800"
                      />
                    </div>
                    <div>
                      <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Waktu Tempuh Perjalanan (Menit)</label>
                      <input
                        type="text"
                        name="waktuTempuh"
                        value={formData.waktuTempuh}
                        onChange={handleInputChange}
                        placeholder="Contoh: 15"
                        className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800"
                      />
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* STEP 7: Selesai & Ringkasan */}
            {currentStep === 7 && registrationCode && (
              <div id="step-content-7" className="space-y-8 animate-fade-in block">
                <div className="text-center space-y-3 pb-6 border-b border-slate-100">
                  <div className="mx-auto h-14 w-14 bg-emerald-50 rounded-full border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-sm animate-bounce-slow">
                    <CheckCircle className="h-8 w-8" />
                  </div>
                  <h3 className="text-xl font-black text-[#191654] tracking-tight">Formulir Pendaftaran Berhasil Disubmit!</h3>
                  <p className="text-xs text-slate-400 font-semibold max-w-md mx-auto">
                    Data calon peserta didik sukses diproses dan disimpan di Server Pendaftaran Terpusat SMA Al-Ghazaly Bogor.
                  </p>
                </div>

                {/* Print Layout Card */}
                <div className="border border-slate-200 rounded-3xl overflow-hidden bg-slate-50/50">
                  
                  {/* Digital Code Receipt Header */}
                  <div className="bg-gradient-to-r from-[#019342] to-[#191654] text-white p-6 justify-between items-center flex flex-wrap gap-4">
                    <div>
                      <span className="text-[10px] font-black uppercase text-emerald-300 tracking-widest block">No. Registrasi PPDB</span>
                      <span id="receipt-reg-code" className="text-base md:text-lg font-black tracking-wider block font-mono">{registrationCode}</span>
                    </div>

                    <div className="text-right shrink-0">
                      <span className="text-[9px] font-bold block opacity-75">Tgl Pendaftaran</span>
                      <span className="text-xs font-black block font-mono">
                        {new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                      </span>
                    </div>
                  </div>

                  {/* Summary of Data */}
                  <div className="p-6 md:p-8 space-y-6 bg-white">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-650 font-semibold">
                      
                      <div className="space-y-3">
                        <h4 className="text-[10px] font-black uppercase text-slate-400 tracking-wider pb-1.5 border-b border-slate-100">DATA PENDAFTAR</h4>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Nama Calon Siswa</span>
                          <span className="font-extrabold text-[#191654] text-right">{formData.namaLengkap}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">NISN</span>
                          <span className="font-bold text-slate-705 font-mono">{formData.nisn}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">NIK / No. KTP</span>
                          <span className="font-bold text-slate-705 font-mono">{formData.nik}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Jenis Kelamin</span>
                          <span className="font-bold text-slate-705">{formData.jenisKelamin}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">TTL</span>
                          <span className="font-bold text-slate-705 text-right">{formData.tempatLahir}, {formData.tanggalLahir}</span>
                        </div>
                      </div>

                      <div className="space-y-3">
                        <h4 className="text-[10px] font-black uppercase text-slate-400 tracking-wider pb-1.5 border-b border-slate-100">INFORMASI SEKOLAH</h4>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Sekolah Pilihan</span>
                          <span className="font-black text-emerald-700">SMA AL-GHAZALY BOGOR</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Status Registrasi</span>
                          <span className="font-black px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[9.5px] uppercase tracking-wider">Verifikasi Berkas</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Ayah Kandung</span>
                          <span className="font-bold text-slate-705">{formData.namaAyah}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">Ibu Kandung</span>
                          <span className="font-bold text-slate-705">{formData.namaIbu}</span>
                        </div>
                        <div className="flex justify-between py-1 border-b border-slate-50">
                          <span className="text-slate-400">No. HP / WhatsApp</span>
                          <span className="font-bold text-slate-705 font-mono">{formData.noHp}</span>
                        </div>
                      </div>

                    </div>

                    {/* QR Code and Next Info Box */}
                    <div className="pt-6 border-t border-slate-100 flex flex-col md:flex-row items-center gap-6 bg-emerald-50/20 p-5 rounded-2xl border border-emerald-50">
                      <div className="h-24 w-24 shrink-0 bg-white border border-slate-200 rounded-xl p-2 flex items-center justify-center shadow-inner">
                        <QrCode className="h-full w-full text-slate-800" />
                      </div>

                      <div className="space-y-1">
                        <h5 className="text-xs font-black text-[#191654]">Langkah Selanjutnya yang Wajib Dilakukan:</h5>
                        <ol className="list-decimal list-inside text-[11px] text-slate-505 font-semibold space-y-1 leading-relaxed">
                          <li>Hubungi Sekretariat PPDB via WhatsApp dengan menunjukkan Nomor Registrasi di atas untuk diverifikasi instan.</li>
                          <li>Siapkan fotokopi Akta Kelahiran, Kartu Keluarga (KK), dan Ijazah / SKHUN SMP (legalisir asli) masing-masing 2 lembar.</li>
                          <li>Bawalah seluruh berkas fisik tersebut bersama cetak bukti pendaftaran ini langsung ke Ruang Administrasi/TU sesuai arahan panitia.</li>
                        </ol>
                      </div>
                    </div>

                  </div>
                </div>

                <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                  <button
                    id="print-form-action"
                    onClick={() => {
                      window.print();
                    }}
                    className="w-full sm:w-auto px-6 py-3.5 rounded-xl border-2 border-slate-200 hover:border-slate-800 text-slate-700 hover:text-slate-900 text-xs font-black tracking-widest uppercase transition-colors flex items-center justify-center gap-1.5"
                  >
                    <Download className="h-4 w-4" />
                    <span>Cetak Bukti Registrasi</span>
                  </button>

                  <button
                    id="reset-form-action"
                    onClick={handleReset}
                    className="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-[#019342] hover:bg-[#191654] text-white text-xs font-black tracking-widest uppercase transition-all shadow flex items-center justify-center gap-1.5"
                  >
                    <Send className="h-4 w-4" />
                    <span>Daftarkan Siswa Baru Lainnya</span>
                  </button>
                </div>

              </div>
            )}

            {/* Step Wizard Action Footer */}
            {currentStep < 7 && (
              <div id="wizard-buttons-footer" className="border-t border-slate-100 pt-8 flex items-center justify-between">
                
                <button
                  id="wizard-prev-btn"
                  type="button"
                  onClick={handlePrev}
                  disabled={currentStep === 1}
                  className={`px-5 py-3 rounded-xl border border-slate-200 text-xs font-black tracking-wider uppercase flex items-center gap-1.5 transition-all ${
                    currentStep === 1 
                      ? 'opacity-30 cursor-not-allowed text-slate-300 border-slate-100'
                      : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'
                  }`}
                >
                  <ArrowLeft className="h-4 w-4" />
                  <span>Kembali</span>
                </button>

                {currentStep < 6 ? (
                  <button
                    id="wizard-next-btn"
                    type="button"
                    onClick={handleNext}
                    className="px-6 py-3 rounded-xl bg-[#019342] hover:bg-[#191654] text-white text-xs font-black tracking-widest uppercase transition-all shadow flex items-center gap-1.5"
                  >
                    <span>Selanjutnya</span>
                    <ArrowRight className="h-4 w-4" />
                  </button>
                ) : (
                  <button
                    id="wizard-submit-btn"
                    type="submit"
                    disabled={isSubmitting}
                    onClick={handleSubmitRegistration}
                    className="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-[#191654] text-white text-xs font-black tracking-widest uppercase transition-all shadow flex items-center gap-1.5"
                  >
                    {isSubmitting ? (
                      <>
                        <div className="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin" />
                        <span>Memproses Pendaftaran...</span>
                      </>
                    ) : (
                      <>
                        <span>Submit Pendaftaran</span>
                        <Send className="h-4 w-4" />
                      </>
                    )}
                  </button>
                )}

              </div>
            )}

          </form>

        </div>

        {/* Support helper details */}
        <div id="registration-support-footer-info" className="mt-8 flex items-center gap-2 bg-[#f4f7f5] border border-slate-150 rounded-2xl p-4 text-[10.5px] text-slate-500 font-bold leading-relaxed shadow-inner">
          <Clock className="h-5 w-5 text-[#019342] shrink-0" />
          <span>Formulir PPDB Online menggunakan enkripsi SSL aman. Seluruh data dilindungi kerahasiaannya di bawah Undang-Undang Perlindungan Data Pribadi Negara Republik Indonesia.</span>
        </div>

      </div>

    </div>
  );
}
