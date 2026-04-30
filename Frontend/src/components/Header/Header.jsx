import { Link } from "react-router-dom";
import "./Header.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser, faUserPlus } from "@fortawesome/free-solid-svg-icons";

function Header() {
  const isLogged = false;

  return (
    <header className="cabecera">
      <div className="cabecera__contenedor">

        <Link to="/" className="cabecera__marca">
          <img src="/img/Logo.webp" alt="Logo" className="cabecera__logo-img" />
        </Link>

        <nav className="cabecera__navegacion">
          <Link to="/servicios">Servicios</Link>
          <Link to="/sobre-nosotros">Sobre Nosotros</Link>
          <Link to="/blog">Blog</Link>
          <Link to="/contacto">Contacto</Link>
        </nav>

        <div className="cabecera__auth">
          {isLogged ? (
            <button className="cabecera__btn">
              <FontAwesomeIcon icon={faUser} /> Usuario
            </button>
          ) : (
            <>
              <Link to="/login" className="cabecera__btn">
                <FontAwesomeIcon icon={faUser} />
                Iniciar sesión
              </Link>

              <Link to="/registro" className="cabecera__btn cabecera__btn--gold">
                <FontAwesomeIcon icon={faUserPlus} />
                Crear cuenta
              </Link>
            </>
          )}
        </div>

      </div>
    </header>
  );
}

export default Header;