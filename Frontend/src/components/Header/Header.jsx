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
    await logout(); // ahora borra token y state
    setOpenMenu(false);
  };

  // 🔹 Cerrar menú al hacer click fuera
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setOpenMenu(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  // 🔹 Evitar flash login/logout mientras carga
  if (loading) return null;

  return (
    <header className="cabecera">
      <div className="cabecera__contenedor">

        {/* LOGO */}
        <Link to="/" className="cabecera__marca">
          <img src="/img/Logo.webp" alt="Logo" className="cabecera__logo-img" />
        </Link>

        {/* NAV */}
        <nav className="cabecera__navegacion">
          <Link to="/servicios">Servicios</Link>
          <Link to="/sobre-nosotros">Sobre Nosotros</Link>
          <Link to="/blog">Blog</Link>
          <Link to="/contacto">Contacto</Link>
        </nav>

        {/* AUTH */}
        <div className="cabecera__auth">

          {user ? (
            <div className="user-menu" ref={menuRef}>

              <button
                className="cabecera__btn"
                onClick={() => setOpenMenu(!openMenu)}
              >
                <FontAwesomeIcon icon={faUser} />
                {user.nombre}
              </button>

              {openMenu && (
                <div className="dropdown">

                  <button
                    className="dropdown__item"
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