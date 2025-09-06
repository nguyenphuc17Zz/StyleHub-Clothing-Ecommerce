import { API_URL } from "./apiUrl";
import axios from "axios";

export const categoryApi = {
    getAllCategory: getAllCategory,
}

async function getAllCategory() {
    try {
        const res = await axios.get(`${API_URL}/categories`);
        return res.data;
    } catch (err) {
        console.error("Không thể lấy categories", err);
        throw err;
    }
}
