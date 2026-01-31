/*
  # Add role and phone to users table

  1. Changes
    - Add `role` column to users table with default 'customer'
    - Add `phone` column to users table
    - Add CHECK constraint on role field
    - Add index on role for faster queries
  
  2. Security
    - Maintains existing RLS policies
    - Role-based access control enabled
*/

-- Add role column to users table (if not exists)
ALTER TABLE users ADD COLUMN IF NOT EXISTS role varchar(20) DEFAULT 'customer';

-- Add phone column to users table (if not exists)
ALTER TABLE users ADD COLUMN IF NOT EXISTS phone varchar(20);

-- Add CHECK constraint on role (drop first if exists to be safe)
DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'users_role_check') THEN
    ALTER TABLE users DROP CONSTRAINT users_role_check;
  END IF;
END $$;

ALTER TABLE users ADD CONSTRAINT users_role_check 
  CHECK (role IN ('admin', 'customer', 'supplier', 'delivery'));

-- Create index on role for faster queries
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
