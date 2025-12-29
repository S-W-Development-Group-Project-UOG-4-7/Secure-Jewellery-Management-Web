/*
  # Create deliveries table

  1. New Tables
    - `deliveries`
      - `id` (bigserial, primary key)
      - `supplier_id` (bigint, foreign key to suppliers)
      - `item_name` (varchar 255, not null)
      - `quantity` (integer, not null)
      - `invoice_image` (text, nullable - stores file path)
      - `delivery_date` (date, not null)
      - `created_at` (timestamptz, default now())
      - `updated_at` (timestamptz, default now())
  
  2. Security
    - Enable RLS on `deliveries` table
    - Add policies for authenticated users to manage deliveries
  
  3. Foreign Keys
    - supplier_id references suppliers(id) with CASCADE delete
  
  4. Indexes
    - Index on supplier_id for faster joins
    - Index on delivery_date for filtering
*/

CREATE TABLE IF NOT EXISTS deliveries (
  id bigserial PRIMARY KEY,
  supplier_id bigint NOT NULL,
  item_name varchar(255) NOT NULL,
  quantity integer NOT NULL CHECK (quantity > 0),
  invoice_image text,
  delivery_date date NOT NULL,
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL,
  CONSTRAINT fk_deliveries_supplier
    FOREIGN KEY (supplier_id)
    REFERENCES suppliers(id)
    ON DELETE CASCADE
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_deliveries_supplier_id ON deliveries(supplier_id);
CREATE INDEX IF NOT EXISTS idx_deliveries_date ON deliveries(delivery_date DESC);
CREATE INDEX IF NOT EXISTS idx_deliveries_created_at ON deliveries(created_at DESC);

-- Enable Row Level Security
ALTER TABLE deliveries ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view deliveries" ON deliveries;
DROP POLICY IF EXISTS "Authenticated users can insert deliveries" ON deliveries;
DROP POLICY IF EXISTS "Authenticated users can update deliveries" ON deliveries;
DROP POLICY IF EXISTS "Authenticated users can delete deliveries" ON deliveries;

-- Policy: Authenticated users can view all deliveries
CREATE POLICY "Authenticated users can view deliveries"
  ON deliveries
  FOR SELECT
  TO authenticated
  USING (true);

-- Policy: Authenticated users can insert deliveries
CREATE POLICY "Authenticated users can insert deliveries"
  ON deliveries
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

-- Policy: Authenticated users can update deliveries
CREATE POLICY "Authenticated users can update deliveries"
  ON deliveries
  FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

-- Policy: Authenticated users can delete deliveries
CREATE POLICY "Authenticated users can delete deliveries"
  ON deliveries
  FOR DELETE
  TO authenticated
  USING (true);