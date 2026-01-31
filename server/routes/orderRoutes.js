import express from "express";
import { protect, adminOnly } from "../middlewares/authMiddleware.js";
import { uploadDesign } from "../middlewares/uploadDesign.js";
import {
  createOrder,
  getMyOrders,
  getOrderTimeline,
  listOrdersAdmin,
  approveOrder,
  rejectOrder,
  updateOrderStatus,
} from "../controllers/orderController.js";

const router = express.Router();

// customer create order (with file upload)
router.post("/", protect, uploadDesign.array("designFiles", 5), createOrder);

// customer
router.get("/my", protect, getMyOrders);
router.get("/:id/timeline", protect, getOrderTimeline);

// admin/manager
router.get("/", protect, adminOnly, listOrdersAdmin);
router.put("/:id/approve", protect, adminOnly, approveOrder);
router.put("/:id/reject", protect, adminOnly, rejectOrder);
router.put("/:id/status", protect, adminOnly, updateOrderStatus);

export default router;
