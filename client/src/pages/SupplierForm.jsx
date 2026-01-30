import React, { useEffect, useState } from "react";
import { useNavigate, useParams, Link } from "react-router-dom";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";

export default function SupplierForm() {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [form, setForm] = useState({ name: "", email: "", phone: "", address: "" });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const loadSupplier = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await API.get(`/suppliers/${id}`);
      const s = res.data?.data || res.data;
      setForm({
        name: s?.name || "",
        email: s?.email || "",
        phone: s?.phone || "",
        address: s?.address || "",
      });
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load supplier");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (isEdit) loadSupplier();
    // eslint-disable-next-line
  }, [id]);

  const handleChange = (e) => setForm((p) => ({ ...p, [e.target.name]: e.target.value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    if (!form.name.trim()) return setError("Supplier name is required");

    try {
      setLoading(true);
      if (isEdit) await API.put(`/suppliers/${id}`, form);
      else await API.post("/suppliers", form);

      navigate("/suppliers");
    } catch (err) {
      setError(err?.response?.data?.message || "Save failed");
    } finally {
      setLoading(false);
    }
  };

  return (
    <AdminLayout title={isEdit ? "Edit Supplier" : "Add Supplier"} subtitle="Supplier form">
      <div className="max-w-xl">
        {error && <p className="text-red-400 mb-4">{error}</p>}

        <form onSubmit={handleSubmit} className="space-y-4">
          <input
            name="name"
            value={form.name}
            onChange={handleChange}
            placeholder="Supplier Name"
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />

          <input
            name="email"
            value={form.email}
            onChange={handleChange}
            placeholder="Email"
            type="email"
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />

          <input
            name="phone"
            value={form.phone}
            onChange={handleChange}
            placeholder="Phone"
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />

          <textarea
            name="address"
            value={form.address}
            onChange={handleChange}
            placeholder="Address"
            rows="3"
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />

          <div className="flex justify-end gap-3 pt-2">
            <Link
              to="/suppliers"
              className="px-4 py-2 rounded-lg border border-gray-500 text-gray-200 hover:bg-white/10"
            >
              Cancel
            </Link>

            <button
              disabled={loading}
              className="bg-[#d4af37] text-black font-semibold px-6 py-2 rounded-lg hover:opacity-90 disabled:opacity-60"
            >
              {loading ? "Saving..." : "Save Supplier"}
            </button>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}
