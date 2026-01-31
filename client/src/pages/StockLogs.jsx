import React, { useEffect, useState } from "react";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";

export default function StockLogs() {
  const [logs, setLogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const fetchLogs = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await API.get("/stock/logs/all");
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setLogs(list);
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load stock logs");
      setLogs([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLogs();
  }, []);

  return (
    <AdminLayout title="Stock Logs" subtitle="All stock update history">
      {loading && <p className="text-gray-400">Loading logs...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && !error && (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Item</th>
                <th className="p-4">Change</th>
                <th className="p-4">New Qty</th>
                <th className="p-4">Updated By</th>
                <th className="p-4">Date</th>
              </tr>
            </thead>
            <tbody>
              {logs.length > 0 ? (
                logs.map((l) => (
                  <tr key={l._id} className="border-b border-white/5">
                    <td className="p-4">{l.itemName || "-"}</td>
                    <td className="p-4">{l.change ?? "-"}</td>
                    <td className="p-4">{l.newQuantity ?? "-"}</td>
                    <td className="p-4">{l?.updatedBy?.name || l?.updatedByEmail || "-"}</td>
                    <td className="p-4">
                      {l.createdAt ? new Date(l.createdAt).toLocaleString() : "-"}
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td className="p-6 text-gray-400" colSpan={5}>
                    No logs found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </AdminLayout>
  );
}
