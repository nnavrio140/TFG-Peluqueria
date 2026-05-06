import "./Contact.css";

function Contact() {
  return (
    <div className="contact">

      {/* HEADER */}
      <div className="section__header">
        <h1 className="section__title">CONTACTANOS</h1>
      </div>

      {/* MAPA ARRIBA */}
      <div className="contact__mapTop">

        <iframe
          title="Jbarber location"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3194.415560084933!2d-4.218478738448671!3d36.80856076711234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd724343c8f94829%3A0xb9e3aefd43513f57!2zMjk3MTggQWxtw6FjaGFyLCBNw6FsYWdh!5e0!3m2!1ses!2ses!4v1778088211109!5m2!1ses!2ses"
          allowFullScreen=""
          loading="lazy"
          referrerPolicy="no-referrer-when-downgrade"
        ></iframe>

      </div>

      {/* 2 COLUMNAS ABAJO */}
      <div className="contact__main">

        {/* IZQUIERDA */}
        <div className="contact__info">

          <img
            src="/img/local.webp"
            alt="Jbarber"
            className="contact__image"
          />

        </div>

        {/* DERECHA */}
        <div className="contact__form">
          <form className="form">

            <div className="form__row">
              <input type="text" placeholder="Nombre" />
              <input type="email" placeholder="Email" />
            </div>

            <input type="text" placeholder="Asunto" />

            <textarea placeholder="Mensaje"></textarea>

            <button type="submit">CONTACTANOS</button>

          </form>
        </div>

      </div>

    </div>
  );
}

export default Contact;