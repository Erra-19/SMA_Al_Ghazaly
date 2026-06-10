import { useEffect, useState, ChangeEvent, FormEvent } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  MapPin, 
  Phone, 
  Mail, 
  Send, 
  CheckCircle, 
  MessageCircle, 
  Clock, 
  Info, 
  ExternalLink,
  Sparkles,
  HelpCircle,
  Users,
  AlertCircle
} from 'lucide-react';
import { submitContact } from '../api';
import { getSettings } from '../api';

interface ContactForm {
  name: string;
  email: string;
  phone: string;
  subject: string;
  message: string;
}

export default function SchoolContact() {
  const [form, setForm] = useState<ContactForm>({
    name: '',
    email: '',
    phone: '',
    subject: 'informasi_umum',
    message: ''
  });

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitSuccess, setSubmitSuccess] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [settings, setSettings] = useState<Record<string, string>>({});

  useEffect(() => {
    getSettings().then(setSettings).catch(() => setSettings({}));
  }, []);

  const address = settings.address || settings.school_address || 'Alamat belum diatur.';
  const phone = settings.phone || settings.school_phone || 'Telepon belum diatur';
  const email = settings.email || settings.school_email || 'Email belum diatur';
  const whatsapp = (settings.whatsapp || settings.school_whatsapp || '').replace(/\D/g, '');
  const rawMapUrl = settings.maps_url || settings.google_maps_url || '';
  const lat = settings.latitude;
  const lng = settings.longitude;

  // Embed URL → regular maps URL untuk tombol (strip /embed path)
  const mapUrl = rawMapUrl.includes('/maps/embed')
    ? rawMapUrl.replace('/maps/embed', '/maps')
    : rawMapUrl || (lat && lng ? `https://www.google.com/maps?q=${lat},${lng}` : '');

  // Embed URL untuk iframe
  const mapEmbedUrl =
    settings.maps_embed_url ||
    (rawMapUrl.includes('/embed') ? rawMapUrl : '') ||
    (lat && lng ? `https://maps.google.com/maps?q=${lat},${lng}&z=16&output=embed` : '');

  const whatsappContacts = whatsapp ? [
    { name: 'Admin Official', phone: whatsapp, role: 'Informasi sekolah dan PPDB' },
  ] : [];

  const faqList = [
    {
      q: 'Kapan jam pelayanan operasional kantor sekretariat?',
      a: 'Kantor sekretariat sekolah melayani hari Senin - Jumat pukul 07:00 - 15:30 WIB, dan hari Sabtu pukul 08:00 - 12:00 WIB. Hari Minggu dan libur nasional kantor tutup.'
    },
    {
      q: 'Bagaimana prosedur berkunjung ke Kampus SMA Al-Ghazaly?',
      a: 'Setiap tamu wajib melapor ke Pos Keamanan di pintu gerbang utama, menukarkan kartu identitas (KTP/SIM) dengan ID Card Tamu, serta akan diarahkan menuju Ruang Penerimaan Tamu di Kantor Utama.'
    },
    {
      q: 'Apakah pendaftaran siswa baru (PPDB) bisa dilakukan online?',
      a: 'Ya, Anda dapat menghubungi Admin Official kami melalui WhatsApp untuk mendapatkan tautan formulir pendaftaran digital beserta panduan dokumen persyaratan lengkap.'
    }
  ];

  const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setForm(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!form.name || !form.email || !form.message) {
      setSubmitError('Mohon lengkapi semua bidang wajib bertanda bintang (*).');
      return;
    }

    setIsSubmitting(true);
    setSubmitError(null);

    try {
      await submitContact(form);
      setSubmitSuccess(true);
      setForm({
        name: '',
        email: '',
        phone: '',
        subject: 'informasi_umum',
        message: ''
      });

      setTimeout(() => setSubmitSuccess(false), 6000);
    } catch (error) {
      setSubmitError(error instanceof Error ? error.message : 'Pesan belum bisa dikirim. Coba lagi sebentar lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div id="contact-page-root" className="bg-[#fcfdfd] text-slate-800 min-h-screen pt-24 pb-16">
      
      {/* 1. Elegant Header Banner */}
      <div 
        id="contact-hero-banner" 
        className="relative overflow-hidden bg-gradient-to-br from-[#019342] to-[#191654] text-white py-24 px-4 sm:px-6 lg:px-8 mb-16 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
      >
        <div className="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:24px_24px]" />
        
        {/* Floating Ambient Blurs */}
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
            <span>Kanal Informasi Resmi SMA Al-Ghazaly</span>
          </motion.div>

          <motion.h1 
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-none"
          >
            Hubungi Kami
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
            className="mt-6 max-w-2xl text-sm md:text-base text-white/90 leading-relaxed font-semibold animate-fade-in"
          >
            Kami selalu terbuka menjawab pertanyaan seputar program akademik, syarat pendaftaran (PPDB), fasilitas, hingga kunjungan kampus. Temukan koordinat kami dengan mudah di bawah ini.
          </motion.p>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {/* 2. Double-Column Contact Info Card Layout */}
        <div id="contact-central-grid" className="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20 items-stretch">
          
          {/* LEFT: Info & Details Panels */}
          <div className="lg:col-span-5 space-y-8 flex flex-col justify-between">
            
            <div className="space-y-6">
              <div>
                <span className="text-[10px] font-black tracking-widest text-[#019342] uppercase bg-[#019342]/10 px-4 py-1.5 rounded-full inline-block mb-3">
                  Informasi Kontak
                </span>
                <h2 className="text-2xl font-black text-[#191654] tracking-tight">
                  Media Komunikasi &amp; Alamat Resmi
                </h2>
                <p className="text-xs font-semibold text-slate-400 mt-1">
                  Pilihlah salah satu sarana komunikasi yang paling nyaman bagi Anda.
                </p>
              </div>

              {/* Items Card List */}
              <div className="space-y-4">
                
                {/* 1. Address */}
                <div className="flex items-start gap-4 p-5 bg-white border border-slate-150 rounded-2xl">
                  <div className="h-10 w-10 shrink-0 rounded-xl bg-[#019342]/10 border border-[#019342]/20 flex items-center justify-center text-[#019342]">
                    <MapPin className="h-5 w-5" />
                  </div>
                  <div>
                    <h3 className="text-xs font-black uppercase text-slate-400 tracking-wider">Alamat Kampus</h3>
                    <p className="text-xs font-bold text-slate-700 leading-relaxed mt-1">
                      {address}
                    </p>
                  </div>
                </div>

                {/* 2. Telephone Lines */}
                <div className="flex items-start gap-4 p-5 bg-white border border-slate-150 rounded-2xl">
                  <div className="h-10 w-10 shrink-0 rounded-xl bg-[#191654]/10 border border-[#191654]/20 flex items-center justify-center text-[#191654]">
                    <Phone className="h-5 w-5" />
                  </div>
                  <div>
                    <h3 className="text-xs font-black uppercase text-slate-400 tracking-wider">Telepon &amp; Fax</h3>
                    <p className="text-xs font-bold text-slate-700 leading-normal mt-1 flex flex-wrap gap-x-3">
                      <span>{phone}</span>
                    </p>
                  </div>
                </div>

                {/* 3. Official Email */}
                <div className="flex items-start gap-4 p-5 bg-white border border-slate-150 rounded-2xl">
                  <div className="h-10 w-10 shrink-0 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600">
                    <Mail className="h-5 w-5" />
                  </div>
                  <div>
                    <h3 className="text-xs font-black uppercase text-slate-400 tracking-wider">E-Mail Resmi</h3>
                    <a 
                      href={`mailto:${email}`} 
                      className="text-xs font-extrabold text-[#019342] hover:underline mt-1 block"
                    >
                      {email}
                    </a>
                    <p className="text-[10px] text-slate-400 font-semibold mt-0.5">Surat menyurat resmi &amp; administrasi umum.</p>
                  </div>
                </div>

              </div>
            </div>

            {/* Whatsapp Direct Channels Grid list */}
            <div className="bg-[#eefcf5] border border-emerald-100 rounded-3xl p-6">
              <h3 className="text-xs font-black text-[#019342] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <MessageCircle className="h-4 w-4 fill-emerald-100 animate-pulse" />
                <span>Konsultasi Instan Via WhatsApp</span>
              </h3>
              <p className="text-[10px] text-emerald-700 font-bold mb-4">
                Hubungi staf pelayanan kami langsung secara cepat (Fast Response):
              </p>

              <div className="space-y-3">
                {whatsappContacts.length ? whatsappContacts.map((contact, i) => (
                  <a
                    key={i}
                    href={`https://wa.me/${contact.phone}?text=Assalamu%20Alaikum%20SMA%20Al-Ghazaly%2C%20saya%20ingin%20bertanya%20mengenai%20sekolah.`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center justify-between p-3 bg-white hover:bg-emerald-50 rounded-xl border border-emerald-50 transition-all duration-200 group"
                  >
                    <div className="overflow-hidden">
                      <p className="text-xs font-extrabold text-[#191654] leading-tight group-hover:text-[#019342] transition-colors">{contact.name}</p>
                      <p className="text-[9.5px] text-slate-400 font-semibold mt-1">{contact.role}</p>
                    </div>
                    
                    <span className="flex items-center gap-1 px-2.5 py-1 text-[9.5px] font-black tracking-wider uppercase rounded-md bg-[#019342] text-white shrink-0 shadow-sm ml-2">
                      Hubungi
                    </span>
                  </a>
                )) : (
                  <div className="rounded-xl border border-dashed border-emerald-100 bg-white p-4 text-[10px] font-bold text-emerald-700">
                    Nomor WhatsApp belum diatur di admin.
                  </div>
                )}
              </div>
            </div>

          </div>

          {/* RIGHT: High-class Inquiry Contact Form */}
          <div className="lg:col-span-7 bg-white border border-slate-150 rounded-3xl p-6 md:p-8 flex flex-col justify-between shadow-[0_12px_36px_rgba(0,0,0,0.015)]">
            
            <div className="mb-6">
              <h3 className="text-lg font-black text-[#191654] tracking-tight">Kirim Pesan Layanan</h3>
              <p className="text-xs text-slate-400 font-semibold mt-1">
                Kirimkan masukan atau permohonan informasi Anda melalui formulir terenkripsi di bawah ini.
              </p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              
              {/* Error Alert Box */}
              {submitError && (
                <div className="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center gap-2">
                  <AlertCircle className="h-4.5 w-4.5 shrink-0 text-rose-500" />
                  <span>{submitError}</span>
                </div>
              )}

              {/* Success Banner */}
              <AnimatePresence>
                {submitSuccess && (
                  <motion.div
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0 }}
                    className="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold leading-relaxed flex items-start gap-3 shadow-inner"
                  >
                    <CheckCircle className="h-5 w-5 text-emerald-500 shrink-0 mt-0.5 animate-bounce-slow" />
                    <div>
                      <span className="block font-black">Pesan Berhasil Terkirim!</span>
                      <span className="font-semibold block mt-0.5 text-[11px] text-emerald-600">
                        Terima kasih telah berkomunikasi dengan SMA Al-Ghazaly Bogor. Sekretaris kami akan memproses formulir ini ke tim pelayanan terkait selambat-lambatnya 1x24 jam kerja ke e-mail Anda.
                      </span>
                    </div>
                  </motion.div>
                )}
              </AnimatePresence>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap <span className="text-rose-500">*</span></label>
                  <input
                    type="text"
                    name="name"
                    required
                    value={form.name}
                    onChange={handleInputChange}
                    placeholder="Contoh: Muhammad Akhyar"
                    className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                  />
                </div>

                <div>
                  <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Alamat Email <span className="text-rose-500">*</span></label>
                  <input
                    type="email"
                    name="email"
                    required
                    value={form.email}
                    onChange={handleInputChange}
                    placeholder="Contoh: akhyar@gmail.com"
                    className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">No. Handphone / WhatsApp</label>
                  <input
                    type="tel"
                    name="phone"
                    value={form.phone}
                    onChange={handleInputChange}
                    placeholder="Contoh: 081234567890"
                    className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold"
                  />
                </div>

                <div>
                  <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Topik Pertanyaan</label>
                  <select
                    name="subject"
                    value={form.subject}
                    onChange={handleInputChange}
                    className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-black"
                  >
                    <option value="informasi_umum">Informasi Akademik &amp; Umum</option>
                    <option value="penerimaan_baru">Penerimaan Siswa Baru (PPDB)</option>
                    <option value="administrasi">Urusan Administrasi / TU</option>
                    <option value="sarana_prasarana">Kunjungan Sarana &amp; Fasilitas</option>
                    <option value="pengaduan_saran">Kritik, Saran &amp; Pengaduan</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Isi Pesan / Pertanyaan <span className="text-rose-500">*</span></label>
                <textarea
                  name="message"
                  required
                  rows={4}
                  value={form.message}
                  onChange={handleInputChange}
                  placeholder="Tuliskan pesan pertanyaan Anda secara lengkap dan santun..."
                  className="w-full px-4 py-3 rounded-xl text-xs bg-slate-50 border border-slate-200 outline-none focus:bg-white focus:ring-2 focus:ring-[#019342]/20 focus:border-[#019342] text-slate-800 font-semibold resize-none"
                />
              </div>

              <div className="pt-2">
                <button
                  id="submit-contact-form"
                  type="submit"
                  disabled={isSubmitting}
                  className="w-full py-3.5 px-6 rounded-xl bg-[#019342] hover:bg-[#191654] text-white text-xs font-black tracking-widest uppercase shadow transition-all duration-350 flex items-center justify-center gap-2"
                >
                  {isSubmitting ? (
                    <>
                      <div className="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin" />
                      <span>Mengirimkan Pesan...</span>
                    </>
                  ) : (
                    <>
                      <Send className="h-4 w-4" />
                      <span>Kirimkan Pesan Sekarang</span>
                    </>
                  )}
                </button>
              </div>

            </form>

            {/* Note support bar */}
            <div className="mt-6 border-t border-slate-100 pt-5 flex items-center gap-1.5 text-[10px] text-slate-400 font-bold">
              <Clock className="h-3.5 w-3.5 text-[#019342]" />
              <span>Tim Humas melayani pesan masuk Senin-Sabtu. Respons hari libur kondisional.</span>
            </div>

          </div>

        </div>

        {/* 3. Embedded Google Maps - High Fidelity Layout */}
        <section id="google-maps-location-frame" className="mb-20">
          
          <div className="flex flex-col md:flex-row items-start md:items-end justify-between mb-8 pb-4 border-b border-slate-100">
            <div>
              <span className="text-[10px] font-black tracking-widest text-[#019342] uppercase bg-[#019342]/10 px-4 py-1.5 rounded-full inline-block">
                Peta Lokasi
              </span>
              <h2 className="text-2xl font-black text-[#191654] tracking-tight mt-3">
                Titik Koordinat Kampus
              </h2>
              <p className="text-xs font-semibold text-slate-400 mt-1">
                Gunakan arahan peta satelit berikut untuk memandu kunjungan berkendara atau berjalan kaki Anda.
              </p>
            </div>
          </div>

          {/* Map Iframe Wrapper with premium Card styling and Float stats overlay */}
          <div className="relative rounded-3xl overflow-hidden border-2 border-slate-100 shadow-[0_15px_40px_rgba(0,0,0,0.02)] bg-slate-100">
            {mapEmbedUrl ? (
              <iframe
                id="google-maps-embed-iframe"
                src={mapEmbedUrl}
                width="100%"
                height="450"
                style={{ border: 0 }}
                allowFullScreen={true}
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                title="Lokasi Koordinat SMA Al-Ghazaly Bogor"
                className="w-full filter grayscale-[10%] contrast-[105%] hover:grayscale-0 transition-all duration-700"
              />
            ) : (
              <div className="w-full h-[450px] flex flex-col items-center justify-center gap-3 text-slate-400">
                <svg className="h-10 w-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                <p className="text-xs font-semibold">Peta belum dikonfigurasi</p>
                <p className="text-[10px]">Isi Latitude &amp; Longitude di menu Pengaturan → Lokasi</p>
              </div>
            )}

          </div>

        </section>

        {/* 4. FAQ Brief Box */}
        <section id="faq-quick-section" className="mb-8 p-8 bg-slate-50 border border-slate-150 rounded-3xl">
          <div className="flex items-center gap-2 mb-6">
            <HelpCircle className="h-5 w-5 text-[#019342]" />
            <h3 className="text-base font-black text-[#191654]">Tanya Jawab (FAQ) Ringkas Kunjungan &amp; Layanan</h3>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {faqList.map((faq, idx) => (
              <div key={idx} className="space-y-2 bg-white p-5 rounded-2xl border border-slate-100">
                <p className="text-xs font-extrabold text-[#191654] leading-snug">
                  {faq.q}
                </p>
                <p className="text-[11px] font-semibold text-slate-500 leading-relaxed">
                  {faq.a}
                </p>
              </div>
            ))}
          </div>
        </section>

      </div>

    </div>
  );
}
