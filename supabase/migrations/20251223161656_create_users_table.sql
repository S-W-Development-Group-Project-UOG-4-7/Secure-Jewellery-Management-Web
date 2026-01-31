/*
  # Create Users Table

  1. New Tables
    - `users`
      - `id` (bigserial, primary key) - Unique user identifier
      - `name` (varchar 255) - User's full name
      - `email` (varchar 255, unique) - User's email address
      - `email_verified_at` (timestamptz, nullable) - Email verification timestamp
      - `password` (varchar 255) - Hashed password using bcrypt
      - `remember_token` (varchar 100, nullable) - Remember me token
      - `created_at` (timestamptz) - Record creation timestamp
      - `updated_at` (timestamptz) - Record update timestamp

  2. Security
    - Enable RLS on `users` table
    - Add policy for users to read their own data
    - Add policy for users to update their own data
*/

CREATE TABLE IF NOT EXISTS users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMPTZ,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE users ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read own data"
  ON users
  FOR SELECT
  TO authenticated
  USING (id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE POLICY "Users can update own data"
  ON users
  FOR UPDATE
  TO authenticated
  USING (id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint)
  WITH CHECK (id = (current_setting('request.jwt.claims', true)::json->>'user_id')::bigint);

CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
