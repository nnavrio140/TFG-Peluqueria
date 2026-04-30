const API_BASE_URL = "http://localhost:8080/api";

export const authService = {
  // 🔹 REGISTRO
  register: async (nombre, email, password, passwordConfirmation) => {
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        nombre,
        email,
        password,
        password_confirmation: passwordConfirmation,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Error en el registro");
    }

    return data;
  },

  // 🔹 LOGIN
  login: async (email, password) => {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Error en el login");
    }

    return data;
  },

  // 🔹 USUARIO ACTUAL
  getMe: async (token) => {
    const response = await fetch(`${API_BASE_URL}/me`, {
      method: "GET",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("No autorizado");
    }

    return await response.json();
  },

  // 🔹 LOGOUT
  logout: async () => {
    const token = localStorage.getItem("token");
    if (!token) return;

    await fetch(`${API_BASE_URL}/logout`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    localStorage.removeItem("token"); // elimina token local
  },

  // 🔹 TOKEN
  getToken: () => localStorage.getItem("token"),

  // 🔵 LOGIN GOOGLE
  googleLogin: () => {
    window.location.href = `${API_BASE_URL}/auth/google`;
  },
};