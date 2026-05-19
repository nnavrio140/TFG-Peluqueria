import "./BlogCard.css";

function BlogCard({ post }) {
  return (
    <div className="blog-card">
      <div className="blog-card__image-container">
        <img
          src={post.image_url || "/img/default.webp"}
          alt={post.title}
          className="blog-card__image"
          loading="lazy"
        />
      </div>

      <h3 className="blog-card__title">
        {post.title}
      </h3>
    </div>
  );
}

export default BlogCard;