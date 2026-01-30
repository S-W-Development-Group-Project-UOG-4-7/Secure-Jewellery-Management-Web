import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";
import ConfirmModal from "../components/ConfirmModal.jsx";

export default function Suppliers() {
  const [suppliers, setSuppliers] = useState([]);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(true);
  const [deleteId, setDeleteId] = useState(null);
  const [error, setError] = useState("");

  const fetchSuppliers = async () => {
    try {
      setLoading(true);
      setError("");

      const res = await API.get("/suppliers", { params: { search } });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setSuppliers(list);
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load suppliers");
      setSuppliers([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSuppliers();
    // eslint-disable-next-line
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    fetchSuppliers();
  };

  const handleDelete = async () => {
    try {
      await API.delete(`/suppliers/${deleteId}`);
      setDeleteId(null);
      fetchSuppliers();
    } catch (err) {
      alert(err?.response?.data?.message || "Delete failed");
    }
  };

  return (
    <AdminLayout title="Suppliers" subtitle="Supplier Management (Admin only)">
      <div className="flex items-center justify-between mb-6 gap-3">
        <form onSubmit={handleSearch} className="flex gap-3 w-full max-w-xl">
          <input
            className="flex-1 px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
            placeholder="Search supplier by name/email/phone..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
          <button className="bg-[#d4af37] text-black font-semibold px-6 py-2 rounded-lg hover:opacity-90">
            Search
          </button>
        </form>

        <Link
          to="/suppliers/new"
          className="bg-[#d4af37] text-black font-semibold px-4 py-2 rounded-lg hover:opacity-90"
        >
          + Add Supplier
        </Link>
      </div>

      {loading && <p className="text-gray-400">Loading suppliers...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && !error && (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Name</th>
                <th className="p-4">Email</th>
                <th className="p-4">Phone</th>
                <th className="p-4">Address</th>
                <th className="p-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              {suppliers.length > 0 ? (
                suppliers.map((s) => (
                  <tr key={s._id} className="border-b border-white/5">
                    <td className="p-4">{s.name}</td>
                    <td className="p-4">{s.email || "-"}</td>
                    <td className="p-4">{s.phone || "-"}</td>
                    <td className="p-4">{s.address || "-"}</td>
                    <td className="p-4 flex gap-2">
                      <Link
                        to={`/suppliers/edit/${s._id}`}
                        className="px-3 py-1 rounded-lg bg-blue-500/20 text-blue-200 hover:bg-blue-500/30"
                      >
                        Edit
                      </Link>
                      <button
                        onClick={() => setDeleteId(s._id)}
                        className="px-3 py-1 rounded-lg bg-red-500/20 text-red-200 hover:bg-red-500/30"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td className="p-6 text-gray-400" colSpan={5}>
                    No suppliers found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}

      <ConfirmModal
        open={Boolean(deleteId)}
        title="Confirm Delete"
        message="Delete this supplier?"
        confirmText="Delete"
        onCancel={() => setDeleteId(null)}
        onConfirm={handleDelete}
      />
    </AdminLayout>
  );
}
