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
  socials?: Socials;
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
  first_name: string;
  last_name: string;
  job_title?: string;
  company_name?: string;
  address?: Address;
}

export interface Project {
  id: number;
  pid: string;
  name: string;
  poster_url: string|File;
  production?: Date;
  description?: string;
  media?: MediaItem[];
  customer_id: number;
  customer?: Customer;
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
