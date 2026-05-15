import { useState } from "react";
import "./Blog.css";
import BlogCard from "../../components/BlogCard/BlogCard";

function Blog() {

  const posts = [
    { id: 1, title: "Buzz Cut", image: "/img/buzzcut.webp" },
    { id: 2, title: "Low Fade", image: "/img/lowfade.webp" },
    { id: 3, title: "Taper Fade", image: "/img/taperfade.webp" },
    { id: 4, title: "French Crop", image: "/img/frenchcrop.webp" },
    { id: 5, title: "Mid Fade", image: "/img/midfade.webp" },
    { id: 6, title: "Corte Libertino", image: "/img/librito.webp" },
    { id: 7, title: "Corte Mullet", image: "/img/mullet.webp" },
    { id: 8, title: "Corte Francés", image: "/img/frances.webp" },
    { id: 9, title: "Skin Fade", image: "/img/skin.webp" },
    { id: 10, title: "Pompadour", image: "/img/pompadour.webp" },
    { id: 11, title: "Crop Texturizado", image: "/img/crop.webp" },
    { id: 12, title: "Undercut", image: "/img/undercut.webp" },
  ];

  const postsPerPage = 8;
  const [currentPage, setCurrentPage] = useState(0);

  const pageCount = Math.ceil(posts.length / postsPerPage);

  const start = currentPage * postsPerPage;
  const currentPosts = posts.slice(start, start + postsPerPage);

  return (
    <div className="blog">

      <div className="section__header">
        <h1 className="section__title">BLOG</h1>
      </div>

      <section className="blog__section">

        <span className="blog__subtitle">NUESTRO BLOG</span>
        <h2 className="blog__heading">ÚLTIMAS TENDENCIAS</h2>

        {/* GRID */}
        <div className="blog__grid">
          {currentPosts.map((post) => (
            <BlogCard key={post.id} post={post} />
          ))}
        </div>

        {/* PAGINATION */}
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
            onClick={() => setCurrentPage((p) => Math.min(p + 1, pageCount - 1))}
            disabled={currentPage === pageCount - 1}
          >
            →
          </button>

        </div>

      </section>
    </div>
  );
}

export default Blog;