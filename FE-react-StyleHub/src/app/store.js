import { configureStore } from "@reduxjs/toolkit";
import cartSlice, { cartMiddleware } from "./features/cart/cartSlice";
import forgotPasswordReducer from "../components/ForgotPassword/forgotPasswordSlice";

export const store = configureStore({
  reducer: {
    cart: cartSlice,
    forgotPassword: forgotPasswordReducer,

  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware().concat(cartMiddleware),
});
