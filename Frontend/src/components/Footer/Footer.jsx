import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {
  faLocationDot,
  faEnvelope,
  faPhone,
  faClock,
} from '@fortawesome/free-solid-svg-icons'
import './Footer.css'

function Footer() {
  return (
    <footer className="pie">
      <div className="pie__contenedor">
        <h2 className="pie__titulo">CONTACTANOS</h2>
        <p className="pie__texto">
          Tu próximo corte empieza aquí. Escríbenos, llámanos o pásate por la barbería.
          Nos encargamos de que salgas con el look que buscas.
        </p>

        <div className="pie__grid">
          <article className="pie__bloque">
            <div className="pie__icono">
              <FontAwesomeIcon icon={faLocationDot} />
            </div>
            <strong>Dirección</strong>
            <p>Calle Fátima, nº 1, Almáchar, 29718</p>
          </article>

          <article className="pie__bloque">
            <div className="pie__icono">
              <FontAwesomeIcon icon={faEnvelope} />
            </div>
            <strong>Email</strong>
            <p>jbarber@gmail.com</p>
          </article>

          <article className="pie__bloque">
            <div className="pie__icono">
              <FontAwesomeIcon icon={faPhone} />
            </div>
            <strong>Teléfono</strong>
            <p>(+34) 643 12 55 67</p>
          </article>

          <article className="pie__bloque">
            <div className="pie__icono">
              <FontAwesomeIcon icon={faClock} />
            </div>
            <strong>Horario</strong>
            <p>Mon - Fri: 10:00 - 14:00 / 16:00 - 20:00<br />Sat: 10:00 - 14:00</p>
          </article>
        </div>

        <div className="pie__linea" />
        <p className="pie__copy">© 2026 JBarber. Todos los derechos reservados.</p>
      </div>
    </footer>
  )
}

export default Footer
