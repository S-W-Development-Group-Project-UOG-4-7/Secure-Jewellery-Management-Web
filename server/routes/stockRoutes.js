import express from "express";
import { protect, adminOnly } from "../middlewares/authMiddleware.js";
import {
  getStock,
  updateStockQuantity,
  getStockLogs,
} from "../controllers/stockController.js";

const router = express.Router();

/**
 * ✅ STOCK ROUTES
 * Base path in server.js should be:
 * app.use("/api/stock", stockRoutes);
 */

// ✅ Get stock logs (ADMIN)
router.get("/logs/all", protect, adminOnly, getStockLogs);

// ✅ Get all stock items (ADMIN)
router.get("/", protect, adminOnly, getStock);

// ✅ Update stock quantity (ADMIN)
router.put("/:id", protect, adminOnly, updateStockQuantity);

export default router;
