import React from "react";
import { Link } from "react-router-dom";
import "./Register.css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGoogle } from "@fortawesome/free-brands-svg-icons";

const Register = () => {
  return (
    <div className="register-container">

      <div className="section__header">
        <h1 className="section__title">CREAR CUENTA</h1>
      </div>

      <div className="register-box">

        {/* FORM */}
        <div className="form-section">
          <div className="form-wrapper">

            <button className="google-btn">
              <FontAwesomeIcon icon={faGoogle} />
              <span>Iniciar sesión con Google</span>
            </button>

            <form className="form">

              <label>Nombre de usuario</label>
              <input type="text" />

              <label>Email</label>
              <input type="email" />

              <label>Contraseña</label>
              <input type="password" />

              <label>Confirmar contraseña</label>
              <input type="password" />

            </form>

            {/* ACCIONES */}
            <div className="actions">

              <button className="create-btn">
                Crear cuenta
              </button>

              <p className="login-text">
                ¿Ya tienes cuenta?
              </p>

              <button className="login-btn">
                Iniciar sesión
              </button>

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