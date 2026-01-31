import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";
import ConfirmModal from "../components/ConfirmModal.jsx";

export default function Customer() {
  const [customers, setCustomers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const [search, setSearch] = useState("");
  const [status, setStatus] = useState("");

  const [deleteId, setDeleteId] = useState(null);

  const fetchCustomers = async () => {
    try {
      setLoading(true);
      setError("");

      const res = await API.get("/customers", { params: { search, status } });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setCustomers(list);
    } catch (err) {
      setError(err?.response?.data?.message || err.message || "Failed to load customers");
      setCustomers([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCustomers();
    // eslint-disable-next-line
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    fetchCustomers();
  };

  const handleDelete = async () => {
    try {
      await API.delete(`/customers/${deleteId}`);
      setDeleteId(null);
      fetchCustomers();
    } catch (err) {
      alert(err?.response?.data?.message || "Delete failed");
    }
  };

  return (
    <AdminLayout title="Customers" subtitle="Manage customers (Admin only)">
      <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <form onSubmit={handleSearch} className="flex gap-3 w-full md:max-w-2xl">
          <input
            className="flex-1 px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
            placeholder="Search by name / email / phone..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />

          <select
            className="px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
            value={status}
            onChange={(e) => setStatus(e.target.value)}
          >
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="blocked">Blocked</option>
          </select>

          <button className="bg-[#d4af37] text-black font-semibold px-6 py-2 rounded-lg hover:opacity-90">
            Search
          </button>
        </form>

        <Link
          to="/customers/new"
          className="bg-[#d4af37] text-black font-semibold px-4 py-2 rounded-lg hover:opacity-90"
        >
          + Add Customer
        </Link>
      </div>

      {loading && <p className="text-gray-400">Loading customers...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && !error && (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Name</th>
                <th className="p-4">Email</th>
                <th className="p-4">Phone</th>
                <th className="p-4">Status</th>
                <th className="p-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              {customers.length > 0 ? (
                customers.map((c) => (
                  <tr key={c._id} className="border-b border-white/5">
                    <td className="p-4">{c.name}</td>
                    <td className="p-4">{c.email}</td>
                    <td className="p-4">{c.phone || "-"}</td>
                    <td className="p-4">
                      <span
                        className={`px-3 py-1 rounded-full text-sm font-semibold ${
                          c.status === "blocked"
                            ? "bg-red-500/20 text-red-300"
                            : "bg-green-500/20 text-green-300"
                        }`}
                      >
                        {c.status || "active"}
                      </span>
                    </td>
                    <td className="p-4 flex gap-2">
                      <Link
                        to={`/customers/edit/${c._id}`}
                        className="px-3 py-1 rounded-lg bg-blue-500/20 text-blue-200 hover:bg-blue-500/30"
                      >
                        Edit
                      </Link>
                      <button
                        onClick={() => setDeleteId(c._id)}
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
                    No customers found.
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
        message="Delete this customer? This cannot be undone."
        confirmText="Delete"
        onCancel={() => setDeleteId(null)}
        onConfirm={handleDelete}
      />
    </AdminLayout>
  );
}
