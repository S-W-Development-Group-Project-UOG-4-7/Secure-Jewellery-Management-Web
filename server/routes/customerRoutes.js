import express from "express";
import {
  getCustomers,
  getCustomerById,
  createCustomer,
  updateCustomer,
  deleteCustomer,
  getMyProfile,
  updateMyProfile,
  changeMyPassword,
} from "../controllers/customerController.js";

import { protect, adminOnly } from "../middlewares/authMiddleware.js";

const router = express.Router();

// ✅ PROFILE: logged-in user (admin/customer both)
router.get("/me/profile", protect, getMyProfile);
router.put("/me/profile", protect, updateMyProfile);
router.put("/me/password", protect, changeMyPassword);

// ✅ ADMIN ONLY: customer CRUD
router.get("/", protect, adminOnly, getCustomers);
router.post("/", protect, adminOnly, createCustomer);
router.get("/:id", protect, adminOnly, getCustomerById);
router.put("/:id", protect, adminOnly, updateCustomer);
router.delete("/:id", protect, adminOnly, deleteCustomer);

export default router;
