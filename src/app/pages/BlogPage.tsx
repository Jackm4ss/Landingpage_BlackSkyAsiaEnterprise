import { useEffect, useMemo, useState } from "react";
import { useInfiniteQuery } from "@tanstack/react-query";
import { format, parseISO } from "date-fns";
import { ArrowUpRight, CalendarDays, Clock3, Search, Tag, UserRound, X } from "lucide-react";
import { Link } from "react-router";
import { Footer } from "../components/Footer";
import { Navbar } from "../components/Navbar";
import { getBlogPosts, type BlogPostQuery, type BlogPostSummary } from "../blog/blog-api";
import "./BlogPage.css";

const FALLBACK_IMAGE =
  "https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=1400&q=80";

function useDebouncedValue(value: string, delay = 350) {
  const [debouncedValue, setDebouncedValue] = useState(value);

  useEffect(() => {
    const timeout = window.setTimeout(() => setDebouncedValue(value), delay);

    return () => window.clearTimeout(timeout);
  }, [delay, value]);

  return debouncedValue;
}

function formatDate(value: string | null) {
  if (!value) {
    return "Draft";
  }

  const date = parseISO(value);

  return Number.isNaN(date.getTime()) ? "Published" : format(date, "MMM dd, yyyy");
}

function BlogFeature({ post }: { post: BlogPostSummary }) {
  return (
    <article className="blog-feature-card">
      <img src={post.featured_image ?? FALLBACK_IMAGE} alt={`${post.title} cover`} />
      <div className="blog-feature-overlay" />
      <div className="blog-feature-content">
        <div className="blog-card-kicker">
          <span>{post.category?.name ?? "Editorial"}</span>
          <span>{post.reading_minutes} min read</span>
        </div>
        <h2>{post.title}</h2>
        <p>{post.excerpt}</p>
        <div className="blog-card-meta">
          <span>
            <UserRound aria-hidden="true" />
            {post.author?.name ?? "Black Sky Editorial"}
          </span>
          <span>
            <CalendarDays aria-hidden="true" />
            {formatDate(post.published_at)}
          </span>
        </div>
        <Link to={`/blog/${post.slug}`}>
          <span>Read Feature</span>
          <ArrowUpRight aria-hidden="true" />
        </Link>
      </div>
    </article>
  );
}

function BlogPostCard({ post }: { post: BlogPostSummary }) {
  return (
    <article className="blog-post-card">
      <Link to={`/blog/${post.slug}`} aria-label={`Read ${post.title}`}>
        <img src={post.featured_image ?? FALLBACK_IMAGE} alt={`${post.title} cover`} loading="lazy" />
      </Link>
      <div className="blog-post-card-body">
        <div className="blog-card-kicker">
          <span>{post.category?.name ?? "Editorial"}</span>
          <span>{post.reading_minutes} min read</span>
        </div>
        <h3>
          <Link to={`/blog/${post.slug}`}>{post.title}</Link>
        </h3>
        <p>{post.excerpt}</p>
        <div className="blog-card-meta">
          <span>
            <CalendarDays aria-hidden="true" />
            {formatDate(post.published_at)}
          </span>
          <span>
            <UserRound aria-hidden="true" />
            {post.author?.name ?? "Editorial"}
          </span>
        </div>
        <div className="blog-card-tags">
          {post.tags.slice(0, 3).map((tag) => (
            <span key={tag.slug}>
              {tag.name}
            </span>
          ))}
        </div>
      </div>
    </article>
  );
}

export function BlogPage() {
  const initialParams = useMemo(() => new URLSearchParams(window.location.search), []);
  const [search, setSearch] = useState(initialParams.get("search") ?? "");
  const [category, setCategory] = useState(initialParams.get("category") ?? "");
  const [tag, setTag] = useState(initialParams.get("tag") ?? "");
  const debouncedSearch = useDebouncedValue(search);

  useEffect(() => {
    document.title = "Blog | Black Sky Enterprise";
  }, []);

  const query: BlogPostQuery = {
    search: debouncedSearch,
    category,
    tag,
    perPage: 9,
  };

  const postsQuery = useInfiniteQuery({
    queryKey: ["blog-posts", query],
    queryFn: ({ pageParam }) =>
      getBlogPosts({
        ...query,
        cursor: pageParam,
      }),
    initialPageParam: "",
    getNextPageParam: (lastPage) => lastPage.meta.next_cursor ?? undefined,
  });

  const pages = postsQuery.data?.pages ?? [];
  const posts = pages.flatMap((page) => page.data);
  const feature = posts[0];
  const gridPosts = feature ? posts.slice(1) : posts;
  const filters = pages[0]?.meta.filters ?? { categories: [], tags: [] };
  const hasFilters = Boolean(search || category || tag);
  const activeCategoryName = useMemo(
    () => filters.categories.find((item) => item.slug === category)?.name,
    [category, filters.categories],
  );

  const clearFilters = () => {
    setSearch("");
    setCategory("");
    setTag("");
  };

  return (
    <>
      <Navbar />
      <main className="blog-page">
        <section className="blog-hero">
          <div>
            <span>Black Sky Blog</span>
            <h1>Concert stories, guides, and live culture intelligence</h1>
          </div>
          <p>
            Editorial content built for fans, artists, venues, and search discovery. Read practical
            concert guides, artist stories, and behind-the-scenes notes from the Black Sky team.
          </p>
        </section>

        <section className="blog-index-section" aria-label="Blog posts">
          <div className="blog-filter-bar">
            <label className="blog-search-field">
              <Search aria-hidden="true" />
              <span className="sr-only">Search blog posts</span>
              <input
                type="search"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Search articles, artists, venues..."
              />
            </label>

            <label>
              <Tag aria-hidden="true" />
              <span className="sr-only">Category</span>
              <select value={category} onChange={(event) => setCategory(event.target.value)}>
                <option value="">All Categories</option>
                {filters.categories.map((item) => (
                  <option key={item.slug} value={item.slug}>
                    {item.name}
                  </option>
                ))}
              </select>
            </label>

            <label>
              <Tag aria-hidden="true" />
              <span className="sr-only">Tag</span>
              <select value={tag} onChange={(event) => setTag(event.target.value)}>
                <option value="">All Tags</option>
                {filters.tags.map((item) => (
                  <option key={item.slug} value={item.slug}>
                    {item.name}
                  </option>
                ))}
              </select>
            </label>

            <button type="button" onClick={clearFilters} disabled={!hasFilters}>
              <X aria-hidden="true" />
              Clear
            </button>
          </div>

          <div className="blog-section-heading">
            <div>
              <span>Latest Articles</span>
              <h2>{activeCategoryName ?? "All Editorial"}</h2>
            </div>
            <p>Cursor paginated articles with category and tag filters for scalable discovery.</p>
          </div>

          {postsQuery.isLoading ? (
            <div className="blog-empty-state">
              <Clock3 aria-hidden="true" />
              <strong>Loading articles</strong>
              <span>Preparing the Black Sky editorial catalog.</span>
            </div>
          ) : postsQuery.isError ? (
            <div className="blog-empty-state">
              <Clock3 aria-hidden="true" />
              <strong>Articles could not load</strong>
              <span>Please refresh the page or try again shortly.</span>
            </div>
          ) : posts.length === 0 ? (
            <div className="blog-empty-state">
              <Search aria-hidden="true" />
              <strong>No articles found</strong>
              <span>Try clearing filters or searching a broader keyword.</span>
            </div>
          ) : (
            <>
              {feature && <BlogFeature post={feature} />}
              <div className="blog-post-grid">
                {gridPosts.map((post) => (
                  <BlogPostCard key={post.id} post={post} />
                ))}
              </div>
            </>
          )}

          {postsQuery.hasNextPage && (
            <div className="blog-load-more">
              <button
                type="button"
                onClick={() => postsQuery.fetchNextPage()}
                disabled={postsQuery.isFetchingNextPage}
              >
                {postsQuery.isFetchingNextPage ? "Loading" : "Load More"}
              </button>
            </div>
          )}
        </section>
      </main>
      <Footer />
    </>
  );
}
