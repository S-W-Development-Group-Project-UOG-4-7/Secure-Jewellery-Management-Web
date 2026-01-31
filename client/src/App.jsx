import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider } from "./contexts/AuthContext";
import ProtectedRoute from "./components/ProtectedRoute.jsx";
import { Toaster } from "react-hot-toast";

// Pages
import Landing from "./pages/Landing.jsx";
import Login from "./pages/Login.jsx";
import Register from "./pages/Register.jsx";
import Otp from "./pages/Otp.jsx";
import CustomerDashboard from "./pages/CustomerDashboard.jsx";
import AIStudio from "./pages/AIStudio.jsx";

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Toaster position="top-center" />
        <Routes>
          <Route path="/" element={<Landing />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/otp" element={<Otp />} />

          {/* ✅ FIXED ROUTE: Matches navigate("/customer-dashboard") */}
          <Route
            path="/customer-dashboard"
            element={
              <ProtectedRoute allowedRoles={["customer", "admin"]}>
                <CustomerDashboard />
              </ProtectedRoute>
            }
          />

          <Route
            path="/ai-studio"
            element={
              <ProtectedRoute allowedRoles={["customer", "admin"]}>
                <AIStudio />
              </ProtectedRoute>
            }
          />

          <Route path="*" element={<Navigate to="/" />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}