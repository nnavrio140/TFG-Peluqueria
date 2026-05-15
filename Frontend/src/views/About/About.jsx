import { useEffect, useState } from "react";
import "./About.css";
import BarberCard from "../../components/BarberCard/BarberCard";
import { EMPLEADOS_ENDPOINT } from "../../services/endpoints";

function About() {
  const [barbers, setBarbers] = useState([]);

  useEffect(() => {
    fetch(EMPLEADOS_ENDPOINT)
      .then((res) => res.json())
      .then((data) => setBarbers(data.data))
      .catch((err) => console.error(err));
  }, []);

  const getBarberImage = (id) => {
    switch (id) {
      case 1:
        return "/img/juanje.webp";
      case 2:
        return "/img/nico.webp";
      case 3:
        return "/img/antonio.webp";
      default:
        return "/img/barber-default.webp";
    }
  };

  return (
    <div className="about">

      {/* HEADER */}
      <div className="section__header">
        <h1 className="section__title">SOBRE NOSOTROS</h1>
      </div>

      {/* BARBERS */}
      <div className="about__barbers-section">
        <div className="about__barbers">
          {barbers.map((barber) => (
            <BarberCard
              key={barber.id}
              barber={barber}
              image={getBarberImage(barber.id)}
            />
          ))}
        </div>
      </div>

    </div>
  );
}

export default About;