import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBars } from '@fortawesome/free-solid-svg-icons'
import './Header.css'
import logoImg from '../../img/Logo.webp'

function Logo() {
  return (
    <div className="cabecera__marca">
      <img src={logoImg} alt="Logo JBarber" className="cabecera__logo-img" />
    </div>
  )
}

function Navigation() {
  return (
    <nav className="cabecera__navegacion" aria-label="Menú principal">
      <a href="#servicios">Servicios</a>
      <a href="#sobre-nosotros">Sobre Nosotros</a>
      <a href="#blog">Blog</a>
      <a href="#contacto">Contacto</a>
    </nav>
  )
}

function Header({ simple = false }) {
  if (simple) {
    return (
      <header className="cabecera cabecera--simple">
        <div className="cabecera__contenedor cabecera__contenedor--simple">
          <Logo />
        </div>
      </header>
    )
  }

  return (
    <header className="cabecera">
      <div className="cabecera__contenedor">
        <button className="cabecera__menu" type="button" aria-label="Abrir menú">
          <FontAwesomeIcon icon={faBars} />
        </button>

        <Navigation />

        <Logo />
      </div>
    </header>
  )
}

export default Header