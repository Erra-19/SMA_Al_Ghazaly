import { useState, useEffect, useMemo } from 'react';
import { getAcademicCalendars } from '../api';
import { EventActivity } from '../types';
import { Calendar, ChevronLeft, ChevronRight } from 'lucide-react';

const MONTH_ID = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];
const DAY_SHORT = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

const dotColor: Record<string, string> = {
  green:  'bg-green-500',
  red:    'bg-red-500',
  blue:   'bg-blue-500',
  purple: 'bg-purple-500',
  orange: 'bg-orange-400',
  teal:   'bg-teal-500',
};

const badgeCls: Record<string, string> = {
  green:  'bg-green-50 text-green-800 border-green-200',
  red:    'bg-red-50 text-red-800 border-red-200',
  blue:   'bg-blue-50 text-blue-800 border-blue-200',
  purple: 'bg-purple-50 text-purple-800 border-purple-200',
  orange: 'bg-orange-50 text-orange-800 border-orange-200',
  teal:   'bg-teal-50 text-teal-800 border-teal-200',
};

export default function AcademicCalendarPage() {
  const now = new Date();
  const [events, setEvents] = useState<EventActivity[]>([]);
  const [loading, setLoading] = useState(true);
  const [curYear, setCurYear] = useState(now.getFullYear());
  const [curMonth, setCurMonth] = useState(now.getMonth()); // 0-indexed
  const [selectedDay, setSelectedDay] = useState<number | null>(null);

  useEffect(() => {
    setLoading(true);
    getAcademicCalendars()
      .then((data) => {
        setEvents(data);
        const todayIso = now.toISOString().split('T')[0];

        // Prefer the nearest upcoming event
        const nearest = [...data]
          .filter(e => (e.endIso || e.startIso || '') >= todayIso)
          .sort((a, b) => (a.startIso || '').localeCompare(b.startIso || ''))[0];

        if (nearest?.startIso) {
          const parts = nearest.startIso.split('-').map(Number);
          setCurYear(parts[0]);
          setCurMonth(parts[1] - 1);
        } else {
          // No upcoming events — fall back to the most recent month that has ANY event
          const last = [...data]
            .filter(e => e.startIso)
            .sort((a, b) => {
              const ia = a.endIso || a.startIso || '';
              const ib = b.endIso || b.startIso || '';
              return ib.localeCompare(ia); // descending — most recent first
            })[0];
          if (last) {
            const iso = last.endIso || last.startIso!;
            const parts = iso.split('-').map(Number);
            setCurYear(parts[0]);
            setCurMonth(parts[1] - 1);
          }
        }
      })
      .catch(() => setEvents([]))
      .finally(() => setLoading(false));
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Build map: day-of-month → events active on that day
  const eventsForDay = useMemo(() => {
    const map: Record<number, EventActivity[]> = {};
    for (const ev of events) {
      const s = ev.startIso;
      if (!s) continue;
      const [sy, sm, sd] = s.split('-').map(Number);
      const eIso = ev.endIso || s;
      const [ey, em, ed] = eIso.split('-').map(Number);

      const cur = new Date(sy, sm - 1, sd);
      const end = new Date(ey, em - 1, ed);
      while (cur <= end) {
        if (cur.getFullYear() === curYear && cur.getMonth() === curMonth) {
          const d = cur.getDate();
          if (!map[d]) map[d] = [];
          if (!map[d].find(x => x.id === ev.id)) map[d].push(ev);
        }
        cur.setDate(cur.getDate() + 1);
      }
    }
    return map;
  }, [events, curYear, curMonth]);

  // All unique events visible in current month, sorted by start date
  const monthEvents = useMemo(() => {
    const seen = new Set<string>();
    const arr: EventActivity[] = [];
    for (const evs of Object.values(eventsForDay)) {
      for (const ev of evs) {
        if (!seen.has(ev.id)) { seen.add(ev.id); arr.push(ev); }
      }
    }
    return arr.sort((a, b) => (a.startIso || '').localeCompare(b.startIso || ''));
  }, [eventsForDay]);

  const selectedEvents = selectedDay ? (eventsForDay[selectedDay] || []) : [];

  // Calendar grid cells
  const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
  const firstDow   = new Date(curYear, curMonth, 1).getDay(); // 0=Sun
  const cells: (number | null)[] = [
    ...Array<null>(firstDow).fill(null),
    ...Array.from({ length: daysInMonth }, (_, i) => i + 1),
  ];
  while (cells.length % 7 !== 0) cells.push(null);

  const prevMonth = () => {
    setSelectedDay(null);
    if (curMonth === 0) { setCurYear(y => y - 1); setCurMonth(11); }
    else setCurMonth(m => m - 1);
  };
  const nextMonth = () => {
    setSelectedDay(null);
    if (curMonth === 11) { setCurYear(y => y + 1); setCurMonth(0); }
    else setCurMonth(m => m + 1);
  };

  const todayYear  = now.getFullYear();
  const todayMonth = now.getMonth();
  const todayDate  = now.getDate();

  return (
    <div className="min-h-screen bg-slate-50 pt-20 pb-16">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Page header */}
        <div className="py-12 text-center">
          <span className="text-xs font-black uppercase tracking-widest text-emerald-600 block mb-2">
            Jadwal Akademik
          </span>
          <h1 className="text-3xl font-black text-slate-900">Kalender Pendidikan</h1>
          <p className="text-sm text-slate-500 mt-2 max-w-md mx-auto">
            Jadwal kegiatan dan agenda akademik SMA Al-Ghazaly 2025/2026
          </p>
        </div>

        {loading ? (
          <div className="bg-white rounded-3xl border border-slate-100 p-8 animate-pulse">
            <div className="h-8 bg-slate-200 rounded w-48 mx-auto mb-6" />
            <div className="grid grid-cols-7 gap-1">
              {Array(35).fill(null).map((_, i) => (
                <div key={i} className="h-14 bg-slate-100 rounded-xl" />
              ))}
            </div>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {/* ── Calendar grid (left, 2/3) ── */}
            <div className="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

              {/* Month navigation */}
              <div className="flex items-center justify-between mb-5">
                <button
                  onClick={prevMonth}
                  className="p-2 rounded-full hover:bg-slate-100 transition text-slate-600"
                  aria-label="Bulan sebelumnya"
                >
                  <ChevronLeft className="h-4 w-4" />
                </button>
                <span className="text-base font-black text-slate-900">
                  {MONTH_ID[curMonth]} {curYear}
                </span>
                <button
                  onClick={nextMonth}
                  className="p-2 rounded-full hover:bg-slate-100 transition text-slate-600"
                  aria-label="Bulan berikutnya"
                >
                  <ChevronRight className="h-4 w-4" />
                </button>
              </div>

              {/* Day name headers */}
              <div className="grid grid-cols-7 mb-1">
                {DAY_SHORT.map(d => (
                  <div
                    key={d}
                    className="text-center text-[10px] font-black text-slate-400 uppercase tracking-wider py-1"
                  >
                    {d}
                  </div>
                ))}
              </div>

              {/* Day cells */}
              <div className="grid grid-cols-7 gap-1">
                {cells.map((day, i) => {
                  if (!day) return <div key={`blank-${i}`} />;
                  const isToday    = curYear === todayYear && curMonth === todayMonth && day === todayDate;
                  const isSelected = day === selectedDay;
                  const dayEvts    = eventsForDay[day] || [];
                  const hasEvents  = dayEvts.length > 0;

                  return (
                    <button
                      key={`day-${day}`}
                      onClick={() => hasEvents && setSelectedDay(isSelected ? null : day)}
                      className={[
                        'flex flex-col items-center pt-2 pb-2.5 px-1 rounded-xl transition min-h-[52px]',
                        isSelected
                          ? 'bg-emerald-600 text-white shadow-md'
                          : isToday
                          ? 'bg-emerald-50 border-2 border-emerald-400 text-emerald-800'
                          : hasEvents
                          ? 'hover:bg-slate-50 text-slate-800 cursor-pointer'
                          : 'text-slate-300 cursor-default',
                      ].join(' ')}
                    >
                      <span className="text-xs font-black leading-none">{day}</span>
                      {hasEvents && (
                        <div className="flex gap-0.5 mt-1.5 flex-wrap justify-center max-w-[32px]">
                          {dayEvts.slice(0, 3).map(ev => (
                            <span
                              key={ev.id}
                              className={[
                                'h-1.5 w-1.5 rounded-full flex-shrink-0',
                                isSelected
                                  ? 'bg-white/60'
                                  : dotColor[ev.color || 'blue'] || 'bg-blue-500',
                              ].join(' ')}
                            />
                          ))}
                          {dayEvts.length > 3 && (
                            <span className={`text-[8px] font-black leading-none ${isSelected ? 'text-white/70' : 'text-slate-400'}`}>
                              +{dayEvts.length - 3}
                            </span>
                          )}
                        </div>
                      )}
                    </button>
                  );
                })}
              </div>

              {/* Legend */}
              <div className="flex flex-wrap gap-x-5 gap-y-2 mt-5 pt-5 border-t border-slate-100">
                {([
                  ['Libur', 'green'],
                  ['Ujian', 'red'],
                  ['Akademik', 'blue'],
                  ['Kegiatan', 'purple'],
                  ['PPDB', 'teal'],
                  ['Orientasi', 'orange'],
                ] as [string, string][]).map(([label, color]) => (
                  <div key={label} className="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500">
                    <span className={`h-2.5 w-2.5 rounded-full ${dotColor[color]}`} />
                    {label}
                  </div>
                ))}
              </div>
            </div>

            {/* ── Right sidebar ── */}
            <div className="space-y-4">

              {/* Selected day detail */}
              {selectedDay !== null && (
                <div className="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                  <h3 className="text-xs font-black text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <Calendar className="h-3.5 w-3.5 text-emerald-600" />
                    {selectedDay} {MONTH_ID[curMonth]} {curYear}
                  </h3>
                  {selectedEvents.length === 0 ? (
                    <p className="text-xs text-slate-400 font-medium">Tidak ada kegiatan.</p>
                  ) : (
                    <div className="space-y-2">
                      {selectedEvents.map(ev => (
                        <div
                          key={ev.id}
                          className={`p-3 rounded-xl border ${badgeCls[ev.color || 'blue'] || badgeCls.blue}`}
                        >
                          <span className="text-[8px] font-black uppercase tracking-widest block">{ev.category}</span>
                          <p className="font-black text-sm leading-snug mt-0.5">{ev.title}</p>
                          {ev.description && (
                            <p className="text-[10px] mt-1 opacity-60 leading-relaxed">{ev.description}</p>
                          )}
                          {ev.endDate && (
                            <p className="text-[9px] mt-1 opacity-50 font-semibold">
                              s/d {ev.endDate.day} {ev.endDate.month} {ev.endDate.year}
                            </p>
                          )}
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              )}

              {/* All events in current month */}
              <div className="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 max-h-[500px] overflow-y-auto">
                <h3 className="text-xs font-black text-slate-900 uppercase tracking-wider mb-3">
                  Kegiatan {MONTH_ID[curMonth]}
                </h3>
                {monthEvents.length === 0 ? (
                  <p className="text-xs text-slate-400 font-medium">Tidak ada kegiatan bulan ini.</p>
                ) : (
                  <div className="space-y-2">
                    {monthEvents.map(ev => {
                      const startDay = ev.startIso ? Number(ev.startIso.split('-')[2]) : null;
                      return (
                        <div
                          key={ev.id}
                          onClick={() => {
                            if (startDay) setSelectedDay(selectedDay === startDay ? null : startDay);
                          }}
                          className={[
                            'p-2.5 rounded-xl border cursor-pointer hover:shadow-sm transition',
                            badgeCls[ev.color || 'blue'] || badgeCls.blue,
                          ].join(' ')}
                        >
                          <div className="flex items-center gap-1 mb-0.5">
                            <span className="text-[8px] font-black uppercase tracking-widest">{ev.date.day} {ev.date.month}</span>
                            {ev.endDate && (
                              <span className="text-[8px] opacity-50">- {ev.endDate.day} {ev.endDate.month}</span>
                            )}
                          </div>
                          <p className="font-bold text-[11px] leading-snug">{ev.title}</p>
                        </div>
                      );
                    })}
                  </div>
                )}
              </div>
            </div>

          </div>
        )}
      </div>
    </div>
  );
}
