import CustomOrder from "../models/CustomOrder.js";
import OrderStatusHistory from "../models/OrderStatusHistory.js";

// helper: record status history
const pushHistory = async ({ orderId, status, comment = "", userId = null }) => {
  await OrderStatusHistory.create({
    order: orderId,
    status,
    comment,
    updatedBy: userId,
  });
};

// ✅ CUSTOMER: Create order + upload designs
// POST /api/orders
export const createOrder = async (req, res) => {
  try {
    const { title, requirements, notes } = req.body || {};

    if (!title || !title.trim()) {
      return res.status(400).json({ message: "Title is required" });
    }

    const files = req.files || [];
    const designFiles = files.map((f) => ({
      filename: f.filename,
      url: `/uploads/designs/${f.filename}`,
    }));

    const order = await CustomOrder.create({
      customer: req.user._id,
      title: title.trim(),
      requirements: (requirements || "").trim(),
      notes: (notes || "").trim(),
      designFiles,
      status: "pending",
    });

    await pushHistory({
      orderId: order._id,
      status: "pending",
      comment: "Order submitted",
      userId: req.user._id,
    });

    return res.status(201).json({
      message: "Custom order submitted ✅",
      data: order,
    });
  } catch (err) {
    console.error("createOrder error:", err);
    return res.status(500).json({ message: err.message || "Server error" });
  }
};

// ✅ CUSTOMER: My orders
// GET /api/orders/my
export const getMyOrders = async (req, res) => {
  try {
    const list = await CustomOrder.find({ customer: req.user._id })
      .sort({ createdAt: -1 })
      .lean();

    return res.status(200).json({ data: list });
  } catch (err) {
    console.error("getMyOrders error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// ✅ CUSTOMER: Timeline
// GET /api/orders/:id/timeline
export const getOrderTimeline = async (req, res) => {
  try {
    const { id } = req.params;

    const order = await CustomOrder.findById(id).lean();
    if (!order) return res.status(404).json({ message: "Order not found" });

    // customer can only access own order
    const isOwner = String(order.customer) === String(req.user._id);
    const isAdmin = req.user.role === "admin";
    if (!isOwner && !isAdmin) {
      return res.status(403).json({ message: "Forbidden" });
    }

    const history = await OrderStatusHistory.find({ order: id })
      .sort({ createdAt: 1 })
      .populate("updatedBy", "name email role")
      .lean();

    return res.status(200).json({ data: { order, history } });
  } catch (err) {
    console.error("getOrderTimeline error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// ✅ ADMIN/MANAGER: list orders (optional filters)
// GET /api/orders?status=pending&search=...
export const listOrdersAdmin = async (req, res) => {
  try {
    const { status = "", search = "" } = req.query;

    const filter = {};
    if (status) filter.status = status;

    // basic search by title
    if (search.trim()) {
      filter.title = { $regex: search.trim(), $options: "i" };
    }

    const list = await CustomOrder.find(filter)
      .populate("customer", "name email role")
      .sort({ createdAt: -1 });

    return res.status(200).json({ data: list });
  } catch (err) {
    console.error("listOrdersAdmin error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// ✅ ADMIN/MANAGER: approve
// PUT /api/orders/:id/approve
export const approveOrder = async (req, res) => {
  try {
    const { id } = req.params;
    const { managerComment = "" } = req.body || {};

    const order = await CustomOrder.findById(id);
    if (!order) return res.status(404).json({ message: "Order not found" });

    order.status = "approved";
    order.managerComment = managerComment.trim();
    order.approvedBy = req.user._id;
    order.approvedAt = new Date();
    order.rejectedBy = null;
    order.rejectedAt = null;

    await order.save();

    await pushHistory({
      orderId: order._id,
      status: "approved",
      comment: managerComment.trim() || "Approved",
      userId: req.user._id,
    });

    return res.status(200).json({ message: "Order approved ✅", data: order });
  } catch (err) {
    console.error("approveOrder error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// ✅ ADMIN/MANAGER: reject
// PUT /api/orders/:id/reject
export const rejectOrder = async (req, res) => {
  try {
    const { id } = req.params;
    const { managerComment = "" } = req.body || {};

    const order = await CustomOrder.findById(id);
    if (!order) return res.status(404).json({ message: "Order not found" });

    order.status = "rejected";
    order.managerComment = managerComment.trim();
    order.rejectedBy = req.user._id;
    order.rejectedAt = new Date();
    order.approvedBy = null;
    order.approvedAt = null;

    await order.save();

    await pushHistory({
      orderId: order._id,
      status: "rejected",
      comment: managerComment.trim() || "Rejected",
      userId: req.user._id,
    });

    return res.status(200).json({ message: "Order rejected ❌", data: order });
  } catch (err) {
    console.error("rejectOrder error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// ✅ ADMIN/MANAGER: update status (tracking)
// PUT /api/orders/:id/status
export const updateOrderStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const { status, comment = "" } = req.body || {};

    const allowed = ["in_progress", "ready", "delivered", "approved", "rejected", "pending", "requested"];
    if (!allowed.includes(status)) {
      return res.status(400).json({ message: "Invalid status" });
    }

    const order = await CustomOrder.findById(id);
    if (!order) return res.status(404).json({ message: "Order not found" });

    order.status = status;
    await order.save();

    await pushHistory({
      orderId: order._id,
      status,
      comment: comment.trim(),
      userId: req.user._id,
    });

    return res.status(200).json({ message: "Status updated ✅", data: order });
  } catch (err) {
    console.error("updateOrderStatus error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};
