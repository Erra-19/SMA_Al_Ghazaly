import { motion } from 'motion/react';
import { Calendar, MapPin, Clock, ArrowRight, Eye, Sparkles } from 'lucide-react';
import { EventActivity, Post } from '../types';

interface EventsTimelineProps {
  onSelectEvent: (evt: EventActivity) => void;
  onSelectPost: (post: Post) => void;
  events?: EventActivity[];
  posts?: Post[];
}

export default function EventsTimeline({ onSelectEvent, onSelectPost, events = [], posts = [] }: EventsTimelineProps) {
  return (
    <section id="events-section" className="py-24 bg-white relative border-t border-slate-100">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="text-center space-y-3 mb-16">
          <span className="inline-block rounded-full bg-slate-100 border border-slate-200/60 px-3.5 py-1 text-[10px] font-bold text-slate-800 uppercase tracking-widest">
            Aktivitas Terkini &amp; Agenda
          </span>
          <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-sans">
            Kegiatan Akademik &amp; Berita Sekolah
          </h2>
          <p className="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto font-medium">
            Pantau dokumentasi kegiatan harian siswa serta kalender agenda terdekat di lingkungan SMA Al-Ghazaly Bogor.
          </p>
        </div>

        {/* Bento Split layout */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          
          {/* Left Block: Posts / Articles (lg:col-span-7) */}
          <div className="lg:col-span-7 space-y-6">
            <div className="flex items-center justify-between border-b border-slate-150 pb-4">
              <h3 className="text-sm font-black text-slate-900 flex items-center gap-2 uppercase tracking-wide">
                <Sparkles className="h-4.5 w-4.5 text-slate-950 animate-pulse" />
                Kabar Berita &amp; Artikel Opini
              </h3>
              <span className="text-[10px] font-black text-slate-400 uppercase tracking-wider">Total {posts.length} Artikel</span>
            </div>

            <div className="grid grid-cols-1 gap-6">
              {posts.map((post, i) => (
                <motion.div
                  key={post.id}
                  initial={{ opacity: 0, x: -15 }}
                  whileInView={{ opacity: 1, x: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: i * 0.1 }}
                  onClick={() => onSelectPost(post)}
                  className="group cursor-pointer flex flex-col sm:flex-row items-stretch gap-5 bg-white rounded-2xl p-4 border border-slate-200/70 hover:border-slate-350 hover:shadow-[0_8px_24px_rgba(0,0,0,0.015)] transition-all duration-300"
                >
                  {/* Thumbnail */}
                  <div className="sm:w-44 h-36 shrink-0 rounded-xl overflow-hidden bg-slate-50 relative border border-slate-100">
                    <img
                      src={post.image}
                      referrerPolicy="no-referrer"
                      alt={post.title}
                      className="w-full h-full object-cover transition duration-500 group-hover:scale-102"
                    />
                    <span className="absolute bottom-2.5 left-2.5 inline-block rounded-full bg-primary-green px-2.5 py-0.5 text-[8px] font-black text-primary-white uppercase tracking-wider">
                      {post.category}
                    </span>
                  </div>

                  {/* Body Content */}
                  <div className="flex-1 flex flex-col justify-between py-1">
                    <div className="space-y-2">
                      <div className="flex items-center gap-3 text-[9px] font-black uppercase tracking-wider text-slate-400">
                        <span>{post.date}</span>
                        <span>•</span>
                        <span>{post.readTime}</span>
                      </div>
                      
                      <h4 className="text-xs font-extrabold text-slate-900 group-hover:text-black transition leading-snug line-clamp-2">
                        {post.title}
                      </h4>
                      
                      <p className="text-[11px] text-slate-500 line-clamp-2 leading-relaxed font-semibold">
                        {post.excerpt}
                       </p>
                    </div>

                    <div className="flex items-center gap-1.5 text-[10px] text-slate-800 font-extrabold group-hover:text-hover-blue transition-colors mt-3 sm:mt-0 uppercase tracking-wider">
                      <span>Baca Artikel</span>
                      <ArrowRight className="h-3.5 w-3.5" />
                    </div>
                  </div>
                </motion.div>
              ))}
            </div>
          </div>

          {/* Right Block: Interactive Calendar Agenda (lg:col-span-5) */}
          <div className="lg:col-span-5 space-y-6">
            <div className="flex items-center justify-between border-b border-slate-150 pb-4">
              <h3 className="text-sm font-black text-slate-900 flex items-center gap-2 uppercase tracking-wide">
                <Calendar className="h-4.5 w-4.5 text-slate-950" />
                Kalender Acara Mendatang
              </h3>
              <span className="text-[9px] font-black tracking-widest text-slate-800 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full">UPCOMING</span>
            </div>

            <div className="space-y-5">
              {events.map((evt, i) => (
                <motion.div
                  key={evt.id}
                  initial={{ opacity: 0, x: 15 }}
                  whileInView={{ opacity: 1, x: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: i * 0.1 }}
                  onClick={() => onSelectEvent(evt)}
                  className="group cursor-pointer flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200/70 hover:border-slate-350 hover:shadow-[0_8px_24px_rgba(0,0,0,0.015)] transition-all duration-300"
                >
                  {/* Calendar icon box — always shows start date only */}
                  <div className="shrink-0 flex flex-col items-center justify-center w-14 rounded-xl bg-primary-green/5 border border-primary-green/10 text-center shadow-none transition-all duration-300 group-hover:bg-primary-green group-hover:border-primary-green group-hover:text-primary-white px-1 py-2">
                    <span className="text-[9px] uppercase font-black text-primary-green tracking-wider group-hover:text-primary-white transition-colors">
                      {evt.date.month}
                    </span>
                    <span className="text-xl font-black text-slate-950 group-hover:text-primary-white leading-none mt-1 transition-colors">
                      {evt.date.day}
                    </span>
                    <span className="text-[8px] font-bold text-slate-400 group-hover:text-primary-white/80 mt-0.5 transition-colors">
                      {evt.date.year}
                    </span>
                  </div>

                  {/* Info details */}
                  <div className="flex-1 space-y-2">
                    <div className="flex items-center gap-2 flex-wrap">
                      <span className="inline-block rounded-full bg-slate-100 border border-slate-200/60 px-2.5 py-0.5 text-[9px] font-extrabold text-slate-800 uppercase tracking-wide">
                        {evt.category}
                      </span>
                      {evt.endDate && (
                        <span className="text-[9px] font-semibold text-slate-400">
                          s/d {evt.endDate.day} {evt.endDate.month} {evt.endDate.year}
                        </span>
                      )}
                    </div>

                    <h4 className="text-xs font-black text-slate-900 group-hover:text-black transition leading-snug line-clamp-1 uppercase tracking-wide">
                      {evt.title}
                    </h4>

                    {/* Metadata indicators */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[10px] text-slate-400 font-semibold pt-1">
                      <div className="flex items-center gap-1">
                        <Clock className="h-3.5 w-3.5 text-slate-400" />
                        <span className="truncate">{evt.time}</span>
                      </div>
                      <div className="flex items-center gap-1">
                        <MapPin className="h-3.5 w-3.5 text-slate-400" />
                        <span className="truncate">{evt.location}</span>
                      </div>
                    </div>
                  </div>

                  {/* Micro interaction */}
                  <div className="self-center p-1.5 opacity-0 group-hover:opacity-100 transition rounded-full bg-primary-green text-primary-white hover:bg-hover-blue shadow-sm">
                    <Eye className="h-3.5 w-3.5" />
                  </div>
                </motion.div>
              ))}
            </div>

            {/* Quick Helper text */}
            <div className="rounded-2xl bg-primary-green text-primary-white p-5 relative overflow-hidden shadow-none">
              <div className="absolute right-0 bottom-0 opacity-10 translate-x-5 translate-y-5 text-primary-white">
                <Calendar className="h-32 w-32" />
              </div>
              <div className="relative z-10 space-y-2">
                <span className="text-[8px] font-black text-primary-white uppercase tracking-widest bg-primary-white/15 px-2.5 py-0.5 rounded-full inline-block">Sistem Akademik</span>
                <h4 className="text-sm font-extrabold text-primary-white">Kalender Terintegrasi Akademik G-Suite</h4>
                <p className="text-[11px] text-primary-white/90 leading-relaxed max-w-xs font-medium">
                  Seluruh agenda di atas di-sinkronisasikan secara langsung dengan kalender dinas terpadu sekolah untuk memudahkan pengawasan wali murid.
                </p>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section>
  );
}
