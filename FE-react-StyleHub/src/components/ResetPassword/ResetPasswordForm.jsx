import React, { useState } from "react";
import "./ResetPasswordForm.css";
import { useSelector } from "react-redux";
import { Link, useNavigate } from "react-router-dom";
import { auth } from "../../api/authApi";
import { setEmailForConfirm } from "../ForgotPassword/forgotPasswordSlice";
import { useDispatch } from "react-redux";
import { clearEmail } from "../ForgotPassword/forgotPasswordSlice";

const ResetPasswordForm = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const email = useSelector((state) => state.forgotPassword.email);

  const [form, setForm] = useState({
    password: "",
    confirmPassword: "",
  });
  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };
  const [message, setMessage] = useState("");
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (form.password !== form.confirmPassword) {
      setMessage("Mật khẩu không khớp");
      return;
    }
    try {
      const res = await auth.resetPassword({
        'email': email,
        'password': form.password 
      });
      if (res.data.success) {
        dispatch(clearEmail());
        navigate("/login");
        return;
      } else {
        setMessage("Đặt lại mật khẩu thất bại");
      }
    } catch (err) {
      alert(err.response?.data?.error || "Đặt lại mật khẩu thất bại");
    }
  };

  return (
    <div className="reset-container">
      <div className="reset-box">
        <h2>Đặt Lại Mật Khẩu</h2>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Mật khẩu mới</label>
            <input
              type="password"
              value={form.password}
              onChange={handleChange}
              placeholder="Nhập mật khẩu mới"
              name="password"
              required
            />
          </div>
          <div className="input-group">
            <label>Nhập lại mật khẩu</label>
            <input
              type="password"
              value={form.confirmPassword}
              onChange={handleChange}
              placeholder="Nhập lại mật khẩu"
              required
              name="confirmPassword"
            />
          </div>
          {message && <p className="error-message">{message}</p>}
          <div className="button-group">
            <button type="submit" className="btn-confirm">
              Xác Nhận
            </button>
            <button
              type="button"
              className="btn-cancel"
              onClick={() => navigate("/login")}
            >
              Hủy
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ResetPasswordForm;
