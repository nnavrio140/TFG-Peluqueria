import "./Services.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";

function Services() {
  return (
    <div className="services">

      <div className="section__header">
        <h1 className="section__title">SERVICIOS</h1>
      </div>

      <div className="services__grid">

        <ServiceCard
          icon="/img/corte_barba.webp"
          title="CORTE & BARBA"
          text="Corte clásico o moderno combinado con arreglo y perfilado de barba. Trabajamos cada detalle para lograr un estilo preciso."
        />

        <ServiceCard
          icon="/img/navaja.webp"
          title="AFEITADO"
          text="Afeitado tradicional con navaja y toalla caliente. Experiencia relajante y profesional."
        />

        <ServiceCard
          icon="/img/tijeras.webp"
          title="CORTE"
          text="Corte de cabello con técnica precisa y acabado moderno."
        />

        <ServiceCard
          icon="/img/peinado.webp"
          title="CORTE & TINTE"
          text="Coloración y corte profesional adaptado a tu estilo."
        />

      </div>

      <div className="prices">

        <div className="prices__container">

          <div className="price">
            <div className="price__top">
              <h4>CORTE & BARBA</h4>
              <div className="line"></div>
              <span>12€</span>
            </div>
            <p>Corte clásico o moderno adaptado a tu estilo. Incluye asesoría personalizada.</p>
          </div>

          <div className="price">
            <div className="price__top">
              <h4>CORTE</h4>
              <div className="line"></div>
              <span>8€</span>
            </div>
            <p>Afeitado tradicional con toalla caliente.</p>
          </div>

          <div className="price">
            <div className="price__top">
              <h4>CORTE & TINTE</h4>
              <div className="line"></div>
              <span>20€</span>
            </div>
            <p>Perfilado y definición de barba con navaja.</p>
          </div>

          <div className="price">
            <div className="price__top">
              <h4>AFEITADO</h4>
              <div className="line"></div>
              <span>6€</span>
            </div>
            <p>Tratamiento facial completo para la piel.</p>
          </div>

        </div>

      </div>

    </div>
  );
}

export default Services;