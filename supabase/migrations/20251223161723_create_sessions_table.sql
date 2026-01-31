/*
  # Create Sessions Table

  1. New Tables
    - `sessions`
      - `id` (varchar 255, primary key) - Session identifier
      - `user_id` (bigint, nullable, foreign key) - References users table
      - `ip_address` (varchar 45, nullable) - User's IP address
      - `user_agent` (text, nullable) - User's browser user agent
      - `payload` (text) - Session data payload
      - `last_activity` (integer) - Last activity timestamp

  2. Security
    - Enable RLS on `sessions` table
    - Add policy for users to read their own sessions
    - Add policy for users to update their own sessions
    - Add policy for users to delete their own sessions

  3. Important Notes
    - Session data is stored as serialized text
    - last_activity is stored as Unix timestamp (integer)
    - IP address field supports both IPv4 and IPv6 (max 45 characters)
*/

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
  ip_address VARCHAR(45),
  user_agent TEXT,
  payload TEXT NOT NULL,
  last_activity INTEGER NOT NULL
);

ALTER TABLE sessions ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own sessions"
  ON sessions
  FOR SELECT
  TO authenticated
  USING (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "Users can update own sessions"
  ON sessions
  FOR UPDATE
  TO authenticated
  USING (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint)
  WITH CHECK (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "Users can delete own sessions"
  ON sessions
  FOR DELETE
  TO authenticated
  USING (user_id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "System can insert sessions"
  ON sessions
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity);
