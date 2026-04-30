const API_BASE_URL = 'http://localhost:8080/api';

export const authService = {
  // Registro de nuevo usuario
  register: async (nombre, email, password, passwordConfirmation) => {
    try {
      const response = await fetch(`${API_BASE_URL}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nombre,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error en el registro');
      }

      // Guardar el token en localStorage
      localStorage.setItem('token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));

      return data;
    } catch (error) {
      console.error('Error en registro:', error);
      throw error;
    }
  },

  // Login de usuario
  login: async (email, password) => {
    try {
      const response = await fetch(`${API_BASE_URL}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email,
          password,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error en el login');
      }

      // Guardar el token en localStorage
      localStorage.setItem('token', data.token);

      return data;
    } catch (error) {
      console.error('Error en login:', error);
      throw error;
    }
  },

  // Obtener datos del usuario autenticado
  getMe: async (token) => {
    try {
      const response = await fetch(`${API_BASE_URL}/me`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('No autorizado');
      }

      return await response.json();
    } catch (error) {
      console.error('Error obteniendo datos del usuario:', error);
      throw error;
    }
  },

  // Logout
  logout: async (token) => {
    try {
      await fetch(`${API_BASE_URL}/logout`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      // Limpiar localStorage
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    } catch (error) {
      console.error('Error en logout:', error);
      throw error;
    }
  },

  // Obtener token del localStorage
  getToken: () => {
    return localStorage.getItem('token');
  },

  // Verificar si el usuario está autenticado
  isAuthenticated: () => {
    return !!localStorage.getItem('token');
  },
};
