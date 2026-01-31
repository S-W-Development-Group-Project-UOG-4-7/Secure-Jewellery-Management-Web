import express from "express";
import { protect, adminOnly } from "../middlewares/authMiddleware.js";

import uploadInvoice from "../middlewares/uploadInvoice.js";

import {
  createDelivery,
  getDeliveries,
  getDeliveryById,
  updateDelivery,
  deleteDelivery,
} from "../controllers/deliveryController.js";

const router = express.Router();

/**
 * ✅ ADMIN ONLY
 */
router.get("/", protect, adminOnly, getDeliveries);
router.get("/:id", protect, adminOnly, getDeliveryById);

router.post(
  "/",
  protect,
  adminOnly,
  uploadInvoice.single("invoice"), // ✅ must match frontend key
  createDelivery
);

router.put(
  "/:id",
  protect,
  adminOnly,
  uploadInvoice.single("invoice"),
  updateDelivery
);

router.delete("/:id", protect, adminOnly, deleteDelivery);

export default router;
