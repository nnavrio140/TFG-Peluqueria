import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "./Auth.css"; 

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGoogle } from "@fortawesome/free-brands-svg-icons";
import { authService } from "../../services/authService";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      await authService.login(email, password);
      console.log("Login exitoso");
      navigate("/"); // Redirigir a home
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

            <button className="google-btn" type="button">
              <FontAwesomeIcon icon={faGoogle} />
              <span>Iniciar sesión con Google</span>
            </button>

            {error && <div style={{ color: "red", marginBottom: "10px" }}>{error}</div>}

            <form className="form" onSubmit={handleSubmit}>

              <label>Nombre de usuario o email</label>
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

            </form>

            {/* ACCIONES */}
            <div className="actions">

              <button 
                className="create-btn" 
                onClick={handleSubmit}
                disabled={loading}
              >
                {loading ? "Iniciando sesión..." : "Iniciar sesión"}
              </button>

              <p className="login-text">
                ¿No tienes cuenta?
              </p>

              <Link to="/register">
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