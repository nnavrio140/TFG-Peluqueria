import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFacebookF, faInstagram, faTiktok } from "@fortawesome/free-brands-svg-icons";
import "./BarberCard.css";

function BarberCard({ barber, image }) {
  return (
    <div className="barber__card">

      <div className="barber__imgWrapper">
        <img
          src={image}
          alt={barber.nombre}
          className="barber__img"
        />
      </div>

      <h3 className="barber__name">
        {barber.nombre}
      </h3>

      <div className="barber__socials">

        <a href="https://facebook.com" target="_blank" rel="noreferrer">
          <FontAwesomeIcon icon={faFacebookF} />
        </a>

        <a href="https://tiktok.com" target="_blank" rel="noreferrer">
          <FontAwesomeIcon icon={faTiktok} />
        </a>

        <a href="https://instagram.com" target="_blank" rel="noreferrer">
          <FontAwesomeIcon icon={faInstagram} />
        </a>

      </div>

    </div>
  );
}

export default BarberCard;