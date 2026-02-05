import express from "express";
import mongoose from "mongoose";
import cors from "cors";
import dotenv from "dotenv";
import { OpenAI } from "openai";

// Import Routes
import supplierRoutes from "./routes/supplierRoutes.js";
import deliveryRoutes from "./routes/deliveryRoutes.js";
import stockRoutes from "./routes/stockRoutes.js";
import authRoutes from "./routes/authRoutes.js";

dotenv.config();
const app = express();

// --- MIDDLEWARE ---

// Security: Enable CORS for your React frontend
app.use(cors({
  origin: process.env.CLIENT_URL || "http://localhost:5173",
  credentials: true
}));

// Increase limit for JSON payloads (useful for base64 strings or complex design objects)
app.use(express.json({ limit: "10mb" }));

// --- AI INITIALIZATION ---

let openai;
if (process.env.OPENAI_API_KEY) {
  openai = new OpenAI({
    apiKey: process.env.OPENAI_API_KEY,
  });
  console.log("✅ OpenAI API initialized");
} else {
  console.warn("⚠️  OPENAI_API_KEY not found. AI features disabled.");
}

// --- DATABASE CONNECTION ---

mongoose
  .connect(process.env.MONGO_URI)
  .then(() => console.log("✅ MongoDB Atlas Connected"))
  .catch((err) => console.log("❌ MongoDB Error:", err.message));

// --- EXISTING ROUTES ---

app.use("/api/auth", authRoutes);
app.use("/api/suppliers", supplierRoutes);
app.use("/api/deliveries", deliveryRoutes);
app.use("/api/stock", stockRoutes);

// --- AI DESIGN STUDIO ENDPOINTS ---

/**
 * @route   POST /api/designs/generate
 * @desc    Generate a jewelry design image using OpenAI DALL-E 3
 */
app.post("/api/designs/generate", async (req, res) => {
  if (!openai) {
    return res.status(503).json({
      success: false,
      error: "AI service not configured. Please add OPENAI_API_KEY to .env"
    });
  }

  try {
    const {
      designPrompt,
      designType = "ring",
      materials = [],
      gemstones = [],
      customizations = {}
    } = req.body;

    if (!designPrompt?.trim()) {
      return res.status(400).json({
        success: false,
        error: "Design prompt is required"
      });
    }

    console.log(`📸 Generating ${designType} design: "${designPrompt.substring(0, 50)}..."`);

    // Build the descriptive prompt for DALL-E
    const materialNames = materials.map(m => m.name).join(", ") || "precious metal";
    const gemstoneNames = gemstones.map(g => g.name).join(", ") || "clear stones";
    
    const aiPrompt = `
      Create a photorealistic, professional product image of a ${designType} for a jewelry e-commerce website.
      
      SPECIFICATIONS:
      - Type: ${designType}
      - Material: ${materialNames}
      - Gemstones: ${gemstoneNames}
      - Style: ${customizations.style || "modern"}
      - Complexity: ${customizations.complexity || "medium"}
      - Additional details: "${designPrompt}"

      IMAGE REQUIREMENTS:
      - Studio photography on clean white background
      - Professional jewelry lighting to highlight reflections
      - Accurate material textures (metal shine, gemstone sparkle)
      - Commercial e-commerce quality
      - No watermarks, no text, no borders
      - Focus on the single piece of jewelry
    `;

    // Call OpenAI Image Generation API
    const response = await openai.images.generate({
      model: "dall-e-3",
      prompt: aiPrompt,
      n: 1,
      size: "1024x1024",
      quality: "standard", 
      style: "natural"
    });

    const imageUrl = response.data[0].url;
    const revisedPrompt = response.data[0].revised_prompt || aiPrompt;

    console.log("✅ AI design generated successfully");

    // Construct the design object for the frontend
    const designData = {
      id: Date.now().toString(),
      title: designPrompt.substring(0, 50) + (designPrompt.length > 50 ? "..." : ""),
      originalPrompt: designPrompt,
      aiPrompt: revisedPrompt,
      type: designType,
      materials: materials,
      gemstones: gemstones,
      customizations: customizations,
      imageUrl: imageUrl,
      estimatedCost: Math.floor(Math.random() * 5000) + 1000, // Placeholder calculation
      complexity: customizations.complexity || "medium",
      createdAt: new Date().toISOString(),
      isAIGenerated: true
    };

    res.status(200).json({
      success: true,
      message: "AI design generated successfully",
      data: designData
    });

  } catch (error) {
    console.error("❌ OpenAI API Error:", error.message);
    
    let errorMessage = "AI image generation failed";
    let statusCode = 500;
    
    if (error.message.includes("billing")) {
      errorMessage = "OpenAI billing issue. Please check your account.";
      statusCode = 402;
    } else if (error.message.includes("content policy")) {
      errorMessage = "Prompt was rejected by content safety system.";
      statusCode = 400;
    }

    res.status(statusCode).json({
      success: false,
      error: errorMessage,
      details: error.message
    });
  }
});

/**
 * @route   GET /api/designs/history
 * @desc    Fetch design history (Mock logic until DB model is ready)
 */
app.get("/api/designs/history", async (req, res) => {
  try {
    res.status(200).json({
      success: true,
      data: [], // DB integration pending
      message: "Design history (DB integration pending)"
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      error: "Failed to fetch design history"
    });
  }
});

/**
 * @route   POST /api/designs/save
 * @desc    Persist a generated design to MongoDB
 */
app.post("/api/designs/save", async (req, res) => {
  try {
    const design = req.body;
    
    // Log the action; logic here will change once Design Model is defined
    console.log("💾 Design saved (DB integration pending):", design.title);
    
    res.status(200).json({
      success: true,
      message: "Design saved successfully",
      id: design.id || Date.now().toString()
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      error: "Failed to save design"
    });
  }
});

// --- BASE ENDPOINT ---

app.get("/", (req, res) => res.send("🚀 Jewelry Management API with AI Design Studio"));

// --- SERVER START ---

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`🚀 Server running on port ${PORT}`);
  console.log(`🌐 AI Design endpoint: POST http://localhost:${PORT}/api/designs/generate`);
});