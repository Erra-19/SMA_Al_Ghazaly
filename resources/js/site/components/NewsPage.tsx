import { useState, useEffect } from 'react';
import { getPosts } from '../api';
import { Post } from '../types';
import { Calendar, BookOpen, ArrowLeft, Tag } from 'lucide-react';

export default function NewsPage() {
  const [posts, setPosts] = useState<Post[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedPost, setSelectedPost] = useState<Post | null>(null);
  const [filter, setFilter] = useState<string>('all');

  useEffect(() => {
    setLoading(true);
    getPosts().then(setPosts).catch(() => setPosts([])).finally(() => setLoading(false));
  }, []);

  const categories = ['all', ...Array.from(new Set(posts.map((p) => p.category)))];
  const filtered = filter === 'all' ? posts : posts.filter((p) => p.category === filter);

  if (selectedPost) {
    return (
      <div className="min-h-screen bg-white pt-20 pb-16">
        <div className="max-w-3xl mx-auto px-4 sm:px-6">
          <button
            onClick={() => setSelectedPost(null)}
            className="flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition mt-6 mb-8"
          >
            <ArrowLeft className="h-4 w-4" />
            Kembali ke Berita
          </button>
          {selectedPost.image && (
            <img
              src={selectedPost.image}
              alt={selectedPost.title}
              className="w-full h-64 object-cover rounded-2xl mb-6"
            />
          )}
          <div className="flex items-center gap-2 mb-3">
            <span className="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full flex items-center gap-1">
              <Tag className="h-3 w-3" />
              {selectedPost.category}
            </span>
          </div>
          <h1 className="text-2xl font-black text-slate-900 mb-3 leading-snug">{selectedPost.title}</h1>
          <div className="flex items-center gap-4 text-xs text-slate-400 mb-8 pb-6 border-b border-slate-100">
            <span className="flex items-center gap-1.5">
              <Calendar className="h-3.5 w-3.5" />
              {selectedPost.date}
            </span>
            <span className="flex items-center gap-1.5">
              <BookOpen className="h-3.5 w-3.5" />
              {selectedPost.readTime}
            </span>
          </div>
          <div
            className="prose prose-sm max-w-none text-slate-700 leading-relaxed"
            dangerouslySetInnerHTML={{ __html: selectedPost.content }}
          />
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-50 pt-20 pb-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Page header */}
        <div className="py-12 text-center">
          <span className="text-xs font-black uppercase tracking-widest text-emerald-600 block mb-2">
            Berita &amp; Artikel
          </span>
          <h1 className="text-3xl font-black text-slate-900">Halaman Berita</h1>
          <p className="text-sm text-slate-500 mt-2 max-w-md mx-auto">
            Informasi terkini seputar kegiatan dan prestasi SMA Al-Ghazaly
          </p>
        </div>

        {/* Category filter */}
        <div className="flex flex-wrap gap-2 justify-center mb-10">
          {categories.map((cat) => (
            <button
              key={cat}
              onClick={() => setFilter(cat)}
              className={`px-4 py-1.5 rounded-full text-xs font-bold transition ${
                filter === cat
                  ? 'bg-emerald-600 text-white shadow-sm'
                  : 'bg-white text-slate-600 border border-slate-200 hover:border-emerald-400 hover:text-emerald-700'
              }`}
            >
              {cat === 'all' ? 'Semua' : cat}
            </button>
          ))}
        </div>

        {loading ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {[1, 2, 3, 4, 5, 6].map((i) => (
              <div key={i} className="bg-white rounded-2xl overflow-hidden animate-pulse border border-slate-100">
                <div className="h-48 bg-slate-200" />
                <div className="p-5 space-y-3">
                  <div className="h-3 bg-slate-200 rounded w-1/4" />
                  <div className="h-5 bg-slate-200 rounded w-3/4" />
                  <div className="h-3 bg-slate-200 rounded w-full" />
                  <div className="h-3 bg-slate-200 rounded w-2/3" />
                </div>
              </div>
            ))}
          </div>
        ) : filtered.length === 0 ? (
          <div className="text-center py-20 text-slate-400">
            <BookOpen className="h-12 w-12 mx-auto mb-3 opacity-30" />
            <p className="text-sm font-semibold">Belum ada berita tersedia.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filtered.map((post) => (
              <button
                key={post.id}
                onClick={() => setSelectedPost(post)}
                className="text-left bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group cursor-pointer border border-slate-100 flex flex-col"
              >
                <div className="relative h-48 bg-slate-100 overflow-hidden shrink-0">
                  {post.image ? (
                    <img
                      src={post.image}
                      alt={post.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-teal-100">
                      <BookOpen className="h-12 w-12 text-emerald-300" />
                    </div>
                  )}
                  <span className="absolute top-3 left-3 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
                    {post.category}
                  </span>
                </div>
                <div className="p-5 flex flex-col flex-1">
                  <h3 className="text-sm font-black text-slate-800 line-clamp-2 mb-2 group-hover:text-emerald-700 transition leading-snug">
                    {post.title}
                  </h3>
                  <p className="text-xs text-slate-500 line-clamp-2 mb-4 flex-1">{post.excerpt}</p>
                  <div className="flex items-center gap-3 text-[10px] text-slate-400">
                    <span className="flex items-center gap-1">
                      <Calendar className="h-3 w-3" />
                      {post.date}
                    </span>
                    <span className="flex items-center gap-1">
                      <BookOpen className="h-3 w-3" />
                      {post.readTime}
                    </span>
                  </div>
                </div>
              </button>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
