import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { userApi } from "../../api/userApi";
import "./UserProfileForm.css"; // import css

export default function UserProfileForm() {
  const [userData, setUserData] = useState(null);
  const [formData, setFormData] = useState({
    name: "",
    password: "",
    confirmPassword: "",
  });
  const navigate = useNavigate();

  useEffect(() => {
    const token =
      localStorage.getItem("token") || sessionStorage.getItem("token");

    if (!token) {
      navigate("/login");
      return;
    }

    async function loadUser() {
      try {
        const data = await userApi.getUserProfile();
        setUserData(data);
        setFormData((prev) => ({
          ...prev,
          name: data.name || "",
        }));
      } catch (err) {
        console.error("Lỗi lấy user:", err);
        navigate("/login");
      }
    }

    loadUser();
  }, [navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (formData.password !== formData.confirmPassword) {
      alert("Mật khẩu nhập lại không khớp!");
      return;
    }

    try {
      const res = await userApi.updateProfile({
        name: formData.name,
        password: formData.password,
      });
      if (res.success) {
        alert("Cập nhật thành công!");
      } else {
        alert("Cập nhật thất bại");
        return;
      }

      setUserData(res.user);
    } catch (err) {
      console.error("Lỗi cập nhật user:", err);
      alert("Cập nhật thất bại!");
    }
  };

  if (!userData) return <p>Đang tải...</p>;

  return (
    <div className="profile-container">
      <h2 className="profile-title">User Profile</h2>
      <form onSubmit={handleSubmit} className="profile-form">
        <div className="form-group">
          <label>Tên</label>
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>Mật khẩu mới</label>
          <input
            type="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            placeholder="Nhập mật khẩu mới"
          />
        </div>

        <div className="form-group">
          <label>Xác nhận mật khẩu</label>
          <input
            type="password"
            name="confirmPassword"
            value={formData.confirmPassword}
            onChange={handleChange}
            placeholder="Nhập lại mật khẩu"
          />
        </div>

        <button type="submit" className="btn-submit">
          Lưu thay đổi
        </button>
      </form>
    </div>
  );
}
