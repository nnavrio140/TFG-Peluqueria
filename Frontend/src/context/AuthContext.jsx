import { createContext, useState, useEffect } from "react";
import { authService } from "../services/authService";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem("token"));
  const [loading, setLoading] = useState(true);

  // 🔹 Cargar usuario al iniciar la app
  useEffect(() => {
    const loadUser = async () => {
      if (!token) {
        setLoading(false);
        return;
      }

      try {
        const data = await authService.getMe(token);
        setUser(data);
      } catch {
        localStorage.removeItem("token");
        setToken(null);
        setUser(null);
      } finally {
        setLoading(false);
      }
    };

    loadUser();
  }, [token]);

  // 🔹 LOGIN CON EMAIL/PASSWORD
  const login = async (email, password) => {
    const data = await authService.login(email, password);

    if (!data.token) throw new Error("Error al iniciar sesión");

    localStorage.setItem("token", data.token);
    setToken(data.token);

    const me = await authService.getMe(data.token);
    setUser(me);
  };

  // 🔹 REGISTER CON EMAIL/PASSWORD
  const register = async (nombre, email, password, confirm) => {
    const data = await authService.register(nombre, email, password, confirm);

    if (!data.token) throw new Error("Error al registrarse");

    localStorage.setItem("token", data.token);
    setToken(data.token);

    const me = await authService.getMe(data.token);
    setUser(me);
  };

  // 🔹 LOGOUT
  const logout = async () => {
    await authService.logout(); // ahora logout coge token automáticamente
    setUser(null);
    setToken(null);
  };

  // 🔹 LOGIN CON GOOGLE
  const loginWithGoogle = () => {
    authService.googleLogin();
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        setUser,
        setToken,
        login,
        register,
        logout,
        loginWithGoogle,
        loading,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};