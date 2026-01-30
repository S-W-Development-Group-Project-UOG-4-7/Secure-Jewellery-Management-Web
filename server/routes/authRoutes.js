import express from "express";
import {
  loginUser,
  sendOtp,
  verifyOtp,
  registerCustomer,
  getMe,
} from "../controllers/authController.js";

import { protect } from "../middlewares/authMiddleware.js";

const router = express.Router();

// ✅ Auth flow
router.post("/register", registerCustomer);
router.post("/login", loginUser);
router.post("/send-otp", sendOtp);
router.post("/verify-otp", verifyOtp);

// ✅ Profile endpoint 
// This will respond to BOTH /api/auth/me and /api/users/me (due to server.js mapping)
router.get("/me", protect, getMe);

export default router;