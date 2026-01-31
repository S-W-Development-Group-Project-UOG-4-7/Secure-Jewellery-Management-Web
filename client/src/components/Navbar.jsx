import React from "react";
import { Link, useNavigate } from "react-router-dom";

export default function Navbar() {
  const navigate = useNavigate();
  const token = localStorage.getItem("token");

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("userId");
    navigate("/login");
  };

  return (
    <nav className="sticky top-0 z-50 bg-black/80 backdrop-blur-xl border-b border-yellow-400/10">
      <div className="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <Link
          to="/"
          className="text-xl font-bold bg-gradient-to-r from-yellow-400 to-yellow-200 bg-clip-text text-transparent"
        >
          SJM
        </Link>

        <div className="flex items-center gap-4">
          <Link to="/customers" className="text-gray-300 hover:text-yellow-400 transition">
            Customers
          </Link>

          <Link to="/profile" className="text-gray-300 hover:text-yellow-400 transition">
            Profile
          </Link>

          {token ? (
            <button
              onClick={logout}
              className="px-4 py-2 rounded-lg bg-yellow-400 text-black font-semibold hover:brightness-110 transition"
            >
              Logout
            </button>
          ) : (
            <Link
              to="/login"
              className="px-4 py-2 rounded-lg bg-yellow-400 text-black font-semibold hover:brightness-110 transition"
            >
              Login
            </Link>
          )}
        </div>
      </div>
    </nav>
  );
}
