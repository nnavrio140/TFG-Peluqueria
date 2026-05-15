import { useEffect, useState } from "react";
import "./Home.css";
import { Link } from "react-router-dom";
import ServiceCard from "../../components/ServiceCard/ServiceCard";
import { SERVICIOS_ENDPOINT } from "../../services/endpoints";

function Home() {

  const [services, setServices] = useState([]);

  useEffect(() => {
    fetch(SERVICIOS_ENDPOINT)
      .then((res) => res.json())
      .then((data) => {
        console.log("SERVICIOS API:", data.data);
        setServices(data.data.slice(0, 3));
      })
      .catch((error) => console.error("Error:", error));
  }, []);

  const getServiceImage = (name = "") => {
    if (name.includes("Barba")) return "/img/corte_barba.webp";
    if (name.includes("Afeitado")) return "/img/navaja.webp";
    if (name.includes("Teñido")) return "/img/peinado.webp";
    if (name.includes("Corte")) return "/img/tijeras.webp";
    return "/img/default.webp";
  };

  return (
    <div className="home">

      <div className="section__header home__header">
        <img src="/img/Logo.webp" alt="logo" className="home__logo" />
      </div>

      <div className="servicios">

        <h2 className="home__title">
          NUESTROS MEJORES SERVICIOS
        </h2>

        <div className="grid">

          {services.map((service) => (
            <ServiceCard
              key={service.id}
              icon={getServiceImage(service.nombre || service.nombre_servicio)}
              title={service.nombre || service.nombre_servicio}
              text={service.descripcion}
            />
          ))}

        </div>

        <Link to="/reserva" className="btn">
          RESERVA AHORA
        </Link>

      </div>

      <div className="stats">

        <div className="stat">
          <img src="/img/cuchilla.webp" alt="afeitados" />
          <strong>2500</strong>
          <span>AFEITADOS</span>
        </div>

        <div className="stat">
          <img src="/img/tijeras.webp" alt="cortes" />
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