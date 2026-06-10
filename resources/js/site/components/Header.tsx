import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Menu, X, Search, GraduationCap, ChevronDown, CheckCircle } from 'lucide-react';

interface HeaderProps {
  onSearchChange?: (val: string) => void;
  searchTerm?: string;
  onOpenRegisterForm?: () => void;
  activeTab?: string;
  onTabChange?: (tabId: string) => void;
  logoUrl?: string;
}

function AlGhazalyLogo() {
  return (
    <svg
      className="h-10 w-10 shrink-0 text-[#019342] transition-transform duration-300 group-hover:scale-105"
      viewBox="0 0 100 100"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      {/* Outer Circle */}
      <circle cx="50" cy="50" r="45" stroke="#019342" strokeWidth="2.5" />
      <circle cx="50" cy="50" r="41" stroke="#019342" strokeWidth="0.75" strokeOpacity="0.4" />
      
      {/* Globe lines */}
      <circle cx="50" cy="50" r="28" stroke="#019342" strokeWidth="1" strokeDasharray="3 3" strokeOpacity="0.5" />
      <path d="M14 50 H86" stroke="#019342" strokeWidth="1" strokeOpacity="0.4" />
      <path d="M22 35 H78" stroke="#019342" strokeWidth="0.75" strokeOpacity="0.4" />
      <path d="M22 65 H78" stroke="#019342" strokeWidth="0.75" strokeOpacity="0.4" />
      <path d="M50 5 A45 45 0 0 0 50 95" stroke="#019342" strokeWidth="1" strokeOpacity="0.4" />
      
      {/* Crescent Moon */}
      <path
        d="M32 50 C32 38 42 28 54 28 C48 31 45 40 45 50 C45 60 48 69 54 72 C42 72 32 62 32 50 Z"
        fill="#019342"
        fillOpacity="0.2"
        stroke="#019342"
        strokeWidth="1.5"
      />

      {/* Embedded Minaret Minar Tower representation in the center-left */}
      <g transform="translate(4, 0)">
        {/* Base */}
        <rect x="26" y="68" width="6" height="12" rx="0.5" fill="#019342" />
        {/* Shaft */}
        <rect x="27.5" y="32" width="3" height="36" fill="#019342" />
        {/* Balconies */}
        <rect x="25" y="44" width="8" height="2" rx="0.5" fill="#019342" />
        <rect x="25" y="58" width="8" height="2" rx="0.5" fill="#019342" />
        {/* Top Spire */}
        <path d="M26.5 32 L29 20 L31.5 32 Z" fill="#019342" />
        <circle cx="29" cy="18.5" r="1" fill="#019342" />
      </g>

      {/* Dome shape at bottom near center */}
      <path d="M38 78 C38 70 48 70 48 78 Z" fill="#019342" />

      {/* Airplane Silhouette wing across */}
      <path
        d="M25 65 L34 56 L28 47 L31 45 L39 52 L51 42 L42 30 L45 28 L57 37 L73 23 C76 20 79 20 81 22 C83 24 82 27 79 30 L65 44 L73 55 L70 58 L62 49 L51 60 L57 69 L54 72 L46 62 Z"
        fill="#019342"
        stroke="#ffffff"
        strokeWidth="0.75"
      />
    </svg>
  );
}

export default function Header({ 
  onSearchChange, 
  searchTerm, 
  onOpenRegisterForm, 
  activeTab: externalActiveTab, 
  onTabChange,
  logoUrl,
}: HeaderProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [localActiveTab, setLocalActiveTab] = useState('home');

  const activeTab = externalActiveTab !== undefined ? externalActiveTab : localActiveTab;
  const setActiveTab = (tab: string) => {
    if (onTabChange) {
      onTabChange(tab);
    } else {
      setLocalActiveTab(tab);
    }
  };

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 20) {
        setScrolled(true);
      } else {
        setScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const menuItems = [
    { label: 'Home', id: 'home', hash: '#hero' },
    { label: 'Profil', id: 'profil', hash: '#hero' },
    { label: 'Program', id: 'program', hash: '#programs-section' },
    { label: 'Pengajar', id: 'pengajar', hash: '#events-section' },
    { label: 'Fasilitas', id: 'fasilitas', hash: '#announcements-section' },
    { label: 'Kontak', id: 'kontak', hash: 'footer' },
  ];

  const handleNavClick = (id: string, hash: string) => {
    setActiveTab(id);
    setIsOpen(false);
    
    if (id === 'profil' || id === 'program' || id === 'home' || id === 'pengajar' || id === 'fasilitas' || id === 'kontak') {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
      setTimeout(() => {
        const element = document.querySelector(hash);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }, 100);
    }
  };

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-40 transition-all duration-300 ${
        scrolled
          ? 'bg-white/95 backdrop-blur-md shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] border-b border-slate-100 py-3'
          : 'bg-white/80 backdrop-blur-md border-b border-slate-100/60 py-4'
      }`}
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">
          
          {/* Logo Brand */}
          <a
            id="brand-logo"
            href="#hero"
            onClick={(e) => {
              e.preventDefault();
              handleNavClick('home', '#hero');
            }}
            className="flex items-center gap-3.5 group shrink-0"
          >
            {logoUrl ? (
              <img
                src={logoUrl}
                alt="Logo SMA Al-Ghazaly"
                className="h-10 w-10 shrink-0 object-contain transition-transform duration-300 group-hover:scale-105"
              />
            ) : (
              <AlGhazalyLogo />
            )}
            
            <div className="flex flex-col">
              <span className="text-xl md:text-2xl font-semibold tracking-tight text-slate-700 leading-none">
                SMA ALGHAZALY
              </span>
              <span className="text-[9px] font-bold tracking-widest text-[#94a3b8] uppercase mt-2.5 leading-none">
                YAYASAN ISLAMIC CENTER ALGHAZALY
              </span>
            </div>
          </a>

          {/* Desktop Center Navigation */}
          <nav className="hidden md:flex items-center justify-center gap-6 lg:gap-8 flex-1 px-4">
            {menuItems.map((item) => (
              <button
                key={item.id}
                id={`nav-${item.id}`}
                onClick={() => handleNavClick(item.id, item.hash)}
                className={`text-sm font-semibold tracking-wide transition relative cursor-pointer ${
                  activeTab === item.id
                    ? 'text-[#019342] font-bold'
                    : 'text-slate-600 hover:text-[#191654]'
                }`}
              >
                {item.label}
              </button>
            ))}
          </nav>

          {/* Desktop Right Side CTA */}
          <div className="hidden md:flex items-center gap-4 shrink-0">
            <button
              id="header-ppdb-cta"
              onClick={onOpenRegisterForm}
              className="text-xs font-bold px-6 py-2.5 rounded-none tracking-widest uppercase transition-all duration-300 bg-[#019342] text-white hover:bg-[#191654] inline-block text-center cursor-pointer shadow-sm"
            >
              PENDAFTARAN
            </button>
          </div>

          {/* Mobile Menu Action button */}
          <div className="flex items-center gap-3 md:hidden">
            <button
              id="mobile-hamburger"
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 rounded-full transition text-slate-850 hover:bg-slate-100"
            >
              {isOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
            </button>
          </div>
          
        </div>
      </div>

      {/* Mobile Drawer Overlay */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="md:hidden bg-white border-b border-slate-100 shadow-lg overflow-hidden text-slate-800"
          >
            <div className="px-4 pt-2 pb-6 space-y-2">
              {onSearchChange && (
                <div className="relative mb-3">
                  <input
                    id="mobile-search-input"
                    type="text"
                    placeholder="Cari berita atau agenda..."
                    value={searchTerm}
                    onChange={(e) => onSearchChange(e.target.value)}
                    className="w-full pl-9 pr-3 py-2 rounded-full text-xs bg-slate-50 border border-slate-200 outline-none focus:ring-1 focus:ring-slate-950 text-slate-800"
                  />
                  <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
                </div>
              )}
              
              <div className="grid grid-cols-1 gap-1">
                {menuItems.map((item) => (
                  <button
                    key={item.id}
                    id={`mobile-nav-${item.id}`}
                    onClick={() => handleNavClick(item.id, item.hash)}
                    className={`w-full text-left px-4 py-2.5 text-xs font-semibold rounded-full transition ${
                      activeTab === item.id
                        ? 'bg-primary-green/10 text-primary-green'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-hover-blue'
                    }`}
                  >
                    {item.label}
                  </button>
                ))}
              </div>

              <div className="pt-4 border-t border-slate-100">
                <button
                  id="mobile-drawer-ppdb-btn"
                  onClick={() => {
                    setIsOpen(false);
                    if (onOpenRegisterForm) onOpenRegisterForm();
                  }}
                  className="w-full py-3 rounded-full bg-primary-green text-primary-white font-extrabold text-xs uppercase tracking-wider transition hover:bg-hover-blue flex items-center justify-center gap-2 shadow-sm text-center cursor-pointer"
                >
                  <CheckCircle className="h-4 w-4" />
                  PPDB 2026/2027
                </button>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
