/*
  # Create OTP Logs Table

  1. New Tables
    - `otp_logs`
      - `id` (bigserial, primary key) - Unique OTP log identifier
      - `user_id` (bigint, foreign key) - References users table
      - `otp_code` (varchar 6) - 6-digit OTP code
      - `expires_at` (timestamptz) - OTP expiration timestamp (5 minutes from creation)
      - `status` (varchar 20, default 'pending') - OTP status (pending/verified/expired)
      - `created_at` (timestamptz) - Record creation timestamp
      - `updated_at` (timestamptz) - Record update timestamp

  2. Security
    - Enable RLS on `otp_logs` table
    - Add policy for users to read their own OTP logs
    - Add policy for users to update their own OTP logs (for verification)
    - Add policy for system to insert OTP logs

  3. Important Notes
    - OTP expires after 5 minutes (handled in application logic)
    - Each OTP is single-use (status changes from pending to verified/expired)
    - Foreign key constraint ensures OTP logs are deleted when user is deleted
    - Status field uses CHECK constraint to ensure only valid values
*/

CREATE TABLE IF NOT EXISTS otp_logs (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  otp_code VARCHAR(6) NOT NULL,
  expires_at TIMESTAMPTZ NOT NULL,
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'verified', 'expired')),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE otp_logs ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own OTP logs"
  ON otp_logs
  FOR SELECT
  TO authenticated
  USING (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "Users can update own OTP logs"
  ON otp_logs
  FOR UPDATE
  TO authenticated
  USING (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint)
  WITH CHECK (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "System can insert OTP logs"
  ON otp_logs
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE INDEX IF NOT EXISTS idx_otp_logs_user_id ON otp_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_otp_logs_status ON otp_logs(status);
CREATE INDEX IF NOT EXISTS idx_otp_logs_expires_at ON otp_logs(expires_at);
