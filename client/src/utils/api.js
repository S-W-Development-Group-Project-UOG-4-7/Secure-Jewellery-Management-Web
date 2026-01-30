import axios from "axios";

// Create Axios instance
// Using 127.0.0.1 avoids DNS resolution issues common with 'localhost'
const API = axios.create({
  baseURL: "http://127.0.0.1:5001/api", 
  withCredentials: false,
});

// ✅ Attach token automatically
API.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// ✅ Handle errors globally
API.interceptors.response.use(
  (res) => res,
  (err) => {
    // Log connection errors specifically
    if (err.message === "Network Error" || err.code === "ERR_NETWORK") {
      console.error("🚨 Backend Server Unreachable! Is it running on port 5001?");
    }

    if (err?.response?.status === 401) {
      // Optional: Logic to redirect to login if token expires
      // localStorage.removeItem("token");
      // window.location.href = "/login";
    }
    return Promise.reject(err);
  }
);

export default API;