import mongoose from "mongoose";

const orderSchema = new mongoose.Schema({
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "User",
    required: true,
  },
  design: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "Design",
    required: true,
  },
  orderNumber: {
    type: String,
    required: true,
    unique: true,
    default: () => `SJM-${Math.floor(100000 + Math.random() * 900000)}`,
  },
  status: {
    type: String,
    enum: ["Pending", "Processing", "Confirmed", "Shipped", "Delivered", "Cancelled"],
    default: "Pending",
  },
  totalPrice: {
    type: Number,
    required: true,
  },
  shippingAddress: {
    street: String,
    city: String,
    state: String,
    zipCode: String,
    country: { type: String, default: "Sri Lanka" },
  },
  paymentStatus: {
    type: String,
    enum: ["Unpaid", "Paid", "Refunded"],
    default: "Unpaid",
  },
  notes: String,
  createdAt: {
    type: Date,
    default: Date.now,
  },
});

const Order = mongoose.model("Order", orderSchema);
export default Order;