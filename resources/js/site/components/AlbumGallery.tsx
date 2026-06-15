import { useState, useEffect, useCallback } from 'react';
import { X, ChevronLeft, ChevronRight, Images } from 'lucide-react';
import { getAlbums, getAlbum } from '../api';

type AlbumItem = { id: string; slug: string; title: string; description: string; image: string };
type MediaItem = { id: string; url: string; filename: string; mime_type: string };
type AlbumDetail = { id: string; slug: string; title: string; description: string; cover: string; medias: MediaItem[] };

export default function AlbumGallery() {
  const [albums, setAlbums] = useState<AlbumItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [openAlbum, setOpenAlbum] = useState<AlbumDetail | null>(null);
  const [albumLoading, setAlbumLoading] = useState(false);
  const [lightbox, setLightbox] = useState<{ medias: MediaItem[]; index: number } | null>(null);

  useEffect(() => {
    getAlbums()
      .then(setAlbums)
      .catch(() => setAlbums([]))
      .finally(() => setLoading(false));
  }, []);

  const openAlbumDetail = async (album: AlbumItem) => {
    setAlbumLoading(true);
    try {
      const detail = await getAlbum(album.slug);
      setOpenAlbum(detail);
    } catch {
      // fallback empty
      setOpenAlbum({ id: album.id, slug: album.slug, title: album.title, description: album.description, cover: album.image, medias: [] });
    } finally {
      setAlbumLoading(false);
    }
  };

  const openLightbox = (medias: MediaItem[], index: number) => setLightbox({ medias, index });

  const closeLightbox = useCallback(() => setLightbox(null), []);

  const lightboxPrev = useCallback(() => {
    setLightbox(prev => prev ? { ...prev, index: (prev.index - 1 + prev.medias.length) % prev.medias.length } : null);
  }, []);

  const lightboxNext = useCallback(() => {
    setLightbox(prev => prev ? { ...prev, index: (prev.index + 1) % prev.medias.length } : null);
  }, []);

  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if (!lightbox) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') lightboxPrev();
      if (e.key === 'ArrowRight') lightboxNext();
    };
    window.addEventListener('keydown', handler);
    return () => window.removeEventListener('keydown', handler);
  }, [lightbox, closeLightbox, lightboxPrev, lightboxNext]);

  return (
    <div className="py-20 px-4 bg-white">
      <div className="max-w-6xl mx-auto">

        {/* Header */}
        <div className="text-center mb-12 space-y-2">
          <span className="inline-block bg-[#019342]/10 text-[#019342] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Galeri</span>
          <h2 className="text-2xl md:text-3xl font-black text-slate-900">Album Foto Kegiatan</h2>
          <p className="text-xs text-slate-500 font-semibold max-w-md mx-auto">Koleksi dokumentasi kegiatan, prestasi, dan momen berharga SMA Al-Ghazaly Bogor.</p>
        </div>

        {/* Album grid */}
        {loading ? (
          <div className="flex justify-center py-20">
            <div className="h-8 w-8 rounded-full border-2 border-[#019342] border-t-transparent animate-spin" />
          </div>
        ) : albums.length === 0 ? (
          <div className="flex flex-col items-center py-20 text-slate-400">
            <Images className="h-12 w-12 mb-3 opacity-30" />
            <p className="text-xs font-semibold">Belum ada album yang dipublikasikan.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {albums.map(album => (
              <button
                key={album.id}
                onClick={() => openAlbumDetail(album)}
                className="group text-left rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5"
              >
                <div className="aspect-video bg-slate-100 overflow-hidden relative">
                  {album.image ? (
                    <img src={album.image} alt={album.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center text-slate-300">
                      <Images className="h-10 w-10" />
                    </div>
                  )}
                  <div className="absolute inset-0 bg-[#019342]/0 group-hover:bg-[#019342]/10 transition-colors duration-200 flex items-center justify-center">
                    <span className="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 text-[#019342] text-xs font-black px-4 py-1.5 rounded-full shadow">
                      Lihat Foto
                    </span>
                  </div>
                </div>
                <div className="p-4">
                  <h3 className="font-bold text-slate-800 text-sm truncate">{album.title}</h3>
                  {album.description && (
                    <p className="text-xs text-slate-400 mt-0.5 line-clamp-2">{album.description}</p>
                  )}
                </div>
              </button>
            ))}
          </div>
        )}
      </div>

      {/* Album Detail Modal */}
      {(openAlbum || albumLoading) && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/60 backdrop-blur-sm" onClick={() => setOpenAlbum(null)} />
          <div className="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

            {/* Modal header */}
            <div className="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
              <div>
                <h3 className="font-bold text-slate-800 text-base">{openAlbum?.title ?? '...'}</h3>
                {openAlbum?.description && <p className="text-xs text-slate-400 mt-0.5">{openAlbum.description}</p>}
              </div>
              <button onClick={() => setOpenAlbum(null)} className="p-1.5 rounded-full hover:bg-slate-100 transition">
                <X className="h-4 w-4 text-slate-500" />
              </button>
            </div>

            {/* Photos grid */}
            <div className="flex-1 overflow-y-auto p-5">
              {albumLoading ? (
                <div className="flex justify-center py-16">
                  <div className="h-7 w-7 rounded-full border-2 border-[#019342] border-t-transparent animate-spin" />
                </div>
              ) : openAlbum && openAlbum.medias.length === 0 ? (
                <div className="flex flex-col items-center py-16 text-slate-400">
                  <Images className="h-10 w-10 mb-2 opacity-30" />
                  <p className="text-xs font-semibold">Album ini belum memiliki foto.</p>
                </div>
              ) : openAlbum ? (
                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                  {openAlbum.medias.map((media, i) => (
                    <button
                      key={media.id}
                      onClick={() => openLightbox(openAlbum.medias, i)}
                      className="aspect-square rounded-xl overflow-hidden border border-slate-100 bg-slate-50 hover:opacity-90 transition-opacity group relative"
                    >
                      <img src={media.url} alt={media.filename} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" />
                    </button>
                  ))}
                </div>
              ) : null}
            </div>

            <div className="px-6 py-3 border-t border-slate-100 shrink-0">
              <p className="text-[10px] text-slate-400 font-semibold text-center">
                {openAlbum ? `${openAlbum.medias.length} foto` : ''}
              </p>
            </div>
          </div>
        </div>
      )}

      {/* Lightbox */}
      {lightbox && (
        <div className="fixed inset-0 z-[60] flex items-center justify-center bg-black/95">
          <button onClick={closeLightbox} className="absolute top-4 right-4 p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
            <X className="h-5 w-5 text-white" />
          </button>

          <button onClick={lightboxPrev} className="absolute left-3 p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
            <ChevronLeft className="h-6 w-6 text-white" />
          </button>
          <button onClick={lightboxNext} className="absolute right-3 p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
            <ChevronRight className="h-6 w-6 text-white" />
          </button>

          <img
            src={lightbox.medias[lightbox.index].url}
            alt={lightbox.medias[lightbox.index].filename}
            className="max-w-[90vw] max-h-[88vh] object-contain rounded-xl"
          />

          <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
            {lightbox.medias.map((_, i) => (
              <button
                key={i}
                onClick={() => setLightbox(prev => prev ? { ...prev, index: i } : null)}
                className={`w-1.5 h-1.5 rounded-full transition-all ${i === lightbox.index ? 'bg-white w-4' : 'bg-white/40'}`}
              />
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
