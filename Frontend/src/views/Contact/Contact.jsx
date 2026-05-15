import "./Contact.css";
import { useState } from "react";
import axios from "axios";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { CONTACT_ENDPOINT } from "../../services/endpoints";

function Contact() {

  // datos del formulario
  const [formData, setFormData] = useState({
    nombre: "",
    email: "",
    asunto: "",
    mensaje: "",
  });

  // loading botón
  const [loading, setLoading] = useState(false);

  // cambiar inputs
  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  // enviar form
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      setLoading(true);

      const res = await axios.post(
        CONTACT_ENDPOINT,
        formData,
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      toast.success(res.data.message || "Enviado ✔");

      setFormData({
        nombre: "",
        email: "",
        asunto: "",
        mensaje: "",
      });

    } catch (error) {
      toast.error(
        error.response?.data?.message || "Error al enviar"
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="contact">

      <div className="section__header">
        <h1 className="section__title">CONTACTANOS</h1>
      </div>

      <div className="contact__mapTop">
        <iframe
          title="Jbarber location"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3194.415560084933!2d-4.218478738448671!3d36.80856076711234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd724343c8f94829%3A0xb9e3aefd43513f57!2zMjk3MTggQWxtw6FjaGFyLCBNw6FsYWdh!5e0!3m2!1ses!2ses"
          loading="lazy"
        ></iframe>
      </div>

      <div className="contact__main">

        <div className="contact__info">
          <img src="/img/local.webp" alt="Jbarber" className="contact__image" />
        </div>

        <div className="contact__form">

          <form className="form" onSubmit={handleSubmit}>

            <div className="form__row">

              <input
                type="text"
                name="nombre"
                placeholder="Nombre"
                value={formData.nombre}
                onChange={handleChange}
                required
              />

              <input
                type="email"
                name="email"
                placeholder="Email"
                value={formData.email}
                onChange={handleChange}
                required
              />

            </div>

            <input
              type="text"
              name="asunto"
              placeholder="Asunto"
              value={formData.asunto}
              onChange={handleChange}
            />

            <textarea
              name="mensaje"
              placeholder="Mensaje"
              value={formData.mensaje}
              onChange={handleChange}
              required
            />

            <button type="submit" disabled={loading}>
              {loading ? "ENVIANDO..." : "CONTACTANOS"}
            </button>

          </form>

        </div>
      </div>

      <ToastContainer
        position="top-center"
        autoClose={2500}
        hideProgressBar={false}
        closeButton={false}
        pauseOnHover={true}
        draggable={false}
      />
    </div>
  );
}

export default Contact;