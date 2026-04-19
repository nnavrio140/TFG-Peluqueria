import './Home.css'

function Home() {
  return (
    <div className="home">

      {/* LOGO */}
      <div className="hero">
        <img src="/img/Logo.webp" alt="logo" className="logo" />
      </div>

      {/* SERVICIOS */}
      <div className="servicios">

        <div className="titulo">NUESTROS SERVICIOS</div>

        <div className="grid">

          <div className="card">
            <div className="icon-wrap">
              <img src="/img/tijeras.webp" alt="" />
            </div>
            <h3>CORTE & BARBA</h3>
            <p>Corte clásico o moderno con acabado perfecto.</p>
          </div>

          <div className="card">
            <div className="icon-wrap">
              <img src="/img/navaja.webp" alt="" />
            </div>
            <h3>AFEITADO NORMAL</h3>
            <p>Afeitado tradicional con navaja.</p>
          </div>

          <div className="card">
            <div className="icon-wrap">
              <img src="/img/bigote.webp" alt="" />
            </div>
            <h3>DÍA ESPECIAL</h3>
            <p>Servicio premium para eventos.</p>
          </div>

        </div>

        <div className="btn">RESERVA AHORA</div>

      </div>

      {/* STATS CON ICONOS */}
      <div className="stats">

        <div className="stat">
          <img src="/img/cuchilla.webp" alt="" />
          <strong>2500</strong>
          <span>AFEITADOS</span>
        </div>

        <div className="stat">
          <img src="/img/tijeras.webp" alt="" />
          <strong>4500</strong>
          <span>CORTES</span>
        </div>

        <div className="stat">
          <img src="/img/peluqueros.webp" alt="" />
          <strong>3</strong>
          <span>PELUQUEROS</span>
        </div>

      </div>

    </div>
  )
}

export default Home