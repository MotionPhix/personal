export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  email_verified_at?: string;
}

export interface Image {
  id: string;
  fid: string;
  mime_type: string;
  src: string;
}

export interface Address {
  street: string;
  city: string;
  state: string;
  country: string;
}

export interface Customer {
  id: number;
  cid: string;
  first_name: string;
  last_name: string;
  company_name: string;
  address: string[];
  customer: Customer;
}

export interface Project {
  id: number;
  pid: string;
  name?: string;
  poster: string;
  production?: string;
  description?: string;
  images?: Image[];
  customer?: Customer;
}

export interface Logo {
  id: number;
  did: string;
  name: string;
  poster?: string;
  file_path: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
  };
};
