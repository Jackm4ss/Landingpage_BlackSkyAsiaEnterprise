import { http } from "../lib/http";

export type PortfolioWorkSummary = {
  id: number;
  title: string;
  slug: string;
  category: string;
  year: string;
  location: string;
  role: string | null;
  attendance: string | null;
  excerpt: string;
  featured_image: string | null;
  accent_color: string;
  published_at: string | null;
  updated_at: string | null;
};

export type PortfolioWorkDetail = PortfolioWorkSummary & {
  description: string;
  gallery_images: string[];
  meta_title: string | null;
  meta_description: string | null;
  meta_keywords: string | null;
  canonical_url: string | null;
  og_image: string | null;
};

export type PortfolioWorksResponse = {
  data: PortfolioWorkSummary[];
  meta: {
    per_page: number;
    next_cursor: string | null;
    filters?: {
      categories: string[];
    };
  };
};

export type PortfolioWorksQuery = {
  search?: string;
  category?: string;
  cursor?: string;
  perPage?: number;
};

export async function getPortfolioWorks(query: PortfolioWorksQuery = {}) {
  const params = new URLSearchParams();

  if (query.search) params.set("search", query.search);
  if (query.category) params.set("category", query.category);
  if (query.cursor) params.set("cursor", query.cursor);
  params.set("per_page", String(query.perPage ?? 8));

  const { data } = await http.get<PortfolioWorksResponse>(`/api/v1/portfolio?${params}`);

  return data;
}

export async function getPortfolioWork(slug: string) {
  const { data } = await http.get<{ data: PortfolioWorkDetail }>(
    `/api/v1/portfolio/${encodeURIComponent(slug)}`,
  );

  return data.data;
}
