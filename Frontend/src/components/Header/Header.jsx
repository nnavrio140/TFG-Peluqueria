import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBars, faStar } from '@fortawesome/free-solid-svg-icons'
import './Header.css'

function Logo({ simple }) {
  return (
    <div className={`cabecera__marca ${simple ? 'cabecera__marca--simple' : ''}`}>
      <FontAwesomeIcon icon={faStar} />
      <span className="cabecera__marca-texto">Barber</span>
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
          <Logo simple />
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