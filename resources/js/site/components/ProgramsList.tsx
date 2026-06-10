import { useEffect, useState } from 'react';
import { motion } from 'motion/react';
import { BookOpen, GraduationCap, Cpu, Users, ArrowRight, Award } from 'lucide-react';
import { getPrograms } from '../api';

const iconMap = {
  BookOpen: BookOpen,
  GraduationCap: GraduationCap,
  Cpu: Cpu,
  Users: Users,
};

interface ProgramsListProps {
  onOpenPrograms?: () => void;
}

export default function ProgramsList({ onOpenPrograms }: ProgramsListProps) {
  const [programs, setPrograms] = useState<any[]>([]);

  useEffect(() => {
    getPrograms('unggulan').then(setPrograms).catch(() => setPrograms([]));
  }, []);

  return (
    <section id="programs-section" className="py-24 bg-slate-50 text-slate-900 relative overflow-hidden border-t border-slate-100">
      {/* Super subtle background pattern */}
      <div className="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#000_1px,transparent_1px)] [background-size:20px_20px]" />

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header content section */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end mb-16">
          <div className="lg:col-span-8 space-y-4">
            <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-150 border border-slate-250 px-3.5 py-1 text-[10px] font-black uppercase tracking-wider text-slate-800">
              <Award className="h-4 w-4 text-primary-green animate-pulse" />
              Sains &amp; Religi Terintegrasi
            </span>
            <h2 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">
              Program Unggulan Akademik &amp; Karakter
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 max-w-2xl font-medium leading-relaxed">
              Kami menyelaraskan kurikulum intelektual kementerian dengan pembinaan qolbu dan bekal keterampilan teknologi terdepan untuk menghadirkan alumni berkualitas dunia.
            </p>
          </div>

          <div className="lg:col-span-4 lg:text-right hidden lg:block">
            <a
              id="programs-action-pdf"
              href="#program"
              onClick={(event) => {
                event.preventDefault();
                onOpenPrograms?.();
              }}
              className="inline-flex items-center gap-2 text-[10px] font-black tracking-wider uppercase bg-white border border-slate-200 text-slate-800 rounded-full px-5 py-3 hover:bg-slate-55 hover:text-hover-blue hover:border-hover-blue/40 transition shadow-sm"
            >
              Pelajari Kurikulum Merdeka
              <ArrowRight className="h-4 w-4" />
            </a>
          </div>
        </div>

        {/* Bento/Modern grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {programs.map((prog, i) => {
            const IconComponent = iconMap[prog.icon as keyof typeof iconMap] || BookOpen;
            return (
              <motion.div
                key={prog.id}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.1, duration: 0.5 }}
                onClick={() => onOpenPrograms?.()}
                className="group relative rounded-3xl border border-slate-200/70 bg-white p-6 hover:border-slate-350 hover:shadow-[0_12px_30px_rgba(0,0,0,0.025)] transition-all duration-300 flex flex-col justify-between min-h-[300px]"
              >
                <div className="space-y-5">
                  {/* Icon shape wrapper */}
                  <div className="h-11 w-11 rounded-2xl flex items-center justify-center bg-primary-green/5 text-primary-green border border-primary-green/15 group-hover:bg-primary-green group-hover:text-primary-white group-hover:border-primary-green transition-all duration-300">
                    <IconComponent className="h-5 w-5" />
                  </div>
                  
                  <div className="space-y-2">
                    <h3 className="text-xs font-black text-slate-900 group-hover:text-black transition-colors uppercase tracking-wide">
                      {prog.title}
                    </h3>
                    <p className="text-[11px] text-slate-500 group-hover:text-slate-700 transition-colors leading-relaxed font-semibold">
                      {prog.description}
                    </p>
                  </div>
                </div>

                {/* Arrow hint indicator */}
                <div className="pt-4 border-t border-slate-100 mt-6 flex items-center justify-between">
                  <span className="text-[9px] font-bold text-slate-400 tracking-wider uppercase group-hover:text-primary-green transition-colors">
                    Kurikulum Terpadu
                  </span>
                  <ArrowRight className="h-4 w-4 text-slate-400 group-hover:text-hover-blue group-hover:translate-x-1 transition" />
                </div>
              </motion.div>
            );
          })}
        </div>

        {!programs.length && (
          <div className="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-xs font-bold text-slate-400">
            Belum ada program unggulan yang dipublikasikan.
          </div>
        )}

        {/* Highlight Banner Callout */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="mt-12 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)]"
        >
          <div className="space-y-2 max-w-xl text-left">
            <h4 className="text-sm font-extrabold text-slate-900 flex items-center gap-2">
              <span className="h-2 w-2 rounded-full bg-primary-green animate-ping" />
              Sistem Belajar Sesuai dengan Minat &amp; Bakat Anda!
            </h4>
            <p className="text-xs text-slate-500 leading-relaxed font-semibold">
              SMA Al-Ghazaly membebaskan siswa memilih ko-kurikuler penunjang, didukung bimbingan intensif portofolio SNBP, persiapan mandiri masuk asrama tuntas dan  terarah.
            </p>
          </div>
          
          <button
            id="excel-programs-cta"
            onClick={() => {
              onOpenPrograms?.();
            }}
            className="w-full md:w-auto shrink-0 px-6 py-3.5 rounded-full bg-primary-green hover:bg-hover-blue text-primary-white text-[10px] font-black uppercase tracking-wider transition shadow-sm cursor-pointer"
          >
            Lihat Alur PPDB 2026/2027
          </button>
        </motion.div>

      </div>
    </section>
  );
}
