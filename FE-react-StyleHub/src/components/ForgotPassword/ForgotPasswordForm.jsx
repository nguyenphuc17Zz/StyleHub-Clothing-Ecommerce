import React, { useState } from "react";
import "./ForgotPasswordForm.css";
import { Link } from "react-router-dom";
import { auth } from "../../api/authApi";
import { useNavigate } from "react-router-dom";
import { useDispatch } from "react-redux";
import { setEmailForConfirm } from "./forgotPasswordSlice";

const ForgotPasswordForm = () => {
  const [email, setEmail] = useState("");
  const [data, setData] = useState({ message: "", success: false });
  const navigate = useNavigate();
  const dispatch = useDispatch();

  const handleChange = (e) => {
    setEmail(e.target.value);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setData({ message: "", success: false });
    try {
      const res = await auth.forgot({ email });
      setData({
        message: res.data.message || "Vui lòng kiểm tra email của bạn",
        success: res.data.success || true,
      });
      if (!res.data.success) {
        return;
      } else {
        dispatch(setEmailForConfirm(email));
        navigate("/confirm-token");
      }
    } catch (err) {
      setData({
        message: err.response?.data?.message || "Yêu cầu thất bại",
        success: err.response?.data?.success || false,
      });
    }
  };

  return (
    <div className="forgot-container">
      <div className="forgot-box">
        <h2>Quên Mật Khẩu</h2>
        <p>Nhập email của bạn để nhận link đặt lại mật khẩu</p>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Email</label>
            <input
              type="email"
              value={email}
              onChange={handleChange}
              placeholder="Nhập email của bạn"
              required
            />
          </div>
          {data.message && (
            <p className={data.success ? "success" : "error"}>{data.message}</p>
          )}
          <button type="submit" className="btn-confirm">
            Xác Nhận
          </button>
        </form>
        <p className="back-login">
          <a href="/login">Trở về trang đăng nhập</a>
        </p>
      </div>
    </div>
  );
};

export default ForgotPasswordForm;
