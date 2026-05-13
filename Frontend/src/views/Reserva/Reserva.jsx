import { useEffect, useState } from "react";
import "./Reserva.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";
import BarberCard from "../../components/BarberCard/BarberCard";

function Reserva() {
  const [step, setStep] = useState(1);

  const [servicios, setServicios] = useState([]);
  const [servicio, setServicio] = useState(null);

  const [empleados, setEmpleados] = useState([]);
  const [empleado, setEmpleado] = useState(null);

  const [dias, setDias] = useState([]);
  const [fecha, setFecha] = useState(null);

  const [horas, setHoras] = useState([]);
  const [hora, setHora] = useState(null);

  useEffect(() => {
    fetch("http://localhost:8080/api/servicios")
      .then(res => res.json())
      .then(data => setServicios(data.data))
      .catch(console.log);
  }, []);

  const getServiceImage = (name = "") => {
    if (name.includes("Barba")) return "/img/corte_barba.webp";
    if (name.includes("Afeitado")) return "/img/navaja.webp";
    if (name.includes("Teñido")) return "/img/peinado.webp";
    if (name.includes("Corte")) return "/img/tijeras.webp";

    return "/img/default.webp";
  };

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

  useEffect(() => {
    if (step !== 2) return;

    fetch("http://localhost:8080/api/empleados")
      .then(res => res.json())
      .then(data => setEmpleados(data.data || []))
      .catch(console.log);
  }, [step]);

  useEffect(() => {
    if (step !== 3 || !servicio || !empleado) return;

    fetch(
      `http://localhost:8080/api/dias-disponibles?id_servicio=${servicio.id}&id_empleado=${empleado.id}`
    )
      .then(res => res.json())
      .then(data => setDias(data.dias || []))
      .catch(console.log);
  }, [step, servicio, empleado]);

  useEffect(() => {
    if (step !== 4 || !servicio || !empleado || !fecha) return;

    fetch(
      `http://localhost:8080/api/disponibilidad?id_servicio=${servicio.id}&id_empleado=${empleado.id}&fecha=${fecha}`
    )
      .then(res => res.json())
      .then(data => setHoras(data.disponibilidad || []))
      .catch(console.log);
  }, [step, servicio, empleado, fecha]);

  const crearCita = () => {
    fetch("http://localhost:8080/api/citas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        id_servicio: servicio.id,
        id_empleado: empleado.id,
        fecha,
        hora_inicio: hora
      })
    })
      .then(res => res.json())
      .then(() => alert("Cita creada ✔"))
      .catch(console.log);
  };

  return (
    <div className="reserva">

      {step === 1 && (
        <div className="reserva__servicesStep">

          <div className="section__header">
            <h1 className="section__title">ELIGE SERVICIO</h1>
          </div>

          <div className="reserva__servicesGrid">
            {servicios.map(s => (
              <div
                key={s.id}
                className="reserva__serviceItem"
                onClick={() => {
                  setServicio(s);
                  setStep(2);
                }}
              >
                <ServiceCard
                  icon={getServiceImage(s.nombre)}
                  title={s.nombre}
                  text={s.descripcion}
                />
              </div>
            ))}
          </div>

        </div>
      )}

      {step === 2 && (
        <div className="reserva__step reserva__step--barbers">

          <div className="section__header">
            <h1 className="section__title">ELIGE BARBERO</h1>
          </div>

          <div className="reserva__grid-barbers">
            {empleados.map(e => (
              <div
                key={e.id}
                className="reserva__click"
                onClick={() => {
                  setEmpleado(e);
                  setStep(3);
                }}
              >
                <BarberCard
                  barber={e}
                  image={getBarberImage(e.id)}
                />
              </div>
            ))}
          </div>

          <button
            className="reserva__back"
            onClick={() => setStep(1)}
          >
            Volver
          </button>

        </div>
      )}

      {step === 3 && (
        <div className="reserva__step">

          <div className="section__header">
            <h1 className="section__title">ELIGE FECHA</h1>
          </div>

          <div className="reserva__grid-simple">
            {dias.map(d => (
              <div
                key={d}
                className="reserva__click"
                onClick={() => {
                  setFecha(d);
                  setStep(4);
                }}
              >
                <ServiceCard title={d} text="" />
              </div>
            ))}
          </div>

          <button
            className="reserva__back"
            onClick={() => setStep(2)}
          >
            Volver
          </button>

        </div>
      )}

      {step === 4 && (
        <div className="reserva__step">

          <div className="section__header">
            <h1 className="section__title">ELIGE HORA</h1>
          </div>

          <div className="reserva__grid-simple">
            {horas.map(h => (
              <div
                key={h}
                className="reserva__click"
                onClick={() => {
                  setHora(h);
                  setStep(5);
                }}
              >
                <ServiceCard title={h} text="" />
              </div>
            ))}
          </div>

          <button
            className="reserva__back"
            onClick={() => setStep(3)}
          >
            Volver
          </button>

        </div>
      )}

      {step === 5 && (
        <div className="reserva__step reserva__confirm">

          <div className="section__header">
            <h1 className="section__title">CONFIRMAR</h1>
          </div>

          <div className="reserva__summary">
            <p>Servicio: {servicio?.nombre}</p>
            <p>Barbero: {empleado?.usuario?.nombre}</p>
            <p>Fecha: {fecha}</p>
            <p>Hora: {hora}</p>
          </div>

          <button
            className="reserva__confirmBtn"
            onClick={crearCita}
          >
            Confirmar cita
          </button>

        </div>
      )}

    </div>
  );
}

export default Reserva;