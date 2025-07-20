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
  uuid?: string;
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
  uuid: string;
  brand: string;
  poster_url: string;
  file_url: string;
  mime_type: string;
}

export interface Download {
  id: number;
  uuid: string;
  title: string;
  description?: string;
  brand?: string;
  category?: string;
  file_type?: string;
  file_size?: number;
  download_count: number;
  is_featured: boolean;
  is_public: boolean;
  sort_order: number;
  meta_title?: string;
  meta_description?: string;
  tags?: string[];
  poster_url?: string;
  download_url?: string;
  thumb_url?: string;
  medium_url?: string;
  formatted_file_size: string;
  file_extension?: string;
  created_at: string;
  updated_at: string;
  deleted_at?: string;
}

export interface DownloadStats {
  total_downloads: number;
  public_downloads: number;
  featured_downloads: number;
  total_download_count: number;
  categories_count: number;
  file_types_count: number;
  brands_count: number;
  downloads_by_category: Record<string, number>;
  downloads_by_file_type: Record<string, number>;
  most_downloaded: Array<{
    uuid: string;
    title: string;
    download_count: number;
  }>;
  recent_downloads: Array<{
    uuid: string;
    title: string;
    created_at: string;
  }>;
}

export interface DownloadFilters {
  search: string;
  category: string;
  file_type: string;
  brand: string;
  featured: string;
  public: string;
  sort_by: string;
  sort_direction: string;
}

export interface DownloadOptions {
  categories: string[];
  file_types: string[];
  brands: string[];
}

export interface DownloadFormData {
  title: string;
  description?: string;
  brand?: string;
  category?: string;
  is_featured: boolean;
  is_public: boolean;
  sort_order?: number;
  meta_title?: string;
  meta_description?: string;
  tags?: string[];
  poster_image?: File | null;
  download_file?: File | null;
}

export interface BulkDownloadAction {
  ids: number[];
  action: 'delete' | 'feature' | 'unfeature' | 'publish' | 'unpublish';
}

export interface DownloadReorderItem {
  id: number;
  sort_order: number;
}

// Pagination types
export interface PaginatedDownloads {
  data: Download[];
  current_page: number;
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: Array<{
    url?: string;
    label: string;
    active: boolean;
  }>;
  next_page_url?: string;
  path: string;
  per_page: number;
  prev_page_url?: string;
  to: number;
  total: number;
}

// API Response types
export interface DownloadApiResponse {
  success: boolean;
  message?: string;
  data?: any;
  errors?: Record<string, string[]>;
}

export interface DownloadBulkResponse {
  success: boolean;
  message: string;
  count: number;
}

// Form validation types
export interface DownloadFormErrors {
  title?: string;
  description?: string;
  brand?: string;
  category?: string;
  is_featured?: string;
  is_public?: string;
  sort_order?: string;
  meta_title?: string;
  meta_description?: string;
  tags?: string;
  poster_image?: string;
  download_file?: string;
}

// Component prop types
export interface DownloadCardProps {
  download: Download;
  showActions?: boolean;
  onEdit?: (download: Download) => void;
  onDelete?: (download: Download) => void;
  onToggleFeatured?: (download: Download) => void;
  onTogglePublic?: (download: Download) => void;
}

export interface DownloadGridProps {
  downloads: Download[];
  loading?: boolean;
  showActions?: boolean;
  selectable?: boolean;
  selectedIds?: number[];
  onSelectionChange?: (ids: number[]) => void;
  onEdit?: (download: Download) => void;
  onDelete?: (download: Download) => void;
  onBulkAction?: (action: BulkDownloadAction) => void;
}

export interface DownloadFiltersProps {
  filters: DownloadFilters;
  options: DownloadOptions;
  onFiltersChange: (filters: Partial<DownloadFilters>) => void;
  onReset: () => void;
}

export interface DownloadStatsProps {
  stats: DownloadStats;
  loading?: boolean;
}

// Search and filter types
export interface DownloadSearchParams {
  query?: string;
  category?: string;
  file_type?: string;
  brand?: string;
  featured?: boolean;
  public?: boolean;
  sort_by?: string;
  sort_direction?: 'asc' | 'desc';
  per_page?: number;
  page?: number;
}

// Upload types
export interface UploadProgress {
  loaded: number;
  total: number;
  percentage: number;
}

export interface FileUploadState {
  file?: File;
  progress?: UploadProgress;
  error?: string;
  completed: boolean;
}

export interface DownloadUploadState {
  poster: FileUploadState;
  download_file: FileUploadState;
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
