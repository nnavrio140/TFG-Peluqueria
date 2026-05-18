import { useEffect, useMemo, useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import "./Reserva.css";
import ServiceCard from "../../components/ServiceCard/ServiceCard";
import BarberCard from "../../components/BarberCard/BarberCard";
import { AuthContext } from "../../context/AuthContext";
import {
  SERVICIOS_ENDPOINTS,
  EMPLEADOS_ENDPOINTS,
  DISPONIBILIDAD_ENDPOINTS,
  CITAS_ENDPOINTS,
} from "../../services/endpoints";

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
  const [loadingCita, setLoadingCita] = useState(false);

  const { token } = useContext(AuthContext);
  const navigate = useNavigate();

  const diasDisponiblesSet = useMemo(() => new Set(dias || []), [dias]);

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
      const isWeekend = date.getDay() === 0 || date.getDay() === 6;
      const available = diasDisponiblesSet.has(iso) && !isPast;

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
    const extra = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);

    for (let i = 0; i < extra; i += 1) {
      days.push({
        type: "empty",
        key: `empty-end-${i}`,
      });
    }

    return days;
  }, [diasDisponiblesSet, monthOffset]);

  useEffect(() => {
    fetch(SERVICIOS_ENDPOINTS.INDEX)
      .then((res) => res.json())
      .then((data) => setServicios(data.data))
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

    fetch(EMPLEADOS_ENDPOINTS.INDEX)
      .then((res) => res.json())
      .then((data) => setEmpleados(data.data || []))
      .catch(console.log);
  }, [step]);

  useEffect(() => {
    if (step !== 3 || !servicio || !empleado) return;

    fetch(
      `${DISPONIBILIDAD_ENDPOINTS.DIAS_DISPONIBLES}?id_servicio=${servicio.id}&id_empleado=${empleado.id}`
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
      `${DISPONIBILIDAD_ENDPOINTS.GET_DISPONIBILIDAD}?id_servicio=${servicio.id}&id_empleado=${empleado.id}&fecha=${fecha}`
    )
      .then((res) => res.json())
      .then((data) => setHoras(data.disponibilidad || []))
      .catch(console.log)
      .finally(() => setCargandoHoras(false));
  }, [step, servicio, empleado, fecha]);

  const abrirModalHoras = (iso) => {
    setFecha(iso);
    setHora(null);
  };

  const cerrarModalHoras = () => {
    setFecha(null);
    setHora(null);
    setHoras([]);
  };

  const volverAlCalendario = () => {
    setFecha(null);
    setHora(null);
    setHoras([]);
    setStep(3);
  };

  const crearCita = async () => {
    if (!token) {
      toast.error("Necesitas iniciar sesión para reservar.");
      return navigate("/login");
    }

    if (!servicio || !empleado || !fecha || !hora) {
      toast.error("Selecciona servicio, barbero, fecha y hora.");
      return;
    }

    setLoadingCita(true);

    try {
      const response = await fetch(CITAS_ENDPOINTS.STORE, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          id_servicio: servicio.id,
          id_empleado: empleado.id,
          fecha,
          hora_inicio: hora,
        }),
      });

      let data;

      try {
        data = await response.json();
      } catch (jsonError) {
        const text = await response.text();
        throw new Error(
          text || "Respuesta inesperada del servidor al crear la cita."
        );
      }

      if (!response.ok) {
        throw new Error(
          data?.message || data?.error || "Error al crear la cita."
        );
      }

      navigate("/", {
        state: {
          toastMessage: data.message || "Cita creada correctamente",
        },
      });
    } catch (error) {
      toast.error(error?.message || "No se pudo crear la cita.");
      console.error("Error creando cita:", error);
    } finally {
      setLoadingCita(false);
    }
  };

  return (
    <div className="reserva">
      <ToastContainer />

      {/* PASO 1 */}
      {step === 1 && (
        <div className="reserva__servicesStep">
          <div className="section__header">
            <h1 className="section__title">ELIGE SERVICIO</h1>
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
            <h1 className="section__title">ELIGE BARBERO</h1>
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
                <BarberCard barber={e} image={getBarberImage(e.id)} />
              </div>
            ))}
          </div>

          <button
            className="reserva__backButton"
            type="button"
            onClick={() => setStep(1)}
          >
            Volver
          </button>
        </div>
      )}

      {/* PASO 3 */}
      {step === 3 && (
        <div className="reserva__step">
          <div className="section__header">
            <h1 className="section__title">ELIGE FECHA</h1>
          </div>

          <div className="reserva__calendarLayout">
            <div className="reserva__calendarCol">
              <div className="reserva__calendarHeader">
                <button
                  type="button"
                  className="reserva__calendarNav"
                  onClick={() =>
                    setMonthOffset((prev) => Math.max(prev - 1, 0))
                  }
                  disabled={monthOffset === 0}
                >
                  ‹
                </button>

                <h2 className="reserva__calendarMonth">
                  {calendarDates.find((d) => d.type === "day")?.monthLabel ||
                    ""}
                </h2>

                <button
                  type="button"
                  className="reserva__calendarNav"
                  onClick={() => setMonthOffset((prev) => prev + 1)}
                >
                  ›
                </button>
              </div>

              <div className="reserva__calendarGrid">
                {["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"].map(
                  (d) => (
                    <div key={d} className="reserva__calendarWeekday">
                      {d}
                    </div>
                  )
                )}

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
                      type="button"
                      className={`reserva__calendarDay ${
                        day.available ? "is-active" : "is-disabled"
                      } ${fecha === day.iso ? "is-selected" : ""}`}
                      onClick={() => {
                        if (!day.available) return;
                        abrirModalHoras(day.iso);
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
          </div>

          {fecha && (
            <div className="reserva__hoursModal" onClick={cerrarModalHoras}>
              <div
                className="reserva__hoursModalContent"
                onClick={(e) => e.stopPropagation()}
              >
                <div className="reserva__hoursHeader">
                  <h2>Horas disponibles</h2>
                  <span>{fecha}</span>
                </div>

                {cargandoHoras ? (
                  <div className="reserva__empty">Cargando horarios...</div>
                ) : horas.length > 0 ? (
                  <>
                    <p className="reserva__selectLabel">
                      Selecciona una hora
                    </p>

                    <div className="reserva__hoursOptions">
                      {horas.map((h) => (
                        <button
                          key={h}
                          type="button"
                          className={`reserva__hourOption ${
                            hora === h ? "is-selected" : ""
                          }`}
                          onClick={() => setHora(h)}
                        >
                          {h}
                        </button>
                      ))}
                    </div>

                    <button
                      className="btn btn--gold reserva__hoursContinue"
                      type="button"
                      disabled={!hora}
                      onClick={() => setStep(4)}
                    >
                      Continuar
                    </button>
                  </>
                ) : (
                  <div className="reserva__empty">
                    No hay horas disponibles
                  </div>
                )}

                <button
                  type="button"
                  className="reserva__hoursClose"
                  onClick={cerrarModalHoras}
                >
                  Cerrar
                </button>
              </div>
            </div>
          )}

          <button
            className="btn btn--ghost reserva__backButton"
            type="button"
            onClick={() => setStep(2)}
          >
            Volver
          </button>
        </div>
      )}

      {/* PASO 4 */}
      {step === 4 && (
        <div className="reserva__step reserva__confirmStep">
          <div className="section__header">
            <h1 className="section__title">CONFIRMAR CITA</h1>
          </div>

          <div className="reserva__summary">
            <p>
              <strong>Servicio:</strong> {servicio?.nombre}
            </p>

            <p>
              <strong>Barbero:</strong>{" "}
              {empleado?.nombre || empleado?.usuario?.nombre}
            </p>

            <p>
              <strong>Fecha:</strong> {fecha}
            </p>

            <p>
              <strong>Hora:</strong> {hora}
            </p>
          </div>

          <div className="reserva__confirmActions">
            <button
              className="reserva__back reserva__actionButton"
              type="button"
              onClick={volverAlCalendario}
            >
              Volver
            </button>

            <button
              className="reserva__confirmButton reserva__actionButton"
              type="button"
              disabled={!hora || loadingCita}
              onClick={crearCita}
            >
              {loadingCita ? "Creando cita..." : "Confirmar cita"}
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default Reserva;