import { BrowserRouter, Routes, Route } from "react-router-dom";

import MainLayout from "./layouts/MainLayout";
import AuthLayout from "./layouts/AuthLayout";

import Home from "./views/Home/Home";
import Services from "./views/Services/Services";
import Register from "./views/Auth/Register";
import Login from "./views/Auth/Login";

function App() {
  return (
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
        </Route>

      </Routes>
    </BrowserRouter>
  );
}

export default App;