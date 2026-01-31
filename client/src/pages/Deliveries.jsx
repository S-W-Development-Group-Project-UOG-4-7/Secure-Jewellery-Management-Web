import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";
import ConfirmModal from "../components/ConfirmModal.jsx";

export default function Deliveries() {
  const [deliveries, setDeliveries] = useState([]);
  const [loading, setLoading] = useState(true);
  const [deleteId, setDeleteId] = useState(null);
  const [error, setError] = useState("");

  const fetchDeliveries = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await API.get("/deliveries");
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setDeliveries(list);
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load deliveries");
      setDeliveries([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDeliveries();
  }, []);

  const handleDelete = async () => {
    try {
      await API.delete(`/deliveries/${deleteId}`);
      setDeleteId(null);
      fetchDeliveries();
    } catch (err) {
      alert(err?.response?.data?.message || "Delete failed");
    }
  };

  return (
    <AdminLayout title="Deliveries" subtitle="Delivery records (Admin only)">
      <div className="flex items-center justify-between mb-6">
        <Link
          to="/deliveries/new"
          className="bg-[#d4af37] text-black font-semibold px-4 py-2 rounded-lg hover:opacity-90"
        >
          + New Delivery
        </Link>

        <button
          onClick={fetchDeliveries}
          className="px-4 py-2 rounded-lg border border-white/20 hover:bg-white/5"
        >
          Refresh
        </button>
      </div>

      {loading && <p className="text-gray-400">Loading deliveries...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && !error && (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Supplier</th>
                <th className="p-4">Item</th>
                <th className="p-4">Qty</th>
                <th className="p-4">Date</th>
                <th className="p-4">Invoice</th>
                <th className="p-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              {deliveries.length > 0 ? (
                deliveries.map((d) => (
                  <tr key={d._id} className="border-b border-white/5">
                    <td className="p-4">{d?.supplier?.name || d?.supplierName || "Unknown"}</td>
                    <td className="p-4">{d.itemName || "-"}</td>
                    <td className="p-4">{d.quantity ?? "-"}</td>
                    <td className="p-4">{d.date ? new Date(d.date).toLocaleDateString() : "-"}</td>
                    <td className="p-4">
                      {d.invoiceUrl ? (
                        <a
                          href={d.invoiceUrl}
                          target="_blank"
                          rel="noreferrer"
                          className="text-[#d4af37] hover:underline"
                        >
                          View
                        </a>
                      ) : (
                        <span className="text-gray-400">No</span>
                      )}
                    </td>
                    <td className="p-4 flex gap-2">
                      <Link
                        to={`/deliveries/edit/${d._id}`}
                        className="px-3 py-1 rounded-lg bg-blue-500/20 text-blue-200 hover:bg-blue-500/30"
                      >
                        Edit
                      </Link>
                      <button
                        onClick={() => setDeleteId(d._id)}
                        className="px-3 py-1 rounded-lg bg-red-500/20 text-red-200 hover:bg-red-500/30"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td className="p-6 text-gray-400" colSpan={6}>
                    No deliveries found.
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
        message="Delete this delivery record?"
        confirmText="Delete"
        onCancel={() => setDeleteId(null)}
        onConfirm={handleDelete}
      />
    </AdminLayout>
  );
}
