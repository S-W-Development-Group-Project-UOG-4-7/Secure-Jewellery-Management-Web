import React, { useEffect, useState } from "react";
import AdminLayout from "../components/AdminLayout.jsx";
import API from "../utils/api.js";

export default function Profile() {
  const [profile, setProfile] = useState(null);
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(true);

  const tryGet = async (path) => {
    try {
      const res = await API.get(path);
      return res.data?.data || res.data;
    } catch {
      return null;
    }
  };

  const loadProfile = async () => {
    setLoading(true);
    setError("");

    // ✅ Try these in order (no crashes if one missing)
    const p =
      (await tryGet("/users/me")) ||
      (await tryGet("/customers/me")) ||
      (await tryGet("/auth/me")) ||
      null;

    if (!p) setError("Profile endpoint not found on server (add /users/me or /customers/me).");
    setProfile(p);
    setLoading(false);
  };

  useEffect(() => {
    loadProfile();
  }, []);

  return (
    <AdminLayout title="My Profile" subtitle="Account details">
      {loading && <p className="text-gray-400">Loading profile...</p>}
      {error && <p className="text-red-400">{error}</p>}

      {!loading && profile && (
        <div className="bg-black/30 border border-white/10 rounded-2xl p-6 max-w-xl">
          <div className="space-y-2">
            <p className="text-gray-300">
              <span className="text-[#d4af37] font-semibold">Name:</span>{" "}
              {profile.name}
            </p>
            <p className="text-gray-300">
              <span className="text-[#d4af37] font-semibold">Email:</span>{" "}
              {profile.email}
            </p>
            <p className="text-gray-300">
              <span className="text-[#d4af37] font-semibold">Role:</span>{" "}
              {profile.role || "customer"}
            </p>
          </div>

          <button
            onClick={loadProfile}
            className="mt-5 px-4 py-2 rounded-lg border border-white/20 hover:bg-white/5"
          >
            Refresh
          </button>
        </div>
      )}
    </AdminLayout>
  );
}
