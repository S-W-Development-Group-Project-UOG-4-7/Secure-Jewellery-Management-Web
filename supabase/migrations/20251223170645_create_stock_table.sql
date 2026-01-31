/*
  # Create stock table

  1. New Tables
    - `stock`
      - `id` (bigserial, primary key)
      - `item_name` (varchar 255, unique, not null)
      - `quantity` (integer, not null, default 0)
      - `last_updated` (timestamptz, default now())
      - `updated_by` (bigint, foreign key to users, nullable)
  
  2. Security
    - Enable RLS on `stock` table
    - Add policies for authenticated users to manage stock
  
  3. Foreign Keys
    - updated_by references users(id) with SET NULL on delete
  
  4. Indexes
    - Index on item_name for faster lookups
    - Index on last_updated for sorting
*/

CREATE TABLE IF NOT EXISTS stock (
  id bigserial PRIMARY KEY,
  item_name varchar(255) UNIQUE NOT NULL,
  quantity integer NOT NULL DEFAULT 0 CHECK (quantity >= 0),
  last_updated timestamptz DEFAULT now() NOT NULL,
  updated_by bigint,
  CONSTRAINT fk_stock_updated_by
    FOREIGN KEY (updated_by)
    REFERENCES users(id)
    ON DELETE SET NULL
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_stock_item_name ON stock(item_name);
CREATE INDEX IF NOT EXISTS idx_stock_last_updated ON stock(last_updated DESC);

-- Enable Row Level Security
ALTER TABLE stock ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view stock" ON stock;
DROP POLICY IF EXISTS "Authenticated users can insert stock" ON stock;
DROP POLICY IF EXISTS "Authenticated users can update stock" ON stock;
DROP POLICY IF EXISTS "Authenticated users can delete stock" ON stock;

-- Policy: Authenticated users can view all stock
CREATE POLICY "Authenticated users can view stock"
  ON stock
  FOR SELECT
  TO authenticated
  USING (true);

-- Policy: Authenticated users can insert stock
CREATE POLICY "Authenticated users can insert stock"
  ON stock
  FOR INSERT
  TO authenticated
  WITH CHECK (true);

-- Policy: Authenticated users can update stock
CREATE POLICY "Authenticated users can update stock"
  ON stock
  FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

-- Policy: Authenticated users can delete stock
CREATE POLICY "Authenticated users can delete stock"
  ON stock
  FOR DELETE
  TO authenticated
  USING (true);