import { useEffect, useState } from 'react';
import { motion } from 'motion/react';
import { ArrowRight, BookOpen, Award, ShieldCheck, Sparkles } from 'lucide-react';
import { getStats, type SiteStats } from '../api';

interface HeroProps {
  onOpenRegisterForm?: () => void;
}

export default function Hero({ onOpenRegisterForm }: HeroProps) {
  const [stats, setStats] = useState<SiteStats | null>(null);

  useEffect(() => {
    getStats().then(setStats).catch(() => {});
  }, []);

  const handleLearnMore = () => {
    const section = document.querySelector('#programs-section');
    if (section) {
      section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  return (
    <section
      id="hero"
      className="relative min-h-screen flex items-center justify-center pt-28 pb-16 overflow-hidden bg-slate-50"
    >
      {/* Super subtle decorative grid pattern */}
      <div className="absolute inset-0 opacity-4 bg-[linear-gradient(to_right,#000_1px,transparent_1px),linear-gradient(to_bottom,#000_1px,transparent_1px)] [background-size:20px_20px]" />
      
      {/* Soft ambient light blurs */}
      <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-slate-200 rounded-full blur-[130px] opacity-60" />
      <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary-green/10 rounded-full blur-[130px] opacity-40" />

      <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 md:py-20 z-10 w-full">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          
          {/* Left Column: Heading & Content */}
          <div className="lg:col-span-7 space-y-6 text-left">
            <motion.div
              initial={{ opacity: 0, y: -20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6 }}
              className="inline-flex items-center gap-2 rounded-full bg-slate-200/80 border border-slate-300/40 px-4 py-1.5 text-[11px] font-bold text-slate-800 backdrop-blur-sm"
            >
              <Sparkles className="h-3.5 w-3.5 text-amber-500" />
              <span>PPDB TA 2026/2027 Telah Dibuka Resmi</span>
            </motion.div>

            <motion.h1
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.15 }}
              className="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight font-sans tracking-tight"
            >
              Mewujudkan <span className="text-transparent bg-clip-text bg-gradient-to-r from-primary-green to-hover-blue">Insan Berkualitas</span> &amp; Berakhlak Mulia
            </motion.h1>

            <motion.p
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.25 }}
              className="text-xs sm:text-sm text-slate-550 leading-relaxed max-w-xl font-medium"
            >
              Yayasan Islamic Centre Al-Ghazaly Bogor. Sekolah Menengah Atas dengan kurikulum Merdeka terintegrasi pembinaan moral kepribadian yang luhur, dan sistem asrama (boarding) maupun reguler (fullday) berkualitas tinggi.
            </motion.p>

            {/* Accreditation details */}
            <motion.div
              initial={{ opacity: 0, y: 15 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.35 }}
              className="flex flex-wrap items-center gap-3.5 text-xs font-medium text-slate-600"
            >
              <div className="flex items-center gap-1.5 bg-white border border-slate-200 rounded-full px-4 py-1.5 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                <ShieldCheck className="h-4 w-4 text-primary-green" />
                <span>Terakreditasi: <strong className="text-slate-900">A (Amat Baik)</strong></span>
              </div>
              <div className="flex items-center gap-1.5 bg-white border border-slate-200 rounded-full px-4 py-1.5 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                <Award className="h-4 w-4 text-amber-500" />
                <span>Status: <strong className="text-slate-900">Islamic Centre</strong></span>
              </div>
            </motion.div>

            {/* Call to Actions */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.45 }}
              className="flex flex-wrap gap-4 pt-2"
            >
              <button
                id="hero-register-btn"
                onClick={onOpenRegisterForm}
                className="px-8 py-3.5 text-xs font-black tracking-wider uppercase rounded-full bg-primary-green text-primary-white shadow-sm hover:bg-hover-blue cursor-pointer transition flex items-center gap-2 inline-flex"
              >
                Daftar Sekarang
                <ArrowRight className="h-4 w-4" />
              </button>
              
              <button
                id="hero-detail-btn"
                onClick={handleLearnMore}
                className="px-7 py-3.5 text-xs font-black tracking-wider uppercase rounded-full border border-slate-200 bg-white text-slate-800 hover:text-hover-blue hover:bg-slate-50 transition shadow-sm cursor-pointer"
              >
                Informasi Lanjut
              </button>
            </motion.div>
          </div>

          {/* Right Column: Dynamic floating interactive card grid */}
          <div className="lg:col-span-5 relative mt-6 lg:mt-0">
            {/* Main Stats Bento Card */}
            <motion.div
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 0.7, delay: 0.3 }}
              className="relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] space-y-5"
            >
              <div className="flex justify-between items-center border-b border-slate-100 pb-3.5">
                <div className="flex items-center gap-2">
                  <div className="h-2 w-2 rounded-full bg-primary-green animate-pulse" />
                  <span className="text-[9px] font-black text-slate-800 uppercase tracking-widest">Live Enrollment Stats</span>
                </div>
                <BookOpen className="h-4 w-4 text-slate-400" />
              </div>

              {/* Statistics Grid */}
              <div className="grid grid-cols-2 gap-4">
                <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100/90 hover:border-slate-200/80 transition-colors">
                  <span className="block text-2xl font-black text-slate-900">
                    {stats ? `${stats.accepted_registrations}` : '—'}
                  </span>
                  <p className="text-[9px] uppercase font-bold text-slate-500 mt-1 tracking-wider leading-relaxed">Siswa Diterima PPDB</p>
                </div>

                <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100/90 hover:border-slate-200/80 transition-colors">
                  <span className="block text-2xl font-black text-slate-900">
                    {stats ? `${stats.active_teachers}` : '—'}
                  </span>
                  <p className="text-[9px] uppercase font-bold text-slate-500 mt-1 tracking-wider leading-relaxed">Pengajar Aktif</p>
                </div>

                <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100/90 hover:border-slate-200/80 transition-colors">
                  <span className="block text-2xl font-black text-slate-900">100%</span>
                  <p className="text-[9px] uppercase font-bold text-slate-500 mt-1 tracking-wider leading-relaxed">Kelulusan &amp; Sukses</p>
                </div>

                <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100/90 hover:border-slate-200/80 transition-colors">
                  <span className="block text-2xl font-black text-slate-900">
                    {stats ? `${stats.ekskul_count}` : '—'}
                  </span>
                  <p className="text-[9px] uppercase font-bold text-slate-500 mt-1 tracking-wider leading-relaxed">Ekskul Unggulan</p>
                </div>
              </div>
            </motion.div>

            {/* Small decorative floating card - with real photo context */}
            <motion.div
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.6, delay: 0.6 }}
              className="absolute -bottom-6 -left-6 hidden sm:flex items-center gap-3 bg-white border border-slate-150 p-3 rounded-2xl shadow-md"
            >
              <div className="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-900 font-extrabold text-xs shadow-sm">
                A
              </div>
              <div className="flex flex-col">
                <span className="text-xs font-extrabold text-slate-900 leading-none">Terbuka Untuk Umum</span>
              </div>
            </motion.div>
          </div>
          
        </div>
      </div>
    </section>
  );
}
