import bcrypt from "bcryptjs";
import jwt from "jsonwebtoken";
import User from "../models/User.js";
import { sendEmail } from "../utils/sendEmail.js";

const generateOTP = () => Math.floor(100000 + Math.random() * 900000).toString();
const OTP_EXPIRY_MINUTES = 5;
const normEmail = (email) => (email || "").toLowerCase().trim();

export const registerCustomer = async (req, res) => {
  try {
    const { name, email, password } = req.body || {};
    if (!name || !email || !password) return res.status(400).json({ message: "Required fields missing" });
    
    const emailNorm = normEmail(email);
    const existingUser = await User.findOne({ email: emailNorm });
    if (existingUser) return res.status(409).json({ message: "Email already exists" });

    const hashedPassword = await bcrypt.hash(password, 10);
    const user = await User.create({
      name: name.trim(), email: emailNorm, password: hashedPassword, role: "customer", otpVerified: false
    });

    return res.status(201).json({ message: "Success. Please login.", userId: user._id });
  } catch (err) {
    return res.status(500).json({ message: "Server error" });
  }
};

export const loginUser = async (req, res) => {
  try {
    const { email, password } = req.body || {};
    const user = await User.findOne({ email: normEmail(email) });
    if (!user || !(await bcrypt.compare(password, user.password))) {
      return res.status(401).json({ message: "Invalid credentials" });
    }

    user.otpVerified = false;
    await user.save();

    return res.status(200).json({ message: "Login success. OTP required.", userId: user._id });
  } catch (err) {
    return res.status(500).json({ message: "Server error" });
  }
};

export const sendOtp = async (req, res) => {
  try {
    const { userId } = req.body;
    const user = await User.findById(userId);
    const otp = generateOTP();
    user.otp = await bcrypt.hash(otp, 10);
    user.otpExpires = new Date(Date.now() + OTP_EXPIRY_MINUTES * 60 * 1000);
    await user.save();

    console.log(`✅ OTP: ${user.email} = ${otp}`);
    await sendEmail(user.email, "SJM Code", `<h1>${otp}</h1>`);

    return res.status(200).json({ message: "OTP sent" });
  } catch (err) {
    return res.status(500).json({ message: "Failed to send" });
  }
};

export const verifyOtp = async (req, res) => {
  try {
    const { userId, otp } = req.body;
    const user = await User.findById(userId);
    
    const otpMatch = await bcrypt.compare(String(otp), user.otp);
    if (!otpMatch || user.otpExpires < new Date()) return res.status(401).json({ message: "Invalid/Expired OTP" });

    user.otpVerified = true;
    user.otp = null;
    await user.save();

    const token = jwt.sign({ id: user._id, role: user.role }, process.env.JWT_SECRET, { expiresIn: "7d" });

    // ✅ SESSION: Set HTTP-Only Cookie
    res.cookie("token", token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "strict",
      maxAge: 7 * 24 * 60 * 60 * 1000 // 7 days
    });

    return res.status(200).json({
      success: true,
      token, // Kept for legacy support in localStorage if needed
      user: { id: user._id, name: user.name, email: user.email, role: user.role }
    });
  } catch (err) {
    return res.status(500).json({ message: "Server error" });
  }
};

export const getMe = async (req, res) => {
    if (!req.user) return res.status(401).json({ message: "Unauthorized" });
    res.status(200).json({ success: true, data: req.user });
};

// ✅ ADDED: Logout to clear session
export const logoutUser = (req, res) => {
  res.cookie("token", "", { maxAge: 1 });
  res.status(200).json({ success: true, message: "Logged out" });
};