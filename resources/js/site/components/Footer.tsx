import { useEffect, useState } from 'react';
import { Building2, Phone, Mail, FileText, ArrowUp, Facebook, Twitter, Youtube, CheckCircle } from 'lucide-react';
import { getSettings } from '../api';

export default function Footer() {
  const [settings, setSettings] = useState<Record<string, string>>({});

  useEffect(() => {
    getSettings().then(setSettings).catch(() => setSettings({}));
  }, []);

  const address = settings.address || settings.school_address || 'Alamat belum diatur.';
  const phone = settings.phone || settings.school_phone || 'Telepon belum diatur';
  const email = settings.email || settings.school_email || 'Email belum diatur';
  const whatsapp = (settings.whatsapp || settings.school_whatsapp || '').replace(/\D/g, '');

  const handleScrollTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleLinkClick = (hash: string) => {
    const el = document.querySelector(hash);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  return (
    <footer className="relative bg-zinc-950 text-white pt-24 pb-12 overflow-hidden border-t border-zinc-900">
      {/* Visual glowing elements */}
      <div className="absolute -bottom-10 left-10 w-96 h-96 bg-zinc-900/10 rounded-full blur-[120px] pointer-events-none" />
      <div className="absolute top-10 right-10 w-80 h-80 bg-zinc-900/5 rounded-full blur-3xl pointer-events-none" />

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10 space-y-16 font-sans">
        
        {/* Upper section: Contact and Newsletter box */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 border-b border-zinc-800 pb-12 items-center">
          <div className="lg:col-span-6 space-y-4 text-left">
            <h3 className="text-lg font-black flex items-center gap-2 uppercase tracking-wide text-zinc-100">
              <span className="h-2 w-2 rounded-full bg-primary-green animate-pulse" />
              Yayasan Islamic Centre Al-Ghazaly Bogor
            </h3>
            <p className="text-xs text-zinc-400 leading-relaxed font-semibold max-w-lg">
              Kami berdedikasi menciptakan sinergi unggul antara ilmu pengetahuan modern, kecakapan analitis kritis, serta keluhuran akhlak mulia berlandaskan nilai-nilai dasar Al-Qur'an.
            </p>
          </div>

          <div className="lg:col-span-6">
            <div className="bg-zinc-900/60 border border-zinc-850 rounded-3xl p-5 sm:p-6 text-left relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
              <div className="space-y-1 w-full">
                <span className="text-[9px] font-black text-zinc-400 uppercase tracking-widest block">Pertanyaan Lebih Lanjut?</span>
                <span className="text-sm font-extrabold text-white block uppercase tracking-wide">Hubungi Humas Sekolah</span>
                <p className="text-[10px] text-zinc-500 font-medium">Layanan respons cepat untuk konsultasi pendaftaran kelas.</p>
              </div>

              <a
                id="footer-whatsapp-chat"
                href={whatsapp ? `https://wa.me/${whatsapp}` : '#'}
                target="_blank"
                rel="noopener noreferrer"
                className="w-full sm:w-auto shrink-0 bg-primary-green hover:bg-hover-blue text-primary-white px-5 py-3 rounded-full text-xs font-black uppercase tracking-wider transition text-center flex items-center justify-center gap-1.5"
              >
                Chat WhatsApp
                <CheckCircle className="h-4 w-4" />
              </a>
            </div>
          </div>
        </div>

        {/* Middle grid section: Link matrices */}
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-10 text-left">
          
          {/* Col 1: Brand details (md:col-span-4) */}
          <div className="md:col-span-4 space-y-6">
            <div className="flex items-center gap-2">
              <div className="h-10 w-10 flex items-center justify-center rounded-xl bg-primary-green text-primary-white">
                <Building2 className="h-5 w-5" />
              </div>
              <div className="flex flex-col">
                <span className="text-sm font-black tracking-wider leading-none uppercase">SMA Al-Ghazaly</span>
                <span className="text-[8px] font-black tracking-widest text-zinc-500 uppercase mt-1">Islamic Centre Bogor</span>
              </div>
            </div>

            <p className="text-[11px] text-zinc-400 leading-relaxed font-semibold">
              {address}
            </p>

            {/* Social icons */}
            <div className="flex items-center gap-3">
              <a
                id="footer-social-fb"
                href="https://facebook.com"
                target="_blank"
                rel="noreferrer"
                className="h-9 w-9 flex items-center justify-center rounded-xl bg-zinc-900 text-zinc-400 hover:bg-primary-green hover:text-primary-white transition cursor-pointer"
                aria-label="Facebook Link"
              >
                <Facebook className="h-4 w-4" />
              </a>
              <a
                id="footer-social-tw"
                href="https://twitter.com"
                target="_blank"
                rel="noreferrer"
                className="h-9 w-9 flex items-center justify-center rounded-xl bg-zinc-900 text-zinc-400 hover:bg-primary-green hover:text-primary-white transition cursor-pointer"
                aria-label="Twitter Link"
              >
                <Twitter className="h-4 w-4" />
              </a>
              <a
                id="footer-social-yt"
                href="https://youtube.com"
                target="_blank"
                rel="noreferrer"
                className="h-9 w-9 flex items-center justify-center rounded-xl bg-zinc-900 text-zinc-400 hover:bg-primary-green hover:text-primary-white transition cursor-pointer"
                aria-label="YouTube Link"
              >
                <Youtube className="h-4 w-4" />
              </a>
            </div>
          </div>

          {/* Col 2: Tautan (md:col-span-2) */}
          <div className="md:col-span-3 space-y-4">
            <h4 className="text-[10px] font-black uppercase text-zinc-100 tracking-wider">Tautan Utama</h4>
            <ul className="space-y-2.5 text-xs text-zinc-400 font-semibold">
              <li>
                <button
                  id="foot-link-home"
                  onClick={() => handleLinkClick('#hero')}
                  className="hover:text-primary-green transition cursor-pointer text-left"
                >
                  Beranda Website
                </button>
              </li>
              <li>
                <a
                  id="foot-link-ppdb"
                  href="https://wa.me/${whatsapp}"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="hover:text-primary-green transition cursor-pointer text-left inline-block"
                >
                  Penerimaan PPDB 2026
                </a>
              </li>
              <li>
                <button
                  id="foot-link-ann"
                  onClick={() => handleLinkClick('#announcements-section')}
                  className="hover:text-primary-green transition cursor-pointer text-left"
                >
                  Pengumuman Akademik
                </button>
              </li>
              <li>
                <button
                  id="foot-link-evt"
                  onClick={() => handleLinkClick('#events-section')}
                  className="hover:text-primary-green transition cursor-pointer text-left"
                >
                  Agenda &amp; Kegiatan
                </button>
              </li>
              <li>
                <a
                  id="foot-link-faq"
                  href="https://wa.me/${whatsapp}"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="hover:text-primary-green transition cursor-pointer text-left inline-block"
                >
                  Tanya Jawab (WhatsApp)
                </a>
              </li>
            </ul>
          </div>

          {/* Col 3: Kategori (md:col-span-2) */}
          <div className="md:col-span-2 space-y-4">
            <h4 className="text-[10px] font-black uppercase text-zinc-100 tracking-wider">Kategori</h4>
            <ul className="space-y-2.5 text-xs text-zinc-400 font-semibold">
              <li className="flex items-center gap-1.5">
                <FileText className="h-3.5 w-3.5 text-zinc-600" />
                <span>Kegiatan OSIS</span>
              </li>
              <li className="flex items-center gap-1.5">
                <FileText className="h-3.5 w-3.5 text-zinc-600" />
                <span>Artikel Pendidikan</span>
              </li>
              <li className="flex items-center gap-1.5">
                <FileText className="h-3.5 w-3.5 text-zinc-600" />
                <span>Karya Guru</span>
              </li>
              <li className="flex items-center gap-1.5">
                <FileText className="h-3.5 w-3.5 text-zinc-600" />
                <span>Galeri Foto</span>
              </li>
            </ul>
          </div>

          {/* Col 4: Kontak Informasi (md:col-span-4) */}
          <div className="md:col-span-3 space-y-4">
            <h4 className="text-[10px] font-black uppercase text-zinc-100 tracking-wider">Kontak Resmi</h4>
            <ul className="space-y-3.5 text-xs text-zinc-400 font-semibold">
              <li className="flex items-start gap-2.5">
                <Phone className="h-4 w-4 text-zinc-500 shrink-0 mt-0.5" />
                <div className="flex flex-col text-[11px]">
                  <span>{phone}</span>
                </div>
              </li>
              <li className="flex items-start gap-2.5">
                <Mail className="h-4 w-4 text-zinc-500 shrink-0 mt-0.5" />
                <span className="text-[11px] truncate">{email}</span>
              </li>
              <li className="flex items-start gap-2.5">
                <Building2 className="h-4 w-4 text-zinc-500 shrink-0 mt-0.5" />
                <span className="text-[11px]">Senin - Jumat: 07:00 - 15:30 WIB</span>
              </li>
            </ul>
          </div>

        </div>

        {/* Lower row: copyright, scroll to top */}
        <div className="flex flex-col sm:flex-row items-center justify-between gap-6 pt-8 border-t border-zinc-900">
          <p className="text-[10px] text-zinc-500 font-semibold tracking-wider text-center sm:text-left">
            Copyright &copy; 2026 Yayasan Islamic Centre Al-Ghazaly Bogor. All Rights Reserved. Mewujudkan Insan Berkualitas.
          </p>
          
          <button
            id="to-top-btn"
            onClick={handleScrollTop}
            className="group h-10 w-10 flex items-center justify-center rounded-full bg-zinc-900 border border-zinc-805 text-zinc-400 hover:bg-primary-green hover:text-primary-white transition shadow-sm cursor-pointer"
            aria-label="Scroll to top"
          >
            <ArrowUp className="h-4 w-4 group-hover:-translate-y-0.5 transition" />
          </button>
        </div>

      </div>
    </footer>
  );
}
