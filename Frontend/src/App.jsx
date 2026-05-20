import { BrowserRouter, Routes, Route } from "react-router-dom";

import { AuthProvider } from "./context/AuthContext";

import MainLayout from "./layouts/MainLayout";
import AuthLayout from "./layouts/AuthLayout";
import ProtectedRoute from "./components/ProtectedRoute/ProtectedRoute";

import Home from "./views/Home/Home";
import Services from "./views/Services/Services";
import Register from "./views/Auth/Register";
import Login from "./views/Auth/Login";
import GoogleSuccess from "./views/Auth/GoogleSuccess";
import About from "./views/About/About";
import Contact from "./views/Contact/Contact";
import Reserva from "./views/Reserva/Reserva";
import Blog from "./views/Blog/Blog";
import Citas from "./views/Citas/Citas";
import AdminDashboard from "./views/AdminDashboard/AdminDashboard";
import VerPerfil from "./views/VerPerfil/VerPerfil";

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          {/* RUTAS CON HEADER Y FOOTER */}
          <Route element={<MainLayout />}>
            <Route path="/" element={<Home />} />
            <Route path="/servicios" element={<Services />} />
            <Route path="/sobre-nosotros" element={<About />} />
            <Route path="/contacto" element={<Contact />} />
            <Route path="/blog" element={<Blog />} />

            <Route
              path="/citas"
              element={
                <ProtectedRoute>
                  <Citas />
                </ProtectedRoute>
              }
            />

            <Route
              path="/reserva"
              element={
                <ProtectedRoute>
                  <Reserva />
                </ProtectedRoute>
              }
            />
          </Route>

          {/* RUTAS SIN HEADER NI FOOTER */}
          <Route element={<AuthLayout />}>
            <Route path="/registro" element={<Register />} />
            <Route path="/login" element={<Login />} />

            {/* CALLBACK GOOGLE */}
            <Route path="/login/success" element={<GoogleSuccess />} />

            {/* VER PERFIL */}
            <Route
              path="/perfil"
              element={
                <ProtectedRoute>
                  <VerPerfil />
                </ProtectedRoute>
              }
            />

            {/* ADMIN DASHBOARD */}
            <Route
              path="/admin/dashboard"
              element={
                <ProtectedRoute>
                  <AdminDashboard />
                </ProtectedRoute>
              }
            />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;