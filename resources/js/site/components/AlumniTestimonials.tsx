import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ArrowLeft, ArrowRight, Quote, Award, Sparkles } from 'lucide-react';
import { Testimonial } from '../types';

interface AlumniTestimonialsProps {
  items?: Testimonial[];
}

export default function AlumniTestimonials({ items = [] }: AlumniTestimonialsProps) {
  const [activeGroupIndex, setActiveGroupIndex] = useState(0);

  // Group testimonials in pairs of 2
  const testimonialGroups = [];
  for (let i = 0; i < items.length; i += 2) {
    testimonialGroups.push(items.slice(i, i + 2));
  }

  const handleNext = () => {
    setActiveGroupIndex((prev) => (prev + 1) % testimonialGroups.length);
  };

  const handlePrev = () => {
    setActiveGroupIndex((prev) => (prev - 1 + testimonialGroups.length) % testimonialGroups.length);
  };

  const activeGroup = testimonialGroups[activeGroupIndex] || [];

  if (!items.length) {
    return (
      <section id="alumni-section" className="py-24 bg-slate-50 relative overflow-hidden border-t border-slate-100">
        <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="text-center space-y-3 mb-12">
            <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-150 border border-slate-250 px-3.5 py-1 text-[10px] font-bold text-slate-800 uppercase tracking-widest">
              <Sparkles className="h-3.5 w-3.5 text-amber-500" />
              Kisah Sukses Alumni
            </span>
            <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
              Dipercaya &amp; Terbukti Berkualitas
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto font-medium">
              Belum ada testimoni yang dipublikasikan.
            </p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section id="alumni-section" className="py-24 bg-slate-50 relative overflow-hidden border-t border-slate-100">
      {/* Visual embellishments */}
      <div className="absolute top-1/2 left-0 -translate-y-1/2 w-72 h-72 bg-slate-100/50 rounded-full blur-3xl pointer-events-none" />
      <div className="absolute bottom-5 right-5 w-80 h-80 bg-slate-200/40 rounded-full blur-3xl pointer-events-none" />

      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header Title */}
        <div className="text-center space-y-3 mb-16">
          <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-150 border border-slate-250 px-3.5 py-1 text-[10px] font-bold text-slate-800 uppercase tracking-widest">
            <Sparkles className="h-3.5 w-3.5 text-amber-500" />
            Kisah Sukses Alumni
          </span>
          <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Dipercaya &amp; Terbukti Berkualitas
          </h2>
          <p className="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto font-medium">
            Bagaimana SMA Al-Ghazaly mempersiapkan kiprah akademik cemerlang siswa di berbagai Perguruan Tinggi Negeri unggulan Indonesia.
          </p>
        </div>

        {/* Carousel Slide Panel */}
        <div className="relative bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.025)] overflow-hidden">
          {/* Large Quote graphic indicator */}
          <div className="absolute top-6 right-8 text-slate-100 select-none pointer-events-none opacity-30">
            <Quote className="h-28 w-28 text-slate-100" />
          </div>

          <AnimatePresence mode="wait">
            <motion.div
              key={activeGroupIndex}
              initial={{ opacity: 0, x: 15 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -15 }}
              transition={{ duration: 0.4 }}
              className="grid grid-cols-1 md:grid-cols-2 gap-8 text-left"
            >
              {activeGroup.map((alumni) => (
                <div
                  key={alumni.id}
                  className="flex flex-col justify-between h-full bg-slate-50/50 p-6 sm:p-8 rounded-2xl border border-slate-150 relative hover:border-primary-green/30 transition-all duration-300"
                >
                  <div className="space-y-4">
                    <Quote className="h-6 w-6 text-primary-green stroke-[2.5]" />
                    
                    <p className="text-xs sm:text-sm text-slate-700 italic font-medium leading-relaxed font-sans">
                      &quot;{alumni.quote}&quot;
                    </p>
                  </div>

                  <div className="mt-6 pt-6 border-t border-slate-205/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    {/* Avatar Profil */}
                    <div className="flex items-center gap-3">
                      <div className="relative h-11 w-11 rounded-xl overflow-hidden border border-slate-200 shadow-sm shrink-0 bg-white">
                        <img
                          src={alumni.avatar}
                          referrerPolicy="no-referrer"
                          alt={alumni.name}
                          className="h-full w-full object-cover"
                        />
                      </div>
                      <div>
                        <h4 className="text-[11px] font-black text-slate-900 uppercase tracking-widest leading-none">{alumni.name}</h4>
                        <p className="text-[9px] font-bold text-slate-400 uppercase tracking-wide mt-1.5 leading-none">{alumni.year}</p>
                      </div>
                    </div>

                    {/* Academic Tag */}
                    <div className="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200/50 px-3 py-1.5 self-start sm:self-center">
                      <Award className="h-4 w-4 text-primary-green shrink-0" />
                      <div className="flex flex-col text-[9px]">
                        <span className="font-extrabold text-slate-900 leading-none">{alumni.university}</span>
                        <span className="text-slate-500 font-semibold mt-1 leading-none">{alumni.major}</span>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </motion.div>
          </AnimatePresence>

          {/* Navigational Slide indicators */}
          <div className="flex items-center justify-between mt-8 pt-6 border-t border-slate-100">
            {/* Dots Pagination indicators */}
            <div className="flex items-center gap-1.5">
              {testimonialGroups.map((_, idx) => (
                <button
                  key={`group-dot-${idx}`}
                  id={`test-dot-${idx}`}
                  onClick={() => setActiveGroupIndex(idx)}
                  className={`h-2.5 transition-all duration-300 rounded-full ${
                    activeGroupIndex === idx ? 'w-6 bg-primary-green' : 'w-2.5 bg-slate-200'
                  }`}
                  aria-label={`Go to slide ${idx + 1}`}
                />
              ))}
            </div>

            {/* Prev & Next button clicks */}
            <div className="flex items-center gap-2">
              <button
                id="test-prev"
                onClick={handlePrev}
                className="p-2.5 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-hover-blue hover:border-hover-blue/40 transition-all cursor-pointer"
                aria-label="Previous Slide"
              >
                <ArrowLeft className="h-4 w-4" />
              </button>
              <button
                id="test-next"
                onClick={handleNext}
                className="p-2.5 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-hover-blue hover:border-hover-blue/40 transition-all cursor-pointer"
                aria-label="Next Slide"
              >
                <ArrowRight className="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

      </div>
    </section>
  );
}
