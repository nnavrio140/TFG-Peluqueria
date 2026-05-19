import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPen, faTrash } from "@fortawesome/free-solid-svg-icons";
import {
  ME_ENDPOINT,
  USUARIOS_ENDPOINTS,
  SERVICIOS_ENDPOINTS,
  CONTACT_ENDPOINTS,
} from "../../services/endpoints";
import "./AdminDashboard.css";

const ROLE_OPTIONS = [
  { label: "Administrador", value: "admin" },
  { label: "Empleado", value: "empleado" },
  { label: "Usuario", value: "usuario" },
];

const EMPTY_FORMS = {
  usuarios: {
    nombre: "",
    email: "",
    password: "",
    rol: "usuario",
  },
  servicios: {
    nombre_servicio: "",
    descripcion_corta: "",
    descripcion: "",
    precio: "",
    duracion: "",
  },
};

export default function AdminDashboard() {
  const navigate = useNavigate();

  const [activeSection, setActiveSection] = useState("usuarios");
  const [user, setUser] = useState(null);
  const [checkingAuth, setCheckingAuth] = useState(true);
  const [accessDenied, setAccessDenied] = useState(false);

  const [usuarios, setUsuarios] = useState([]);
  const [servicios, setServicios] = useState([]);
  const [contactos, setContactos] = useState([]);

  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState("");

  const [modalOpen, setModalOpen] = useState(false);
  const [modalType, setModalType] = useState("");
  const [editingItem, setEditingItem] = useState(null);
  const [formData, setFormData] = useState({});

  const token = localStorage.getItem("token");

  const authHeaders = {
    "Content-Type": "application/json",
    Accept: "application/json",
    Authorization: `Bearer ${token}`,
  };

  useEffect(() => {
    checkAuth();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    if (user && !accessDenied) {
      loadData();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [activeSection, user, accessDenied]);

  const getArray = (data) => {
    if (Array.isArray(data)) return data;
    if (Array.isArray(data?.data)) return data.data;
    if (Array.isArray(data?.usuarios)) return data.usuarios;
    if (Array.isArray(data?.servicios)) return data.servicios;
    if (Array.isArray(data?.contactos)) return data.contactos;
    return [];
  };

  const getApiErrorMessage = async (res) => {
    const data = await res.json().catch(() => null);

    console.error("Error API:", data);

    if (data?.message && data?.errors) {
      const errors = Object.values(data.errors).flat().join(" ");
      return `${data.message}: ${errors}`;
    }

    if (data?.message) return data.message;

    return "No se pudo guardar el registro. Revisa los campos enviados.";
  };

  const getRoleSlug = (item) => {
    const role =
      item?.rol?.slug ||
      item?.role?.slug ||
      item?.rol ||
      item?.role ||
      item?.nombre_rol ||
      item?.rol_nombre ||
      "";

    return String(role).toLowerCase();
  };

  const getUserName = (item) => {
    return item?.nombre || item?.name || "Sin nombre";
  };

  const getServicioNombre = (servicio) => {
    return servicio?.nombre || servicio?.nombre_servicio || "Sin nombre";
  };

  const getServicioDescripcionCorta = (servicio) => {
    return servicio?.descripcion_corta || "Sin descripción corta";
  };

  const getServicioDescripcion = (servicio) => {
    return servicio?.descripcion || "Sin descripción";
  };

  const canAccessDashboard = (loggedUser) => {
    const role = getRoleSlug(loggedUser);
    return role === "admin" || role === "empleado";
  };

  const checkAuth = async () => {
    try {
      if (!token) {
        setAccessDenied(true);
        return;
      }

      const res = await fetch(ME_ENDPOINT, {
        method: "GET",
        headers: authHeaders,
      });

      if (!res.ok) {
        setAccessDenied(true);
        return;
      }

      const data = await res.json();
      const loggedUser = data.user || data;

      setUser(loggedUser);

      if (!canAccessDashboard(loggedUser)) {
        setAccessDenied(true);
      }
    } catch (error) {
      console.error(error);
      setAccessDenied(true);
    } finally {
      setCheckingAuth(false);
    }
  };

  const loadData = async () => {
    setMessage("");

    if (activeSection === "usuarios") {
      await fetchData(USUARIOS_ENDPOINTS.INDEX, setUsuarios, "usuarios");
    }

    if (activeSection === "servicios") {
      await fetchData(SERVICIOS_ENDPOINTS.INDEX, setServicios, "servicios");
    }

    if (activeSection === "contacto") {
      await fetchData(CONTACT_ENDPOINTS.INDEX, setContactos, "contacto");
    }
  };

  const fetchData = async (url, setter, label) => {
    try {
      setLoading(true);

      const res = await fetch(url, {
        method: "GET",
        headers: authHeaders,
      });

      if (!res.ok) throw new Error(`Error cargando ${label}`);

      const data = await res.json();
      setter(getArray(data));
    } catch (error) {
      console.error(error);
      setMessage(`Error al cargar ${label} desde la BD.`);
    } finally {
      setLoading(false);
    }
  };

  const getEndpointsByType = (type) => {
    if (type === "usuarios") return USUARIOS_ENDPOINTS;
    if (type === "servicios") return SERVICIOS_ENDPOINTS;
    return null;
  };

  const refreshCurrentSection = async () => {
    await loadData();
  };

  const changeSection = (section) => {
    if (loading || saving || checkingAuth) return;
    if (activeSection === section) return;

    setMessage("");
    setActiveSection(section);
  };

  const openCreateModal = (type) => {
    if (loading || saving) return;

    setModalType(type);
    setEditingItem(null);
    setFormData({ ...EMPTY_FORMS[type] });
    setModalOpen(true);
  };

  const openEditModal = (type, item) => {
    if (loading || saving) return;

    setModalType(type);
    setEditingItem(item);

    if (type === "usuarios") {
      setFormData({
        nombre: item.nombre || item.name || "",
        email: item.email || "",
        password: "",
        rol: getRoleSlug(item) || "usuario",
      });
    }

    if (type === "servicios") {
      setFormData({
        nombre_servicio: item.nombre || item.nombre_servicio || "",
        descripcion_corta: item.descripcion_corta || "",
        descripcion: item.descripcion || "",
        precio: item.precio || "",
        duracion: item.duracion || "",
      });
    }

    setModalOpen(true);
  };

  const closeModal = () => {
    if (saving) return;

    setModalOpen(false);
    setModalType("");
    setEditingItem(null);
    setFormData({});
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;

    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const buildPayload = () => {
    if (modalType === "usuarios") {
      const payload = { ...formData };

      if (editingItem && !payload.password) {
        delete payload.password;
      }

      return payload;
    }

    if (modalType === "servicios") {
      return {
        nombre_servicio: String(formData.nombre_servicio || "").trim(),
        descripcion_corta: String(formData.descripcion_corta || "").trim(),
        descripcion: String(formData.descripcion || "").trim(),
        precio: Number(formData.precio),
        duracion: Number(formData.duracion),
      };
    }

    return { ...formData };
  };

  const submitForm = async (e) => {
    e.preventDefault();

    const endpoints = getEndpointsByType(modalType);
    if (!endpoints) return;

    const isEditing = Boolean(editingItem);
    const url = isEditing ? endpoints.UPDATE(editingItem.id) : endpoints.STORE;
    const method = isEditing ? "PUT" : "POST";
    const payload = buildPayload();

    console.log("Enviando a Laravel:", {
      url,
      method,
      payload,
    });

    try {
      setSaving(true);
      setMessage("");

      const res = await fetch(url, {
        method,
        headers: authHeaders,
        body: JSON.stringify(payload),
      });

      if (!res.ok) {
        const apiMessage = await getApiErrorMessage(res);
        setMessage(apiMessage);
        return;
      }

      setMessage(
        isEditing
          ? "Registro actualizado correctamente."
          : "Registro creado correctamente."
      );

      setModalOpen(false);
      setModalType("");
      setEditingItem(null);
      setFormData({});

      await refreshCurrentSection();
    } catch (error) {
      console.error(error);
      setMessage("No se pudo conectar con Laravel.");
    } finally {
      setSaving(false);
    }
  };

  const deleteItem = async (type, id) => {
    if (loading || saving) return;

    const endpoints = getEndpointsByType(type);
    if (!endpoints) return;

    try {
      setLoading(true);
      setMessage("");

      const res = await fetch(endpoints.DELETE(id), {
        method: "DELETE",
        headers: authHeaders,
      });

      if (!res.ok) {
        const apiMessage = await getApiErrorMessage(res);
        setMessage(apiMessage);
        return;
      }

      setMessage("Registro eliminado correctamente.");
      await refreshCurrentSection();
    } catch (error) {
      console.error(error);
      setMessage("No se pudo eliminar el registro.");
    } finally {
      setLoading(false);
    }
  };

  const goHome = () => {
    if (loading || saving) return;
    navigate("/");
  };

  const renderContent = () => {
    if (loading) {
      return (
        <div className="admin-content-loader">
          <div className="admin-loader"></div>
        </div>
      );
    }

    return (
      <>
        {message && <div className="admin-message">{message}</div>}

        {activeSection === "usuarios" && (
          <UsuariosSection
            usuarios={usuarios}
            getRoleSlug={getRoleSlug}
            getUserName={getUserName}
            disabled={loading || saving}
            onCreate={() => openCreateModal("usuarios")}
            onEdit={(item) => openEditModal("usuarios", item)}
            onDelete={(id) => deleteItem("usuarios", id)}
          />
        )}

        {activeSection === "servicios" && (
          <ServiciosSection
            servicios={servicios}
            disabled={loading || saving}
            getServicioNombre={getServicioNombre}
            getServicioDescripcionCorta={getServicioDescripcionCorta}
            getServicioDescripcion={getServicioDescripcion}
            onCreate={() => openCreateModal("servicios")}
            onEdit={(item) => openEditModal("servicios", item)}
            onDelete={(id) => deleteItem("servicios", id)}
          />
        )}

        {activeSection === "contacto" && (
          <ContactoSection contactos={contactos} />
        )}
      </>
    );
  };

  if (checkingAuth) {
    return (
      <div className="admin-loading-page">
        <div className="admin-loader"></div>
      </div>
    );
  }

  if (accessDenied) {
    return (
      <div className="admin-denied-page">
        <div className="admin-denied-box">
          <h1>Acceso denegado</h1>
          <p>Solo los administradores y empleados pueden entrar al dashboard.</p>
          <button onClick={() => navigate("/")}>Volver</button>
        </div>
      </div>
    );
  }

  return (
    <div className="admin-dashboard-page">
      <aside className="admin-sidebar">
        <div className="admin-sidebar-content">
          <button
            type="button"
            disabled={loading || saving}
            className={activeSection === "usuarios" ? "active" : ""}
            onClick={() => changeSection("usuarios")}
          >
            Usuarios
          </button>

          <button
            type="button"
            disabled={loading || saving}
            className={activeSection === "servicios" ? "active" : ""}
            onClick={() => changeSection("servicios")}
          >
            Servicios
          </button>

          <button
            type="button"
            disabled={loading || saving}
            className={activeSection === "contacto" ? "active" : ""}
            onClick={() => changeSection("contacto")}
          >
            Contacto
          </button>
        </div>

        <button
          type="button"
          className="admin-back-btn"
          disabled={loading || saving}
          onClick={goHome}
        >
          Volver
        </button>
      </aside>

      <main className="admin-main">
        <div className="admin-overlay"></div>

        <section className="admin-content">
          <h1>ADMIN DASHBOARD</h1>
          {renderContent()}
        </section>
      </main>

      {modalOpen && (
        <AdminModal
          modalType={modalType}
          editingItem={editingItem}
          formData={formData}
          onChange={handleInputChange}
          onSubmit={submitForm}
          onClose={closeModal}
          loading={saving}
        />
      )}
    </div>
  );
}

function UsuariosSection({
  usuarios,
  getRoleSlug,
  getUserName,
  disabled,
  onCreate,
  onEdit,
  onDelete,
}) {
  return (
    <div className="admin-table-wrapper">
      <div className="admin-section-header">
        <h2>Usuarios</h2>

        <button
          type="button"
          className="admin-create-btn"
          disabled={disabled}
          onClick={onCreate}
        >
          Crear usuario
        </button>
      </div>

      <table className="admin-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Password</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          {usuarios.length > 0 ? (
            usuarios.map((usuario) => (
              <tr key={usuario.id}>
                <td>{getUserName(usuario)}</td>
                <td>{usuario.email || "Sin email"}</td>
                <td>••••••••</td>
                <td>{getRoleSlug(usuario) || "usuario"}</td>
                <td>
                  <ActionButtons
                    item={usuario}
                    disabled={disabled}
                    onEdit={onEdit}
                    onDelete={onDelete}
                  />
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="5">No hay usuarios registrados en la BD.</td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

function ServiciosSection({
  servicios,
  disabled,
  getServicioNombre,
  getServicioDescripcionCorta,
  getServicioDescripcion,
  onCreate,
  onEdit,
  onDelete,
}) {
  return (
    <div className="admin-table-wrapper">
      <div className="admin-section-header">
        <h2>Servicios</h2>

        <button
          type="button"
          className="admin-create-btn"
          disabled={disabled}
          onClick={onCreate}
        >
          Crear servicio
        </button>
      </div>

      <table className="admin-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción corta</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Duración</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          {servicios.length > 0 ? (
            servicios.map((servicio) => (
              <tr key={servicio.id}>
                <td>{getServicioNombre(servicio)}</td>
                <td title={getServicioDescripcionCorta(servicio)}>
                  {getServicioDescripcionCorta(servicio)}
                </td>
                <td title={getServicioDescripcion(servicio)}>
                  {getServicioDescripcion(servicio)}
                </td>
                <td>{Number(servicio.precio || 0).toFixed(2)}€</td>
                <td>{servicio.duracion || 0} min</td>
                <td>
                  <ActionButtons
                    item={servicio}
                    disabled={disabled}
                    onEdit={onEdit}
                    onDelete={onDelete}
                  />
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="6">No hay servicios registrados en la BD.</td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

function ContactoSection({ contactos }) {
  return (
    <div className="admin-table-wrapper">
      <div className="admin-section-header">
        <h2>Contacto</h2>
      </div>

      <table className="admin-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Asunto</th>
            <th>Mensaje</th>
          </tr>
        </thead>

        <tbody>
          {contactos.length > 0 ? (
            contactos.map((contacto) => (
              <tr key={contacto.id}>
                <td>{contacto.nombre || "Sin nombre"}</td>
                <td>{contacto.email || "Sin email"}</td>
                <td>{contacto.asunto || "Sin asunto"}</td>
                <td>{contacto.mensaje || "Sin mensaje"}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="4">No hay mensajes de contacto registrados.</td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

function ActionButtons({ item, disabled, onEdit, onDelete }) {
  return (
    <div className="admin-actions">
      <button
        type="button"
        disabled={disabled}
        className="admin-action-icon-btn edit"
        onClick={() => onEdit(item)}
        title="Editar"
        aria-label="Editar"
      >
        <FontAwesomeIcon icon={faPen} />
      </button>

      <button
        type="button"
        disabled={disabled}
        className="admin-action-icon-btn danger"
        onClick={() => onDelete(item.id)}
        title="Eliminar"
        aria-label="Eliminar"
      >
        <FontAwesomeIcon icon={faTrash} />
      </button>
    </div>
  );
}

function AdminModal({
  modalType,
  editingItem,
  formData,
  onChange,
  onSubmit,
  onClose,
  loading,
}) {
  return (
    <div className="admin-modal-backdrop">
      <div className="admin-modal">
        <div className="admin-modal-header">
          <div>
            <span>{editingItem ? "Editar" : "Crear"}</span>
            <h3>{modalType === "usuarios" ? "Usuario" : "Servicio"}</h3>
          </div>

          <button
            type="button"
            className="admin-modal-close"
            onClick={onClose}
            disabled={loading}
            aria-label="Cerrar"
          >
            ×
          </button>
        </div>

        <form onSubmit={onSubmit} className="admin-modal-form">
          {modalType === "usuarios" && (
            <>
              <label>
                Nombre
                <input
                  type="text"
                  name="nombre"
                  value={formData.nombre || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Email
                <input
                  type="email"
                  name="email"
                  value={formData.email || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Password
                <input
                  type="password"
                  name="password"
                  value={formData.password || ""}
                  onChange={onChange}
                  placeholder={
                    editingItem ? "Dejar vacío para no cambiar" : "Contraseña"
                  }
                  required={!editingItem}
                  disabled={loading}
                />
              </label>

              <label>
                Rol
                <select
                  name="rol"
                  value={formData.rol || "usuario"}
                  onChange={onChange}
                  required
                  disabled={loading}
                >
                  {ROLE_OPTIONS.map((role) => (
                    <option key={role.value} value={role.value}>
                      {role.label}
                    </option>
                  ))}
                </select>
              </label>
            </>
          )}

          {modalType === "servicios" && (
            <>
              <label>
                Nombre del servicio
                <input
                  type="text"
                  name="nombre_servicio"
                  value={formData.nombre_servicio || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Descripción corta
                <input
                  type="text"
                  name="descripcion_corta"
                  value={formData.descripcion_corta || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Descripción completa
                <textarea
                  name="descripcion"
                  value={formData.descripcion || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Precio
                <input
                  type="number"
                  step="0.01"
                  name="precio"
                  value={formData.precio || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>

              <label>
                Duración en minutos
                <input
                  type="number"
                  name="duracion"
                  value={formData.duracion || ""}
                  onChange={onChange}
                  required
                  disabled={loading}
                />
              </label>
            </>
          )}

          <div className="admin-modal-actions">
            <button type="button" onClick={onClose} disabled={loading}>
              Cancelar
            </button>

            <button type="submit" disabled={loading}>
              {loading ? "Guardando..." : "Guardar"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}