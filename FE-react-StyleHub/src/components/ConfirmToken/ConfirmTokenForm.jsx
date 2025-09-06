import React, { useState } from "react";
import "./ConfirmTokenForm.css";
import { useSelector } from "react-redux";
import { Link, useNavigate } from "react-router-dom";
import { auth } from "../../api/authApi";
import { setEmailForConfirm } from "../ForgotPassword/forgotPasswordSlice";
import { useDispatch } from "react-redux";

const ConfirmTokenForm = () => {
  const dispatch = useDispatch();
  const [token, setToken] = useState("");
  const email = useSelector((state) => state.forgotPassword.email);
  const [message, setMessage] = useState("");
  const navigate = useNavigate();
  const handleChange = (e) => {
    setToken(e.target.value);
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await auth.confirmToken({ email, token });
      console.log(res);
      if (res.data.success) {
        dispatch(setEmailForConfirm(email));
        navigate("/reset-password");
        return;
      } else {
        setMessage("Token không hợp lệ");
      }
    } catch (err) {
      alert(err.response?.data?.error || "Xác nhận token thất bại");
    }
  };
  return (
    <div className="token-container">
      <div className="token-box">
        <h2>Xác Nhận Token</h2>
        <p>Nhập token bạn nhận được để xác nhận</p>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Token</label>
            <input
              type="text"
              value={token}
              onChange={handleChange}
              placeholder="Nhập token"
              required
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

export default ConfirmTokenForm;
