import { useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

export default function GoogleSuccess() {
  const { setToken, setUser } = useContext(AuthContext);
  const navigate = useNavigate();

  useEffect(() => {
    const completeGoogleLogin = async () => {
      try {
        // Llamamos al endpoint del backend que devuelve JSON con token y usuario
        const response = await fetch("http://localhost:8080/api/auth/google/callback", {
          credentials: "include", // si el backend usa cookies
        });

        if (!response.ok) throw new Error("No se pudo iniciar sesión");

        const data = await response.json();

        if (!data.token) {
          return navigate("/login");
        }

        // Guardar token en localStorage y en AuthContext
        localStorage.setItem("token", data.token);
        setToken(data.token);

        // Guardar usuario en AuthContext
        setUser(data.user || null);

        // Redirigir al home
        navigate("/");
      } catch (error) {
        console.error(error);
        navigate("/login");
      }
    };

    completeGoogleLogin();
  }, []);

  return <p>Iniciando sesión con Google...</p>;
}