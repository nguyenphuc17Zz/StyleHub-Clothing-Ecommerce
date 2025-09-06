import React, { useState } from "react";
import "./RegisterForm.css";
import { Link } from "react-router-dom";
import { auth } from "../../api/authApi";
import { useNavigate } from "react-router-dom";
const RegisterForm = () => {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    confirmpassword: "",
  });
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (form.password !== form.confirmpassword) {
      setError("Mật khẩu không khớp");
      return;
    }
    try {
      const res = await auth.register(form);
      navigate("/login");
    } catch (err) {
      setError(err.response?.data?.message || "Đăng kí thất bại");
    }
  };

  return (
    <div className="register-container">
      <div className="register-box">
        <h2>Đăng Kí</h2>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Họ và tên</label>
            <input
              type="text"
              name="name"
              value={form.name}
              onChange={handleChange}
              placeholder="Nhập họ và tên"
              required
            />
          </div>
          <div className="input-group">
            <label>Email</label>
            <input
              type="email"
              name="email"
              value={form.email}
              onChange={handleChange}
              placeholder="Nhập email"
              required
            />
          </div>
          <div className="input-group">
            <label>Mật khẩu</label>
            <input
              type="password"
              name="password"
              value={form.password}
              onChange={handleChange}
              placeholder="Nhập mật khẩu"
              required
            />
          </div>
          <div className="input-group">
            <label>Nhập lại mật khẩu</label>
            <input
              type="password"
              name="confirmpassword"
              value={form.confirmpassword}
              onChange={handleChange}
              placeholder="Nhập lại mật khẩu"
              required
            />
          </div>
          <button type="submit" className="btn-register">
            Đăng Kí
          </button>
        </form>
        {error && <p className="error-text">{error}</p>}
        <p className="login-text">
          Đã có tài khoản? <Link to="/login">Đăng nhập</Link>
        </p>
      </div>
    </div>
  );
};

export default RegisterForm;
