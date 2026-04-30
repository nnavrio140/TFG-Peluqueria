import { useEffect, useState } from "react";
import "./Home.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";

function Home() {
  const [services, setServices] = useState([]);

  useEffect(() => {
    fetch("http://localhost:8080/api/servicios")
      .then((res) => res.json())
      .then((data) => {
        setServices(data.data.slice(0, 3));
      })
      .catch((error) => console.error("Error:", error));
  }, []);

  const getServiceImage = (name) => {
    switch (name) {
      case "Corte & Barba":
        return "/img/tijeras.webp";
      case "Afeitado":
        return "/img/navaja.webp";
      case "Corte & Teñido":
        return "/img/bigote.webp";
      default:
        return "/img/default.webp";
    }
  };

  return (
    <div className="home">

      {/* 🔥 HEADER reutilizable */}
      <div className="section__header home__header">
        <img src="/img/Logo.webp" alt="logo" className="home__logo" />
      </div>

      {/* SERVICIOS */}
      <div className="servicios">

        <h2 className="home__title">
          NUESTROS MEJORES SERVICIOS
        </h2>

        <div className="grid">
          {services.map((service) => (
            <ServiceCard
              key={service.id}
              icon={getServiceImage(service.nombre)}
              title={service.nombre}
              text={service.descripcion_corta}
            />
          ))}
        </div>

        <div className="btn">
          RESERVA AHORA
        </div>

      </div>

      {/* STATS */}
      <div className="stats">

        <div className="stat">
          <img src="/img/cuchilla.webp" alt="cuchilla" />
          <strong>2500</strong>
          <span>AFEITADOS</span>
        </div>

        <div className="stat">
          <img src="/img/tijeras.webp" alt="tijeras" />
          <strong>4500</strong>
          <span>CORTES</span>
        </div>

        <div className="stat">
          <img src="/img/peluqueros.webp" alt="peluqueros" />
          <strong>3</strong>
          <span>PELUQUEROS</span>
        </div>

      </div>

    </div>
  );
}

export default Home;