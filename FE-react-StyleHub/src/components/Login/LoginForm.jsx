import React, { useState } from "react";
import "./LoginForm.css";
import { FaGoogle } from "react-icons/fa";
import { Link, useNavigate } from "react-router-dom";
import { auth } from "../../api/authApi";
import { GoogleLogin } from "@react-oauth/google";
import { jwtDecode } from "jwt-decode";
import {
  setTokenLocalStorage,
  getTokenLocalStorage,
} from "../../helper/helper";
import { setTokenSession } from "../../helper/helper";

const LoginForm = () => {
  const authorization = (role) => {
    role = role.trim();
    if (role === "user") {
      window.location.href = "http://localhost:3000";
    } else {
      return;
    }
  };

  const [form, setForm] = useState({
    email: "",
    password: "",
    remember: false,
  });
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, type, value, checked } = e.target;
    setForm({
      ...form,
      [name]: type === "checkbox" ? checked : value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await auth.login(form);

      const { token, remember } = res.data;
      if (remember) {
        localStorage.setItem("token", token);
      } else {
        sessionStorage.setItem("token", token);
      }
      const decoded = jwtDecode(token);
      console.log(decoded);
      console.log(decoded.role);
      authorization(decoded.role);
    } catch (err) {
      setError(err.response?.data?.error || "Đăng nhập thất bại");
    }
  };

  const handleGoogleSuccess = async (credentialResponse) => {
    const decoded = jwtDecode(credentialResponse.credential);
    console.log(decoded.name);
    console.log(decoded.email);
    console.log("User info:", decoded);
    try {
      const res = await auth.loginGoogle({
        email: decoded.email,
        name: decoded.name,
      });
      if (res.data.success) {
        console.log(res.data.token);
        setTokenSession(res.data.token);
        const tokenDecoded = jwtDecode(res.data.token);
        authorization(tokenDecoded.role);
      } else {
        setMessage("Đăng nhập Google thất bại");
      }
    } catch (err) {
      setMessage("Có lỗi xảy ra khi đăng nhập bằng Google");
    }
  };

  const handleGoogleError = () => {
    setMessage("Đăng nhập thất bại");
  };
  return (
    <div className="login-container">
      <div className="login-box">
        <h2>Đăng Nhập</h2>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Email</label>
            <input
              type="email"
              value={form.email}
              onChange={handleChange}
              placeholder="Nhập email"
              required
              name="email"
            />
          </div>
          <div className="input-group">
            <label>Mật khẩu</label>
            <input
              type="password"
              value={form.password}
              onChange={handleChange}
              name="password"
              placeholder="Nhập mật khẩu"
              required
            />
          </div>
          <div className="options">
            <label>
              <input
                type="checkbox"
                name="remember"
                value={form.remember}
                onChange={handleChange}
                checked={form.remember}
              />
              Nhớ mật khẩu
            </label>
            <p>
              <Link to="/forgot-password">Quên mật khẩu?</Link>
            </p>
          </div>
          {error && <p className="error">{error}</p>}
          {message && <p className="error-message">{message}</p>}
          <button type="submit" className="btn-login">
            Đăng nhập
          </button>
          {/* <button type="button" className="btn-google">
            Đăng nhập bằng Google
          </button> */}
          <GoogleLogin
            onSuccess={handleGoogleSuccess}
            onError={handleGoogleError}
            shape="pill"
            theme="filled_blue"
            size="large"
            text="signin_with"
          />
        </form>
        <p className="register-text">
          Bạn chưa có tài khoản? <Link to="/register">Đăng ký</Link>
        </p>
      </div>
    </div>
  );
};

export default LoginForm;
