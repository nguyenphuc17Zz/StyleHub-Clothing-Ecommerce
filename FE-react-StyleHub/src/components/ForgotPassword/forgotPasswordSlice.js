import { createSlice } from "@reduxjs/toolkit";
const initialState = {
    email: "",
};
const forgotPasswordSlice = createSlice({
    name: "forgotPassword",
    initialState,
    reducers: {
        setEmailForConfirm: (state, action) => {
            state.email = action.payload;
        },
        clearEmail: (state) => {
            state.email = "";
        },
    },
});

export const { setEmailForConfirm, clearEmail } = forgotPasswordSlice.actions;
export default forgotPasswordSlice.reducer;
