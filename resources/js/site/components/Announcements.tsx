import { useState, useMemo } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Grid, List, Search, ArrowRight, Calendar, User, Tag } from 'lucide-react';
import { Announcement } from '../types';

interface AnnouncementsProps {
  onSelect: (ann: Announcement) => void;
  searchTerm: string;
  items?: Announcement[];
}

export default function Announcements({ onSelect, searchTerm: headerSearchTerm, items = [] }: AnnouncementsProps) {
  const [activeCategory, setActiveCategory] = useState<string>('Semua');
  const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
  const [localSearch, setLocalSearch] = useState('');

  const categories = ['Semua', 'PPDB', 'Akademik', 'Libur'];

  // Combine global header search and local section search
  const combinedSearch = useMemo(() => {
    return (headerSearchTerm || localSearch).trim().toLowerCase();
  }, [headerSearchTerm, localSearch]);

  const filteredAnnouncements = useMemo(() => {
    return items.filter((ann) => {
      const matchesCategory = activeCategory === 'Semua' || ann.category.toLowerCase() === activeCategory.toLowerCase();
      const matchesKeyword =
        ann.title.toLowerCase().includes(combinedSearch) ||
        ann.summary.toLowerCase().includes(combinedSearch) ||
        ann.content.toLowerCase().includes(combinedSearch);
      return matchesCategory && matchesKeyword;
    });
  }, [activeCategory, combinedSearch, items]);

  return (
    <section id="announcements-section" className="py-24 bg-white relative overflow-hidden border-t border-slate-100">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header content */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
          <div className="space-y-3">
            <span className="inline-block rounded-full bg-slate-100 border border-slate-200/60 px-3.5 py-1 text-[10px] font-bold text-slate-800 uppercase tracking-widest">
              Informasi Terkini
            </span>
            <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-sans">
              Pengumuman Terbaru Sekolah
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
              Akses informasi administratif, jadwal seleksi penerimaan murid baru, serta agenda libur nasional terpusat di sini.
            </p>
          </div>

          {/* Toggle View Mode & Local Input */}
          <div className="flex items-center gap-3">
            <div className="relative max-w-xs hidden sm:block">
              <input
                id="search-ann-input"
                type="text"
                placeholder="Cari pengumuman..."
                value={localSearch}
                onChange={(e) => setLocalSearch(e.target.value)}
                className="w-full pl-8 pr-4 py-2 rounded-full text-xs bg-slate-50 border border-slate-200 outline-none focus:ring-1 focus:ring-slate-950 text-slate-850"
              />
              <Search className="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-slate-400" />
            </div>

            <div className="flex items-center gap-1 rounded-full bg-slate-100 p-1.5 border border-slate-200">
              <button
                id="grid-view-btn"
                onClick={() => setViewMode('grid')}
                className={`p-1.5 rounded-full transition-all ${
                  viewMode === 'grid' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'
                }`}
                aria-label="Grid View"
              >
                <Grid className="h-4 w-4" />
              </button>
              <button
                id="list-view-btn"
                onClick={() => setViewMode('list')}
                className={`p-1.5 rounded-full transition-all ${
                  viewMode === 'list' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'
                }`}
                aria-label="List View"
              >
                <List className="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

        {/* Categories Tab selector */}
        <div className="flex flex-wrap items-center gap-2 mb-8 border-b border-slate-150 pb-4">
          {categories.map((cat) => (
            <button
              key={cat}
              id={`cat-btn-${cat}`}
              onClick={() => setActiveCategory(cat)}
              className={`px-4.5 py-2.5 rounded-full text-xs font-bold transition-all ${
                activeCategory === cat
                  ? 'bg-primary-green text-primary-white shadow-sm hover:bg-hover-blue'
                  : 'bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-hover-blue'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Grid/List View Content with Animation */}
        <AnimatePresence mode="popLayout">
          {filteredAnnouncements.length > 0 ? (
            viewMode === 'grid' ? (
              <motion.div
                key="grid-view"
                initial={{ opacity: 0, y: 15 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -15 }}
                transition={{ duration: 0.4 }}
                className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
              >
                {filteredAnnouncements.map((ann, i) => (
                  <motion.div
                    key={ann.id}
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: i * 0.05 }}
                    className="group relative flex flex-col rounded-2xl border border-slate-200 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.015)] overflow-hidden hover:shadow-[0_12px_30px_rgba(0,0,0,0.035)] hover:border-slate-350 transition-all duration-300 transform hover:-translate-y-0.5 h-full"
                  >
                    {/* Badge Floating */}
                    <div className="absolute top-4 left-4 z-10 flex flex-wrap gap-1.5">
                      <span className="inline-block rounded-full bg-primary-green px-3 py-1 text-[9px] font-black uppercase tracking-wider text-primary-white shadow-sm">
                        {ann.category}
                      </span>
                      <span className={`inline-block rounded-full px-3 py-1 text-[9px] font-bold uppercase tracking-wider ${
                        ann.status === 'Penting' ? 'bg-rose-500 text-white shadow-sm' : 'bg-slate-500 text-white shadow-sm'
                      }`}>
                        {ann.status}
                      </span>
                    </div>

                    {/* Image overlay card */}
                    {ann.image && (
                      <div className="relative h-44 overflow-hidden bg-slate-100">
                        <img
                          src={ann.image}
                          referrerPolicy="no-referrer"
                          alt={ann.title}
                          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-102"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent" />
                      </div>
                    )}

                    {/* Card Content Body */}
                    <div className="flex-1 p-6 flex flex-col justify-between">
                      <div className="space-y-3">
                        {/* Meta bar */}
                        <div className="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                          <span className="flex items-center gap-1.5">
                            <Calendar className="h-3.5 w-3.5 text-slate-400" />
                            {ann.date}
                          </span>
                          <span>•</span>
                          <span className="flex items-center gap-1.5">
                            <User className="h-3.5 w-3.5 text-slate-400" />
                            {ann.author}
                          </span>
                        </div>

                        <h3 className="text-sm font-extrabold text-slate-900 group-hover:text-black transition line-clamp-2">
                          {ann.title}
                        </h3>

                        <p className="text-xs text-slate-550 line-clamp-3 leading-relaxed">
                          {ann.summary}
                        </p>
                      </div>

                      {/* CTA link */}
                      <div className="pt-4 border-t border-slate-100 mt-4">
                        <button
                          id={`ann-grid-btn-${ann.id}`}
                          onClick={() => onSelect(ann)}
                          className="text-[10px] font-bold tracking-wider uppercase text-slate-800 hover:text-hover-blue flex items-center gap-1.5 transition-all group/btn"
                        >
                          Selengkapnya
                          <ArrowRight className="h-3.5 w-3.5 group-hover/btn:translate-x-1 transition" />
                        </button>
                      </div>
                    </div>
                  </motion.div>
                ))}
              </motion.div>
            ) : (
              <motion.div
                key="list-view"
                initial={{ opacity: 0, y: 15 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -15 }}
                transition={{ duration: 0.4 }}
                className="space-y-4"
              >
                {filteredAnnouncements.map((ann, i) => (
                  <motion.div
                    key={ann.id}
                    initial={{ opacity: 0, x: -10 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: i * 0.05 }}
                    className="group bg-white rounded-2xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5"
                  >
                    <div className="space-y-2 flex-1">
                      <div className="flex flex-wrap items-center gap-2">
                        <span className="inline-block rounded-full bg-primary-green/10 px-3 py-0.5 text-[9px] font-black tracking-widest text-primary-green uppercase">
                          {ann.category}
                        </span>
                        <span className={`inline-block rounded-full px-3 py-0.5 text-[9px] font-black uppercase tracking-widest ${
                          ann.status === 'Penting' ? 'bg-rose-50 text-rose-700 font-bold' : 'bg-slate-100 text-slate-700'
                        }`}>
                          {ann.status}
                        </span>
                        <span className="text-[10px] font-bold text-slate-400 ml-1">
                          {ann.date} • Diposting oleh {ann.author}
                        </span>
                      </div>

                      <h3 className="text-sm font-extrabold text-slate-900 group-hover:text-black transition">
                        {ann.title}
                      </h3>
                      
                      <p className="text-xs text-slate-600 leading-relaxed max-w-4xl">
                        {ann.summary}
                      </p>
                    </div>

                    <button
                      id={`ann-list-btn-${ann.id}`}
                      onClick={() => onSelect(ann)}
                      className="shrink-0 flex items-center gap-1 bg-slate-50 border border-slate-205 text-slate-800 hover:bg-hover-blue hover:text-primary-white px-4 py-2 rounded-full text-xs font-bold transition-all"
                    >
                      Baca Detail
                      <ArrowRight className="h-3.5 w-3.5 transition group-hover:translate-x-0.5" />
                    </button>
                  </motion.div>
                ))}
              </motion.div>
            )
          ) : (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="py-16 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-250"
            >
              <Tag className="h-9 w-9 text-slate-350 mx-auto mb-3" />
              <p className="text-xs font-bold text-slate-700 mb-1">Pengumuman tidak ditemukan</p>
              <p className="text-[11px] text-slate-400">Silakan gunakan kategori lain atau periksa kembali kata kunci pencarian Anda.</p>
            </motion.div>
          )}
        </AnimatePresence>
        
      </div>
    </section>
  );
}
