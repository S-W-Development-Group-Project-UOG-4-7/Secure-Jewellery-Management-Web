import mongoose from "mongoose";
import bcrypt from "bcryptjs";
import dotenv from "dotenv";

dotenv.config();

// ✅ Import your models (make sure paths match)
import User from "./models/User.js";
import Jewellery from "./models/Jewellery.js";
import LockerVerification from "./models/LockerVerification.js";

// If you already have these models:
import Supplier from "./models/Supplier.js";
import Stock from "./models/Stock.js";
import Delivery from "./models/Delivery.js";

const MONGO_URI = process.env.MONGO_URI || process.env.MONGODB_URI;

const run = async () => {
  try {
    if (!MONGO_URI) throw new Error("MONGO_URI not found in .env");

    await mongoose.connect(MONGO_URI);
    console.log("✅ Mongo connected for seeding");

    // ✅ CLEAN (optional)
    await Promise.all([
      User.deleteMany({}),
      Jewellery.deleteMany({}),
      LockerVerification.deleteMany({}),
      Supplier.deleteMany({}),
      Stock.deleteMany({}),
      Delivery.deleteMany({}),
    ]);

    // ✅ USERS
    const adminPass = await bcrypt.hash("Admin123!", 10);
    const custPass = await bcrypt.hash("Customer123!", 10);

    const admin = await User.create({
      name: "Admin User",
      email: "admin@sjm.com",
      password: adminPass,
      role: "admin",
      isActive: true,
      otpVerified: true,
    });

    const customer = await User.create({
      name: "Customer One",
      email: "customer@sjm.com",
      password: custPass,
      role: "customer",
      isActive: true,
      otpVerified: true,
    });

    // ✅ JEWELLERY
    const jewelleryList = await Jewellery.insertMany([
      {
        name: "Gold Ring 22K",
        type: "Ring",
        weight: 4.2,
        quantity: 5,
        price: 120000,
        description: "22K gold ring",
        createdBy: admin._id,
      },
      {
        name: "Silver Chain",
        type: "Chain",
        weight: 10.5,
        quantity: 2,
        price: 35000,
        description: "Sterling silver chain",
        createdBy: admin._id,
      },
      {
        name: "Diamond Earrings",
        type: "Earrings",
        weight: 2.1,
        quantity: 1,
        price: 240000,
        description: "Diamond set earrings",
        createdBy: admin._id,
      },
    ]);

    // ✅ SUPPLIERS
    const suppliers = await Supplier.insertMany([
      {
        name: "Colombo Gems Pvt Ltd",
        email: "colombogems@mail.com",
        phone: "0771234567",
        address: "Colombo, Sri Lanka",
      },
      {
        name: "Kandy Gold Traders",
        email: "kandygold@mail.com",
        phone: "0719876543",
        address: "Kandy, Sri Lanka",
      },
    ]);

    // ✅ STOCK
    const stockItems = await Stock.insertMany([
      {
        itemName: "Gold Ring 22K",
        quantity: 5,
        updatedBy: admin._id,
      },
      {
        itemName: "Silver Chain",
        quantity: 2,
        updatedBy: admin._id,
      },
      {
        itemName: "Diamond Earrings",
        quantity: 1,
        updatedBy: admin._id,
      },
    ]);

    // ✅ DELIVERIES (basic sample)
    const deliveries = await Delivery.insertMany([
      {
        supplier: suppliers[0]._id,
        supplierName: suppliers[0].name,
        itemName: "Gold Ring 22K",
        quantity: 5,
        date: new Date(),
        invoiceUrl: "",
      },
      {
        supplier: suppliers[1]._id,
        supplierName: suppliers[1].name,
        itemName: "Silver Chain",
        quantity: 2,
        date: new Date(),
        invoiceUrl: "",
      },
    ]);

    // ✅ LOCKER VERIFICATION (Before + After)
    await LockerVerification.insertMany([
      {
        jewelleryId: jewelleryList[0]._id,
        lockerNumber: "L-001",
        stage: "before",
        verifiedBy: admin._id,
        notes: "Before storage check OK",
        result: "matched",
        proofImage: "",
      },
      {
        jewelleryId: jewelleryList[0]._id,
        lockerNumber: "L-001",
        stage: "after",
        verifiedBy: admin._id,
        notes: "After storage check OK",
        result: "matched",
        proofImage: "",
      },
      {
        jewelleryId: jewelleryList[2]._id,
        lockerNumber: "L-002",
        stage: "before",
        verifiedBy: admin._id,
        notes: "Missing stone suspicion",
        result: "mismatch",
        mismatchReason: "Stone count mismatch",
        proofImage: "",
      },
    ]);

    console.log("✅ Seed completed!");
    console.log("✅ Admin login: admin@sjm.com / Admin123!");
    console.log("✅ Customer login: customer@sjm.com / Customer123!");

    process.exit(0);
  } catch (err) {
    console.error("❌ Seed error:", err.message);
    process.exit(1);
  }
};

run();
