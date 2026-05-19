import { useEffect, useState } from "react";
import "./About.css";
import BarberCard from "../../components/BarberCard/BarberCard";
import { EMPLEADOS_ENDPOINT } from "../../services/endpoints";

function About() {
  const [barbers, setBarbers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(EMPLEADOS_ENDPOINT)
      .then((res) => res.json())
      .then((data) => {
        console.log(data);
        setBarbers(data.data || []);
      })
      .catch((err) => {
        console.error(err);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  return (
    <div className="about">

      {/* HEADER */}
      <div className="section__header">
        <h1 className="section__title">SOBRE NOSOTROS</h1>
      </div>

      {/* BARBERS */}
      <div className="about__barbers-section">
        {loading ? (
          <div className="about__loading">
            Cargando barberos...
          </div>
        ) : (
          <div className="about__barbers">
            {barbers.map((barber) => (
              <BarberCard
                key={barber.id}
                barber={barber}
                image={barber.imagen_url || "/img/barber-default.webp"}
              />
            ))}
          </div>
        )}
      </div>

    </div>
  );
}

export default About;