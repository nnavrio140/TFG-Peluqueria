import { BrowserRouter, Routes, Route } from "react-router-dom";

import Header from "./components/Header/Header";
import Footer from "./components/Footer/Footer";

import Home from "./views/Home/Home";
import Services from "./views/Services/Services";
import Register from "./views/Register/Register";

function App() {
  return (
    <BrowserRouter>

      <Header />

      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/servicios" element={<Services />} />
        <Route path="/registro" element={<Register />} />
      </Routes>

      <Footer />

    </BrowserRouter>
  );
}

export default App;