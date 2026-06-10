export interface Announcement {
  id: string;
  title: string;
  category: "PPDB" | "Akademik" | "Informasi" | "Libur";
  date: string;
  summary: string;
  content: string;
  status: "Acara akan Datang" | "Selesai" | "Penting";
  image?: string;
  author: string;
}

export interface EventActivity {
  id: string;
  title: string;
  date: { day: string; month: string; year: string; };
  endDate?: { day: string; month: string; year: string; } | null;
  category: string;
  color?: string;
  location: string;
  time: string;
  description: string;
}

export interface Post {
  id: string;
  title: string;
  category: string;
  date: string;
  excerpt: string;
  content: string;
  readTime: string;
  image: string;
}

export interface Testimonial {
  id: string;
  name: string;
  university: string;
  major: string;
  year: string;
  quote: string;
  avatar: string;
}

export interface ProgramUnggulan {
  id: string;
  title: string;
  description: string;
  icon: string;
  color: string;
}

export interface FaqItem {
  question: string;
  answer: string;
  category: "PPDB" | "Akademik" | "Umum";
}
