/*
  # Create suppliers table

  1. New Tables
    - `suppliers`
      - `id` (bigserial, primary key)
      - `name` (varchar 255, not null)
      - `contact_email` (varchar 255, unique, not null)
      - `phone` (varchar 20, not null)
      - `address` (text, nullable)
      - `created_at` (timestamptz, default now())
      - `updated_at` (timestamptz, default now())
  
  2. Security
    - Enable RLS on `suppliers` table
    - Add policies for authenticated users to manage suppliers
  
  3. Indexes
    - Index on contact_email for faster lookups
    - Index on created_at for sorting
*/

CREATE TABLE IF NOT EXISTS suppliers (
  id bigserial PRIMARY KEY,
  name varchar(255) NOT NULL,
  contact_email varchar(255) UNIQUE NOT NULL,
  phone varchar(20) NOT NULL,
  address text,
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_suppliers_email ON suppliers(contact_email);
CREATE INDEX IF NOT EXISTS idx_suppliers_created_at ON suppliers(created_at DESC);

-- Enable Row Level Security
ALTER TABLE suppliers ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view suppliers" ON suppliers;
DROP POLICY IF EXISTS "Authenticated users can insert suppliers" ON suppliers;
DROP POLICY IF EXISTS "Authenticated users can update suppliers" ON suppliers;
DROP POLICY IF EXISTS "Authenticated users can delete suppliers" ON suppliers;

-- Policy: Authenticated users can view all suppliers
CREATE POLICY "Authenticated users can view suppliers"
  ON suppliers
  FOR SELECT
  TO authenticated
  USING (true);

-- Policy: Authenticated users can insert suppliers
CREATE POLICY "Authenticated users can insert suppliers"
  ON suppliers
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

-- Policy: Authenticated users can update suppliers
CREATE POLICY "Authenticated users can update suppliers"
  ON suppliers
  FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

-- Policy: Authenticated users can delete suppliers
CREATE POLICY "Authenticated users can delete suppliers"
  ON suppliers
  FOR DELETE
  TO authenticated
  USING (true);