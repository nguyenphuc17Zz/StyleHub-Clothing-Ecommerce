import { API_URL } from "./apiUrl";
import axios from "axios";

export const productApi = {
    getLatest: getLatest,
    productDetail: productDetail,
    getAllProduct: getAllProduct,
    productSearchSuggestion: productSearchSuggestion,
    sizeSuggestion: sizeSuggestion,
};


async function getLatest() {
    try {
        const res = await axios.get(`${API_URL}/products/latest`);
        console.log(res.data);
        return res.data;
    } catch (err) {
        console.error("Không thể lấy products latest", err);
        throw err;
    }
}
async function productDetail(id) {
    try {
        const res = await axios.get(`${API_URL}/products/${id}`);
        console.log(res.data);
        return res.data;
    } catch (err) {
        console.error("Không thể lấy products latest", err);
        throw err;
    }
}
async function getAllProduct(params) {
    try {
        const res = await axios.get(`${API_URL}/products`, {
            params: {
                category_id: params.category_id,
                search: params.search,
                page: params.page,
            },
        });
        console.log(res.data);
        return res.data;
    } catch (err) {
        console.error("Không thể lấy all products", err);
        throw err;
    }
}
async function productSearchSuggestion(keyword) {
    try {
        const res = await axios.get(`${API_URL}/products/search/suggestion?keyword=${keyword}`,);
        console.log(res.data);
        return res.data;
    } catch (err) {
        console.error("Không thể thực hiện search suggestion", err);
        throw err;
    }
}
async function sizeSuggestion(weight, age, height) {
    try {
        const res = await axios.get(
            `${API_URL}/size/suggest?weight=${weight}&age=${age}&height=${height}`
        );
        console.log(res.data);
        return res.data;
    } catch (err) {
        console.error("Không thể thực hiện size suggestion", err);
        throw err;
    }
}
