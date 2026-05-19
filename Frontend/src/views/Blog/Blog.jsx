import { useEffect, useState } from "react";
import "./Blog.css";
import BlogCard from "../../components/BlogCard/BlogCard";
import { BLOG_ENDPOINT } from "../../services/endpoints";

function Blog() {
  const [posts, setPosts] = useState([]);
  const [currentPage, setCurrentPage] = useState(0);
  const [loading, setLoading] = useState(true);

  const postsPerPage = 8;

  useEffect(() => {
    fetch(BLOG_ENDPOINT)
      .then((res) => res.json())
      .then((data) => {
        setPosts(data.data || []);
        setCurrentPage(0);
      })
      .catch((error) => {
        console.error("Error cargando blog:", error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  const pageCount = Math.ceil(posts.length / postsPerPage);

  const start = currentPage * postsPerPage;
  const currentPosts = posts.slice(start, start + postsPerPage);

  return (
    <div className="blog">
      {/* HEADER */}
      <div className="section__header">
        <h1 className="section__title">BLOG</h1>
      </div>

      <section className="blog__section">
        <span className="blog__subtitle">NUESTRO BLOG</span>
        <h2 className="blog__heading">ÚLTIMAS TENDENCIAS</h2>

        {loading ? (
          <p className="blog__loading">Cargando cortes...</p>
        ) : posts.length === 0 ? (
          <p className="blog__loading">No hay cortes disponibles todavía.</p>
        ) : (
          <>
            {/* GRID */}
            <div className="blog__grid">
              {currentPosts.map((post) => (
                <BlogCard key={post.id} post={post} />
              ))}
            </div>

            {/* PAGINATION */}
            {pageCount > 1 && (
              <div className="blog__pagination">
                <button
                  className="page-btn"
                  onClick={() => setCurrentPage((p) => Math.max(p - 1, 0))}
                  disabled={currentPage === 0}
                >
                  ←
                </button>

                {Array.from({ length: pageCount }).map((_, i) => (
                  <button
                    key={i}
                    className={`page-btn ${currentPage === i ? "active" : ""}`}
                    onClick={() => setCurrentPage(i)}
                  >
                    {i + 1}
                  </button>
                ))}

                <button
                  className="page-btn"
                  onClick={() =>
                    setCurrentPage((p) => Math.min(p + 1, pageCount - 1))
                  }
                  disabled={currentPage === pageCount - 1}
                >
                  →
                </button>
              </div>
            )}
          </>
        )}
      </section>
    </div>
  );
}

export default Blog;