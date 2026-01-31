import React from "react";
import { Navigate } from "react-router-dom";
import { getUserRole } from "../utils/auth.js";

export default function ProtectedRoute({ children, role }) {
  const userRole = getUserRole();

  // not logged in
  if (!userRole) return <Navigate to="/login" replace />;

  // role mismatch
  if (role && userRole !== role) {
    return <Navigate to={userRole === "admin" ? "/admin-dashboard" : "/customer-dashboard"} replace />;
  }

  return children;
}
