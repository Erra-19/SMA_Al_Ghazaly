import { useState, useEffect, FormEvent, ChangeEvent } from 'react';
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
  Building,
  Upload,
  FileCheck,
  X
} from 'lucide-react';
import { submitRegistration, uploadFormFile, getFormConfig, submitPaymentProof, getPublicSettings } from '../api';

// ─── Dynamic Form Types ───────────────────────────────────────────────────────
interface DynamicField {
  key: string;
  label: string;
  type: 'text' | 'textarea' | 'select' | 'radio' | 'date' | 'tel' | 'email' | 'number';
  placeholder?: string;
  required?: boolean;
  options?: string[];
}
interface DynamicStep { label: string; short_label?: string; fields: DynamicField[]; }
interface DynamicFormConfig { form_id: number; name: string; description?: string; steps: DynamicStep[]; }

// ─── Dynamic Form Component ───────────────────────────────────────────────────
interface UploadedFile {
  url: string; path: string; field_key: string; field_label: string;
  original_name: string; mime_type: string; file_size: number;
}

function DynamicRegistrationForm({ config }: { config: DynamicFormConfig }) {
  const steps = config.steps;
  const totalSteps = steps.length;
  const [current, setCurrent] = useState(0);
  const [answers, setAnswers] = useState<Record<string, string>>({});
  const [uploadedFiles, setUploadedFiles] = useState<Record<string, UploadedFile>>({});
  const [uploadingFields, setUploadingFields] = useState<Record<string, boolean>>({});
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [doneCode, setDoneCode] = useState<string | null>(null);

  const set = (key: string, val: string) => setAnswers(prev => ({ ...prev, [key]: val }));

  const validate = () => {
    const step = steps[current];
    for (const f of step.fields) {
      if (f.required) {
        if (f.type === 'file') {
          if (!uploadedFiles[f.key]) {
            setError(`"${f.label}" wajib diupload.`);
            return false;
          }
        } else if (!answers[f.key]?.trim()) {
          setError(`"${f.label}" wajib diisi.`);
          return false;
        }
      }
    }
    setError(null);
    return true;
  };

  const next = () => { if (validate()) setCurrent(c => Math.min(c + 1, totalSteps - 1)); };
  const back = () => { setCurrent(c => Math.max(c - 1, 0)); setError(null); };

  const handleFileUpload = async (file: File, fieldKey: string, fieldLabel: string) => {
    setUploadingFields(prev => ({ ...prev, [fieldKey]: true }));
    setError(null);
    try {
      const res = await uploadFormFile(file, fieldKey, fieldLabel);
      setUploadedFiles(prev => ({ ...prev, [fieldKey]: res }));
    } catch (e: any) {
      setError(e.message || 'Gagal mengupload file.');
    } finally {
      setUploadingFields(prev => ({ ...prev, [fieldKey]: false }));
    }
  };

  const removeFile = (fieldKey: string) =>
    setUploadedFiles(prev => { const n = { ...prev }; delete n[fieldKey]; return n; });

  const submit = async () => {
    if (!validate()) return;
    setSubmitting(true);
    try {
      const documents = Object.values(uploadedFiles);
      const res = await submitRegistration({
        form_data: answers,
        ...(documents.length > 0 ? { documents } : {}),
      });
      setDoneCode(res.registration_number);
    } catch (e: any) {
      setError(e.message || 'Gagal mengirim pendaftaran.');
    } finally {
      setSubmitting(false);
    }
  };

  if (doneCode) return (
    <div className="text-center py-16 space-y-4">
      <div className="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mx-auto">
        <CheckCircle className="h-8 w-8 text-green-600" />
      </div>
      <h3 className="text-xl font-black text-slate-900">Pendaftaran Berhasil!</h3>
      <p className="text-sm text-slate-500">Simpan nomor pendaftaran Anda:</p>
      <div className="inline-block bg-slate-100 border border-slate-200 rounded-2xl px-8 py-4">
        <span className="text-2xl font-black text-slate-900 tracking-widest">{doneCode}</span>
      </div>
      <p className="text-xs text-slate-400 max-w-sm mx-auto">Gunakan nomor ini untuk mengecek status pendaftaran pada halaman Cek Status PPDB.</p>
    </div>
  );

  const step = steps[current];

  return (
    <div className="space-y-6">
      {/* Step indicator */}
      <div className="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        {steps.map((s, i) => (
          <div key={i} className="flex items-center gap-1 shrink-0">
            <div className={`h-7 w-7 rounded-full flex items-center justify-center text-xs font-black transition-all
              ${i < current ? 'bg-primary-green text-white' : i === current ? 'bg-primary-green text-white ring-4 ring-primary-green/20' : 'bg-slate-100 text-slate-400'}`}>
              {i < current ? <CheckCircle className="h-3.5 w-3.5" /> : i + 1}
            </div>
            <span className={`text-[10px] font-bold hidden sm:block ${i === current ? 'text-slate-900' : 'text-slate-400'}`}>
              {s.short_label || s.label}
            </span>
            {i < steps.length - 1 && <div className={`h-px w-4 ${i < current ? 'bg-primary-green' : 'bg-slate-200'}`} />}
          </div>
        ))}
      </div>

      <div className="space-y-1 mb-6">
        <p className="text-[10px] font-black text-primary-green uppercase tracking-widest">Langkah {current + 1} dari {totalSteps}</p>
        <h4 className="text-base font-black text-slate-900">{step.label}</h4>
      </div>

      {/* Fields */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {step.fields.map(f => (
          <div key={f.key} className={f.type === 'textarea' || f.type === 'file' ? 'sm:col-span-2' : ''}>
            <label className="block text-xs font-bold text-slate-700 mb-1.5">
              {f.label} {f.required && <span className="text-red-500">*</span>}
            </label>
            {f.type === 'textarea' ? (
              <textarea value={answers[f.key] || ''} onChange={e => set(f.key, e.target.value)}
                placeholder={f.placeholder} rows={3}
                className="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-green/30 focus:border-primary-green/50 transition resize-none" />
            ) : f.type === 'select' ? (
              <select value={answers[f.key] || ''} onChange={e => set(f.key, e.target.value)}
                className="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-green/30 focus:border-primary-green/50 transition bg-white">
                <option value="">{f.placeholder || `Pilih ${f.label}...`}</option>
                {(f.options || []).map(o => <option key={o} value={o}>{o}</option>)}
              </select>
            ) : f.type === 'radio' ? (
              <div className="flex flex-wrap gap-3 pt-1">
                {(f.options || []).map(o => (
                  <label key={o} className="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name={f.key} value={o} checked={answers[f.key] === o}
                      onChange={e => set(f.key, e.target.value)} className="h-4 w-4 text-primary-green" />
                    <span className="text-sm text-slate-700">{o}</span>
                  </label>
                ))}
              </div>
            ) : f.type === 'file' ? (
              uploadedFiles[f.key] ? (
                <div className="flex items-center gap-3 border border-emerald-200 bg-emerald-50 rounded-xl px-4 py-3">
                  {uploadedFiles[f.key].mime_type.startsWith('image/') ? (
                    <img src={uploadedFiles[f.key].url} alt={f.label}
                      className="h-12 w-12 object-cover rounded-lg shrink-0 border border-emerald-200" />
                  ) : (
                    <div className="h-12 w-12 flex items-center justify-center bg-red-50 border border-red-200 rounded-lg shrink-0">
                      <FileCheck className="h-5 w-5 text-red-500" />
                    </div>
                  )}
                  <div className="flex-1 min-w-0">
                    <p className="text-xs font-semibold text-slate-800 truncate">{uploadedFiles[f.key].original_name}</p>
                    <p className="text-[10px] text-emerald-600 font-semibold mt-0.5">Berhasil diupload</p>
                  </div>
                  <button type="button" onClick={() => removeFile(f.key)}
                    className="h-7 w-7 flex items-center justify-center rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition shrink-0">
                    <X className="h-4 w-4" />
                  </button>
                </div>
              ) : (
                <label className={`flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl p-6 cursor-pointer transition-colors
                  ${uploadingFields[f.key] ? 'border-blue-200 bg-blue-50 cursor-wait' : 'border-slate-200 hover:border-primary-green/40 hover:bg-slate-50'}`}>
                  <input type="file" className="hidden" accept="image/*,.pdf"
                    disabled={uploadingFields[f.key]}
                    onChange={e => { const file = e.target.files?.[0]; if (file) handleFileUpload(file, f.key, f.label); }} />
                  {uploadingFields[f.key] ? (
                    <>
                      <div className="h-6 w-6 rounded-full border-2 border-blue-400 border-t-transparent animate-spin" />
                      <span className="text-xs text-blue-500 font-semibold">Mengupload...</span>
                    </>
                  ) : (
                    <>
                      <Upload className="h-6 w-6 text-slate-400" />
                      <span className="text-xs text-slate-500 text-center font-medium">{f.placeholder || 'Klik untuk pilih atau seret file ke sini'}</span>
                      <span className="text-[10px] text-slate-400">JPG, PNG, atau PDF · maks. 5MB</span>
                    </>
                  )}
                </label>
              )
            ) : (
              <input type={f.type} value={answers[f.key] || ''} onChange={e => set(f.key, e.target.value)}
                placeholder={f.placeholder}
                className="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-green/30 focus:border-primary-green/50 transition" />
            )}
          </div>
        ))}
      </div>

      {error && (
        <div className="flex items-center gap-2 text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-xs font-semibold">
          <AlertCircle className="h-4 w-4 shrink-0" /> {error}
        </div>
      )}

      {/* Navigation */}
      <div className="flex items-center justify-between pt-4 border-t border-slate-100">
        <button onClick={back} disabled={current === 0}
          className="flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-30 transition">
          <ArrowLeft className="h-4 w-4" /> Kembali
        </button>
        {current < totalSteps - 1 ? (
          <button onClick={next}
            className="flex items-center gap-2 px-6 py-2.5 rounded-full bg-primary-green text-white text-xs font-black hover:bg-hover-blue transition">
            Lanjut <ArrowRight className="h-4 w-4" />
          </button>
        ) : (
          <button onClick={submit} disabled={submitting}
            className="flex items-center gap-2 px-6 py-2.5 rounded-full bg-primary-green text-white text-xs font-black hover:bg-hover-blue disabled:opacity-50 transition">
            {submitting ? 'Mengirim...' : 'Kirim Pendaftaran'} <Send className="h-4 w-4" />
          </button>
        )}
      </div>
    </div>
  );
}

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
  // ─── Dynamic form config from admin ──────────────────────────────────────────
  const [dynamicConfig, setDynamicConfig] = useState<DynamicFormConfig | null>(null);
  const [configLoading, setConfigLoading] = useState(true);

  useEffect(() => {
    getFormConfig('ppdb-registration').then(data => {
      if (data?.steps && data.steps.length > 0) setDynamicConfig(data);
    }).finally(() => setConfigLoading(false));
  }, []);

  // ─── Static fallback form state ───────────────────────────────────────────────
  const [currentStep, setCurrentStep] = useState(1);
  const [formData, setFormData] = useState<RegistrationData>(initialFormData);
  const [registrationCode, setRegistrationCode] = useState<string>('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [validationError, setValidationError] = useState<string | null>(null);

  // Payment step states
  const [paymentProof, setPaymentProof] = useState<File | null>(null);
  const [paymentProofPreview, setPaymentProofPreview] = useState<string | null>(null);
  const [paymentNotes, setPaymentNotes] = useState('');
  const [isSubmittingPayment, setIsSubmittingPayment] = useState(false);
  const [paymentError, setPaymentError] = useState<string | null>(null);
  const [bankSettings, setBankSettings] = useState<Record<string, string>>({});
  const [selectedPaymentType, setSelectedPaymentType] = useState<'full' | 'installment'>('full');

  useEffect(() => {
    getPublicSettings().then(s => {
      setBankSettings(s);
      // Default pilihan bayar sesuai mode
      if (s.ppdb_payment_mode === 'installment') setSelectedPaymentType('installment');
      else setSelectedPaymentType('full');
    });
  }, []);

  // Parse fee items dari settings
  const ppdbFeeItems: { name: string; amount: number }[] = (() => {
    try { return JSON.parse(bankSettings.ppdb_payment_items || '[]'); } catch { return []; }
  })();
  const ppdbTotal = ppdbFeeItems.reduce((s, f) => s + (f.amount || 0), 0);
  const ppdbMode = bankSettings.ppdb_payment_mode || 'full';
  const ppdbInstallmentCount = parseInt(bankSettings.ppdb_installment_count || '2');
  const ppdbDp = parseInt(bankSettings.ppdb_installment_dp || '0');
  const ppdbInstallmentPerCicil = ppdbInstallmentCount > 1
    ? Math.ceil((ppdbTotal - ppdbDp) / (ppdbInstallmentCount - 1)) : ppdbTotal;
  const fmtRp = (v: number) => new Intl.NumberFormat('id-ID').format(v);

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

  const handleProofChange = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0] ?? null;
    setPaymentProof(file);
    setPaymentProofPreview(file ? URL.createObjectURL(file) : null);
    setPaymentError(null);
  };

  const handleSubmitPayment = async () => {
    setIsSubmittingPayment(true);
    setPaymentError(null);
    try {
      await submitPaymentProof(registrationCode, paymentProof, paymentNotes);
      setCurrentStep(8);
      window.scrollTo({ top: 200, behavior: 'smooth' });
    } catch (err) {
      setPaymentError(err instanceof Error ? err.message : 'Gagal mengirim bukti. Coba lagi.');
    } finally {
      setIsSubmittingPayment(false);
    }
  };

  const handleReset = () => {
    setFormData(initialFormData);
    setRegistrationCode('');
    setCurrentStep(1);
    setValidationError(null);
    setPaymentProof(null);
    setPaymentProofPreview(null);
    setPaymentNotes('');
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

      {/* ── Dynamic form (if admin configured) ─────────────────────────────── */}
      {configLoading ? (
        <div className="flex justify-center py-24">
          <div className="h-10 w-10 rounded-full border-4 border-primary-green border-t-transparent animate-spin" />
        </div>
      ) : dynamicConfig ? (
        <div className="mx-auto max-w-3xl px-4 sm:px-6">
          <div className="bg-white border border-slate-100 rounded-3xl p-6 sm:p-10 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
            {dynamicConfig.description && (
              <p className="text-sm text-slate-500 mb-6 pb-6 border-b border-slate-100">{dynamicConfig.description}</p>
            )}
            <DynamicRegistrationForm config={dynamicConfig!} />
          </div>
        </div>
      ) : (

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
            {currentStep <= 6 ? (
              <span id="wizard-current-title" className="text-xs font-black tracking-wider text-emerald-700 uppercase bg-emerald-50 px-4 py-1.5 rounded-full inline-block">
                Langkah {currentStep} dari 6: {STEPS[currentStep - 1]?.label ?? ''}
              </span>
            ) : currentStep === 7 ? (
              <span className="text-xs font-black tracking-wider text-amber-700 uppercase bg-amber-50 px-4 py-1.5 rounded-full inline-block">
                Pembayaran Biaya Pendaftaran
              </span>
            ) : (
              <span className="text-xs font-black tracking-wider text-emerald-700 uppercase bg-emerald-50 px-4 py-1.5 rounded-full inline-block">
                Pendaftaran Selesai
              </span>
            )}
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
            {/* STEP 7: Pembayaran */}
            {currentStep === 7 && registrationCode && (
              <div id="step-content-7" className="space-y-6 animate-fade-in">

                {/* Header */}
                <div className="text-center space-y-2 pb-5 border-b border-slate-100">
                  <div className="mx-auto h-12 w-12 bg-amber-50 rounded-full border border-amber-200 flex items-center justify-center text-amber-600 shadow-sm">
                    <Building className="h-6 w-6" />
                  </div>
                  <h3 className="text-lg font-black text-[#191654]">Pendaftaran Berhasil — Lanjutkan Pembayaran</h3>
                  <p className="text-xs text-slate-500 max-w-md mx-auto">
                    No. Registrasi: <span className="font-black font-mono text-[#019342]">{registrationCode}</span>
                  </p>
                  <p className="text-xs text-slate-400">Silakan transfer ke rekening berikut dan unggah bukti pembayaran.</p>
                </div>

                {/* Rincian Biaya */}
                {ppdbFeeItems.length > 0 && (
                  <div className="rounded-2xl border border-slate-200 overflow-hidden">
                    <div className="bg-slate-50 px-5 py-3 border-b border-slate-100">
                      <h4 className="text-xs font-black text-slate-700 uppercase tracking-wider">Rincian Biaya PPDB</h4>
                    </div>
                    <div className="divide-y divide-slate-100">
                      {ppdbFeeItems.map((item, i) => (
                        <div key={i} className="flex items-center justify-between px-5 py-3">
                          <span className="text-sm text-slate-700">{item.name}</span>
                          <span className="text-sm font-bold text-slate-800">Rp {fmtRp(item.amount)}</span>
                        </div>
                      ))}
                    </div>
                    <div className="flex items-center justify-between px-5 py-3 bg-emerald-50 border-t border-emerald-100">
                      <span className="text-sm font-black text-emerald-800">Total</span>
                      <span className="text-base font-black text-emerald-700">Rp {fmtRp(ppdbTotal)}</span>
                    </div>
                  </div>
                )}

                {/* Pilihan Cara Bayar */}
                {(ppdbMode === 'full' || ppdbMode === 'both' || ppdbMode === 'installment') && ppdbTotal > 0 && (
                  <div className="space-y-3">
                    <h4 className="text-sm font-bold text-slate-700">Pilihan Pembayaran</h4>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      {(ppdbMode === 'full' || ppdbMode === 'both') && (
                        <label className={`flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all ${selectedPaymentType === 'full' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'}`}>
                          <input type="radio" name="payType" value="full" checked={selectedPaymentType === 'full'}
                            onChange={() => setSelectedPaymentType('full')} className="mt-0.5 h-4 w-4 text-emerald-600" />
                          <div>
                            <p className="text-sm font-black text-slate-800">Bayar Lunas</p>
                            <p className="text-xs text-slate-500 mt-0.5">Satu kali transfer</p>
                            <p className="text-base font-black text-emerald-700 mt-1">Rp {fmtRp(ppdbTotal)}</p>
                          </div>
                        </label>
                      )}
                      {(ppdbMode === 'installment' || ppdbMode === 'both') && ppdbInstallmentCount > 1 && (
                        <label className={`flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all ${selectedPaymentType === 'installment' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-slate-300'}`}>
                          <input type="radio" name="payType" value="installment" checked={selectedPaymentType === 'installment'}
                            onChange={() => setSelectedPaymentType('installment')} className="mt-0.5 h-4 w-4 text-blue-600" />
                          <div>
                            <p className="text-sm font-black text-slate-800">Cicil {ppdbInstallmentCount}×</p>
                            <p className="text-xs text-slate-500 mt-0.5">
                              {ppdbDp > 0 ? `DP Rp ${fmtRp(ppdbDp)} + ${ppdbInstallmentCount - 1}× Rp ${fmtRp(ppdbInstallmentPerCicil)}` : `${ppdbInstallmentCount}× Rp ${fmtRp(Math.ceil(ppdbTotal / ppdbInstallmentCount))}`}
                            </p>
                            <p className="text-base font-black text-blue-700 mt-1">Rp {fmtRp(ppdbTotal)}</p>
                          </div>
                        </label>
                      )}
                    </div>

                    {/* Jadwal cicilan jika pilih installment */}
                    {selectedPaymentType === 'installment' && ppdbInstallmentCount > 1 && (
                      <div className="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <p className="text-xs font-black text-blue-700 uppercase tracking-wider mb-2">Jadwal Cicilan</p>
                        <div className="space-y-1.5">
                          {Array.from({ length: ppdbInstallmentCount }).map((_, i) => {
                            const amount = i === 0 && ppdbDp > 0 ? ppdbDp : ppdbInstallmentPerCicil;
                            return (
                              <div key={i} className="flex items-center justify-between text-xs">
                                <span className="text-blue-700 font-semibold">{i === 0 ? 'DP / Cicilan 1' : `Cicilan ${i + 1}`}</span>
                                <span className="font-black text-blue-800">Rp {fmtRp(amount)}</span>
                              </div>
                            );
                          })}
                        </div>
                      </div>
                    )}
                  </div>
                )}

                {/* Info Rekening */}
                <div className="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 space-y-3">
                  <h4 className="text-xs font-black text-emerald-800 uppercase tracking-wider">Rekening Tujuan Transfer</h4>
                  {bankSettings.bank_name || bankSettings.bank_account_number ? (
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                      <div>
                        <p className="text-[10px] text-emerald-600 font-bold uppercase">Bank</p>
                        <p className="font-black text-slate-800">{bankSettings.bank_name || '—'}</p>
                      </div>
                      <div>
                        <p className="text-[10px] text-emerald-600 font-bold uppercase">No. Rekening</p>
                        <p className="font-black font-mono text-slate-800 text-base tracking-wider">{bankSettings.bank_account_number || '—'}</p>
                      </div>
                      <div>
                        <p className="text-[10px] text-emerald-600 font-bold uppercase">Atas Nama</p>
                        <p className="font-black text-slate-800">{bankSettings.bank_account_name || '—'}</p>
                      </div>
                    </div>
                  ) : (
                    <p className="text-xs text-slate-500 italic">Info rekening belum dikonfigurasi. Hubungi Sekretariat PPDB untuk informasi pembayaran.</p>
                  )}
                  {selectedPaymentType === 'full' && ppdbTotal > 0 && (
                    <div className="pt-2 border-t border-emerald-100">
                      <p className="text-xs text-emerald-700 font-semibold">
                        Transfer sebesar: <span className="font-black text-base">Rp {fmtRp(ppdbTotal)}</span>
                      </p>
                    </div>
                  )}
                  {selectedPaymentType === 'installment' && ppdbDp > 0 && (
                    <div className="pt-2 border-t border-emerald-100">
                      <p className="text-xs text-emerald-700 font-semibold">
                        Transfer DP sekarang: <span className="font-black text-base">Rp {fmtRp(ppdbDp)}</span>
                      </p>
                    </div>
                  )}
                </div>

                {/* Upload Bukti */}
                <div className="space-y-4">
                  <h4 className="text-sm font-bold text-slate-700">Unggah Bukti Pembayaran</h4>

                  <label className={`flex flex-col items-center justify-center gap-3 border-2 border-dashed rounded-2xl p-8 cursor-pointer transition-colors ${paymentProof ? 'border-emerald-300 bg-emerald-50/50' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50'}`}>
                    <input type="file" className="hidden" accept="image/*,.pdf" onChange={handleProofChange} />
                    {paymentProofPreview && paymentProof?.type.startsWith('image/') ? (
                      <img src={paymentProofPreview} alt="Preview" className="max-h-40 rounded-lg object-contain shadow" />
                    ) : paymentProof ? (
                      <div className="flex items-center gap-2 text-emerald-700">
                        <FileText className="h-8 w-8" />
                        <span className="text-sm font-semibold">{paymentProof.name}</span>
                      </div>
                    ) : (
                      <>
                        <Camera className="h-10 w-10 text-slate-400" />
                        <div className="text-center">
                          <p className="text-sm font-bold text-slate-600">Klik untuk unggah bukti transfer</p>
                          <p className="text-xs text-slate-400 mt-0.5">JPG, PNG, atau PDF · Maks. 8 MB</p>
                        </div>
                      </>
                    )}
                  </label>

                  <div>
                    <label className="block text-xs font-semibold text-slate-600 mb-1">Catatan (opsional)</label>
                    <textarea
                      value={paymentNotes}
                      onChange={e => setPaymentNotes(e.target.value)}
                      className="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"
                      rows={2}
                      placeholder="Contoh: Transfer via BCA mobile, sudah transfer Rp 500.000..."
                    />
                  </div>
                </div>

                {paymentError && (
                  <div className="flex items-center gap-2 text-red-700 bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-xs font-semibold">
                    <AlertCircle className="h-4 w-4 shrink-0" /> {paymentError}
                  </div>
                )}

                <div className="flex flex-col sm:flex-row gap-3 pt-2">
                  <button
                    type="button"
                    onClick={handleSubmitPayment}
                    disabled={isSubmittingPayment}
                    className="flex-1 px-6 py-3.5 rounded-xl bg-[#019342] hover:bg-[#191654] disabled:opacity-60 text-white text-xs font-black tracking-widest uppercase transition-all shadow flex items-center justify-center gap-2"
                  >
                    {isSubmittingPayment ? (
                      <><div className="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin" /><span>Mengirim...</span></>
                    ) : (
                      <><Send className="h-4 w-4" /><span>Kirim Bukti Pembayaran</span></>
                    )}
                  </button>
                  <button
                    type="button"
                    onClick={() => { setCurrentStep(8); }}
                    className="px-5 py-3.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold tracking-wide transition-colors"
                  >
                    Lewati, Kirim Nanti
                  </button>
                </div>

              </div>
            )}

            {/* STEP 8: Menunggu Verifikasi */}
            {currentStep === 8 && registrationCode && (
              <div id="step-content-8" className="space-y-8 animate-fade-in block">
                <div className="text-center space-y-3 pb-6 border-b border-slate-100">
                  <div className="mx-auto h-14 w-14 bg-emerald-50 rounded-full border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-sm animate-bounce-slow">
                    <CheckCircle className="h-8 w-8" />
                  </div>
                  <h3 className="text-xl font-black text-[#191654] tracking-tight">Pendaftaran Berhasil Dikirim!</h3>
                  <p className="text-xs text-slate-400 font-semibold max-w-md mx-auto">
                    Data dan bukti pembayaran sudah diterima. Admin akan memeriksa dan mengonfirmasi pendaftaran Anda.
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
                        <h5 className="text-xs font-black text-[#191654]">Status Pendaftaran Anda:</h5>
                        <div className="flex items-center gap-2 text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2 text-xs font-semibold">
                          <Clock className="h-4 w-4 shrink-0" />
                          <span>Menunggu verifikasi pembayaran dan konfirmasi admin. Harap tunggu 1×24 jam kerja.</span>
                        </div>
                        <ol className="list-decimal list-inside text-[11px] text-slate-505 font-semibold space-y-1 leading-relaxed mt-2">
                          <li>Admin akan memeriksa bukti pembayaran yang Anda kirimkan.</li>
                          <li>Setelah terverifikasi, pendaftaran akan dikonfirmasi dan Anda akan dihubungi via WhatsApp/email.</li>
                          <li>Siapkan berkas fisik: Akta Kelahiran, KK, dan Ijazah/SKHUN SMP (legalisir asli) 2 lembar untuk diserahkan ke sekolah.</li>
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
            {currentStep < 7 && currentStep >= 1 && (
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

      )} {/* end ternary: no dynamic config → show static form */}

    </div>
  );
}
