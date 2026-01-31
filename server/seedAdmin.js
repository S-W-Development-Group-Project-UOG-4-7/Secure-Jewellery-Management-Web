import dotenv from "dotenv";
import mongoose from "mongoose";
import bcrypt from "bcryptjs";
import User from "./models/User.js";

dotenv.config();

async function seedAdmin() {
  try {
    await mongoose.connect(process.env.MONGO_URI);
    console.log("✅ MongoDB connected");

    const email = "adminn@sjm.com";
    const plainPassword = "Admin@123"; // change if you want

    const existing = await User.findOne({ email });
    if (existing) {
      console.log("⚠️ Admin already exists:", email);
      process.exit(0);
    }

    const hashed = await bcrypt.hash(plainPassword, 10);

    const admin = await User.create({
      name: "System Admin",
      email,
      password: hashed,
      role: "admin",
      phone: "",
      address: "",
    });

    console.log("✅ Admin created!");
    console.log("Email:", admin.email);
    console.log("Password:", plainPassword);
    process.exit(0);
  } catch (err) {
    console.log("❌ Seed error:", err);
    process.exit(1);
  }
}

seedAdmin();
