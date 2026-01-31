import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";
import API from "../utils/api";
import PageShell from "../components/PageShell.jsx";

import {
  FaMagic,
  FaGem,
  FaRing,
  FaLink,
  FaCircle,
  FaRegCircle,
  FaCube,
  FaHistory,
  FaSync,
  FaLightbulb,
  FaEye,
  FaCrown,
  FaCheck,
  FaArrowLeft,
  FaShoppingCart,
  FaFileInvoice,
  FaTimes,
  FaStar,
  FaShoppingBag
} from "react-icons/fa";

export default function AIStudio() {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [generating, setGenerating] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [showOrderSuccess, setShowOrderSuccess] = useState(false);
  const [lastOrderNumber, setLastOrderNumber] = useState("");
  
  const [designPrompt, setDesignPrompt] = useState("");
  const [designType, setDesignType] = useState("ring");
  const [currentDesign, setCurrentDesign] = useState(null);
  const [designHistory, setDesignHistory] = useState([]);
  const [imageError, setImageError] = useState(false);

  const [selectedStyle, setSelectedStyle] = useState("");
  const [selectedStone, setSelectedStone] = useState("");

  const designTypes = [
    { id: "ring", name: "Ring", icon: FaRing },
    { id: "necklace", name: "Necklace", icon: FaLink },
    { id: "earring", name: "Earrings", icon: FaCircle },
    { id: "bracelet", name: "Bracelet", icon: FaRegCircle },
    { id: "tiara", name: "Tiara", icon: FaCrown }
  ];

  const promptSuggestions = {
    styles: ["Kandyan Traditional", "Art Deco", "Minimalist Modern", "Filigree Gold", "Vintage Royal"],
    stones: ["Blue Ceylon Sapphire", "Padparadscha", "Matale Ruby", "Green Emerald", "Star Sapphire"]
  };

  useEffect(() => {
    loadDesignHistory();
  }, []);

  useEffect(() => { setImageError(false); }, [currentDesign]);
  
  const loadDesignHistory = async () => {
    try {
      const res = await API.get("/designs/history");
      if (res.data?.success) setDesignHistory(res.data.data || []);
    } catch (err) { console.error("History load error:", err.message); }
  };

  const handleEnhancePrompt = () => {
    if (!designPrompt) return;
    const enhanced = `High-end professional jewelry photography of a ${designType}, ${designPrompt}${selectedStyle ? ', ' + selectedStyle + ' style' : ''}${selectedStone ? ', featuring ' + selectedStone : ''}, solid 22k Sri Lankan gold, intricate craftsmanship, isolated on plain white background, 8k resolution, cinematic studio lighting, macro lens focus.`;
    setDesignPrompt(enhanced);
    setSuccess("Prompt optimized for AI rendering!");
    setTimeout(() => setSuccess(""), 2000);
  };

  const handleGenerateDesign = async () => {
    if (!designPrompt.trim()) {
      setError("Please describe your design or use the keywords below.");
      return;
    }
    
    try {
      setGenerating(true);
      setError("");
      setSuccess("");
      
      const res = await API.post("/designs/generate", {
        designPrompt,
        designType,
        customizations: { style: selectedStyle, complexity: "High" }
      });
      
      if (res.data.success) {
        const savedDesign = res.data.data;
        setCurrentDesign(savedDesign);
        setDesignHistory(prev => [savedDesign, ...prev].slice(0, 5));
        setSuccess("Design forged successfully in Matale Studio!");
        setTimeout(() => setSuccess(""), 3000);
      }
    } catch (err) {
      setError("The AI Forge is cooling down. Please try again in 10 seconds.");
    } finally {
      setGenerating(false);
    }
  };

  const handleCreateOrder = async () => {
    if (!currentDesign) return;
    try {
        setLoading(true);
        const res = await API.post("/orders", {
            design: currentDesign._id,
            totalPrice: currentDesign.customizations?.estimatedCost || 185000,
            shippingAddress: { city: "Matale", country: "Sri Lanka" }
        });
        if (res.data.success) {
            setLastOrderNumber(res.data.data.orderNumber);
            setShowOrderSuccess(true); // Open the success modal
        }
    } catch (err) { 
        setError("Order failed. Please refresh."); 
    } finally { 
        setLoading(false); 
    }
  };

  return (
    <div className="min-h-screen bg-gray-950 text-white selection:bg-[#d4af37]/30">
      <PageShell title="AI Studio Forge" subtitle="Matale Premium Jewellery Visualizer">
        
        {/* REDIRECT ERROR FIX: Success Modal Overlay */}
        <AnimatePresence>
          {showOrderSuccess && (
            <motion.div 
              initial={{ opacity: 0 }} 
              animate={{ opacity: 1 }} 
              className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
            >
              <motion.div 
                initial={{ scale: 0.9, y: 20 }} 
                animate={{ scale: 1, y: 0 }} 
                className="bg-gray-900 border border-[#d4af37]/30 p-8 rounded-[2.5rem] max-w-md w-full text-center shadow-[0_0_50px_rgba(212,175,55,0.15)]"
              >
                <div className="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                  <FaCheck className="text-green-400 text-3xl" />
                </div>
                <h2 className="text-2xl font-black uppercase tracking-tighter mb-2">Order Confirmed!</h2>
                <p className="text-gray-400 mb-6">Your order <span className="text-[#d4af37] font-bold">#{lastOrderNumber}</span> has been received by Matale Studio.</p>
                
                <div className="space-y-3">
                  <button 
                    onClick={() => navigate("/customer-dashboard")} // ✅ Ensure this matches your route in App.jsx
                    className="w-full py-4 bg-[#d4af37] text-black font-black uppercase text-sm rounded-2xl hover:bg-[#f4d03f] transition-all"
                  >
                    Go to My Orders
                  </button>
                  <button 
                    onClick={() => setShowOrderSuccess(false)}
                    className="w-full py-4 bg-white/5 border border-white/10 text-white font-bold uppercase text-xs rounded-2xl hover:bg-white/10 transition-all"
                  >
                    Stay in Studio
                  </button>
                </div>
              </motion.div>
            </motion.div>
          )}
        </AnimatePresence>

        <div className="mb-8 flex justify-between items-center">
          <button onClick={() => navigate(-1)} className="flex items-center space-x-2 text-gray-500 hover:text-white transition-all group">
            <FaArrowLeft className="group-hover:-translate-x-1 transition-transform" /> 
            <span className="text-sm font-bold uppercase tracking-tighter">Exit Studio</span>
          </button>
          <div className="flex items-center space-x-3 bg-white/5 px-4 py-2 rounded-2xl border border-white/10">
             <div className="w-2 h-2 rounded-full bg-green-500 animate-pulse" />
             <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">AI Core Active • LKR</span>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
          <div className="lg:col-span-7 space-y-8">
            <div className="bg-gray-900/50 border border-white/10 rounded-[2.5rem] p-8 shadow-2xl relative">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-xl font-black uppercase tracking-tighter flex items-center">
                  <FaLightbulb className="text-[#d4af37] mr-3" /> Step 1: Define Design
                </h2>
                <button 
                  onClick={handleEnhancePrompt}
                  className="text-xs font-bold bg-[#d4af37]/10 text-[#d4af37] px-4 py-2 rounded-xl border border-[#d4af37]/20 hover:bg-[#d4af37]/20 transition-all flex items-center"
                >
                  <FaMagic className="mr-2" /> Magic Enhance
                </button>
              </div>

              <textarea
                value={designPrompt}
                onChange={(e) => setDesignPrompt(e.target.value)}
                placeholder="Ex: A heavy gold bangle with swan motifs..."
                className="w-full h-32 px-6 py-5 bg-black/50 border border-white/5 rounded-3xl focus:outline-none focus:border-[#d4af37]/50 transition-all text-lg placeholder:text-gray-700"
              />

              <div className="mt-8 space-y-6">
                <div>
                   <p className="text-[10px] font-black text-gray-500 uppercase mb-3 tracking-widest">Style Keywords</p>
                   <div className="flex flex-wrap gap-2">
                     {promptSuggestions.styles.map(s => (
                       <button key={s} onClick={() => {setDesignPrompt(prev => prev + " " + s); setSelectedStyle(s)}} className="px-3 py-1.5 bg-white/5 border border-white/5 rounded-lg text-[11px] hover:border-[#d4af37]/40 hover:text-[#d4af37] transition-all">+{s}</button>
                     ))}
                   </div>
                </div>
                <div>
                   <p className="text-[10px] font-black text-gray-500 uppercase mb-3 tracking-widest">Gems & Stones</p>
                   <div className="flex flex-wrap gap-2">
                     {promptSuggestions.stones.map(s => (
                       <button key={s} onClick={() => {setDesignPrompt(prev => prev + " " + s); setSelectedStone(s)}} className="px-3 py-1.5 bg-white/5 border border-white/5 rounded-lg text-[11px] hover:border-blue-400/40 hover:text-blue-400 transition-all">+{s}</button>
                     ))}
                   </div>
                </div>
              </div>
            </div>

            <div className="bg-gray-900/50 border border-white/10 rounded-[2.5rem] p-8">
              <h2 className="text-xl font-black uppercase tracking-tighter mb-6 flex items-center">
                <FaRing className="text-[#d4af37] mr-3" /> Step 2: Select Type
              </h2>
              <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                {designTypes.map((t) => (
                  <button key={t.id} onClick={() => setDesignType(t.id)} className={`p-5 rounded-3xl border flex flex-col items-center gap-3 transition-all ${designType === t.id ? 'bg-[#d4af37] border-[#d4af37] text-black shadow-lg shadow-[#d4af37]/20' : 'bg-white/5 border-white/5 text-gray-500 hover:border-white/20'}`}>
                    <t.icon className="text-2xl" />
                    <span className="text-[10px] font-black uppercase">{t.name}</span>
                  </button>
                ))}
              </div>
            </div>

            <button
              onClick={handleGenerateDesign}
              disabled={generating || !designPrompt}
              className="w-full py-6 rounded-[2rem] font-black text-xl bg-gradient-to-r from-[#d4af37] to-[#f4d03f] text-black shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-20 flex items-center justify-center space-x-4"
            >
              {generating ? <><FaSync className="animate-spin" /> <span>Forging In Progress...</span></> : <><FaStar /> <span>Forge Design</span></>}
            </button>
          </div>

          <div className="lg:col-span-5 space-y-8">
            <div className="bg-gray-900/50 border border-white/10 rounded-[3rem] p-4 shadow-2xl overflow-hidden">
               <div className="aspect-square rounded-[2.5rem] bg-black border border-white/5 overflow-hidden relative group">
                  {currentDesign?.imageUrl && !imageError ? (
                    <motion.img initial={{ opacity: 0 }} animate={{ opacity: 1 }} src={currentDesign.imageUrl} className="w-full h-full object-cover" onError={() => setImageError(true)} />
                  ) : (
                    <div className="w-full h-full flex flex-col items-center justify-center text-gray-800 italic">
                      <FaCube className="text-6xl mb-4 opacity-10" />
                      <span className="text-xs uppercase tracking-[0.3em] opacity-30">Awaiting Forge</span>
                    </div>
                  )}
                  
                  {generating && (
                    <div className="absolute inset-0 bg-black/80 backdrop-blur-md flex flex-col items-center justify-center space-y-4">
                        <div className="w-16 h-16 border-t-2 border-[#d4af37] rounded-full animate-spin" />
                        <p className="text-[10px] font-black uppercase tracking-widest text-[#d4af37]">Calculating Facets...</p>
                    </div>
                  )}
               </div>

               {currentDesign && (
                 <div className="p-6 space-y-6">
                    <div className="flex justify-between items-end">
                      <div>
                        <p className="text-[10px] font-black text-gray-500 uppercase tracking-widest">Estimated Value</p>
                        <p className="text-3xl font-black text-[#d4af37]">Rs. {currentDesign.customizations?.estimatedCost?.toLocaleString()}</p>
                      </div>
                      <div className="text-right">
                         <p className="text-[10px] font-black text-green-500 uppercase">Status</p>
                         <p className="text-xs font-bold">Ready for Production</p>
                      </div>
                    </div>

                    <div className="flex gap-4">
                      <button onClick={handleCreateOrder} disabled={loading} className="flex-[2] py-4 bg-white text-black rounded-2xl font-black uppercase text-xs hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <FaShoppingCart /> Place Order
                      </button>
                      <button className="flex-1 py-4 bg-white/5 border border-white/10 rounded-2xl font-black uppercase text-[10px] hover:bg-white/10 transition-all">
                        <FaFileInvoice className="inline mr-2" /> Quote
                      </button>
                    </div>
                 </div>
               )}
            </div>

            <div className="bg-gray-900/30 border border-white/5 rounded-[2.5rem] p-6">
              <h3 className="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-6 flex items-center">
                <FaHistory className="mr-2" /> Recent Forgings
              </h3>
              <div className="space-y-4">
                {designHistory.slice(0, 3).map((d) => (
                  <div key={d._id} onClick={() => setCurrentDesign(d)} className="flex items-center gap-4 p-3 bg-black/30 rounded-2xl cursor-pointer hover:bg-white/5 transition-all border border-transparent hover:border-white/5">
                    <img src={d.imageUrl} className="w-14 h-14 rounded-xl object-cover" />
                    <div className="flex-1 min-w-0">
                      <p className="text-[11px] font-bold truncate uppercase">{d.title}</p>
                      <p className="text-[10px] text-[#d4af37] font-black">Rs. {d.customizations?.estimatedCost?.toLocaleString()}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>

        <AnimatePresence>
          {(error || success) && !showOrderSuccess && (
            <motion.div initial={{ y: 100 }} animate={{ y: 0 }} exit={{ y: 100 }} className="fixed bottom-10 left-0 right-0 flex justify-center z-50 pointer-events-none">
               <div className={`px-8 py-4 rounded-full border shadow-2xl flex items-center gap-3 pointer-events-auto ${error ? 'bg-red-950 border-red-500/50 text-red-200' : 'bg-gray-900 border-[#d4af37]/50 text-[#d4af37]'}`}>
                 {error ? <FaTimes /> : <FaCheck />}
                 <span className="text-xs font-bold uppercase tracking-widest">{error || success}</span>
               </div>
            </motion.div>
          )}
        </AnimatePresence>

      </PageShell>
    </div>
  );
}