import { Link, useNavigate } from "react-router-dom";
import { useState, useContext, useEffect, useRef } from "react";
import "./Header.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faUser,
  faUserPlus,
  faBars,
  faXmark,
} from "@fortawesome/free-solid-svg-icons";

import { AuthContext } from "../../context/AuthContext";

function Header() {
  const { user, logout, loading } = useContext(AuthContext);
  const navigate = useNavigate();

  const [openMenu, setOpenMenu] = useState(false);
  const [mobileMenu, setMobileMenu] = useState(false);

  const menuRef = useRef(null);
  const mobileRef = useRef(null);

  const closeMobileMenu = () => {
    setMobileMenu(false);
    setOpenMenu(false);
  };

  const handleLogout = async () => {
    await logout();

    setOpenMenu(false);
    setMobileMenu(false);

    navigate("/");
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (
        menuRef.current &&
        !menuRef.current.contains(event.target)
      ) {
        setOpenMenu(false);
      }

      if (
        mobileRef.current &&
        !mobileRef.current.contains(event.target)
      ) {
        setMobileMenu(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  useEffect(() => {
    if (mobileMenu) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }

    return () => {
      document.body.style.overflow = "";
    };
  }, [mobileMenu]);

  if (loading) return null;

  return (
    <header className="cabecera">
      <div className="cabecera__contenedor" ref={mobileRef}>

        {/* LOGO */}
        <Link to="/" className="cabecera__marca" onClick={closeMobileMenu}>
          <img
            src="/img/Logo.webp"
            alt="Logo"
            className="cabecera__logo-img"
          />
        </Link>

        {/* BOTÓN HAMBURGUESA */}
        <button
          className="cabecera__hamburguesa"
          type="button"
          onClick={() => setMobileMenu(!mobileMenu)}
          aria-label={mobileMenu ? "Cerrar menú" : "Abrir menú"}
        >
          <FontAwesomeIcon icon={mobileMenu ? faXmark : faBars} />
        </button>

        {/* MENÚ */}
        <div
          className={`cabecera__menu ${
            mobileMenu ? "cabecera__menu--activo" : ""
          }`}
        >

          {/* NAV */}
          <nav className="cabecera__navegacion">
            <Link to="/servicios" onClick={closeMobileMenu}>
              Servicios
            </Link>

            <Link to="/citas" onClick={closeMobileMenu}>
              Citas
            </Link>

            <Link to="/sobre-nosotros" onClick={closeMobileMenu}>
              Sobre Nosotros
            </Link>

            <Link to="/blog" onClick={closeMobileMenu}>
              Blog
            </Link>

            <Link to="/contacto" onClick={closeMobileMenu}>
              Contacto
            </Link>
          </nav>

          {/* AUTH */}
          <div className="cabecera__auth">

            {user ? (
              <div className="user-menu" ref={menuRef}>

                <button
                  className={`cabecera__btn cabecera__btn--user ${
                    openMenu ? "cabecera__btn--active" : ""
                  }`}
                  type="button"
                  onClick={() => setOpenMenu(!openMenu)}
                >
                  <FontAwesomeIcon icon={faUser} />
                  <span>{user.nombre}</span>
                </button>

                {openMenu && (
                  <div className="dropdown dropdown--user">
                    <button
                      className="dropdown__item dropdown__item--logout"
                      type="button"
                      onClick={handleLogout}
                    >
                      Cerrar sesión
                    </button>
                  </div>
                )}

              </div>
            ) : (
              <>
                <Link
                  to="/login"
                  className="cabecera__btn"
                  onClick={closeMobileMenu}
                >
                  <FontAwesomeIcon icon={faUser} />
                  <span>Login</span>
                </Link>

                <Link
                  to="/registro"
                  className="cabecera__btn"
                  onClick={closeMobileMenu}
                >
                  <FontAwesomeIcon icon={faUserPlus} />
                  <span>Registro</span>
                </Link>
              </>
            )}

          </div>
        </div>
      </div>
    </header>
  );
}

export default Header;