import { API_URL } from "./apiUrl";
import axios from "axios";

export const userApi = {
    getUserProfile: fetchUserProfile,
    updateProfile: updateUserProfile,
};

async function fetchUserProfile() {
    const token =
        localStorage.getItem("token") || sessionStorage.getItem("token");

    try {
        const res = await fetch(`${API_URL}/user/profile`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
            },
        });

        if (!res.ok) throw new Error("Không thể lấy thông tin user");

        const data = await res.json();
        return data;
    } catch (err) {
        console.error(err);
        throw err;
    }
}

async function updateUserProfile(payload) {
    const token = localStorage.getItem("token") || sessionStorage.getItem("token");

    try {
        const res = await axios.put(
            `${API_URL}/user/profile`,
            payload,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            }
        );

        return res.data;
    } catch (err) {
        console.error("Lỗi cập nhật user:", err.response ? err.response.data : err);
        throw err;
    }
}
