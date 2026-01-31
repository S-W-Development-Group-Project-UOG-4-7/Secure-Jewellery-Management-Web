import express from "express";
import {
  getSuppliers,
  getSupplierById,
  createSupplier,
  updateSupplier,
  deleteSupplier,
} from "../controllers/supplierController.js";

import { protect, adminOnly } from "../middlewares/authMiddleware.js";

const router = express.Router();

// ✅ Admin-only supplier CRUD
router.get("/", protect, adminOnly, getSuppliers);
router.get("/:id", protect, adminOnly, getSupplierById);
router.post("/", protect, adminOnly, createSupplier);
router.put("/:id", protect, adminOnly, updateSupplier);
router.delete("/:id", protect, adminOnly, deleteSupplier);

export default router;
