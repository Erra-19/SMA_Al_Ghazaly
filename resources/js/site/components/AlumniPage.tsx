import { useEffect, useState, useMemo } from 'react';
import { motion } from 'motion/react';
import { Award, Quote, GraduationCap, Search, Users } from 'lucide-react';
import { AlumnusItem } from '../types';
import { getAlumni } from '../api';

export default function AlumniPage() {
  const [alumni, setAlumni] = useState<AlumnusItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [yearFilter, setYearFilter] = useState('');

  useEffect(() => {
    setLoading(true);
    getAlumni()
      .then(setAlumni)
      .catch(() => setAlumni([]))
      .finally(() => setLoading(false));
  }, []);

  const years = useMemo(() => {
    const unique = [...new Set(alumni.map((a) => String(a.graduation_year)).filter(Boolean))];
    return unique.sort((a, b) => Number(b) - Number(a));
  }, [alumni]);

  const filtered = useMemo(() => {
    const q = search.trim().toLowerCase();
    return alumni.filter((a) => {
      const matchYear = !yearFilter || String(a.graduation_year) === yearFilter;
      const matchSearch =
        !q ||
        a.name.toLowerCase().includes(q) ||
        (a.current_institution ?? '').toLowerCase().includes(q) ||
        (a.major ?? '').toLowerCase().includes(q);
      return matchYear && matchSearch;
    });
  }, [alumni, search, yearFilter]);

  return (
    <section className="min-h-screen bg-white py-20">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="mb-12 text-center space-y-3">
          <span className="inline-flex items-center gap-1.5 rounded-full bg-green-50 border border-green-200 px-3.5 py-1 text-[10px] font-bold text-green-800 uppercase tracking-widest">
            <GraduationCap className="h-3.5 w-3.5" />
            Alumni SMA Al-Ghazaly
          </span>
          <h1 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Kebanggaan Generasi Kami
          </h1>
          <p className="text-sm text-slate-500 max-w-xl mx-auto font-medium">
            Alumni terbaik yang telah melanjutkan perjalanan akademik dan karier mereka, menjadi bukti nyata kualitas pendidikan Al-Ghazaly.
          </p>
        </div>

        {/* Filter bar */}
        <div className="flex flex-col sm:flex-row gap-3 mb-8">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Cari nama alumni, kampus, atau jurusan..."
              className="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 transition"
            />
          </div>
          <select
            value={yearFilter}
            onChange={(e) => setYearFilter(e.target.value)}
            className="w-full sm:w-44 px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 transition"
          >
            <option value="">Semua Angkatan</option>
            {years.map((y) => (
              <option key={y} value={y}>
                Angkatan {y}
              </option>
            ))}
          </select>
        </div>

        {/* Loading state */}
        {loading && (
          <div className="py-24 flex flex-col items-center gap-3">
            <div className="h-8 w-8 rounded-full border-2 border-green-500 border-t-transparent animate-spin" />
            <p className="text-xs text-slate-400 font-medium">Memuat data alumni...</p>
          </div>
        )}

        {/* Empty state */}
        {!loading && filtered.length === 0 && (
          <div className="py-20 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <Users className="h-10 w-10 text-slate-300 mx-auto mb-3" />
            <p className="text-sm font-semibold text-slate-600 mb-1">
              {alumni.length === 0 ? 'Belum ada data alumni.' : 'Tidak ada alumni yang sesuai filter.'}
            </p>
            <p className="text-xs text-slate-400">
              {alumni.length === 0
                ? 'Data alumni akan ditampilkan setelah ditambahkan oleh admin.'
                : 'Coba ubah kata kunci atau filter angkatan.'}
            </p>
          </div>
        )}

        {/* Alumni grid */}
        {!loading && filtered.length > 0 && (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filtered.map((alumnus, i) => (
              <AlumnusCard key={alumnus.id} alumnus={alumnus} index={i} />
            ))}
          </div>
        )}

      </div>
    </section>
  );
}

function AlumnusCard({ alumnus, index }: { alumnus: AlumnusItem; index: number }) {
  const initials = alumnus.name
    .split(' ')
    .slice(0, 2)
    .map((w) => w[0])
    .join('')
    .toUpperCase();

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ delay: index * 0.04, duration: 0.4 }}
      className="group flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 overflow-hidden"
    >
      {/* Card top: avatar + name + year */}
      <div className="flex items-start gap-4 p-5 pb-4">
        {alumnus.photo ? (
          <img
            src={alumnus.photo}
            alt={alumnus.name}
            referrerPolicy="no-referrer"
            className="h-14 w-14 rounded-xl object-cover border border-slate-100 shrink-0"
          />
        ) : (
          <div className="h-14 w-14 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center text-lg font-black text-green-700 shrink-0">
            {initials}
          </div>
        )}
        <div className="flex-1 min-w-0">
          <h3 className="font-bold text-slate-900 text-sm leading-snug line-clamp-2">{alumnus.name}</h3>
          <span className="inline-block mt-1 rounded-full bg-green-50 border border-green-200 px-2 py-0.5 text-[9px] font-black text-green-700 uppercase tracking-wide">
            Angkatan {alumnus.graduation_year ?? '—'}
          </span>
        </div>
      </div>

      {/* Campus & major */}
      {(alumnus.current_institution || alumnus.major) && (
        <div className="px-5 pb-3">
          <div className="flex items-start gap-2 bg-slate-50 rounded-xl px-3 py-2.5">
            <Award className="h-4 w-4 text-green-600 shrink-0 mt-0.5" />
            <div className="min-w-0">
              {alumnus.current_institution && (
                <p className="text-xs font-bold text-slate-800 leading-snug truncate">
                  {alumnus.current_institution}
                </p>
              )}
              {alumnus.major && (
                <p className="text-[10px] text-slate-500 font-medium leading-snug mt-0.5 truncate">
                  {alumnus.major}
                </p>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Achievement */}
      {alumnus.achievement && (
        <div className="px-5 pb-3">
          <p className="text-[11px] text-slate-500 leading-relaxed line-clamp-2">
            {alumnus.achievement}
          </p>
        </div>
      )}

      {/* Testimonial quote — only if linked */}
      {alumnus.testimonial && (
        <div className="mt-auto mx-5 mb-5 pt-4 border-t border-slate-100">
          <div className="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-3.5 border border-green-100">
            <Quote className="absolute top-2.5 right-2.5 h-5 w-5 text-green-200" />
            <p className="text-[11px] text-slate-600 italic leading-relaxed line-clamp-3 pr-6">
              &quot;{alumnus.testimonial.quote}&quot;
            </p>
            {alumnus.testimonial.rating && (
              <div className="flex gap-0.5 mt-2">
                {[1, 2, 3, 4, 5].map((s) => (
                  <svg
                    key={s}
                    className={`h-3 w-3 ${s <= alumnus.testimonial!.rating! ? 'text-yellow-400' : 'text-slate-200'}`}
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                ))}
              </div>
            )}
          </div>
        </div>
      )}
    </motion.div>
  );
}
