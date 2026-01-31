// client/src/pages/Otp.jsx
import React, { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";
import API from "../utils/api";
import Countdown from "react-countdown"; // Optional: install with npm install react-countdown

export default function Otp() {
  const navigate = useNavigate();
  const [otp, setOtp] = useState(["", "", "", "", "", ""]);
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const [resendLoading, setResendLoading] = useState(false);
  const [resendCountdown, setResendCountdown] = useState(60);
  const [attempts, setAttempts] = useState(0);
  const [isVerified, setIsVerified] = useState(false);
  
  const userId = localStorage.getItem("userId");
  const userEmail = localStorage.getItem("userEmail") || "your email";
  
  // Create refs for each OTP input
  const inputRefs = useRef([]);

  // Security check on mount
  useEffect(() => {
    if (!userId) {
      navigate("/login");
      return;
    }

    // Auto-send OTP on component mount
    handleSendOtp();
    
    // Start countdown for resend
    const countdownInterval = setInterval(() => {
      setResendCountdown(prev => prev > 0 ? prev - 1 : 0);
    }, 1000);
    
    return () => clearInterval(countdownInterval);
  }, [userId, navigate]);

  // Handle OTP input changes
  const handleOtpChange = (index, value) => {
    // Only allow numbers
    if (value && !/^\d+$/.test(value)) return;
    
    const newOtp = [...otp];
    newOtp[index] = value;
    setOtp(newOtp);
    setErrors({});
    
    // Auto-focus next input
    if (value && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
    
    // Auto-submit when last digit is entered
    if (value && index === 5) {
      const fullOtp = newOtp.join("");
      if (fullOtp.length === 6) {
        setTimeout(() => {
          handleVerifyOtp(fullOtp);
        }, 300);
      }
    }
  };

  // Handle backspace
  const handleKeyDown = (index, e) => {
    if (e.key === "Backspace") {
      if (!otp[index] && index > 0) {
        // Move to previous input if current is empty
        inputRefs.current[index - 1]?.focus();
        const newOtp = [...otp];
        newOtp[index - 1] = "";
        setOtp(newOtp);
      } else if (otp[index]) {
        // Clear current input
        const newOtp = [...otp];
        newOtp[index] = "";
        setOtp(newOtp);
      }
    }
    
    // Navigate with arrow keys
    if (e.key === "ArrowLeft" && index > 0) {
      inputRefs.current[index - 1]?.focus();
    }
    if (e.key === "ArrowRight" && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  // Send OTP function
  const handleSendOtp = async () => {
    try {
      setResendLoading(true);
      setMessage("");
      setErrors({});
      
      await API.post("/auth/send-otp", { userId });
      
      setMessage({
        type: "success",
        text: "Verification code sent successfully! Check your email."
      });
      
      // Reset countdown
      setResendCountdown(60);
      setResendLoading(false);
      
    } catch (error) {
      setErrors({
        server: error?.response?.data?.message || "Failed to send verification code"
      });
      setResendLoading(false);
    }
  };

  // Verify OTP function
  const handleVerifyOtp = async (enteredOtp = otp.join("")) => {
    if (loading || isVerified) return;
    
    // Validation
    if (enteredOtp.length !== 6) {
      setErrors({ otp: "Please enter a 6-digit code" });
      
      // Shake animation on error
      document.getElementById("otpContainer").classList.add("animate-shake");
      setTimeout(() => {
        document.getElementById("otpContainer").classList.remove("animate-shake");
      }, 500);
      return;
    }
    
    try {
      setLoading(true);
      setErrors({});
      setMessage("");
      
      const res = await API.post("/auth/verify-otp", { userId, otp: enteredOtp });
      
      // Save authentication data
      localStorage.setItem("token", res.data.token);
      
      // Get user profile
      const me = await API.get("/users/me");
      const userData = me.data?.data || me.data;
      
      // Save user data
      localStorage.setItem("role", userData.role);
      localStorage.setItem("userData", JSON.stringify(userData));
      
      // Success animation and redirect
      setIsVerified(true);
      setMessage({
        type: "success",
        text: "Verification successful! Redirecting..."
      });
      
      // Animate success state
      document.getElementById("otpContainer").classList.add("animate-success");
      
      // Redirect based on role after delay
      setTimeout(() => {
        if (userData.role === "admin") {
          navigate("/admin-dashboard");
        } else {
          navigate("/customer-dashboard");
        }
      }, 1500);
      
    } catch (error) {
      setAttempts(prev => prev + 1);
      
      const errorMsg = error?.response?.data?.message || "Verification failed";
      setErrors({ server: errorMsg });
      
      // Clear OTP on failure
      setOtp(["", "", "", "", "", ""]);
      inputRefs.current[0]?.focus();
      
      // Shake animation on error
      document.getElementById("otpContainer").classList.add("animate-shake");
      setTimeout(() => {
        document.getElementById("otpContainer").classList.remove("animate-shake");
      }, 500);
      
    } finally {
      setLoading(false);
    }
  };

  // Handle paste OTP
  const handlePaste = (e) => {
    e.preventDefault();
    const pastedData = e.clipboardData.getData("text").slice(0, 6);
    if (/^\d+$/.test(pastedData)) {
      const newOtp = pastedData.split("").slice(0, 6);
      const paddedOtp = [...newOtp, ...Array(6 - newOtp.length).fill("")];
      setOtp(paddedOtp);
      
      // Focus last filled input
      const lastFilledIndex = newOtp.length - 1;
      if (lastFilledIndex >= 0) {
        inputRefs.current[lastFilledIndex]?.focus();
      }
    }
  };

  // Render countdown for resend
  const renderCountdown = ({ seconds, completed }) => {
    if (completed) {
      return <span className="text-[#d4af37]">Ready to resend</span>;
    }
    return <span className="text-gray-400">Resend in {seconds}s</span>;
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black text-white flex items-center justify-center p-4 relative overflow-hidden">
      {/* Animated Background */}
      <div className="absolute inset-0 -z-10">
        <div className="absolute top-1/3 left-1/4 w-72 h-72 bg-gradient-to-r from-[#d4af37]/10 to-transparent rounded-full blur-3xl"></div>
        <div className="absolute bottom-1/3 right-1/4 w-72 h-72 bg-gradient-to-l from-[#f4d03f]/10 to-transparent rounded-full blur-3xl"></div>
      </div>
      
      {/* Grid Pattern */}
      <div className="absolute inset-0 bg-grid-white/[0.02] bg-[size:50px_50px] -z-10"></div>
      
      {/* Floating Elements */}
      <div className="absolute top-10 left-10 w-6 h-6 border border-[#d4af37]/30 rounded-full animate-ping"></div>
      <div className="absolute bottom-10 right-10 w-8 h-8 border border-[#f4d03f]/20 rounded-full animate-ping" style={{ animationDelay: '0.5s' }}></div>

      <motion.div
        initial={{ opacity: 0, scale: 0.95 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.5 }}
        className="w-full max-w-lg"
      >
        {/* Header */}
        <div className="text-center mb-8">
          <motion.div
            initial={{ scale: 0 }}
            animate={{ scale: 1 }}
            transition={{ delay: 0.2, type: "spring" }}
            className="inline-block p-4 rounded-2xl bg-gradient-to-br from-[#d4af37]/20 to-[#f4d03f]/10 border border-[#d4af37]/30 mb-6"
          >
            <div className="w-16 h-16 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#f4d03f] flex items-center justify-center mx-auto">
              <i className="fas fa-shield-alt text-black text-2xl"></i>
            </div>
          </motion.div>
          
          <h1 className="text-3xl font-bold mb-2">
            Verify Your Identity
            <span className="block text-sm font-normal text-gray-400 mt-2">
              Enter the 6-digit code sent to {userEmail}
            </span>
          </h1>
          
          <div className="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 mt-4">
            <i className="fas fa-clock text-blue-400"></i>
            <span className="text-sm text-blue-400">Code expires in 10 minutes</span>
          </div>
        </div>

        {/* Messages */}
        <AnimatePresence>
          {message.text && (
            <motion.div
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              className={`mb-6 p-4 rounded-xl border ${
                message.type === "success" 
                  ? "bg-green-500/10 border-green-500/30 text-green-400"
                  : "bg-blue-500/10 border-blue-500/30 text-blue-400"
              }`}
            >
              <div className="flex items-center space-x-3">
                <i className={`fas ${
                  message.type === "success" ? "fa-check-circle" : "fa-info-circle"
                }`}></i>
                <span className="text-sm font-medium">{message.text}</span>
              </div>
            </motion.div>
          )}
          
          {errors.server && (
            <motion.div
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              className="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30"
            >
              <div className="flex items-center space-x-3">
                <i className="fas fa-exclamation-triangle text-red-400"></i>
                <span className="text-red-400 text-sm font-medium">{errors.server}</span>
              </div>
            </motion.div>
          )}
        </AnimatePresence>

        {/* Main OTP Card */}
        <motion.div
          id="otpContainer"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.3 }}
          className="bg-gradient-to-br from-gray-900/80 to-black/80 backdrop-blur-xl border border-gray-800/50 rounded-2xl p-8 shadow-2xl shadow-black/50"
        >
          {/* OTP Input Section */}
          <div className="mb-8">
            <div className="flex items-center justify-between mb-4">
              <label className="text-sm font-medium text-gray-300">
                <i className="fas fa-key mr-2 text-[#d4af37]"></i>
                6-Digit Verification Code
              </label>
              {attempts > 0 && (
                <span className="text-xs text-gray-400">
                  <i className="fas fa-history mr-1"></i>
                  Attempt {attempts}/3
                </span>
              )}
            </div>
            
            {/* OTP Inputs */}
            <div 
              className="flex justify-between mb-2" 
              onPaste={handlePaste}
            >
              {otp.map((digit, index) => (
                <motion.div
                  key={index}
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  className="relative"
                >
                  <input
                    ref={el => inputRefs.current[index] = el}
                    type="text"
                    maxLength="1"
                    value={digit}
                    onChange={(e) => handleOtpChange(index, e.target.value)}
                    onKeyDown={(e) => handleKeyDown(index, e)}
                    className={`w-14 h-14 text-center text-2xl font-bold rounded-xl bg-gray-900/50 border ${
                      errors.otp ? "border-red-500/50" : "border-gray-700"
                    } ${
                      digit ? "border-[#d4af37] bg-[#d4af37]/10" : ""
                    } focus:outline-none focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 transition-all duration-200`}
                    disabled={loading || isVerified}
                    inputMode="numeric"
                    pattern="\d*"
                  />
                  {index === 2 && (
                    <div className="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-500">
                      Security code
                    </div>
                  )}
                </motion.div>
              ))}
            </div>
            
            {errors.otp && (
              <p className="text-red-400 text-xs mt-2 flex items-center space-x-1">
                <i className="fas fa-exclamation-circle"></i>
                <span>{errors.otp}</span>
              </p>
            )}
          </div>

          {/* Action Buttons */}
          <div className="space-y-4">
            <motion.button
              type="button"
              onClick={() => handleVerifyOtp()}
              disabled={loading || isVerified}
              whileHover={!loading && !isVerified ? { scale: 1.02 } : {}}
              whileTap={!loading && !isVerified ? { scale: 0.98 } : {}}
              className={`w-full py-3.5 px-6 rounded-xl font-bold transition-all duration-300 ${
                loading || isVerified
                  ? "bg-gray-700 cursor-not-allowed"
                  : "bg-gradient-to-r from-[#d4af37] to-[#f4d03f] hover:shadow-lg hover:shadow-[#d4af37]/30 text-black"
              } flex items-center justify-center space-x-3`}
            >
              {loading ? (
                <>
                  <div className="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                  <span>Verifying...</span>
                </>
              ) : isVerified ? (
                <>
                  <i className="fas fa-check-circle"></i>
                  <span>Verified Successfully!</span>
                </>
              ) : (
                <>
                  <i className="fas fa-shield-alt"></i>
                  <span>Verify & Continue</span>
                  <i className="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </>
              )}
            </motion.button>
            
            <div className="flex items-center justify-center space-x-4">
              <button
                type="button"
                onClick={handleSendOtp}
                disabled={resendLoading || resendCountdown > 0}
                className={`py-2.5 px-6 rounded-xl font-medium transition-all duration-300 flex items-center space-x-2 ${
                  resendLoading || resendCountdown > 0
                    ? "opacity-50 cursor-not-allowed border border-gray-700 text-gray-500"
                    : "border border-[#d4af37]/40 text-[#d4af37] hover:bg-[#d4af37]/10"
                }`}
              >
                {resendLoading ? (
                  <>
                    <div className="w-4 h-4 border-2 border-[#d4af37] border-t-transparent rounded-full animate-spin"></div>
                    <span>Sending...</span>
                  </>
                ) : (
                  <>
                    <i className="fas fa-redo-alt"></i>
                    <span>
                      {resendCountdown > 0 ? (
                        <Countdown
                          date={Date.now() + resendCountdown * 1000}
                          renderer={renderCountdown}
                        />
                      ) : (
                        "Resend Code"
                      )}
                    </span>
                  </>
                )}
              </button>
              
              <button
                type="button"
                onClick={() => navigate("/login")}
                className="py-2.5 px-6 rounded-xl border border-gray-700 text-gray-400 hover:text-white hover:border-gray-600 transition-all duration-300"
              >
                <i className="fas fa-arrow-left mr-2"></i>
                Back to Login
              </button>
            </div>
          </div>

          {/* Security Tips */}
          <div className="mt-8 pt-6 border-t border-gray-800">
            <h3 className="text-sm font-semibold text-gray-300 mb-3 flex items-center">
              <i className="fas fa-lightbulb mr-2 text-[#d4af37]"></i>
              Security Tips
            </h3>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              {[
                { icon: "fa-clock", text: "Code expires in 10 minutes" },
                { icon: "fa-user-shield", text: "Never share your OTP" },
                { icon: "fa-envelope", text: "Check spam folder" },
                { icon: "fa-mobile-alt", text: "Request SMS option" }
              ].map((tip, idx) => (
                <div key={idx} className="flex items-center space-x-2 text-xs text-gray-400">
                  <i className={`fas ${tip.icon} text-[#d4af37]`}></i>
                  <span>{tip.text}</span>
                </div>
              ))}
            </div>
          </div>
        </motion.div>

        {/* Additional Security Info */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.5 }}
          className="mt-6 text-center"
        >
          <div className="inline-flex items-center space-x-6 text-xs text-gray-500">
            <div className="flex items-center space-x-2">
              <i className="fas fa-lock text-green-400"></i>
              <span>End-to-end encrypted</span>
            </div>
            <div className="flex items-center space-x-2">
              <i className="fas fa-history text-blue-400"></i>
              <span>Real-time monitoring</span>
            </div>
            <div className="flex items-center space-x-2">
              <i className="fas fa-shield-alt text-purple-400"></i>
              <span>Bank-level security</span>
            </div>
          </div>
        </motion.div>
      </motion.div>

      {/* Footer Watermark */}
      <div className="absolute bottom-6 right-6 text-gray-700 text-xs">
        SJM Pro • 2FA Verification • v2.5.1
      </div>

      {/* Custom Styles */}
      <style>{`
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
          20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes success {
          0% { transform: scale(1); }
          50% { transform: scale(1.02); }
          100% { transform: scale(1); }
        }
        
        .animate-shake {
          animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        
        .animate-success {
          animation: success 0.5s ease-in-out;
        }
        
        .bg-grid-white {
          background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(255 255 255 / 0.05)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
        
        /* Hide number input spinners */
        input[type="text"]::-webkit-outer-spin-button,
        input[type="text"]::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        
        input[type="text"] {
          -moz-appearance: textfield;
        }
        
        /* Custom focus styles for OTP inputs */
        input:focus {
          box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        /* Loading spinner animation */
        @keyframes spin {
          to { transform: rotate(360deg); }
        }
        
        .animate-spin {
          animation: spin 1s linear infinite;
        }
      `}</style>
    </div>
  );
}