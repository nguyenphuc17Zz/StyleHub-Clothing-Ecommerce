import { API_URL } from "./apiUrl";
import axios from "axios";
import { getTokenLocalStorage, getTokenSessionStorage } from "../helper/helper";
export const orderApi = {
    checkOut: checkOut,
    getAllOrders: getAllOrders,
    getItems: getItems,
}


async function checkOut(data) {
    try {

        const token = getTokenLocalStorage() || getTokenSessionStorage();
        if (!token) {
            window.location.href = "/login";
        }
        const res = await axios.post(
            `${API_URL}/order/add`,
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
        console.error("Không thể checkout ", err);
        throw err;
    }

}
async function getAllOrders() {
    try {

        const token = getTokenLocalStorage() || getTokenSessionStorage();
        if (!token) {
            window.location.href = "/login";
        }
        const res = await axios.get(
            `${API_URL}/order`,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json"
                }
            }
        );
        return res.data;
    } catch (err) {
        console.error("Không thể get orders", err);
        throw err;
    }
}
async function getItems(id) {
    try {

        const token = getTokenLocalStorage() || getTokenSessionStorage();
        if (!token) {
            window.location.href = "/login";
        }
        const res = await axios.get(
            `${API_URL}/order/${id}`,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json"
                }
            }
        );
        return res.data;
    } catch (err) {
        console.error("Không thể get items", err);
        throw err;
    }
}


