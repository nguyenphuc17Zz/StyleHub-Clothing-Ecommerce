export function setTokenLocalStorage(token) {
    localStorage.setItem("token", token);
}
export function setTokenSession(token) {
    localStorage.setItem("token", token);
}
export function redirectToLogin() {
    window.location.href = "http://localhost:3000/login";
}
export function getTokenLocalStorage() {
    return localStorage.getItem("token");
}
export function getTokenSessionStorage() {
    return sessionStorage.getItem("token");
}
export function deleteTokenLocalStorage() {
    localStorage.removeItem("token");
}
export function deleteTokenSessionStorage() {
    sessionStorage.removeItem("token");

}
// import axios from "axios";

// const token = localStorage.getItem("token");

// axios.get("http://localhost:8000/api/admin/data", {
//   headers: { Authorization: `Bearer ${token}` }
// })
// .then(res => console.log(res.data))
// .catch(err => {
//   if (err.response?.status === 401) {
//     // Chưa login → redirect về login page
//     localStorage.removeItem("token");
//     window.location.href = "http://localhost:3000/login";
//   } else if (err.response?.status === 403) {
//     alert("Bạn không có quyền truy cập");
//   }
// });
