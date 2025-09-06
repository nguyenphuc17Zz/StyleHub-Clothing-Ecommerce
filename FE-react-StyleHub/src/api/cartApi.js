import { API_URL } from "./apiUrl";
import axios from "axios";
import { getTokenLocalStorage, getTokenSessionStorage } from "../helper/helper";
export const cartApi = {
    createCart: createCart,
    updateAddItemCard: updateAddItemCard,
    deleteItem: deleteItem,
}

async function createCart() {
    try {
        const token =
            getTokenLocalStorage?.() || getTokenSessionStorage?.();

        const res = await axios.get(`${API_URL}/cart`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        return res.data;
    } catch (err) {
        console.error("Không thể lấy cart", err);
        throw err;
    }
}
async function updateAddItemCard(data) {
    try {

        const token = getTokenLocalStorage() || getTokenSessionStorage();
        if (!token) {
            window.location.href = "/login";
        }
        const res = await axios.post(
            `${API_URL}/cart/add`,
            data,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json"
                }
            }
        );

        return res.data;
    } catch (err) {
        console.error("Không thể add item vô cart", err);
        throw err;
    }
}
async function deleteItem(data) {
    try {
        const token = getTokenLocalStorage() || getTokenSessionStorage();
        if (!token) {
            window.location.href = "/login";
            return;
        }

        const res = await axios.delete(`${API_URL}/cart/delete`, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            data: data, 
        });

        return res.data;
    } catch (err) {
        console.error("Không thể delete item cart", err);
        throw err;
    }
}
