import React, { useState, useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import "./Auth.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGoogle } from "@fortawesome/free-brands-svg-icons";

import { AuthContext } from "../../context/AuthContext";

const Register = () => {
  const { register, loginWithGoogle } = useContext(AuthContext);

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

    // 🔹 VALIDACIONES
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
      await register(nombre, email, password, passwordConfirm);

      // limpiar form
      setNombre("");
      setEmail("");
      setPassword("");
      setPasswordConfirm("");

      navigate("/"); // redirigir al dashboard
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

            {/* 🔵 BOTÓN GOOGLE */}
            <button
              className="google-btn"
              type="button"
              onClick={loginWithGoogle}
            >
              <FontAwesomeIcon icon={faGoogle} />
              <span>Iniciar sesión con Google</span>
            </button>

            {error && (
              <div style={{ color: "red", marginBottom: "10px" }}>
                {error}
              </div>
            )}

            <form className="form" onSubmit={handleSubmit}>

              <label>Nombre de usuario</label>
              <input
                type="text"
                value={nombre}
                onChange={(e) => setNombre(e.target.value)}
              />

              <label>Email</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />

              <label>Contraseña</label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />

              <label>Confirmar contraseña</label>
              <input
                type="password"
                value={passwordConfirm}
                onChange={(e) => setPasswordConfirm(e.target.value)}
              />

              <button
                className="create-btn"
                type="submit"
                disabled={loading}
              >
                {loading ? "Creando cuenta..." : "Crear cuenta"}
              </button>

            </form>

            <div className="actions">
              <p className="login-text">¿Ya tienes cuenta?</p>

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