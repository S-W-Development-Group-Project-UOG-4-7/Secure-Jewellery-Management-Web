/*
  # Create Customers Table

  1. New Tables
    - `customers`
      - `id` (bigserial, primary key) - Unique customer identifier
      - `name` (varchar 255) - Customer's full name (mapped from full_name)
      - `email` (varchar 255, unique) - Customer's email address
      - `phone` (varchar 20) - Customer's phone number
      - `address` (text, nullable) - Customer's address
      - `status` (varchar 20, default 'active') - Customer status (active/inactive)
      - `created_at` (timestamptz) - Record creation timestamp
      - `updated_at` (timestamptz) - Record update timestamp

  2. Security
    - Enable RLS on `customers` table
    - Add policy for authenticated users to read all customers
    - Add policy for authenticated users to insert customers
    - Add policy for authenticated users to update customers
    - Add policy for authenticated users to delete customers

  3. Important Notes
    - All customer data is accessible to authenticated users only
    - Status field uses CHECK constraint to ensure only 'active' or 'inactive' values
    - Email must be unique across all customers
    - Phone number has maximum length of 20 characters
*/

CREATE TABLE IF NOT EXISTS customers (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address TEXT,
  status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE customers ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Authenticated users can view all customers"
  ON customers
  FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authenticated users can insert customers"
  ON customers
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated users can update customers"
  ON customers
  FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Authenticated users can delete customers"
  ON customers
  FOR DELETE
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS idx_customers_email ON customers(email);
CREATE INDEX IF NOT EXISTS idx_customers_status ON customers(status);
CREATE INDEX IF NOT EXISTS idx_customers_created_at ON customers(created_at DESC);
