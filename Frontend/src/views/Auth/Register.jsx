import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "./Auth.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGoogle } from "@fortawesome/free-brands-svg-icons";
import { authService } from "../../services/authService";

const Register = () => {
  const [nombre, setNombre] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirm, setPasswordConfirm] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    // Validaciones básicas
    if (!nombre || !email || !password || !passwordConfirm) {
      setError("Por favor completa todos los campos");
      return;
    }

    if (password !== passwordConfirm) {
      setError("Las contraseñas no coinciden");
      return;
    }

    if (password.length < 8) {
      setError("La contraseña debe tener al menos 8 caracteres");
      return;
    }

    setLoading(true);

    try {
      await authService.register(nombre, email, password, passwordConfirm);
      console.log("Registro exitoso");
      navigate("/"); // Redirigir a home tras registro exitoso
    } catch (err) {
      setError(err.message || "Error en el registro");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="register-container">

      <div className="section__header">
        <h1 className="section__title">CREAR CUENTA</h1>
      </div>

      <div className="register-box">

        {/* FORM */}
        <div className="form-section">
          <div className="form-wrapper">

            <button className="google-btn" type="button">
              <FontAwesomeIcon icon={faGoogle} />
              <span>Iniciar sesión con Google</span>
            </button>

            {error && <div style={{ color: "red", marginBottom: "10px" }}>{error}</div>}

            <form className="form" onSubmit={handleSubmit}>

              <label>Nombre de usuario</label>
              <input 
                type="text" 
                value={nombre}
                onChange={(e) => setNombre(e.target.value)}
                required
              />

              <label>Email</label>
              <input 
                type="email" 
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />

              <label>Contraseña</label>
              <input 
                type="password" 
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />

              <label>Confirmar contraseña</label>
              <input 
                type="password" 
                value={passwordConfirm}
                onChange={(e) => setPasswordConfirm(e.target.value)}
                required
              />

            </form>

            {/* ACCIONES */}
            <div className="actions">

              <button 
                className="create-btn"
                onClick={handleSubmit}
                disabled={loading}
                type="button"
              >
                {loading ? "Creando cuenta..." : "Crear cuenta"}
              </button>

              <p className="login-text">
                ¿Ya tienes cuenta?
              </p>

              <Link to="/login">
                <button className="login-btn" type="button">
                  Iniciar sesión
                </button>
              </Link>

            </div>

          </div>
        </div>

        {/* LOGO */}
        <div className="logo-section">
          <Link to="/">
            <img src="/img/Logo.webp" alt="Logo" className="logo" />
          </Link>
        </div>

      </div>
    </div>
  );
};

export default Register;