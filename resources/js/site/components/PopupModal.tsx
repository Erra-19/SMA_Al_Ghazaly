import { motion, AnimatePresence } from 'motion/react';
import { X, Calendar, MapPin, Clock, User } from 'lucide-react';

interface PopupModalProps {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  category?: string;
  date?: string;
  location?: string;
  time?: string;
  author?: string;
  content: string;
  image?: string;
}

export default function PopupModal({
  isOpen,
  onClose,
  title,
  category,
  date,
  location,
  time,
  author,
  content,
  image
}: PopupModalProps) {
  return (
    <AnimatePresence>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 0.6 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="absolute inset-0 bg-neutral-900"
          />

          {/* Modal Container */}
          <motion.div
            initial={{ opacity: 0, scale: 0.95, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.95, y: 20 }}
            transition={{ type: 'spring', duration: 0.5 }}
            className="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
          >
            {/* Header / Image banner */}
            {image ? (
              <div className="relative h-48 w-full bg-neutral-100">
                <img
                  src={image}
                  referrerPolicy="no-referrer"
                  alt={title}
                  className="h-full w-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-neutral-900/80 via-neutral-900/20 to-transparent" />
                <button
                  id="close-modal-img-btn"
                  onClick={onClose}
                  className="absolute top-4 right-4 rounded-full bg-black/40 p-2 text-white transition hover:bg-black/60 hover:scale-105"
                  aria-label="Close"
                >
                  <X className="h-5 w-5" />
                </button>
                <div className="absolute bottom-4 left-6 right-6">
                  {category && (
                    <span className="inline-block rounded-full bg-slate-900 px-2.5 py-1 text-[10px] font-bold text-white uppercase tracking-wider mb-2">
                      {category}
                    </span>
                  )}
                  <h3 className="text-xl font-bold text-white drop-shadow-md line-clamp-2">
                    {title}
                  </h3>
                </div>
              </div>
            ) : (
              <div className="p-6 border-b border-slate-100 pr-14">
                <button
                  id="close-modal-no-img-btn"
                  onClick={onClose}
                  className="absolute top-4 right-4 rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-800"
                  aria-label="Close"
                >
                  <X className="h-5 w-5" />
                </button>
                {category && (
                  <span className="inline-block rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[9px] font-black text-slate-800 uppercase tracking-widest mb-2">
                    {category}
                  </span>
                )}
                <h3 className="text-xl font-bold text-slate-900">
                  {title}
                </h3>
              </div>
            )}

            {/* Metadata bar */}
            {(date || location || time || author) && (
              <div className="grid grid-cols-2 gap-3 bg-slate-50 px-6 py-3 text-xs text-slate-600 font-medium border-b border-slate-100">
                {date && (
                  <div className="flex items-center gap-2">
                    <Calendar className="h-4 w-4 text-slate-950 shrink-0" />
                    <span>{date}</span>
                  </div>
                )}
                {time && (
                  <div className="flex items-center gap-2">
                    <Clock className="h-4 w-4 text-slate-950 shrink-0" />
                    <span>{time}</span>
                  </div>
                )}
                {location && (
                  <div className="flex items-center gap-2 col-span-2">
                    <MapPin className="h-4 w-4 text-slate-950 shrink-0" />
                    <span>{location}</span>
                  </div>
                )}
                {author && !time && (
                  <div className="flex items-center gap-2">
                    <User className="h-4 w-4 text-slate-950 shrink-0" />
                    <span>Diposting oleh: {author}</span>
                  </div>
                )}
              </div>
            )}

            {/* Content body */}
            <div className="max-h-[300px] overflow-y-auto p-6 text-sm text-slate-700 leading-relaxed font-sans">
              <p className="whitespace-pre-line">{content}</p>
            </div>

            {/* Footer action */}
            <div className="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100">
              <button
                id="modal-dismiss-btn"
                onClick={onClose}
                className="rounded-full bg-slate-950 px-5 py-2 text-xs font-bold text-white transition hover:bg-slate-900 active:scale-95"
              >
                Tutup Informasi
              </button>
            </div>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}
