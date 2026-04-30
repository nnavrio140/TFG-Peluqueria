import React, { useState, useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import "./Auth.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGoogle } from "@fortawesome/free-brands-svg-icons";

import { AuthContext } from "../../context/AuthContext";

const Login = () => {
  const { login, loginWithGoogle } = useContext(AuthContext);

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    if (!email || !password) {
      setError("Por favor completa todos los campos");
      return;
    }

    setLoading(true);

    try {
      await login(email, password);

      setEmail("");
      setPassword("");

      navigate("/"); // redirigir al dashboard o home
    } catch (err) {
      setError(err.message || "Error en el login");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="register-container">

      <div className="section__header">
        <h1 className="section__title">INICIA SESIÓN</h1>
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

              <button
                className="create-btn"
                type="submit"
                disabled={loading}
              >
                {loading ? "Iniciando sesión..." : "Iniciar sesión"}
              </button>

            </form>

            <div className="actions">
              <p className="login-text">¿No tienes cuenta?</p>

              <Link to="/registro">
                <button className="login-btn" type="button">
                  Crear cuenta
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

export default Login;