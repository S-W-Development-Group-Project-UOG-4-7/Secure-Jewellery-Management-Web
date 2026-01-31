import React, { useEffect, useMemo, useState } from "react";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";

export default function Stock() {
  const [stocks, setStocks] = useState([]);
  const [loading, setLoading] = useState(true);

  const [search, setSearch] = useState("");
  const [showLowStockOnly, setShowLowStockOnly] = useState(false);

  const [successMsg, setSuccessMsg] = useState("");
  const [errorMsg, setErrorMsg] = useState("");

  const [editQty, setEditQty] = useState({});
  const LOW_STOCK_THRESHOLD = 5;

  useEffect(() => {
    if (!successMsg && !errorMsg) return;
    const t = setTimeout(() => {
      setSuccessMsg("");
      setErrorMsg("");
    }, 3000);
    return () => clearTimeout(t);
  }, [successMsg, errorMsg]);

  const fetchStock = async () => {
    try {
      setLoading(true);
      const res = await API.get("/stock");
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setStocks(list);

      const init = {};
      list.forEach((s) => {
        if (s?._id) init[s._id] = s.quantity ?? 0;
      });
      setEditQty(init);
    } catch (err) {
      setErrorMsg(err?.response?.data?.message || "Failed to load stock");
      setStocks([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchStock();
  }, []);

  const filteredStocks = useMemo(() => {
    let list = [...stocks];

    if (search.trim()) {
      const q = search.toLowerCase();
      list = list.filter((s) => (s.itemName || "").toLowerCase().includes(q));
    }

    if (showLowStockOnly) {
      list = list.filter((s) => Number(s.quantity) <= LOW_STOCK_THRESHOLD);
    }

    return list;
  }, [stocks, search, showLowStockOnly]);

  const updateQuantity = async (stockId) => {
    try {
      const newQty = Number(editQty[stockId]);
      if (Number.isNaN(newQty) || newQty < 0) {
        setErrorMsg("Quantity must be a valid number and cannot be negative.");
        return;
      }

      const res = await API.put(`/stock/${stockId}`, { quantity: newQty });
      setSuccessMsg(res?.data?.message || "Stock updated ✅");
      fetchStock();
    } catch (err) {
      setErrorMsg(err?.response?.data?.message || "Stock update failed ❌");
    }
  };

  return (
    <AdminLayout title="Stock" subtitle="Update jewellery stock">
      {successMsg && (
        <div className="mb-4 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-200">
          ✅ {successMsg}
        </div>
      )}

      {errorMsg && (
        <div className="mb-4 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-red-200">
          ❌ {errorMsg}
        </div>
      )}

      <div className="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
          <input
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by item name..."
            className="w-full sm:w-80 rounded-xl border border-[#d4af37]/30 bg-black/40 px-4 py-2 text-white placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40"
          />

          <label className="flex items-center gap-2 text-gray-300">
            <input
              type="checkbox"
              checked={showLowStockOnly}
              onChange={(e) => setShowLowStockOnly(e.target.checked)}
              className="h-4 w-4 accent-[#d4af37]"
            />
            Low Stock Only (≤ {LOW_STOCK_THRESHOLD})
          </label>
        </div>

        <button
          onClick={fetchStock}
          className="rounded-xl bg-[#d4af37] px-5 py-2 font-semibold text-black hover:opacity-90"
        >
          Refresh
        </button>
      </div>

      <div className="overflow-x-auto rounded-2xl border border-white/10 bg-black/40">
        <table className="min-w-full text-left">
          <thead className="bg-[#d4af37]/10 text-[#d4af37]">
            <tr>
              <th className="px-5 py-4 font-semibold">Item</th>
              <th className="px-5 py-4 font-semibold">Quantity</th>
              <th className="px-5 py-4 font-semibold">Status</th>
              <th className="px-5 py-4 font-semibold">Update</th>
            </tr>
          </thead>

          <tbody>
            {loading ? (
              <tr>
                <td colSpan="4" className="px-5 py-10 text-center text-gray-400">
                  Loading stock...
                </td>
              </tr>
            ) : filteredStocks.length === 0 ? (
              <tr>
                <td colSpan="4" className="px-5 py-10 text-center text-gray-400">
                  No stock found.
                </td>
              </tr>
            ) : (
              filteredStocks.map((stock) => {
                const qty = Number(stock.quantity ?? 0);
                const isLow = qty <= LOW_STOCK_THRESHOLD;

                return (
                  <tr
                    key={stock._id || stock.itemName}
                    className="border-t border-white/5 hover:bg-white/5 transition"
                  >
                    <td className="px-5 py-4 text-white font-medium">{stock.itemName}</td>
                    <td className="px-5 py-4">
                      <input
                        type="number"
                        min={0}
                        value={editQty[stock._id] ?? qty}
                        onChange={(e) =>
                          setEditQty((prev) => ({
                            ...prev,
                            [stock._id]: e.target.value,
                          }))
                        }
                        className="w-28 rounded-xl border border-white/10 bg-black/40 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40"
                      />
                    </td>

                    <td className="px-5 py-4">
                      {isLow ? (
                        <span className="inline-flex items-center gap-2 rounded-full bg-red-500/10 px-3 py-1 text-sm font-semibold text-red-200 border border-red-500/20">
                          ⚠ Low Stock
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-2 rounded-full bg-green-500/10 px-3 py-1 text-sm font-semibold text-green-200 border border-green-500/20">
                          ✅ Available
                        </span>
                      )}
                    </td>

                    <td className="px-5 py-4">
                      <button
                        onClick={() => updateQuantity(stock._id)}
                        className="rounded-xl bg-[#d4af37] px-4 py-2 font-semibold text-black hover:opacity-90"
                      >
                        Save
                      </button>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </AdminLayout>
  );
}
