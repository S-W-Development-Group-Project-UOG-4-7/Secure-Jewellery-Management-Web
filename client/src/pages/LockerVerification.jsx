import React, { useEffect, useState } from "react";
import API from "../utils/api.js";
import AdminLayout from "../components/AdminLayout.jsx";

export default function LockerVerification() {
  const [tab, setTab] = useState("before"); // before | after
  const [jewellery, setJewellery] = useState([]);

  const [form, setForm] = useState({
    jewelleryId: "",
    lockerNumber: "",
    notes: "",
    result: "matched", // matched | mismatch
    mismatchReason: "",
  });

  const [proof, setProof] = useState(null);

  const [logs, setLogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [msg, setMsg] = useState("");
  const [error, setError] = useState("");

  const loadJewellery = async () => {
    try {
      const res = await API.get("/jewellery", { params: { search: "" } });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setJewellery(list);
    } catch {
      setJewellery([]);
    }
  };

  const loadLogs = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await API.get("/locker", { params: { stage: tab } });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setLogs(list);
    } catch (err) {
      setError(err?.response?.data?.message || "Failed to load verification logs");
      setLogs([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadJewellery();
  }, []);

  useEffect(() => {
    loadLogs();
    // eslint-disable-next-line
  }, [tab]);

  const onChange = (e) => setForm((p) => ({ ...p, [e.target.name]: e.target.value }));

  const submit = async (e) => {
    e.preventDefault();
    setMsg("");
    setError("");

    if (!form.jewelleryId) return setError("Select a jewellery item");
    if (!form.lockerNumber.trim()) return setError("Locker number required");
    if (form.result === "mismatch" && !form.mismatchReason.trim())
      return setError("Mismatch reason required");

    try {
      const fd = new FormData();
      fd.append("stage", tab);
      fd.append("jewelleryId", form.jewelleryId);
      fd.append("lockerNumber", form.lockerNumber);
      fd.append("notes", form.notes);
      fd.append("result", form.result);
      if (form.result === "mismatch") fd.append("mismatchReason", form.mismatchReason);
      if (proof) fd.append("proof", proof);

      await API.post("/locker", fd, { headers: { "Content-Type": "multipart/form-data" } });

      setMsg("Verification saved ✅");
      setForm({
        jewelleryId: "",
        lockerNumber: "",
        notes: "",
        result: "matched",
        mismatchReason: "",
      });
      setProof(null);
      loadLogs();
    } catch (err) {
      setError(err?.response?.data?.message || "Save failed");
    }
  };

  return (
    <AdminLayout title="Locker Verification" subtitle="Before/After storage verification">
      <div className="flex gap-2 mb-6">
        <button
          onClick={() => setTab("before")}
          className={`px-4 py-2 rounded-xl border ${
            tab === "before"
              ? "bg-[#d4af37]/15 text-[#d4af37] border-[#d4af37]/30"
              : "border-white/15 text-gray-300 hover:bg-white/5"
          }`}
        >
          Before Storage
        </button>
        <button
          onClick={() => setTab("after")}
          className={`px-4 py-2 rounded-xl border ${
            tab === "after"
              ? "bg-[#d4af37]/15 text-[#d4af37] border-[#d4af37]/30"
              : "border-white/15 text-gray-300 hover:bg-white/5"
          }`}
        >
          After Storage
        </button>
      </div>

      {msg && <div className="mb-4 text-green-200 border border-green-500/30 bg-green-500/10 px-4 py-3 rounded-xl">✅ {msg}</div>}
      {error && <div className="mb-4 text-red-200 border border-red-500/30 bg-red-500/10 px-4 py-3 rounded-xl">❌ {error}</div>}

      <form onSubmit={submit} className="bg-black/30 border border-white/10 rounded-2xl p-5 mb-6 space-y-3">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
          <select
            name="jewelleryId"
            value={form.jewelleryId}
            onChange={onChange}
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          >
            <option value="">Select Jewellery</option>
            {jewellery.map((j) => (
              <option key={j._id} value={j._id}>
                {j.name}
              </option>
            ))}
          </select>

          <input
            name="lockerNumber"
            value={form.lockerNumber}
            onChange={onChange}
            placeholder="Locker Number (ex: L-001)"
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          />
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
          <select
            name="result"
            value={form.result}
            onChange={onChange}
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
          >
            <option value="matched">Matched</option>
            <option value="mismatch">Mismatch</option>
          </select>

          <input
            name="mismatchReason"
            value={form.mismatchReason}
            onChange={onChange}
            placeholder="Mismatch reason (only if mismatch)"
            disabled={form.result !== "mismatch"}
            className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none disabled:opacity-50"
          />
        </div>

        <textarea
          name="notes"
          value={form.notes}
          onChange={onChange}
          placeholder="Notes"
          rows={3}
          className="w-full px-4 py-2 rounded-lg bg-black border border-[#d4af37]/30 focus:outline-none"
        />

        <div className="space-y-2">
          <label className="text-sm text-gray-300">Proof Image (optional)</label>
          <input type="file" accept="image/*" onChange={(e) => setProof(e.target.files?.[0] || null)} />
        </div>

        <div className="flex justify-end">
          <button className="bg-[#d4af37] text-black font-semibold px-6 py-2 rounded-lg hover:opacity-90">
            Save Verification
          </button>
        </div>
      </form>

      <h3 className="text-lg font-semibold text-[#d4af37] mb-3">Results ({tab})</h3>

      {loading ? (
        <p className="text-gray-400">Loading results...</p>
      ) : (
        <div className="overflow-x-auto bg-black/30 border border-white/10 rounded-xl">
          <table className="w-full">
            <thead>
              <tr className="text-left text-[#d4af37] border-b border-white/10">
                <th className="p-4">Jewellery</th>
                <th className="p-4">Locker</th>
                <th className="p-4">Result</th>
                <th className="p-4">Notes</th>
                <th className="p-4">Proof</th>
                <th className="p-4">Date</th>
              </tr>
            </thead>
            <tbody>
              {logs.length ? (
                logs.map((x) => (
                  <tr key={x._id} className="border-b border-white/5">
                    <td className="p-4">{x?.jewellery?.name || x?.jewelleryName || "-"}</td>
                    <td className="p-4">{x.lockerNumber || "-"}</td>
                    <td className="p-4">
                      <span
                        className={`px-3 py-1 rounded-full text-sm font-semibold ${
                          x.result === "mismatch"
                            ? "bg-red-500/20 text-red-300"
                            : "bg-green-500/20 text-green-300"
                        }`}
                      >
                        {x.result}
                      </span>
                    </td>
                    <td className="p-4">{x.notes || "-"}</td>
                    <td className="p-4">
                      {x.proofImage ? (
                        <a className="text-[#d4af37] hover:underline" href={x.proofImage} target="_blank" rel="noreferrer">
                          View
                        </a>
                      ) : (
                        <span className="text-gray-400">No</span>
                      )}
                    </td>
                    <td className="p-4">{x.createdAt ? new Date(x.createdAt).toLocaleString() : "-"}</td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="p-6 text-gray-400">No results.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </AdminLayout>
  );
}
