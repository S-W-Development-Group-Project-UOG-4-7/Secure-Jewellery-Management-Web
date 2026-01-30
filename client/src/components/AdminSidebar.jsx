import React from "react";
import { NavLink } from "react-router-dom";

const linkBase = "block px-4 py-3 rounded-xl transition border border-transparent";
const active = "bg-[#d4af37]/15 text-[#d4af37] border-[#d4af37]/30";
const inactive = "text-gray-300 hover:bg-white/5 hover:border-white/10";

export default function AdminSidebar() {
  const Item = ({ to, label }) => (
    <NavLink
      to={to}
      className={({ isActive }) => `${linkBase} ${isActive ? active : inactive}`}
    >
      {label}
    </NavLink>
  );

  return (
    <aside className="w-full md:w-72 bg-black/60 border border-white/10 rounded-2xl p-4 h-fit sticky top-6">
      <div className="mb-5">
        <h2 className="text-xl font-bold text-[#d4af37]">Admin Panel</h2>
        <p className="text-xs text-gray-400">SJM Management</p>
      </div>

      <div className="space-y-2">
        <Item to="/admin-dashboard" label="Dashboard" />
        <Item to="/customers" label="Customers" />
        <Item to="/suppliers" label="Suppliers" />
        <Item to="/deliveries" label="Deliveries" />
        <Item to="/stock" label="Stock" />
        <Item to="/stock-logs" label="Stock Logs" />
        <Item to="/jewellery" label="Jewellery" />
        <Item to="/locker-verification" label="Locker Verification" />
        <Item to="/users" label="User Management" />
        <Item to="/profile" label="My Profile" />
      </div>
    </aside>
  );
}
