import express from "express";
import { protect, adminOnly } from "../middlewares/authMiddleware.js";
import {
  getMe,
  listUsers,
  createUser,
  updateUserRole,
  toggleUserActive,
} from "../controllers/userController.js";

const router = express.Router();

// ✅ logged-in user's profile
router.get("/me", protect, getMe);

// ✅ admin user module
router.get("/", protect, adminOnly, listUsers);
router.post("/", protect, adminOnly, createUser);
router.put("/:id/role", protect, adminOnly, updateUserRole);
router.put("/:id/toggle-active", protect, adminOnly, toggleUserActive);

export default router;
