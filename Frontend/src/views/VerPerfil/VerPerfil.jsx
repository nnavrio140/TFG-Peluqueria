import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { ME_ENDPOINT, USUARIOS_ENDPOINTS } from "../../services/endpoints";
import "./VerPerfil.css";

const EMPTY_PROFILE_FORM = {
  nombre: "",
  email: "",
  password: "",
  password_confirmation: "",
  rol: "usuario",
};

export default function VerPerfil() {
  const navigate = useNavigate();
  const token = localStorage.getItem("token");

  const [user, setUser] = useState(null);
  const [formData, setFormData] = useState(EMPTY_PROFILE_FORM);
  const [checkingAuth, setCheckingAuth] = useState(true);
  const [saving, setSaving] = useState(false);
  const [deleting, setDeleting] = useState(false);
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
  const [message, setMessage] = useState("");
  const [messageType, setMessageType] = useState("");

  const jsonHeaders = {
    "Content-Type": "application/json",
    Accept: "application/json",
    Authorization: `Bearer ${token}`,
  };

  useEffect(() => {
    checkAuth();
  }, []);

  const getApiErrorMessage = async (res) => {
    const data = await res.json().catch(() => null);
    console.error("Error API:", data);

    if (data?.message && data?.errors) {
      return `${data.message}: ${Object.values(data.errors).flat().join(" ")}`;
    }

    return data?.message || "No se pudo completar la acción. Revisa los campos.";
  };

  const getUserName = (item) => item?.nombre || item?.name || "";

  const getRoleSlug = (item) =>
    String(
      item?.rol?.slug ||
        item?.role?.slug ||
        item?.rol ||
        item?.role ||
        item?.nombre_rol ||
        item?.rol_nombre ||
        "usuario"
    ).toLowerCase();

  const checkAuth = async () => {
    try {
      if (!token) {
        navigate("/login");
        return;
      }

      const res = await fetch(ME_ENDPOINT, {
        method: "GET",
        headers: jsonHeaders,
      });

      if (!res.ok) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        navigate("/login");
        return;
      }

      const data = await res.json();
      const loggedUser = data.user || data;

      setUser(loggedUser);

      setFormData({
        nombre: getUserName(loggedUser),
        email: loggedUser.email || "",
        password: "",
        password_confirmation: "",
        rol: getRoleSlug(loggedUser) || "usuario",
      });
    } catch (error) {
      console.error(error);
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      navigate("/login");
    } finally {
      setCheckingAuth(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;

    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const buildPayload = () => {
    const payload = {
      nombre: String(formData.nombre || "").trim(),
      email: String(formData.email || "").trim(),
      rol: formData.rol || getRoleSlug(user) || "usuario",
    };

    if (formData.password) {
      payload.password = formData.password;
      payload.password_confirmation = formData.password_confirmation;
    }

    return payload;
  };

  const submitProfile = async (e) => {
    e.preventDefault();

    if (!user?.id) {
      setMessageType("error");
      setMessage("No se ha podido identificar el usuario.");
      return;
    }

    if (formData.password && formData.password !== formData.password_confirmation) {
      setMessageType("error");
      setMessage("Las contraseñas no coinciden.");
      return;
    }

    try {
      setSaving(true);
      setMessage("");
      setMessageType("");

      const res = await fetch(USUARIOS_ENDPOINTS.UPDATE(user.id), {
        method: "PUT",
        headers: jsonHeaders,
        body: JSON.stringify(buildPayload()),
      });

      if (!res.ok) {
        setMessageType("error");
        setMessage(await getApiErrorMessage(res));
        return;
      }

      const data = await res.json().catch(() => null);
      const updatedUser = data?.user || data?.usuario || data || {
        ...user,
        nombre: formData.nombre,
        email: formData.email,
        rol: formData.rol,
      };

      localStorage.setItem("user", JSON.stringify(updatedUser));

      window.location.href = "/";
    } catch (error) {
      console.error(error);
      setMessageType("error");
      setMessage("No se pudo conectar con Laravel.");
    } finally {
      setSaving(false);
    }
  };

  const deleteAccount = async () => {
    if (!user?.id || deleting || saving) return;

    try {
      setDeleting(true);
      setMessage("");
      setMessageType("");

      const res = await fetch(USUARIOS_ENDPOINTS.DELETE(user.id), {
        method: "DELETE",
        headers: jsonHeaders,
      });

      if (!res.ok) {
        setMessageType("error");
        setMessage(await getApiErrorMessage(res));
        setShowDeleteConfirm(false);
        return;
      }

      localStorage.removeItem("token");
      localStorage.removeItem("user");

      window.location.href = "/login";
    } catch (error) {
      console.error(error);
      setMessageType("error");
      setMessage("No se pudo eliminar la cuenta.");
    } finally {
      setDeleting(false);
    }
  };

  const goBack = () => {
    if (!saving && !deleting) {
      navigate("/");
    }
  };

  if (checkingAuth) {
    return (
      <div className="perfil-page">
        <div className="perfil-loader"></div>
      </div>
    );
  }

  return (
    <div className="perfil-page">
      <section className="perfil-panel">
        <div className="perfil-panel-header">
          <div>
            <span>Editar</span>
            <h1>Perfil</h1>
          </div>

          <button
            type="button"
            className="perfil-close-btn"
            onClick={goBack}
            disabled={saving || deleting}
            aria-label="Volver"
          >
            <span>×</span>
          </button>
        </div>

        <form className="perfil-form" onSubmit={submitProfile}>
          {message && (
            <div className={`perfil-message ${messageType}`}>
              {message}
            </div>
          )}

          <label>
            Nombre
            <input
              type="text"
              name="nombre"
              value={formData.nombre}
              onChange={handleInputChange}
              placeholder="Tu nombre"
              required
              disabled={saving || deleting}
            />
          </label>

          <label>
            Email
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleInputChange}
              placeholder="tuemail@gmail.com"
              required
              disabled={saving || deleting}
            />
          </label>

          <label>
            Nueva contraseña
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleInputChange}
              placeholder="Dejar vacío para no cambiar"
              disabled={saving || deleting}
            />
          </label>

          <label>
            Confirmar contraseña
            <input
              type="password"
              name="password_confirmation"
              value={formData.password_confirmation}
              onChange={handleInputChange}
              placeholder="Repite la nueva contraseña"
              disabled={saving || deleting}
            />
          </label>

          <div className="perfil-actions">
            <button
              type="button"
              className="perfil-cancel-btn"
              onClick={goBack}
              disabled={saving || deleting}
            >
              Cancelar
            </button>

            <button
              type="submit"
              className="perfil-save-btn"
              disabled={saving || deleting}
            >
              {saving ? "Guardando..." : "Guardar"}
            </button>
          </div>

          <div className="perfil-danger-zone">
            <div>
              <h2>Eliminar cuenta</h2>
              <p>
                Esta acción eliminará tu usuario completamente y no se podrá
                deshacer.
              </p>
            </div>

            {!showDeleteConfirm ? (
              <button
                type="button"
                className="perfil-delete-btn"
                onClick={() => setShowDeleteConfirm(true)}
                disabled={saving || deleting}
              >
                Eliminar cuenta
              </button>
            ) : (
              <div className="perfil-delete-confirm">
                <p>¿Seguro que quieres eliminar esta cuenta?</p>

                <div className="perfil-delete-actions">
                  <button
                    type="button"
                    className="perfil-delete-cancel-btn"
                    onClick={() => setShowDeleteConfirm(false)}
                    disabled={deleting}
                  >
                    No, cancelar
                  </button>

                  <button
                    type="button"
                    className="perfil-delete-confirm-btn"
                    onClick={deleteAccount}
                    disabled={deleting}
                  >
                    {deleting ? "Eliminando..." : "Sí, eliminar"}
                  </button>
                </div>
              </div>
            )}
          </div>
        </form>
      </section>
    </div>
  );
}