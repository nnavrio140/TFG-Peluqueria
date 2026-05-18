import { useContext, useEffect, useMemo, useState } from "react";
import { AuthContext } from "../../context/AuthContext";
import {
  CITAS_ENDPOINTS,
  DISPONIBILIDAD_ENDPOINTS,
} from "../../services/endpoints";
import "./Citas.css";

function Citas() {
  const { token, user } = useContext(AuthContext);

  const [citas, setCitas] = useState([]);
  const [monthOffset, setMonthOffset] = useState(0);
  const [selected, setSelected] = useState(null);

  const [editingCita, setEditingCita] = useState(null);
  const [formValues, setFormValues] = useState({
    fecha: "",
    hora_inicio: "",
  });

  const [apiError, setApiError] = useState(null);

  const [deletingCitaId, setDeletingCitaId] = useState(null);
  const [savingCita, setSavingCita] = useState(false);

  const [horasDisponibles, setHorasDisponibles] = useState([]);
  const [cargandoHoras, setCargandoHoras] = useState(false);

  useEffect(() => {
    const load = async () => {
      try {
        const res = await fetch(CITAS_ENDPOINTS.INDEX, {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        });

        const data = await res.json();
        setCitas(data.data || []);
      } catch (error) {
        setApiError("Error al cargar las citas.");
      }
    };

    if (token) load();
  }, [token]);

  useEffect(() => {
    if (!selected) return;

    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";

    return () => {
      document.body.style.overflow = previousOverflow;
    };
  }, [selected]);

  useEffect(() => {
    if (!editingCita || !formValues.fecha) return;

    const servicioId = editingCita.servicio?.id;
    const empleadoId = editingCita.empleado?.id;

    if (!servicioId || !empleadoId) {
      setApiError("No se puede cargar disponibilidad de esta cita.");
      return;
    }

    const cargarHorasDisponibles = async () => {
      setApiError(null);
      setCargandoHoras(true);
      setHorasDisponibles([]);

      try {
        const response = await fetch(
          `${DISPONIBILIDAD_ENDPOINTS.GET_DISPONIBILIDAD}?id_servicio=${servicioId}&id_empleado=${empleadoId}&fecha=${formValues.fecha}`,
          {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          }
        );

        const data = await response.json();

        if (!response.ok) {
          setApiError(
            data?.message || data?.error || "Error al cargar los horarios."
          );
          return;
        }

        let horas = data.disponibilidad || [];

        const mismaFechaOriginal = formValues.fecha === editingCita.fecha;
        const horaOriginal = editingCita.hora_inicio;

        if (
          mismaFechaOriginal &&
          horaOriginal &&
          !horas.includes(horaOriginal)
        ) {
          horas = [horaOriginal, ...horas];
        }

        setHorasDisponibles(horas);

        if (!horas.includes(formValues.hora_inicio)) {
          setFormValues((prev) => ({
            ...prev,
            hora_inicio: "",
          }));
        }
      } catch (error) {
        setApiError("Error de conexión al cargar los horarios.");
      } finally {
        setCargandoHoras(false);
      }
    };

    cargarHorasDisponibles();
  }, [editingCita, formValues.fecha, token]);

  const canManageCita = (cita) => {
    if (!user) return false;
    if (user.rol?.slug === "admin") return true;
    if (cita.usuario?.id === user.id) return true;

    if (
      user.rol?.slug === "empleado" &&
      user.empleado?.id &&
      cita.empleado?.id === user.empleado.id
    ) {
      return true;
    }

    return false;
  };

  const openEditCita = (cita) => {
    setEditingCita(cita);

    setFormValues({
      fecha: cita.fecha || "",
      hora_inicio: cita.hora_inicio || "",
    });

    setHorasDisponibles([]);
    setCargandoHoras(false);
    setApiError(null);
  };

  const closeEdit = () => {
    setEditingCita(null);

    setFormValues({
      fecha: "",
      hora_inicio: "",
    });

    setHorasDisponibles([]);
    setCargandoHoras(false);
    setApiError(null);
  };

  const handleFormChange = (field, value) => {
    setFormValues((prev) => ({
      ...prev,
      [field]: value,
    }));
  };

  const handleDeleteCita = async (cita) => {
    if (deletingCitaId) return;

    setApiError(null);
    setDeletingCitaId(cita.id);

    try {
      const response = await fetch(CITAS_ENDPOINTS.DELETE(cita.id), {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      });

      if (!response.ok) {
        const error = await response.json().catch(() => null);
        setApiError(error?.message || "Error al eliminar la cita");
        return;
      }

      setCitas((prev) => prev.filter((item) => item.id !== cita.id));

      setSelected((prev) => {
        if (!prev) return prev;

        const updatedSelected = prev.filter((item) => item.id !== cita.id);

        return updatedSelected.length > 0 ? updatedSelected : null;
      });

      if (editingCita?.id === cita.id) {
        closeEdit();
      }
    } catch (error) {
      setApiError("Error de conexión al eliminar la cita.");
    } finally {
      setDeletingCitaId(null);
    }
  };

  const handleSaveCita = async (event) => {
    event.preventDefault();

    if (!editingCita) return;

    if (!formValues.fecha || !formValues.hora_inicio) {
      setApiError("Selecciona una fecha y una hora disponible.");
      return;
    }

    if (cargandoHoras) {
      setApiError("Espera a que terminen de cargar los horarios.");
      return;
    }

    setSavingCita(true);
    setApiError(null);

    const payload = {
      fecha: formValues.fecha,
      hora_inicio: formValues.hora_inicio,
      id_servicio: editingCita.servicio?.id,
      id_empleado: editingCita.empleado?.id,
    };

    try {
      const response = await fetch(CITAS_ENDPOINTS.UPDATE(editingCita.id), {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        setApiError(data.message || data.error || "Error al actualizar la cita");
        return;
      }

      const updated = data.data;

      setCitas((prev) =>
        prev.map((item) => (item.id === updated.id ? updated : item))
      );

      setSelected((prev) =>
        prev
          ? prev.map((item) => (item.id === updated.id ? updated : item))
          : prev
      );

      closeEdit();
    } catch (error) {
      setApiError("Error de conexión al actualizar la cita.");
    } finally {
      setSavingCita(false);
    }
  };

  const citasMap = useMemo(() => {
    const map = new Map();

    citas.forEach((c) => {
      if (!map.has(c.fecha)) {
        map.set(c.fecha, []);
      }

      map.get(c.fecha).push(c);
    });

    return map;
  }, [citas]);

  const pageTitle = useMemo(() => {
    const roleSlug = user?.rol?.slug || null;

    if (roleSlug === "admin") return "TODAS LAS CITAS";
    if (roleSlug === "empleado") return "CITAS ASIGNADAS";

    return "MIS CITAS";
  }, [user]);

  const calendar = useMemo(() => {
    const today = new Date();

    const base = new Date(
      today.getFullYear(),
      today.getMonth() + monthOffset,
      1
    );

    const monthLabel = base.toLocaleDateString("es-ES", {
      month: "long",
      year: "numeric",
    });

    const days = [];

    const start = (base.getDay() + 6) % 7;

    for (let i = 0; i < start; i++) {
      days.push({
        type: "empty",
        key: `e-${i}`,
      });
    }

    const total = new Date(
      base.getFullYear(),
      base.getMonth() + 1,
      0
    ).getDate();

    for (let d = 1; d <= total; d++) {
      const date = new Date(base.getFullYear(), base.getMonth(), d);

      const iso = `${date.getFullYear()}-${String(
        date.getMonth() + 1
      ).padStart(2, "0")}-${String(d).padStart(2, "0")}`;

      days.push({
        type: "day",
        iso,
        label: d,
        weekday: date.toLocaleDateString("es-ES", {
          weekday: "short",
        }),
        hasCitas: citasMap.has(iso),
        monthLabel,
      });
    }

    const extra = days.length % 7 === 0 ? 0 : 7 - (days.length % 7);

    for (let i = 0; i < extra; i++) {
      days.push({
        type: "empty",
        key: `x-${i}`,
      });
    }

    return days;
  }, [monthOffset, citasMap]);

  const openDay = (iso) => {
    const data = citasMap.get(iso);

    if (data) {
      setSelected(data);
      setEditingCita(null);
      setApiError(null);
    }
  };

  const closeModal = () => {
    setSelected(null);
    closeEdit();
  };

  return (
    <div className="citas">
      <div className="section__header">
        <h1 className="section__title">{pageTitle}</h1>
      </div>

      <div className="citas__calendarWrap">
        <div className="citas__calendarHeader">
          <button type="button" onClick={() => setMonthOffset((p) => p - 1)}>
            ‹
          </button>

          <h2>{calendar.find((d) => d.type === "day")?.monthLabel}</h2>

          <button type="button" onClick={() => setMonthOffset((p) => p + 1)}>
            ›
          </button>
        </div>

        <div className="citas__grid">
          {["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"].map((d) => (
            <div key={d} className="citas__weekday">
              {d}
            </div>
          ))}

          {calendar.map((d, i) => {
            if (d.type === "empty") {
              return <div key={d.key || i} className="citas__empty" />;
            }

            return (
              <button
                key={d.iso}
                type="button"
                className={`citas__day ${d.hasCitas ? "has-citas" : ""}`}
                onClick={() => openDay(d.iso)}
              >
                <span>{d.weekday}</span>
                <strong>{d.label}</strong>
              </button>
            );
          })}
        </div>
      </div>

      {selected && (
        <div className="citas__modal" onClick={closeModal}>
          <div
            className="citas__modalContent"
            onClick={(e) => e.stopPropagation()}
          >
            <h3>Citas del día</h3>

            {apiError && <p className="citas__error">{apiError}</p>}

            {selected.map((cita) => (
              <div key={cita.id} className="citas__card">
                <p>
                  <b>Servicio:</b> {cita.servicio?.nombre || "-"}
                </p>

                <p>
                  <b>Barbero:</b> {cita.empleado?.nombre || "-"}
                </p>

                {user?.rol?.slug !== "usuario" && (
                  <p>
                    <b>Cliente:</b> {cita.usuario?.nombre || "-"}
                  </p>
                )}

                <p>
                  <b>Fecha:</b> {cita.fecha}
                </p>

                <p>
                  <b>Hora inicio:</b> {cita.hora_inicio}
                </p>

                <p>
                  <b>Estado:</b> {cita.estado || "Sin estado"}
                </p>

                {canManageCita(cita) && (
                  <div className="citas__actions">
                    <button
                      type="button"
                      className="citas__btn citas__btn--edit"
                      onClick={() => openEditCita(cita)}
                      disabled={deletingCitaId === cita.id || savingCita}
                    >
                      Editar
                    </button>

                    <button
                      type="button"
                      className="citas__btn citas__btn--delete"
                      onClick={() => handleDeleteCita(cita)}
                      disabled={deletingCitaId === cita.id || savingCita}
                    >
                      {deletingCitaId === cita.id
                        ? "Eliminando..."
                        : "Eliminar"}
                    </button>
                  </div>
                )}

                {editingCita?.id === cita.id && (
                  <form className="citas__editForm" onSubmit={handleSaveCita}>
                    <h4>Editar cita {editingCita.id}</h4>

                    <label>
                      Fecha
                      <input
                        type="date"
                        value={formValues.fecha}
                        onChange={(e) => {
                          handleFormChange("fecha", e.target.value);
                          handleFormChange("hora_inicio", "");
                        }}
                        required
                      />
                    </label>

                    <label>
                      Hora disponible
                      {cargandoHoras ? (
                        <div className="citas__loadingHours">
                          Cargando horarios...
                        </div>
                      ) : (
                        <select
                          value={formValues.hora_inicio}
                          onChange={(e) =>
                            handleFormChange("hora_inicio", e.target.value)
                          }
                          required
                          disabled={
                            !formValues.fecha || horasDisponibles.length === 0
                          }
                        >
                          <option value="">
                            {!formValues.fecha
                              ? "Primero selecciona una fecha"
                              : horasDisponibles.length > 0
                              ? "Selecciona una hora"
                              : "No hay horas disponibles"}
                          </option>

                          {horasDisponibles.map((horaDisponible) => (
                            <option
                              key={horaDisponible}
                              value={horaDisponible}
                            >
                              {horaDisponible}
                            </option>
                          ))}
                        </select>
                      )}
                    </label>

                    <div className="citas__editActions">
                      <button
                        type="submit"
                        disabled={
                          savingCita ||
                          cargandoHoras ||
                          !formValues.fecha ||
                          !formValues.hora_inicio
                        }
                      >
                        {savingCita ? "Guardando..." : "Guardar cambios"}
                      </button>

                      <button
                        type="button"
                        onClick={closeEdit}
                        disabled={savingCita}
                      >
                        Cancelar
                      </button>
                    </div>
                  </form>
                )}
              </div>
            ))}

            <button type="button" onClick={closeModal}>
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default Citas;