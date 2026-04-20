import "./ServiceCard.css";

function ServiceCard({ icon, title, text }) {
  return (
    <div className="service-card">

      <div className="service-card__icon">
        <img src={icon} alt={title} />
      </div>

      <h3 className="service-card__title">
        {title}
      </h3>

      <p className="service-card__text">
        {text}
      </p>

    </div>
  );
}

export default ServiceCard;