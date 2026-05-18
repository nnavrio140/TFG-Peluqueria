import { useEffect, useState } from "react";
import "./Services.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";
import { SERVICIOS_ENDPOINT } from "../../services/endpoints";

function Services() {
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(SERVICIOS_ENDPOINT)
      .then((res) => res.json())
      .then((data) => {
        setServices(data.data || []);
      })
      .catch((error) => {
        console.error("Error:", error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  const getServiceImage = (name) => {
    switch (name) {
      case "Corte & Barba":
        return "/img/corte_barba.webp";
      case "Afeitado":
        return "/img/navaja.webp";
      case "Corte":
        return "/img/tijeras.webp";
      case "Corte & Teñido":
        return "/img/peinado.webp";
      default:
        return "/img/default.webp";
    }
  };

  return (
    <div className="services">
      {/* HEADER */}
      <div className="section__header">
        <h1 className="section__title">SERVICIOS</h1>
      </div>

      {loading ? (
        <div className="services__loading-section">
          <div className="services__loading">
            Preparando nuestros servicios...
          </div>
        </div>
      ) : (
        <>
          {/* CARDS */}
          <div className="services__grid">
            {services.map((service) => (
              <ServiceCard
                key={service.id}
                icon={getServiceImage(service.nombre)}
                title={service.nombre}
                text={service.descripcion}
              />
            ))}
          </div>

          {/* PRICES */}
          <div className="prices">
            <div className="prices__container">
              {services.map((service) => (
                <div className="price" key={service.id}>
                  <div className="price__top">
                    <h4>{service.nombre}</h4>
                    <div className="line"></div>
                    <span>{service.precio}€</span>
                  </div>

                  <p>{service.descripcion_corta}</p>
                </div>
              ))}
            </div>
          </div>
        </>
      )}
    </div>
  );
}

export default Services;