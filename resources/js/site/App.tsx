import { useEffect, useState } from 'react';
import Header from './components/Header';
import Hero from './components/Hero';
import Announcements from './components/Announcements';
import EventsTimeline from './components/EventsTimeline';
import ProgramsList from './components/ProgramsList';
import AlumniTestimonials from './components/AlumniTestimonials';
import SchoolProfile from './components/SchoolProfile';
import SchoolPrograms from './components/SchoolPrograms';
import SchoolTeachers from './components/SchoolTeachers';
import SchoolFacilities from './components/SchoolFacilities';
import SchoolContact from './components/SchoolContact';
import SchoolRegistrationForm from './components/SchoolRegistrationForm';
import PPDBStatusCheck from './components/PPDBStatusCheck';
import Footer from './components/Footer';
import PopupModal from './components/PopupModal';
import { Announcement, EventActivity, Post } from './types';
import { getAnnouncements, getAcademicCalendars, getArticles, getSettings, getTestimonials } from './api';

export default function App() {
  const [searchTerm, setSearchTerm] = useState('');
  const [activeTab, setActiveTab] = useState('home');
  const [announcements, setAnnouncements] = useState<Announcement[]>([]);
  const [events, setEvents] = useState<EventActivity[]>([]);
  const [posts, setPosts] = useState<Post[]>([]);
  const [testimonials, setTestimonials] = useState<any[]>([]);
  const [settings, setSettings] = useState<Record<string, string>>({});
  
  // Modal details state
  const [modalOpen, setModalOpen] = useState(false);
  const [modalData, setModalData] = useState<{
    title: string;
    category?: string;
    date?: string;
    location?: string;
    time?: string;
    author?: string;
    content: string;
    image?: string;
  }>({
    title: '',
    content: '',
  });

  const openAnnouncementDetail = (ann: Announcement) => {
    setModalData({
      title: ann.title,
      category: ann.category,
      date: ann.date,
      author: ann.author,
      content: ann.content,
      image: ann.image,
    });
    setModalOpen(true);
  };

  const openEventDetail = (evt: EventActivity) => {
    setModalData({
      title: evt.title,
      category: evt.category,
      date: `${evt.date.day} ${evt.date.month} ${evt.date.year}`,
      location: evt.location,
      time: evt.time,
      content: evt.description,
    });
    setModalOpen(true);
  };

  const openPostDetail = (post: Post) => {
    setModalData({
      title: post.title,
      category: post.category,
      date: post.date,
      content: post.content,
      image: post.image,
    });
    setModalOpen(true);
  };

  const handleOpenRegisterForm = () => {
    setActiveTab('pendaftaran');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleSearchChange = (val: string) => {
    setSearchTerm(val);
    if (val.trim()) {
      // scroll to announcements tab section automatically on active search
      const section = document.querySelector('#announcements-section');
      if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  };

  useEffect(() => {
    Promise.allSettled([
      getAnnouncements(),
      getAcademicCalendars(),
      getArticles(),
      getTestimonials(),
      getSettings(),
    ]).then(([ann, evt, art, tes, cfg]) => {
      if (ann.status === 'fulfilled') setAnnouncements(ann.value);
      if (evt.status === 'fulfilled') setEvents(evt.value);
      if (art.status === 'fulfilled') setPosts(art.value);
      if (tes.status === 'fulfilled') setTestimonials(tes.value);
      if (cfg.status === 'fulfilled') setSettings(cfg.value);
    });
  }, []);

  return (
    <div className="min-h-screen bg-white text-neutral-800 font-sans antialiased scroll-smooth">
      {/* 1. Header with search functionality */}
      <Header
        searchTerm={searchTerm}
        onSearchChange={handleSearchChange}
        onOpenRegisterForm={handleOpenRegisterForm}
        activeTab={activeTab}
        onTabChange={setActiveTab}
        logoUrl={settings.school_logo || settings.logo}
      />

      {activeTab === 'profil' ? (
        <SchoolProfile />
      ) : activeTab === 'program' ? (
        <SchoolPrograms />
      ) : activeTab === 'pengajar' ? (
        <SchoolTeachers />
      ) : activeTab === 'fasilitas' ? (
        <SchoolFacilities />
      ) : activeTab === 'kontak' ? (
        <SchoolContact />
      ) : activeTab === 'pendaftaran' ? (
        <SchoolRegistrationForm />
      ) : (
        <>

          {/* 2. Interactive Hero module */}
          <Hero onOpenRegisterForm={handleOpenRegisterForm} />

          {/* 3. High-polished announcements carousel/grid */}
          <Announcements
            searchTerm={searchTerm}
            onSelect={openAnnouncementDetail}
            items={announcements}
          />

          {/* 4. Highlighted curriculum and action program */}
          <ProgramsList onOpenPrograms={() => setActiveTab('program')} />

          {/* PPDB Status Check Section */}
          <PPDBStatusCheck />

          {/* 5. Live timeline agendas and articles section */}
          <EventsTimeline
            onSelectEvent={openEventDetail}
            onSelectPost={openPostDetail}
            events={events}
            posts={posts}
          />

          {/* 6. Social testimony slider feedback */}
          <AlumniTestimonials items={testimonials} />
        </>
      )}

      {/* 7. Full context modern footer */}
      <Footer />

      {/* 8. Modular detail dialogue context modal list */}
      <PopupModal
        isOpen={modalOpen}
        onClose={() => setModalOpen(false)}
        title={modalData.title}
        category={modalData.category}
        date={modalData.date}
        location={modalData.location}
        time={modalData.time}
        author={modalData.author}
        content={modalData.content}
        image={modalData.image}
      />
    </div>
  );
}
