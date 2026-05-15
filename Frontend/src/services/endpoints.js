/**
 * API Endpoints Configuration
 * Centralized endpoint management for the TFG-Peluqueria application
 * Use with import.meta.env.VITE_API_BASE_URL as base URL
 */

const API_BASE = import.meta.env.VITE_API_BASE_URL;

/**
 * AUTH Endpoints
 */
export const AUTH_ENDPOINTS = {
  LOGIN: `${API_BASE}/login`,
  REGISTER: `${API_BASE}/register`,
  LOGOUT: `${API_BASE}/logout`,
  ME: `${API_BASE}/me`,
  GOOGLE_LOGIN: `${API_BASE}/auth/google`,
  GOOGLE_CALLBACK: `${API_BASE}/auth/google/callback`,
};

/**
 * SERVICIOS (Services) Endpoints
 */
export const SERVICIOS_ENDPOINTS = {
  INDEX: `${API_BASE}/servicios`,
  STORE: `${API_BASE}/servicios`,
  SHOW: (id) => `${API_BASE}/servicios/${id}`,
  UPDATE: (id) => `${API_BASE}/servicios/${id}`,
  DELETE: (id) => `${API_BASE}/servicios/${id}`,
};

/**
 * EMPLEADOS (Barbers) Endpoints
 */
export const EMPLEADOS_ENDPOINTS = {
  INDEX: `${API_BASE}/empleados`,
  SHOW: (id) => `${API_BASE}/empleados/${id}`,
  HORARIOS: (id) => `${API_BASE}/empleados/${id}/horarios`,
};

/**
 * CITAS (Appointments) Endpoints
 */
export const CITAS_ENDPOINTS = {
  INDEX: `${API_BASE}/citas`,
  STORE: `${API_BASE}/citas`,
  SHOW: (id) => `${API_BASE}/citas/${id}`,
  UPDATE: (id) => `${API_BASE}/citas/${id}`,
  DELETE: (id) => `${API_BASE}/citas/${id}`,
};

/**
 * DISPONIBILIDAD (Availability) Endpoints
 */
export const DISPONIBILIDAD_ENDPOINTS = {
  GET_DISPONIBILIDAD: `${API_BASE}/disponibilidad`,
  DIAS_DISPONIBLES: `${API_BASE}/dias-disponibles`,
};

/**
 * CONTACTO (Contact) Endpoints
 */
export const CONTACTO_ENDPOINTS = {
  STORE: `${API_BASE}/contact`,
};

/**
 * Convenience export for all endpoints
 */
export const ENDPOINTS = {
  AUTH: AUTH_ENDPOINTS,
  SERVICIOS: SERVICIOS_ENDPOINTS,
  EMPLEADOS: EMPLEADOS_ENDPOINTS,
  CITAS: CITAS_ENDPOINTS,
  DISPONIBILIDAD: DISPONIBILIDAD_ENDPOINTS,
  CONTACTO: CONTACTO_ENDPOINTS,
};

export default ENDPOINTS;
