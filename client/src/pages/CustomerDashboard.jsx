import React, { useEffect, useState, useMemo } from "react";
import { Link, useNavigate } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";
import API from "../utils/api";
import Navbar from "../components/Navbar.jsx";
import PageShell from "../components/PageShell.jsx";
import {
  FaUserCircle,
  FaBoxOpen,
  FaClipboardList,
  FaCheckCircle,
  FaBell,
  FaSearch,
  FaMagic,
  FaSync,
  FaRing,
  FaArrowRight,
  FaShieldAlt,
  FaHammer,
  FaMedal,
  FaChartLine
} from "react-icons/fa";
import { 
  FiTrendingUp, 
  FiPackage,
} from "react-icons/fi";

export default function CustomerDashboard() {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [searchQuery, setSearchQuery] = useState("");
  const [stats, setStats] = useState({ 
    active: 0, 
    completed: 0, 
    pending: 0,
    totalValue: 0 
  });

  // Mocked Sri Lankan Gold Rates
  const [goldRate] = useState({ 24: "215,400", 22: "197,500" });

  const loadDashboardData = async () => {
    try {
      setLoading(true);
      setError("");

      // 1. Fetch authenticated user profile
      const userRes = await API.get("/auth/me");
      const userData = userRes.data?.data || userRes.data;
      setUser(userData);

      // 2. Fetch PERSONAL orders only
      // Backend Logic: router.get('/my', protect, getMyOrders) -> Order.find({ user: req.user.id })
      const ordersRes = await API.get("/orders/my");
      const myOrders = Array.isArray(ordersRes.data) ? ordersRes.data : ordersRes.data?.data || [];
      
      setOrders(myOrders);

      // 3. Process Stats for this specific user
      const active = myOrders.filter(o => !["Delivered", "Cancelled"].includes(o.status)).length;
      const completed = myOrders.filter(o => o.status === "Delivered").length;
      const pending = myOrders.filter(o => o.status === "Pending").length;
      const totalValue = myOrders
        .filter(o => o.status !== "Cancelled")
        .reduce((sum, order) => sum + (order.totalPrice || 0), 0);

      setStats({ active, completed, pending, totalValue });

    } catch (err) {
      console.error("Secure Fetch Error:", err);
      setError("Your session has timed out for security.");
      if (err.response?.status === 401) navigate("/login");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadDashboardData();
  }, []);

  const filteredOrders = useMemo(() => {
    return orders.filter(order => 
      order.orderNumber?.toLowerCase().includes(searchQuery.toLowerCase())
    );
  }, [orders, searchQuery]);

  if (loading) {
    return (
      <div className="h-screen bg-black flex flex-col items-center justify-center">
        <FaSync className="animate-spin text-[#d4af37] text-5xl mb-6" />
        <p className="text-gray-500 font-bold uppercase tracking-[0.4em] text-xs">Authenticating Vault Access...</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#020202] text-white font-sans selection:bg-[#d4af37]/30">
      <Navbar />
      
      <PageShell>
        <div className="container mx-auto px-4 py-8">
          
          {/* --- TOP HEADER & IDENTITY --- */}
          <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-8">
            <div>
              <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="flex items-center gap-2 text-[#d4af37] mb-2">
                <FaShieldAlt className="text-xs" />
                <span className="text-[10px] font-black uppercase tracking-[0.3em]">Privacy Shield Active</span>
              </motion.div>
              <h1 className="text-4xl font-black tracking-tighter italic">
                AYUBOWAN, <span className="text-[#d4af37] not-italic">{user?.name?.toUpperCase() || "ARTISAN"}</span>
              </h1>
              <p className="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">
                Personal Forge Dashboard • Sri Lanka
              </p>
            </div>
            
            <div className="flex flex-wrap items-center gap-4">
              {/* Sri Lanka Market Ticker */}
              <div className="hidden md:flex items-center gap-6 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl">
                 <div className="flex flex-col">
                    <span className="text-[8px] font-black text-gray-500 uppercase tracking-widest">24K Gold (LKR)</span>
                    <span className="text-sm font-bold text-[#d4af37]">Rs. {goldRate[24]}</span>
                 </div>
                 <div className="w-px h-6 bg-white/10" />
                 <div className="flex flex-col">
                    <span className="text-[8px] font-black text-gray-500 uppercase tracking-widest">22K Gold (LKR)</span>
                    <span className="text-sm font-bold text-gray-300">Rs. {goldRate[22]}</span>
                 </div>
              </div>

              <div className="relative">
                <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs" />
                <input
                  type="text"
                  placeholder="Order Search..."
                  className="pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:border-[#d4af37] w-full md:w-48 outline-none transition-all text-xs"
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
            </div>
          </div>

          {/* --- MAIN INTERFACE GRID --- */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            
            {/* AI STUDIO MODULE */}
            <motion.div 
              whileHover={{ scale: 1.005 }}
              className="lg:col-span-8 bg-gradient-to-br from-[#121212] to-[#050505] border border-white/10 rounded-[3rem] p-10 relative overflow-hidden group cursor-pointer"
              onClick={() => navigate("/ai-studio")}
            >
              <div className="absolute -top-10 -right-10 opacity-5 group-hover:opacity-10 transition-opacity">
                <FaMagic className="text-[15rem] rotate-12" />
              </div>
              <div className="relative z-10 flex flex-col h-full justify-between">
                <div>
                  <div className="flex items-center gap-3 mb-6">
                    <div className="p-3 bg-[#d4af37]/10 rounded-2xl">
                      <FaMagic className="text-[#d4af37] text-xl" />
                    </div>
                    <span className="text-[10px] font-black uppercase tracking-[0.4em] text-gray-400">Advanced Design Engine</span>
                  </div>
                  <h2 className="text-5xl font-black mb-4 tracking-tighter leading-tight">CRAFT YOUR <br /> VISION.</h2>
                  <p className="text-gray-400 max-w-sm text-sm leading-relaxed font-medium">
                    Use our proprietary Sri Lankan AI to visualize your custom 22K jewellery before the first hammer hits.
                  </p>
                </div>
                <div className="mt-12 flex items-center gap-4 text-[#d4af37] font-black text-xs uppercase tracking-widest group-hover:gap-6 transition-all">
                  Launch Studio <FaArrowRight />
                </div>
              </div>
            </motion.div>

            {/* ARTISAN RANK & LOYALTY */}
            <div className="lg:col-span-4 flex flex-col gap-6">
              <div className="bg-[#111] border border-white/5 rounded-[2.5rem] p-8 flex-1 relative overflow-hidden">
                <div className="absolute top-0 right-0 p-6 opacity-20">
                  <FaMedal className="text-5xl text-[#d4af37]" />
                </div>
                <p className="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-2">Artisan Loyalty</p>
                <h3 className="text-2xl font-black text-[#d4af37] mb-1">GOLD STATUS</h3>
                <p className="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Member since {new Date(user?.createdAt).getFullYear() || "2026"}</p>
                
                <div className="mt-8 space-y-4">
                  <div className="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div className="w-3/4 h-full bg-[#d4af37]" />
                  </div>
                  <div className="flex justify-between text-[9px] font-black uppercase tracking-widest text-gray-500">
                    <span>Next Rank: ELITE</span>
                    <span>750 Points</span>
                  </div>
                </div>
              </div>

              <div className="bg-[#d4af37] rounded-[2.5rem] p-8 text-black shadow-xl shadow-[#d4af37]/10">
                <div className="flex justify-between items-start mb-4">
                  <p className="text-[10px] font-black uppercase tracking-widest opacity-60">Portfolio Valuation</p>
                  <FaChartLine className="opacity-40" />
                </div>
                <h3 className="text-3xl font-black leading-none">Rs. {stats.totalValue.toLocaleString()}</h3>
                <p className="mt-4 text-[10px] font-bold uppercase bg-black/10 inline-block px-2 py-1 rounded">Secured Assets</p>
              </div>
            </div>
          </div>

          {/* --- FORGE HISTORY (MY ORDERS ONLY) --- */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div className="lg:col-span-8">
              <div className="bg-[#0a0a0a] border border-white/5 rounded-[3rem] p-10">
                <div className="flex justify-between items-center mb-10">
                  <div className="flex items-center gap-4">
                    <div className="w-1 h-8 bg-[#d4af37] rounded-full" />
                    <h2 className="text-2xl font-black uppercase tracking-tighter italic">Recent Forgings</h2>
                  </div>
                  <span className="text-[10px] font-black text-gray-600 uppercase tracking-widest">{orders.length} ITEMS TOTAL</span>
                </div>

                <div className="space-y-4">
                  {filteredOrders.length > 0 ? filteredOrders.map((order) => (
                    <motion.div 
                      key={order._id}
                      whileHover={{ x: 5 }}
                      onClick={() => navigate("/orders/track")}
                      className="flex flex-col md:flex-row md:items-center justify-between p-6 bg-white/[0.02] border border-white/5 rounded-[2rem] hover:border-[#d4af37]/30 transition-all cursor-pointer group"
                    >
                      <div className="flex items-center gap-6">
                        <div className="w-16 h-16 bg-black rounded-2xl flex items-center justify-center overflow-hidden border border-white/10 group-hover:border-[#d4af37]/40 transition-colors">
                          {order.design?.imageUrl ? (
                            <img src={order.design.imageUrl} className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" alt="forge" />
                          ) : (
                            <FaRing className="text-gray-800 text-xl" />
                          )}
                        </div>
                        <div>
                          <p className="font-black text-sm tracking-tight group-hover:text-[#d4af37] transition-colors">{order.orderNumber || "ORD-PENDING"}</p>
                          <p className="text-[9px] text-gray-600 font-bold uppercase tracking-widest mt-1">Initiated: {new Date(order.createdAt).toLocaleDateString()}</p>
                        </div>
                      </div>

                      <div className="flex items-center justify-between md:justify-end gap-10 mt-6 md:mt-0">
                        <div className="text-right">
                          <p className="text-sm font-black text-white">Rs. {order.totalPrice?.toLocaleString()}</p>
                          <div className="flex items-center justify-end gap-2 mt-1.5">
                             <div className={`w-1.5 h-1.5 rounded-full ${order.status === 'Delivered' ? 'bg-green-500 shadow-[0_0_8px_#22c55e]' : 'bg-[#d4af37] animate-pulse shadow-[0_0_8px_#d4af37]'}`} />
                             <span className="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">{order.status}</span>
                          </div>
                        </div>
                        <FaArrowRight className="text-gray-800 group-hover:text-[#d4af37] transition-colors" />
                      </div>
                    </motion.div>
                  )) : (
                    <div className="text-center py-20 bg-white/[0.01] rounded-[2rem] border border-dashed border-white/5">
                      <FaBoxOpen className="mx-auto text-5xl text-gray-800 mb-4" />
                      <p className="font-black uppercase text-[10px] text-gray-600 tracking-[0.3em]">Vault currently empty</p>
                    </div>
                  )}
                </div>
              </div>
            </div>

            {/* SIDEBAR PRODUCTION MODULE */}
            <div className="lg:col-span-4">
              <div className="bg-gradient-to-br from-[#111] to-[#050505] border border-white/5 rounded-[3rem] p-10 h-full">
                <div className="flex items-center gap-4 mb-8">
                  <FaHammer className="text-[#d4af37]" />
                  <h3 className="font-black text-sm uppercase tracking-[0.2em] text-gray-300">Live Forge Status</h3>
                </div>
                
                <div className="space-y-10">
                   <div className="relative pl-8 border-l border-white/10 py-1">
                      <div className="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-[#d4af37] shadow-[0_0_10px_#d4af37]" />
                      <p className="text-[10px] font-black text-[#d4af37] uppercase tracking-widest mb-1">Production Stage</p>
                      <p className="text-xs font-bold text-gray-400">You have {stats.active} items in the furnace.</p>
                   </div>
                   
                   <div className="p-6 bg-white/[0.03] border border-white/5 rounded-2xl">
                      <p className="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-4">Quick Links</p>
                      <div className="space-y-3">
                         <button onClick={() => navigate("/orders/track")} className="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Track Craftsmanship</button>
                         <button onClick={() => navigate("/ai-studio")} className="w-full py-3 bg-[#d4af37]/10 hover:bg-[#d4af37]/20 border border-[#d4af37]/20 rounded-xl text-[9px] font-black text-[#d4af37] uppercase tracking-widest transition-all">New Design Studio</button>
                      </div>
                   </div>

                   <div className="text-center pt-4">
                      <FaBell className="mx-auto text-gray-800 mb-2" />
                      <p className="text-[8px] font-bold text-gray-600 uppercase tracking-widest">No New Notifications</p>
                   </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </PageShell>
    </div>
  );
}