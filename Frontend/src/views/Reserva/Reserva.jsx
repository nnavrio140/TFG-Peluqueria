import { useEffect, useMemo, useState } from "react";
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
  const [monthOffset, setMonthOffset] = useState(0);

  const [horas, setHoras] = useState([]);
  const [hora, setHora] = useState(null);
  const [cargandoHoras, setCargandoHoras] = useState(false);

  const diasDisponiblesSet = useMemo(
    () => new Set(dias || []),
    [dias]
  );

  const calendarDates = useMemo(() => {
    const today = new Date();

    const currentMonth = new Date(
      today.getFullYear(),
      today.getMonth() + monthOffset,
      1
    );

    const monthLabel = currentMonth.toLocaleDateString("es-ES", {
      month: "long",
      year: "numeric",
    });

    const days = [];

    const firstWeekday = (currentMonth.getDay() + 6) % 7;

    for (let i = 0; i < firstWeekday; i += 1) {
      days.push({
        type: "empty",
        key: `empty-start-${i}`,
      });
    }

    const daysInMonth = new Date(
      currentMonth.getFullYear(),
      currentMonth.getMonth() + 1,
      0
    ).getDate();

    const todayLocalIso = `${today.getFullYear()}-${String(
      today.getMonth() + 1
    ).padStart(2, "0")}-${String(today.getDate()).padStart(2, "0")}`;

    for (let day = 1; day <= daysInMonth; day += 1) {
      const date = new Date(
        currentMonth.getFullYear(),
        currentMonth.getMonth(),
        day
      );

      const iso = `${date.getFullYear()}-${String(
        date.getMonth() + 1
      ).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")}`;

      const isPast = iso < todayLocalIso;

      const isWeekend =
        date.getDay() === 0 || date.getDay() === 6;

      const available =
        diasDisponiblesSet.has(iso) && !isPast;

      const status = available
        ? "Disponible"
        : isPast
        ? "Fecha pasada"
        : isWeekend
        ? "Cerrado (fin de semana)"
        : "No disponible";

      days.push({
        type: "day",
        iso,
        label: String(day).padStart(2, "0"),
        weekday: date.toLocaleDateString("es-ES", {
          weekday: "short",
        }),
        monthLabel,
        available,
        status,
        isToday: iso === todayLocalIso,
        isWeekend,
        isPast,
      });
    }

    const totalCells = days.length;

    const extra =
      totalCells % 7 === 0
        ? 0
        : 7 - (totalCells % 7);

    for (let i = 0; i < extra; i += 1) {
      days.push({
        type: "empty",
        key: `empty-end-${i}`,
      });
    }

    return days;
  }, [diasDisponiblesSet, monthOffset]);

  useEffect(() => {
    fetch("http://localhost:8080/api/servicios")
      .then((res) => res.json())
      .then((data) => setServicios(data.data))
      .catch(console.log);
  }, []);

  const getServiceImage = (name = "") => {
    if (name.includes("Barba"))
      return "/img/corte_barba.webp";

    if (name.includes("Afeitado"))
      return "/img/navaja.webp";

    if (name.includes("Teñido"))
      return "/img/peinado.webp";

    if (name.includes("Corte"))
      return "/img/tijeras.webp";

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
      .then((res) => res.json())
      .then((data) => setEmpleados(data.data || []))
      .catch(console.log);
  }, [step]);

  useEffect(() => {
    if (step !== 3 || !servicio || !empleado) return;

    fetch(
      `http://localhost:8080/api/dias-disponibles?id_servicio=${servicio.id}&id_empleado=${empleado.id}`
    )
      .then((res) => res.json())
      .then((data) => setDias(data.dias || []))
      .catch(console.log);
  }, [step, servicio, empleado]);

  useEffect(() => {
    if (step !== 3 || !servicio || !empleado || !fecha) return;

    setHoras([]);
    setCargandoHoras(true);

    fetch(
      `http://localhost:8080/api/disponibilidad?id_servicio=${servicio.id}&id_empleado=${empleado.id}&fecha=${fecha}`
    )
      .then((res) => res.json())
      .then((data) => setHoras(data.disponibilidad || []))
      .catch(console.log)
      .finally(() => setCargandoHoras(false));
  }, [step, servicio, empleado, fecha]);

  const crearCita = () => {
    fetch("http://localhost:8080/api/citas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_servicio: servicio.id,
        id_empleado: empleado.id,
        fecha,
        hora_inicio: hora,
      }),
    })
      .then((res) => res.json())
      .then(() => alert("Cita creada ✔"))
      .catch(console.log);
  };

  return (
    <div className="reserva">

      {/* PASO 1 */}
      {step === 1 && (
        <div className="reserva__servicesStep">

          <div className="section__header">
            <h1 className="section__title">
              ELIGE SERVICIO
            </h1>
          </div>

          <div className="reserva__servicesGrid">
            {servicios.map((s) => (
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

      {/* PASO 2 */}
      {step === 2 && (
        <div className="reserva__step reserva__step--barbers">

          <div className="section__header">
            <h1 className="section__title">
              ELIGE BARBERO
            </h1>
          </div>

          <div className="reserva__grid-barbers">
            {empleados.map((e) => (
              <div
                key={e.id}
                className="reserva__click"
                onClick={() => {
                  setEmpleado(e);
                  setFecha(null);
                  setHora(null);
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

{/* ========================= */}
{/* PASO 3 - FECHA Y HORA */}
{/* ========================= */}

{step === 3 && (
  <div className="reserva__step">

    <div className="section__header">
      <h1 className="section__title">ELIGE FECHA</h1>
    </div>

    <div className="reserva__calendarLayout">

      {/* ========================= */}
      {/* COLUMNA IZQUIERDA */}
      {/* ========================= */}

      <div className="reserva__calendarCol">

        {/* MES */}
        <div className="reserva__calendarHeader">

          <button
            type="button"
            className="btn btn--ghost"
            onClick={() =>
              setMonthOffset((prev) => Math.max(prev - 1, 0))
            }
            disabled={monthOffset === 0}
          >
            ‹
          </button>

          <h2 className="reserva__calendarMonth">
            {
              calendarDates.find((d) => d.type === "day")
                ?.monthLabel || ""
            }
          </h2>

          <button
            type="button"
            className="btn btn--ghost"
            onClick={() => setMonthOffset((prev) => prev + 1)}
          >
            ›
          </button>

        </div>

        <p className="reserva__calendarNote">
          Selecciona un día disponible para continuar.
        </p>

        {/* CALENDARIO */}
        <div className="reserva__calendarGrid">

          {["Lun","Mar","Mié","Jue","Vie","Sáb","Dom"].map((d) => (
            <div key={d} className="reserva__calendarWeekday">
              {d}
            </div>
          ))}

          {calendarDates.map((day) => {
            if (day.type === "empty") {
              return (
                <div
                  key={day.key}
                  className="reserva__calendarEmptyCell"
                />
              );
            }

            return (
              <button
                key={day.iso}
                className={`reserva__calendarDay ${
                  day.available ? "is-active" : "is-disabled"
                } ${fecha === day.iso ? "is-selected" : ""}`}
                onClick={() => {
                  if (!day.available) return;
                  setFecha(day.iso);
                  setHora(null);
                }}
                disabled={!day.available}
              >
                <span>{day.weekday}</span>
                <strong>{day.label}</strong>
              </button>
            );
          })}

        </div>

      </div>

      {/* ========================= */}
      {/* COLUMNA DERECHA */}
      {/* ========================= */}

      {fecha && (
        <aside className="reserva__hoursPanel">

          <div className="reserva__hoursHeader">
            <h2>Horas disponibles</h2>
            <span>{fecha}</span>
          </div>

          {cargandoHoras ? (
            <div className="reserva__empty">
              Cargando horarios...
            </div>
          ) : horas.length > 0 ? (
            <div className="reserva__hoursMenu">

              <label className="reserva__selectLabel">
                Selecciona una hora
              </label>

              <select
                className="reserva__hoursSelect"
                value={hora || ""}
                onChange={(e) => setHora(e.target.value)}
              >
                <option value="">Elige una hora</option>

                {horas.map((h) => (
                  <option key={h} value={h}>
                    {h}
                  </option>
                ))}
              </select>

              <button
                className="btn btn--gold"
                disabled={!hora}
                onClick={() => setStep(4)}
              >
                Continuar
              </button>

            </div>
          ) : (
            <div className="reserva__empty">
              No hay horas disponibles
            </div>
          )}

        </aside>
      )}

    </div>

    {/* BOTÓN VOLVER */}
    <button
      className="btn btn--ghost reserva__back"
      onClick={() => setStep(2)}
    >
      Volver
    </button>

  </div>
)}

      {step === 4 && (
        <div className="reserva__step reserva__confirmStep">
          <div className="section__header">
            <h1 className="section__title">
              CONFIRMAR CITA
            </h1>
          </div>

          <div className="reserva__summary">
            <p>Servicio: {servicio?.nombre}</p>
            <p>Barbero: {empleado?.usuario?.nombre}</p>
            <p>Fecha: {fecha}</p>
            <p>Hora: {hora}</p>
          </div>

          <div className="reserva__confirmActions">
            <button
              className="reserva__confirmButton"
              type="button"
              disabled={!hora}
              onClick={crearCita}
            >
              Confirmar cita
            </button>

            <button
              className="reserva__back"
              type="button"
              onClick={() => setStep(3)}
            >
              Cambiar hora
            </button>
          </div>
        </div>
      )}

    </div>
  );
}

export default Reserva;