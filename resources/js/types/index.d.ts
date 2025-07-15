export interface Socials {
  twitter?: string;
  facebook?: string;
  linkedin?: string;
  behance?: string;
}

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  email_verified_at?: string;
  phone_number?: string;
  bio?: string;
  website?: string;
  location?: string;
  timezone?: string;
  socials?: Socials;
  preferences?: Record<string, any>;
  last_login_at?: string;
  is_active?: boolean;
  avatar_url?: string;
  cover_image_url?: string;
  full_name?: string;
  initials?: string;
  is_online?: boolean;
}

export interface Image {
  id: string;
  fid: string;
  size: string;
  mime_type: string;
  model_id?: number;
  src: string;
}

export interface MediaItem {
  id: number;
  model_type: string;
  model_id: number;
  uuid: string;
  collection_name: string;
  name: string;
  file_name: string;
  mime_type: string;
  disk: string;
  conversions_disk: string;
  size: number;
  manipulations: Record<string, any>[];
  custom_properties: Record<string, any>[];
  generated_conversions: {
    thumb: boolean;
  };
  responsive_images: Record<string, any>[];
  order_column: number;
  created_at: string;
  updated_at: string;
  original_url: string;
  preview_url: string;
};

export interface Address {
  street: string;
  city: string;
  state: string;
  country: string;
}

export interface Customer {
  id?: number;
  cid?: string;
  name?: string;
  first_name: string;
  last_name: string;
  job_title?: string;
  company_name?: string;
  email?: string;
  phone_number?: string;
  website?: string;
  address?: Address;
  notes?: string;
  status?: 'active' | 'inactive' | 'prospect';
  avatar_url?: string;
  created_at?: string;
  updated_at?: string;
  projects_count?: number;
}

export interface GalleryImage {
  id: number;
  name: string;
  file_name: string;
  mime_type: string;
  size: number;
  url: string;
  thumb_url: string;
  medium_url: string;
  large_url: string;
}

export interface Project {
  id: number;
  uuid: string;
  pid?: string;
  name: string;
  slug?: string;
  description?: string;
  short_description?: string;
  production_type?: string;
  category?: string;
  status?: 'not_started' | 'in_progress' | 'on_hold' | 'completed' | 'cancelled';
  priority?: 'low' | 'medium' | 'high' | 'urgent';
  start_date?: string;
  end_date?: string;
  estimated_hours?: number;
  actual_hours?: number;
  budget?: number;
  technologies?: string[];
  features?: string[];
  challenges?: string;
  solutions?: string;
  results?: string;
  client_feedback?: string;
  is_featured?: boolean;
  is_public?: boolean;
  sort_order?: number;
  meta_title?: string;
  meta_description?: string;
  poster_url?: string;
  live_url?: string;
  github_url?: string;
  figma_url?: string;
  behance_url?: string;
  dribbble_url?: string;
  media?: MediaItem[];
  customer_id?: number;
  customer?: {
    id?: number;
    name?: string;
    first_name?: string;
    last_name?: string;
    company_name?: string;
  };
  created_at?: string;
  updated_at?: string;
  // Computed properties
  duration?: number;
  progress?: number;
  status_color?: string;
  priority_color?: string;
  main_technologies?: string[];
  hours_variance?: number;
  is_overdue?: boolean;
  gallery_images?: GalleryImage[];
}

export interface Logo {
  id: number;
  lid: string;
  brand: string;
  poster_url: string;
  file_url: string;
  mime_type: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
  };

  notify?: {
    type: string;
    title?: string;
    message: string;
  };
};
