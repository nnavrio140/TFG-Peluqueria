import { Link } from "react-router-dom";
import { useState, useContext, useEffect, useRef } from "react";
import "./Header.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser, faUserPlus } from "@fortawesome/free-solid-svg-icons";

import { AuthContext } from "../../context/AuthContext";

function Header() {
  const { user, logout, loading } = useContext(AuthContext);
  const [openMenu, setOpenMenu] = useState(false);
  const menuRef = useRef(null);

  const handleLogout = async () => {
    await logout();
    setOpenMenu(false);
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setOpenMenu(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  if (loading) return null;

  return (
    <header className="cabecera">
      <div className="cabecera__contenedor">

        {/* LOGO */}
        <Link to="/" className="cabecera__marca">
          <img src="/img/Logo.webp" alt="Logo" className="cabecera__logo-img" />
        </Link>

        {/* NAV (SIN ICONOS) */}
        <nav className="cabecera__navegacion">

          <Link to="/servicios">Servicios</Link>

          <Link to="/citas">Citas</Link>

          <Link to="/sobre-nosotros">Sobre Nosotros</Link>

          <Link to="/blog">Blog</Link>

          <Link to="/contacto">Contacto</Link>

        </nav>

        {/* AUTH (CON ICONOS) */}
        <div className="cabecera__auth">

          {user ? (
            <div className="user-menu" ref={menuRef}>

              <button
                className={`cabecera__btn cabecera__btn--user ${openMenu ? 'cabecera__btn--active' : ''}`}
                onClick={() => setOpenMenu(!openMenu)}
              >
                <FontAwesomeIcon icon={faUser} />
                <span>{user.nombre}</span>
              </button>

              {openMenu && (
                <div className="dropdown dropdown--user">
                  <button
                    className="dropdown__item dropdown__item--logout"
                    onClick={handleLogout}
                  >
                    Cerrar sesión
                  </button>
                </div>
              )}

            </div>
          ) : (
            <>
              <Link to="/login" className="cabecera__btn">
                <FontAwesomeIcon icon={faUser} />
                <span>Login</span>
              </Link>

              <Link to="/registro" className="cabecera__btn cabecera__btn--gold">
                <FontAwesomeIcon icon={faUserPlus} />
                <span>Registro</span>
              </Link>
            </>
          )}

        </div>

      </div>
    </header>
  );
}

export default Header;