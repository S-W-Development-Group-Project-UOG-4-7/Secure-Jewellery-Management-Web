import express from "express";
import mongoose from "mongoose";
import cors from "cors";
import dotenv from "dotenv";
import cookieParser from "cookie-parser"; // ✅ Added for sessions

import supplierRoutes from "./routes/supplierRoutes.js";
import deliveryRoutes from "./routes/deliveryRoutes.js";
import stockRoutes from "./routes/stockRoutes.js";
import authRoutes from "./routes/authRoutes.js";

import Design from "./models/Design.js";
import Order from "./models/Order.js";

dotenv.config();
const app = express();

// ✅ CORS: Credentials must be true for cookies
app.use(cors({
  origin: process.env.CLIENT_URL || "http://localhost:5173",
  credentials: true
}));

app.use(cookieParser()); // ✅ Initialize Cookie Parser
app.use(express.json({ limit: "20mb" })); // Support large Base64 AI images

// ✅ MongoDB Connection
mongoose
  .connect(process.env.MONGO_URI)
  .then(() => console.log("✅ Matale Studio Database Connected"))
  .catch((err) => console.log("❌ MongoDB Error:", err.message));

// ✅ Routes
app.use("/api/auth", authRoutes);
app.use("/api/users", authRoutes); 
app.use("/api/suppliers", supplierRoutes);
app.use("/api/deliveries", deliveryRoutes);
app.use("/api/stock", stockRoutes);

// --- DESIGN ROUTES ---
app.get("/api/designs/history", async (req, res) => {
  try {
    const history = await Design.find().sort({ createdAt: -1 }).limit(10);
    res.status(200).json({ success: true, data: history });
  } catch (error) {
    res.status(500).json({ success: false, error: "Failed to fetch history" });
  }
});

app.post("/api/designs/generate", async (req, res) => {
  try {
    const { designPrompt, designType, customizations } = req.body;
    const cleanPrompt = encodeURIComponent(`Professional Sri Lankan jewelry, ${designType}, ${designPrompt}, white background, 8k.`);
    const pollUrl = `https://image.pollinations.ai/prompt/${cleanPrompt}?width=1024&height=1024&nologo=true&seed=${Math.floor(Math.random() * 100000)}`;

    const response = await fetch(pollUrl);
    const arrayBuffer = await response.arrayBuffer();
    const base64Image = `data:image/png;base64,${Buffer.from(arrayBuffer).toString("base64")}`;

    // LKR Pricing logic (Matale Local Rates)
    const lkrPrice = Math.floor(Math.random() * (450000 - 85000 + 1)) + 85000;

    const savedDesign = await Design.create({
      title: designPrompt.substring(0, 40),
      prompt: designPrompt,
      type: designType,
      imageUrl: base64Image,
      customizations: { ...customizations, estimatedCost: lkrPrice }
    });

    res.status(200).json({ success: true, data: savedDesign });
  } catch (error) {
    res.status(500).json({ success: false, error: "AI Generation failed" });
  }
});

// --- ORDER ROUTES ---
app.post("/api/orders", async (req, res) => {
  try {
    const orderData = {
      ...req.body,
      user: req.body.user || "659f1234567890abcdef1234" // Replace with req.user.id in production
    };
    const newOrder = await Order.create(orderData);
    res.status(201).json({ success: true, data: newOrder });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.get("/api/orders/my", async (req, res) => {
  try {
    const orders = await Order.find().populate('design').sort({ createdAt: -1 });
    res.status(200).json({ success: true, data: orders });
  } catch (error) {
    res.status(500).json({ success: false, error: "Failed to fetch orders" });
  }
});

app.get("/", (req, res) => res.send("🚀 SJM API with Session Support Online"));

const PORT = process.env.PORT || 5001;
app.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));