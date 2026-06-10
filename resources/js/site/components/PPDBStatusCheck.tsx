import { useState, FormEvent } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  Search, 
  CheckCircle, 
  XCircle, 
  Clock, 
  AlertCircle, 
  Loader2, 
  MessageCircle, 
  ArrowRight, 
  Printer, 
  ExternalLink,
  ShieldCheck,
  FileText,
  UserCheck
} from 'lucide-react';
import { checkRegistrationStatus } from '../api';

interface MockResponse {
  code: string;
  name: string;
  school: string;
  status: 'DITERIMA' | 'PROSES' | 'WAWANCARA' | 'CADANGAN' | 'TIDAK_DITEMUKAN';
  stepNum: number; // 1 to 4
  statusLabel: string;
  description: string;
  dateStr: string;
  nextSteps: string[];
}

export default function PPDBStatusCheck() {
  const [searchCode, setSearchCode] = useState('');
  const [isChecked, setIsChecked] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [result, setResult] = useState<MockResponse | null>(null);

  const statusMap = (status?: string): MockResponse['status'] => {
    if (status === 'accepted' || status === 'paid') return 'DITERIMA';
    if (status === 'verified' || status === 'document_review') return 'WAWANCARA';
    if (status === 'rejected') return 'CADANGAN';
    return 'PROSES';
  };

  const handleSearch = async (e: FormEvent) => {
    e.preventDefault();
    if (!searchCode.trim()) return;

    setIsLoading(true);
    setIsChecked(false);

    try {
      const data = await checkRegistrationStatus(searchCode.trim());
      const status = statusMap(data.status);
      const summary = data.payment_summary || {};
      setResult({
        code: data.registration_number || searchCode,
        name: data.student_name || 'Calon Peserta Didik',
        school: data.previous_school || 'Sekolah asal belum diisi',
        status,
        stepNum: status === 'DITERIMA' ? 4 : status === 'WAWANCARA' ? 3 : 2,
        statusLabel: data.status || 'Terdaftar',
        description: `Status pendaftaran: ${data.status || '-'}. Status pembayaran: ${summary.payment_status || data.payment_status || '-'}. Sisa tagihan: ${summary.remaining_amount ?? data.payment_remaining_amount ?? 0}.`,
        dateStr: data.updated_at || data.created_at || 'Baru saja diupdate',
        nextSteps: [
          'Pantau status berkala melalui halaman ini.',
          'Pastikan nomor WhatsApp dan email yang terdaftar tetap aktif.',
          'Ikuti arahan panitia PPDB jika status berubah.',
        ],
      });
    } catch {
      setResult({
        code: searchCode,
        name: '-',
        school: '-',
        status: 'TIDAK_DITEMUKAN',
        stepNum: 1,
        statusLabel: 'Nomor Pendaftaran Tidak Ditemukan',
        description: 'Sistem tidak menemukan berkas dengan nomor pendaftaran tersebut. Mohon periksa kembali nomor pendaftaran dari bukti PPDB Anda.',
        dateStr: '-',
        nextSteps: [
          'Pastikan nomor pendaftaran sama persis dengan bukti pendaftaran.',
          'Hubungi sekretariat PPDB jika Anda merasa sudah mendaftar tetapi data belum ditemukan.',
        ]
      });
    } finally {
      setIsLoading(false);
      setIsChecked(true);
    }
  };

  return (
    <section 
      id="ppdb-status-check-section" 
      className="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-slate-50 to-slate-100 border-t border-b border-slate-150"
    >
      <div className="mx-auto max-w-4xl">
        
        {/* Section Header */}
        <div className="text-center max-w-2xl mx-auto mb-10">
          <span className="text-[10px] font-black tracking-widest text-[#019342] uppercase bg-[#019342]/10 px-4 py-1.5 rounded-full inline-block mb-3.5 shadow-sm">
            Layanan Akademik PPDB
          </span>
          <h2 className="text-2xl md:text-3xl font-black text-[#191654] tracking-tight leading-snug">
            Cek Status Pendaftaran PPDB Online
          </h2>
          <p className="text-xs md:text-sm font-semibold text-slate-500 leading-relaxed mt-2.5">
            Sudah melakukan pengunduhan orisinal formulir? Masukkan kode registrasi PPDB Anda di bawah ini secara seketika untuk melacak jalannya berkas administrasinya.
          </p>
        </div>

        {/* Search Searchbar Panel */}
        <div className="bg-white p-5 rounded-3xl border border-slate-150 shadow-[0_12px_24px_rgba(0,0,0,0.015)] mb-8">
          <form onSubmit={handleSearch} className="flex flex-col sm:flex-row gap-3">
            <div className="relative flex-1">
              <input
                id="ppdb-status-search-input"
                type="text"
                placeholder="Masukkan Nomor Pendaftaran (Contoh: PPDB/ALG-2026/1001)"
                value={searchCode}
                onChange={(e) => setSearchCode(e.target.value)}
                className="w-full pl-11 pr-4 py-3.5 text-xs bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/15 focus:border-[#019342] text-slate-800 font-extrabold tracking-wide placeholder-slate-400"
              />
              <Search className="absolute left-4 top-4.5 h-4 w-4 text-slate-400" />
            </div>
            
            <button
              id="ppdb-status-search-btn"
              type="submit"
              disabled={isLoading || !searchCode.trim()}
              className="px-6 py-3.5 bg-[#019342] hover:bg-[#191654] disabled:bg-slate-300 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-md transition-colors duration-250 flex items-center justify-center gap-2 cursor-pointer"
            >
              {isLoading ? (
                <>
                  <Loader2 className="h-4.5 w-4.5 animate-spin" />
                  <span>Mencari...</span>
                </>
              ) : (
                <>
                  <Search className="h-4.5 w-4.5" />
                  <span>Periksa Status</span>
                </>
              )}
            </button>
          </form>

          <div className="mt-4 border-t border-slate-100 px-1 pt-3.5 text-[10.5px] font-bold text-slate-400">
            Gunakan nomor pendaftaran asli yang diterima setelah mengirim formulir PPDB.
          </div>
        </div>

        {/* Search Result Card Container */}
        <AnimatePresence mode="wait">
          {isChecked && result && (
            <motion.div
              initial={{ opacity: 0, y: 15 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              transition={{ duration: 0.35 }}
              className="bg-white border border-slate-150 rounded-3xl overflow-hidden shadow-[0_15px_35px_rgba(0,0,0,0.02)]"
            >
              
              {/* Result Card Header (Colored accent relative to status) */}
              <div className={`p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 ${
                result.status === 'DITERIMA' 
                  ? 'bg-gradient-to-r from-emerald-600 to-[#019342]' 
                  : result.status === 'WAWANCARA' 
                  ? 'bg-gradient-to-r from-blue-700 to-[#191654]'
                  : result.status === 'PROSES'
                  ? 'bg-gradient-to-r from-amber-500 to-amber-600'
                  : result.status === 'CADANGAN'
                  ? 'bg-gradient-to-r from-zinc-650 to-slate-700'
                  : 'bg-gradient-to-r from-rose-600 to-rose-700'
              }`}>
                <div>
                  <span className="text-[9.5px] uppercase font-black tracking-widest text-white/70 block">
                    Hasil Pencarian Kode: {result.code}
                  </span>
                  <h3 className="text-lg md:text-xl font-black tracking-tight mt-1">
                    {result.statusLabel}
                  </h3>
                </div>

                <div className="shrink-0">
                  <span className={`px-4 py-1.5 rounded-full text-[10.5px] font-black tracking-wider uppercase border border-white/20 bg-white/10`}>
                    {result.status}
                  </span>
                </div>
              </div>

              {/* Result Details Segment */}
              <div className="p-6 md:p-8 space-y-8">
                
                {/* 1. Basic Bio information block (Only if found) */}
                {result.status !== 'TIDAK_DITEMUKAN' && (
                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-y-4 gap-x-6 bg-slate-50 border border-slate-200/60 p-4 rounded-2xl">
                    <div>
                      <span className="text-[9.5px] font-black uppercase text-slate-400 tracking-wider">Nama Calon Siswa</span>
                      <p className="text-xs font-black text-slate-800 mt-0.5">{result.name}</p>
                    </div>
                    <div>
                      <span className="text-[9.5px] font-black uppercase text-slate-400 tracking-wider">Sekolah Asal</span>
                      <p className="text-xs font-bold text-slate-700 mt-0.5">{result.school}</p>
                    </div>
                    <div>
                      <span className="text-[9.5px] font-black uppercase text-slate-400 tracking-wider">Terakhir Diupdate</span>
                      <p className="text-xs font-bold text-slate-500 mt-0.5">{result.dateStr}</p>
                    </div>
                  </div>
                )}

                {/* 2. Custom Stepper Process Visual Block (Only if found) */}
                {result.status !== 'TIDAK_DITEMUKAN' && (
                  <div className="space-y-3.5">
                    <h4 className="text-[11px] font-black text-[#191654] uppercase tracking-wider flex items-center gap-1.5">
                      <ShieldCheck className="h-4 w-4 text-[#019342]" />
                      <span>Tahapan Proses Seleksi PPDB</span>
                    </h4>

                    {/* Progress visual steps */}
                    <div className="grid grid-cols-4 gap-2 relative pt-2">
                      {[
                        { step: 1, label: 'Formulir' },
                        { step: 2, label: 'Verifikasi' },
                        { step: 3, label: 'Tes Fisik' },
                        { step: 4, label: 'Daftar Ulang' }
                      ].map((s) => {
                        const isDone = result.stepNum >= s.step;
                        const isCurrent = result.stepNum === s.step;
                        
                        return (
                          <div key={s.step} className="flex flex-col items-center">
                            {/* Step bullet */}
                            <div className={`h-7 w-7 rounded-full flex items-center justify-center font-bold text-xs select-none border ${
                              isCurrent 
                                ? 'bg-[#019342] text-white border-[#019342] ring-4 ring-emerald-50' 
                                : isDone 
                                ? 'bg-emerald-50 text-[#019342] border-emerald-300' 
                                : 'bg-slate-50 text-slate-350 border-slate-100'
                            }`}>
                              {s.step}
                            </div>
                            <span className={`text-[9.5px] mt-1.5 font-bold tracking-tight text-center ${
                              isCurrent ? 'text-[#019342] font-black' : isDone ? 'text-slate-600' : 'text-slate-350'
                            }`}>
                              {s.label}
                            </span>
                          </div>
                        );
                      })}
                    </div>
                  </div>
                )}

                {/* 3. Status Description Box */}
                <div className="bg-slate-50/50 border border-slate-150 p-5 rounded-2xl">
                  <h4 className="text-[11px] font-black text-[#191654] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <FileText className="h-4 w-4 text-slate-500" />
                    <span>Rekomendasi / Hasil Keputusan</span>
                  </h4>
                  <p className="text-xs text-slate-600 font-semibold leading-relaxed">
                    {result.description}
                  </p>
                </div>

                {/* 4. Action steps / Hal-hal yang harus disiapkan */}
                <div className="space-y-3">
                  <h4 className="text-[11px] font-black text-[#191654] uppercase tracking-wider flex items-center gap-1.5">
                    <UserCheck className="h-4 w-4 text-[#019342]" />
                    <span>Instruksi &amp; Langkah Berikutnya:</span>
                  </h4>
                  
                  <ul className="space-y-2.5">
                    {result.nextSteps.map((stepStr, idx) => (
                      <li key={idx} className="flex items-start gap-2.5 text-xs text-slate-600 font-semibold leading-relaxed">
                        <ArrowRight className="h-4 w-4 text-[#019342] shrink-0 mt-0.5" />
                        <span>{stepStr}</span>
                      </li>
                    ))}
                  </ul>
                </div>

                {/* 5. Footer actions of results (Print/WhatsApp CS) */}
                <div className="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
                  <div className="flex items-center gap-2">
                    <span className="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse" />
                    <span className="text-[10.5px] font-bold text-slate-400">Hubungi Panitia pendaftaran untuk verifikasi lanjutan.</span>
                  </div>

                  <div className="flex gap-2.5">
                    {result.status !='TIDAK_DITEMUKAN' && (
                      <button
                        onClick={() => window.print()}
                        className="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition shadow-sm inline-flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-wider cursor-pointer"
                      >
                        <Printer className="h-4.5 w-4.5" />
                        <span className="hidden sm:inline">Cetak Bukti</span>
                      </button>
                    )}
                    
                    <a
                      href="https://wa.me/6281381008834?text=Assalamu%20Alaikum%20Panitia%20PPDB%20SMA%20Al-Ghazaly%2C%20saya%20ingin%20mengonfirmasi%2520berkas%2520PPDB%2520saya."
                      target="_blank"
                      rel="noopener noreferrer"
                      className="px-4 py-2.5 bg-[#019342] hover:bg-[#191654] text-white rounded-xl transition shadow-sm inline-flex items-center gap-2 text-[10.5px] font-black uppercase tracking-wider cursor-pointer"
                    >
                      <MessageCircle className="h-4.5 w-4.5" />
                      <span>CS Panitia (WA)</span>
                    </a>
                  </div>
                </div>

              </div>

            </motion.div>
          )}
        </AnimatePresence>

      </div>
    </section>
  );
}
