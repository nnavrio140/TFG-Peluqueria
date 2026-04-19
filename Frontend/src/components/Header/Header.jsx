import { Link } from "react-router-dom";
import "./Header.css";

function Header() {
  return (
    <header className="cabecera">
      <div className="cabecera__contenedor">

        {/* BOTÓN MENU (lo dejas aunque no funcione aún) */}
        <button className="cabecera__menu">
          ☰
        </button>

        {/* NAV */}
        <nav className="cabecera__navegacion">
          <Link to="/servicios">Servicios</Link>
          <Link to="/">Sobre Nosotros</Link>
          <Link to="/">Blog</Link>
          <Link to="/">Contacto</Link>
        </nav>

        {/* LOGO */}
        <Link to="/" className="cabecera__marca">
          <img src="/img/Logo.webp" alt="Logo" className="cabecera__logo-img" />
        </Link>

      </div>
    </header>
  );
}

export default Header;