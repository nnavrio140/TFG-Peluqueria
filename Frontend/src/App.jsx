import { BrowserRouter, Routes, Route } from "react-router-dom";

import { AuthProvider } from "./context/AuthContext";

import MainLayout from "./layouts/MainLayout";
import AuthLayout from "./layouts/AuthLayout";

import Home from "./views/Home/Home";
import Services from "./views/Services/Services";
import Register from "./views/Auth/Register";
import Login from "./views/Auth/Login";
import GoogleSuccess from "./views/Auth/GoogleSuccess"; 

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>

          {/* RUTAS CON HEADER */}
          <Route element={<MainLayout />}>
            <Route path="/" element={<Home />} />
            <Route path="/servicios" element={<Services />} />
          </Route>

          {/* RUTAS SIN HEADER */}
          <Route element={<AuthLayout />}>
            <Route path="/registro" element={<Register />} />
            <Route path="/login" element={<Login />} />

            {/* 🔵 CALLBACK GOOGLE */}
            <Route path="/login/success" element={<GoogleSuccess />} />
          </Route>

        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;