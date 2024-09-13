export interface Socials {
  twitter?: string;
  facebook?: string;
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
  poster: string|File;
  production?: Date;
  description?: string;
  images?: Image[];
  customer_id: number;
  customer?: Customer;
}

export interface Logo {
  id: number;
  lid: string;
  name: string;
  poster: string;
  file_path: string;
  mime_type: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
  };
};
