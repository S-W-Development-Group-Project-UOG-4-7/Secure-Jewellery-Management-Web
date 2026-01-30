import express from "express";
import { protect, adminOnly } from "../middlewares/authMiddleware.js";

import {
  getUsers,
  addUser,
  updateRole,
  toggleActive,
} from "../controllers/adminUserController.js";

const router = express.Router();

router.get("/", protect, adminOnly, getUsers);
router.post("/", protect, adminOnly, addUser);
router.put("/:id/role", protect, adminOnly, updateRole);
router.put("/:id/toggle", protect, adminOnly, toggleActive);

export default router;
