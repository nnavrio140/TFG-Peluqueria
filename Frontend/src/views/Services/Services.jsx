import "./Services.css";

function Services() {
  return (
    <div className="services">

      {/* HEADER */}
      <div className="services__header">
        <p className="services__tag">SERVICIOS</p>
        <h1 className="services__title">
          Cortes, afeitados y estilo a medida
        </h1>
      </div>

      {/* 4 CARDS */}
      <div className="services__grid">

        <div className="card">
          <img src="/img/tijeras.webp" alt="" />
          <h3>CORTE & BARBA</h3>
          <p>Corte clásico o moderno combinado con arreglo y perfilado de barba.</p>
        </div>

        <div className="card">
          <img src="/img/navaja.webp" alt="" />
          <h3>AFEITADO</h3>
          <p>Afeitado tradicional con navaja y toalla caliente para una experiencia relajante.</p>
        </div>

        <div className="card">
          <img src="/img/cuchilla.webp" alt="" />
          <h3>CORTE</h3>
          <p>Corte de cabello preciso, limpio y adaptado a tu estilo personal.</p>
        </div>

        <div className="card">
          <img src="/img/tijeras.webp" alt="" />
          <h3>CORTE & TINTE</h3>
          <p>Corte y coloración profesional para renovar completamente tu imagen.</p>
        </div>

      </div>

      {/* PRECIOS (2x2 CAJAS COMO IMAGEN) */}
      <div className="prices">

        <div className="price">
          <div>
            <h4>CORTE & BARBA</h4>
            <p>Corte clásico o moderno adaptado a tu estilo.</p>
          </div>
          <span>12€</span>
        </div>

        <div className="price">
          <div>
            <h4>CORTE</h4>
            <p>Afeitado tradicional con toalla caliente.</p>
          </div>
          <span>8€</span>
        </div>

        <div className="price">
          <div>
            <h4>CORTE & TINTE</h4>
            <p>Perfilado y definición de barba con navaja.</p>
          </div>
          <span>20€</span>
        </div>

        <div className="price">
          <div>
            <h4>AFEITADO</h4>
            <p>Tratamiento facial completo para la piel.</p>
          </div>
          <span>6€</span>
        </div>

      </div>

      {/* BOTON */}
      <div className="btn-wrapper">
        <a href="#contacto" className="btn">
          RESERVA TU CITA
        </a>
      </div>

    </div>
  );
}

export default Services;