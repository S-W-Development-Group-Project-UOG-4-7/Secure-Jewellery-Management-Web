import React, { useEffect, useMemo, useState } from "react";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";
import ConfirmModal from "../components/ConfirmModal.jsx";

export default function Jewellery() {
  const [items, setItems] = useState([]);
  const [search, setSearch] = useState("");

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  // add/edit modal
  const [openModal, setOpenModal] = useState(false);
  const [editing, setEditing] = useState(null);

  const [deleteId, setDeleteId] = useState(null);

  const [form, setForm] = useState({
    name: "",
    type: "",
    weight: "",
    quantity: 1,
    price: "",
    description: "",
  });

  const resetForm = () => {
    setForm({ name: "", type: "", weight: "", quantity: 1, price: "", description: "" });
    setEditing(null);
  };

  const fetchJewellery = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await API.get("/jewellery", { params: { search } });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setItems(list);
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load jewellery");
      setItems([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchJewellery();
    // eslint-disable-next-line
  }, []);

  const filtered = useMemo(() => {
    if (!search.trim()) return items;
    const q = search.toLowerCase();
    return items.filter((x) => (x.name || "").toLowerCase().includes(q));
  }, [items, search]);

  const openAdd = () => {
    resetForm();
    setOpenModal(true);
  };

  const openEdit = (item) => {
    setEditing(item);
    setForm({
      name: item.name || "",
      type: item.type || "",
      weight: item.weight ?? "",
      quantity: item.quantity ?? 1,
      price: item.price ?? "",
      description: item.description || "",
    });
    setOpenModal(true);
  };

  const closeModal = () => {
    setOpenModal(false);
    resetForm();
  };

  const onChange = (e) => setForm((p) => ({ ...p, [e.target.name]: e.target.value }));

  const save = async (e) => {
    e.preventDefault();
    setError("");

    if (!form.name.trim()) return setError("Name is required");
    if (Number(form.quantity) < 0) return setError("Quantity must be >= 0");

    try {
      const payload = {
        ...form,
        weight: form.weight === "" ? undefined : Number(form.weight),
        price: form.price === "" ? undefined : Number(form.price),
        quantity: Number(form.quantity),
      };

      if (editing?._id) await API.put(`/jewellery/${editing._id}`, payload);
      else await API.post("/jewellery", payload);

      closeModal();
      fetchJewellery();
    } catch (err) {
      setError(err?.response?.data?.message || "Save failed");
    }
  };

  const confirmDelete = async () => {
    try {
      await API.delete(`/jewellery/${deleteId}`);
      setDeleteId(null);
      fetchJewellery();
    } catch (err) {
      alert(err?.response?.data?.message || "Delete failed");
    }
  };

  return (
    <AdminLayout title="Jewellery" subtitle="Add / Edit / Delete jewellery items">
      <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div className="flex gap-3 w-full md:max-w-xl">
          <input
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search jewellery..."
            className="flex-1 px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />
          <button
            onClick={fetchJewellery}
            className="px-4 py-2 rounded-lg border border-white/20 hover:bg-white/5"
          >
            Refresh
          </button>
        </div>

        <button
          onClick={openAdd}
          className="bg-[#d4af37] text-black font-semibold px-4 py-2 rounded-lg hover:opacity-90"
        >
          + Add Jewellery
        </button>
      </div>

      {loading && <p className="text-gray-400">Loading jewellery...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && !error && (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Name</th>
                <th className="p-4">Type</th>
                <th className="p-4">Weight</th>
                <th className="p-4">Qty</th>
                <th className="p-4">Price</th>
                <th className="p-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              {filtered.length ? (
                filtered.map((x) => (
                  <tr key={x._id} className="border-b border-white/5">
                    <td className="p-4">{x.name}</td>
                    <td className="p-4">{x.type || "-"}</td>
                    <td className="p-4">{x.weight ?? "-"}</td>
                    <td className="p-4">{x.quantity ?? "-"}</td>
                    <td className="p-4">{x.price ?? "-"}</td>
                    <td className="p-4 flex gap-2">
                      <button
                        onClick={() => openEdit(x)}
                        className="px-3 py-1 rounded-lg bg-blue-500/20 text-blue-200 hover:bg-blue-500/30"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => setDeleteId(x._id)}
                        className="px-3 py-1 rounded-lg bg-red-500/20 text-red-200 hover:bg-red-500/30"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="p-6 text-gray-400">
                    No items found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}

      {/* Add/Edit Modal */}
      {openModal && (
        <div className="fixed inset-0 bg-black/80 flex items-center justify-center z-50 px-4">
          <div className="bg-[#111] border border-[#d4af37]/30 rounded-2xl p-6 w-full max-w-xl">
            <h2 className="text-xl font-bold text-[#d4af37] mb-4">
              {editing ? "Edit Jewellery" : "Add Jewellery"}
            </h2>

            {error && <p className="text-red-400 mb-3">{error}</p>}

            <form onSubmit={save} className="space-y-3">
              <input
                name="name"
                value={form.name}
                onChange={onChange}
                placeholder="Name"
                className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
              />
              <input
                name="type"
                value={form.type}
                onChange={onChange}
                placeholder="Type (Ring, Chain...)"
                className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
              />
              <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input
                  name="weight"
                  value={form.weight}
                  onChange={onChange}
                  placeholder="Weight"
                  className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
                />
                <input
                  name="quantity"
                  type="number"
                  min={0}
                  value={form.quantity}
                  onChange={onChange}
                  placeholder="Quantity"
                  className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
                />
                <input
                  name="price"
                  value={form.price}
                  onChange={onChange}
                  placeholder="Price"
                  className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
                />
              </div>
              <textarea
                name="description"
                value={form.description}
                onChange={onChange}
                placeholder="Description"
                rows={3}
                className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
              />

              <div className="flex justify-end gap-3 pt-2">
                <button
                  type="button"
                  onClick={closeModal}
                  className="px-4 py-2 rounded-lg border border-gray-500 text-gray-200 hover:bg-white/10"
                >
                  Cancel
                </button>
                <button className="bg-[#d4af37] text-black font-semibold px-6 py-2 rounded-lg hover:opacity-90">
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      <ConfirmModal
        open={Boolean(deleteId)}
        title="Confirm Delete"
        message="Delete this jewellery item?"
        confirmText="Delete"
        onCancel={() => setDeleteId(null)}
        onConfirm={confirmDelete}
      />
    </AdminLayout>
  );
}
