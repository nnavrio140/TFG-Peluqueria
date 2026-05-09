const API_BASE_URL = "http://localhost:8080/api";

const parseJsonResponse = async (response) => {
  const text = await response.text();
  const contentType = response.headers.get("content-type") || "";

  if (contentType.includes("application/json")) {
    return JSON.parse(text);
  }

  throw new Error(
    `Respuesta inesperada del servidor (${response.status} ${response.statusText}, content-type: ${contentType}): ${text.slice(0, 200)}`
  );
};

export const authService = {
  // 🔹 REGISTRO
  register: async (nombre, email, password, passwordConfirmation) => {
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        nombre,
        email,
        password,
        password_confirmation: passwordConfirmation,
      }),
    });

    const data = await parseJsonResponse(response);

    if (!response.ok) {
      throw new Error(data.message || "Error en el registro");
    }

    return data;
  },

  // 🔹 LOGIN
  login: async (email, password) => {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({ email, password }),
    });

    const data = await parseJsonResponse(response);

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
        Accept: "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("No autorizado");
    }

    return await parseJsonResponse(response);
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
        Accept: "application/json",
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