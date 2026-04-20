import "./Home.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";

function Home() {
  return (
    <div className="home">

      {/* HERO / LOGO */}
      <div className="hero">
        <img src="/img/Logo.webp" alt="logo" className="logo" />
      </div>

      {/* SERVICIOS */}
      <div className="servicios">

        <div className="titulo">
          NUESTROS SERVICIOS
        </div>

        <div className="grid">

          <ServiceCard
            icon="/img/tijeras.webp"
            title="CORTE & BARBA"
            text="Corte clásico o moderno acompañado de perfilado de barba. Precisión, estilo y acabados impecables."
          />

          <ServiceCard
            icon="/img/navaja.webp"
            title="AFEITADO NORMAL"
            text="Afeitado tradicional con navaja, productos de calidad para una experiencia relajante y profesional."
          />

          <ServiceCard
            icon="/img/bigote.webp"
            title="DÍA ESPECIAL"
            text="Servicio para eventos especiales: corte, barba y estilizado para que luzcas perfecto en tu gran día."
          />

        </div>

        <div className="btn">
          RESERVA AHORA
        </div>

      </div>

      {/* STATS */}
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
  );
}

export default Home;