import React from "react";
import Navbar from "./Navbar.jsx";
import AdminSidebar from "./AdminSidebar.jsx";

export default function AdminLayout({ title, subtitle, children }) {
  return (
    <div className="min-h-screen bg-black text-white">
      <Navbar />

      <div className="max-w-7xl mx-auto px-6 py-8">
        <div className="mb-6">
          <h1 className="text-3xl font-bold text-[#d4af37]">{title}</h1>
          {subtitle && <p className="text-gray-400 mt-1">{subtitle}</p>}
        </div>

        <div className="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-6">
          <AdminSidebar />
          <main className="bg-white/5 border border-white/10 rounded-2xl p-6 overflow-x-auto">
            {children}
          </main>
        </div>
      </div>
    </div>
  );
}
