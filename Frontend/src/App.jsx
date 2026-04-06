import Header from './components/Header/Header'
import Footer from './components/Footer/Footer'
import logoImg from './img/Logo.webp'
import tijerasIcon from './img/tijeras.webp'
import navajaIcon from './img/navaja.webp'
import cuchillaIcon from './img/cuchilla.webp'
import './App.css'

function App() {
  return (
    <>
      <Header />

      <main className="home">

        {/* HERO */}
        <section className="hero-home">
          <img src={logoImg} alt="Logo JBarber" className="hero-home__logo" />
        </section>

        {/* SERVICIOS */}
        <section className="servicios" id="servicios">
          <p className="servicios__small-title">NUESTROS SERVICIOS</p>
          <h1 className="servicios__title">Expertos en estilo barber</h1>

          <div className="servicios__grid">
            <article className="servicio-card">
              <img src={tijerasIcon} alt="Corte y Barba" />
              <h2>CORTE & BARBA</h2>
              <p>
                Corte clásico o moderno con acabado perfecto, barba perfilada y acabado de lujo.
              </p>
            </article>

            <article className="servicio-card">
              <img src={navajaIcon} alt="Afeitado Manual" />
              <h2>AFEITADO MANUAL</h2>
              <p>
                Afeitado profesional con navaja, toalla caliente y productos hidratantes.
              </p>
            </article>

            <article className="servicio-card">
              <img src={cuchillaIcon} alt="Día Especial" />
              <h2>DÍA ESPECIAL</h2>
              <p>
                Servicio premium para eventos y ocasiones, con estilo personalizado y cuidado completo.
              </p>
            </article>
          </div>

          <a href="#contacto" className="button button--primary">
            RESERVA AHORA
          </a>

          {/* ESTADÍSTICAS */}
          <div className="estadisticas">
            <div className="estadistica-card">
              <strong>2500</strong>
              <span>AFEITADO</span>
            </div>
            <div className="estadistica-card">
              <strong>4500</strong>
              <span>CORTES</span>
            </div>
            <div className="estadistica-card">
              <strong>3</strong>
              <span>PELUQUEROS</span>
            </div>
          </div>
        </section>

      </main>

      <Footer />
    </>
  )
}

export default App