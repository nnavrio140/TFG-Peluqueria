import { useEffect, useState } from "react";
import "./Home.css";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";
import { SERVICIOS_ENDPOINT } from "../../services/endpoints";

function Home() {
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);

  const location = useLocation();
  const navigate = useNavigate();

  const getImageUrl = (url) => {
    if (!url) return "/img/default.webp";

    return url.replace(
      "http://ec2-16-192-23-37.eu-north-1.compute.amazonaws.com",
      ""
    );
  };

  useEffect(() => {
    if (location.state?.toastMessage) {
      toast.success(location.state.toastMessage);

      navigate(location.pathname, {
        replace: true,
        state: {},
      });
    }
  }, [location, navigate]);

  useEffect(() => {
    fetch(SERVICIOS_ENDPOINT)
      .then((res) => res.json())
      .then((data) => {
        console.log("SERVICIOS API:", data.data);
        setServices((data.data || []).slice(0, 3));
      })
      .catch((error) => {
        console.error("Error:", error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  return (
    <div className="home">
      <div className="section__header home__header">
        <img src="/img/Logo.webp" alt="logo" className="home__logo" />
      </div>

      <div className="servicios">
        <h2 className="home__title">NUESTROS MEJORES SERVICIOS</h2>

        {loading ? (
          <div className="home__loading-section">
            <div className="home__loading">
              Preparando nuestros servicios...
            </div>
          </div>
        ) : (
          <div className="grid">
            {services.map((service) => (
              <ServiceCard
                key={service.id}
                icon={getImageUrl(service.imagen_url)}
                title={service.nombre || service.nombre_servicio}
                text={service.descripcion}
              />
            ))}
          </div>
        )}

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

      <ToastContainer
        position="top-center"
        autoClose={2500}
        hideProgressBar={false}
        closeButton={false}
        pauseOnHover={true}
        draggable={false}
      />
    </div>
  );
}

export default Home;