import { useEffect, useContext } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";
import { authService } from "../../services/authService";

export default function GoogleSuccess() {
  const { setToken, setUser } = useContext(AuthContext);
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const completeGoogleLogin = async () => {
      try {
        const params = new URLSearchParams(location.search);
        const token = params.get("token");

        if (!token) {
          return navigate("/login", { replace: true });
        }

        localStorage.setItem("token", token);
        setToken(token);

        const user = await authService.getMe(token);
        setUser(user);

        navigate("/", { replace: true });
      } catch (error) {
        console.error(error);
        navigate("/login", { replace: true });
      }
    };

    completeGoogleLogin();
  }, [location.search, navigate, setToken, setUser]);

  return null;
}