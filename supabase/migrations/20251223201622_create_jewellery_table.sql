/*
  # Create Jewellery Management Tables

  ## 1. New Tables
  
  ### `jewellery` table
  - `id` (bigserial, PK)
  - `name` (varchar 255) - Item name/description
  - `type` (varchar 100) - Ring, Necklace, Bracelet, etc.
  - `metal` (varchar 50) - Gold, Silver, Platinum, etc.
  - `weight` (decimal 10,2) - Weight in grams
  - `value` (decimal 12,2) - Estimated value
  - `status` (varchar 20) - available, in_locker, sold
  - `created_at`, `updated_at` (timestamptz)
  
  ### `lockers` table
  - `id` (bigserial, PK)
  - `locker_number` (varchar 50, unique) - Locker identifier
  - `location` (varchar 100) - Physical location
  - `status` (varchar 20) - available, occupied, maintenance
  - `created_at`, `updated_at` (timestamptz)
  
  ### `locker_verifications` table
  - `id` (bigserial, PK)
  - `locker_id` (bigint, FK → lockers)
  - `verified_by` (bigint, FK → users)
  - `before_notes` (text) - Condition before storage
  - `after_notes` (text) - Condition after storage
  - `before_image` (text) - Image path before storage
  - `after_image` (text) - Image path after storage
  - `status` (varchar 20) - pending, pass, fail, flagged
  - `completed_at` (timestamptz)
  - `created_at`, `updated_at` (timestamptz)
  
  ### `locker_verification_items` table
  - `id` (bigserial, PK)
  - `verification_id` (bigint, FK → locker_verifications)
  - `jewellery_id` (bigint, FK → jewellery)
  
  ## 2. Security
  - Enable RLS on all tables
  - Add policies for authenticated users
  - Add CHECK constraints on status fields
  - Add indexes for frequently queried columns
*/

-- Create jewellery table
CREATE TABLE IF NOT EXISTS jewellery (
  id bigserial PRIMARY KEY,
  name varchar(255) NOT NULL,
  type varchar(100) NOT NULL,
  metal varchar(50) NOT NULL,
  weight decimal(10,2) NOT NULL CHECK (weight > 0),
  value decimal(12,2) NOT NULL CHECK (value >= 0),
  status varchar(20) NOT NULL DEFAULT 'available' CHECK (status IN ('available', 'in_locker', 'sold')),
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL
);

-- Create lockers table
CREATE TABLE IF NOT EXISTS lockers (
  id bigserial PRIMARY KEY,
  locker_number varchar(50) UNIQUE NOT NULL,
  location varchar(100) NOT NULL,
  status varchar(20) NOT NULL DEFAULT 'available' CHECK (status IN ('available', 'occupied', 'maintenance')),
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL
);

-- Create locker_verifications table
CREATE TABLE IF NOT EXISTS locker_verifications (
  id bigserial PRIMARY KEY,
  locker_id bigint NOT NULL,
  verified_by bigint NOT NULL,
  before_notes text,
  after_notes text,
  before_image text,
  after_image text,
  status varchar(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'pass', 'fail', 'flagged')),
  completed_at timestamptz,
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL,
  FOREIGN KEY (locker_id) REFERENCES lockers(id) ON DELETE CASCADE,
  FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Create locker_verification_items table
CREATE TABLE IF NOT EXISTS locker_verification_items (
  id bigserial PRIMARY KEY,
  verification_id bigint NOT NULL,
  jewellery_id bigint NOT NULL,
  FOREIGN KEY (verification_id) REFERENCES locker_verifications(id) ON DELETE CASCADE,
  FOREIGN KEY (jewellery_id) REFERENCES jewellery(id) ON DELETE CASCADE
);

-- Add is_active column to users table
DO $$
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.columns
    WHERE table_name = 'users' AND column_name = 'is_active'
  ) THEN
    ALTER TABLE users ADD COLUMN is_active boolean DEFAULT true NOT NULL;
  END IF;
END $$;

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_jewellery_status ON jewellery(status);
CREATE INDEX IF NOT EXISTS idx_jewellery_type ON jewellery(type);
CREATE INDEX IF NOT EXISTS idx_jewellery_created_at ON jewellery(created_at DESC);

CREATE INDEX IF NOT EXISTS idx_lockers_number ON lockers(locker_number);
CREATE INDEX IF NOT EXISTS idx_lockers_status ON lockers(status);

CREATE INDEX IF NOT EXISTS idx_verifications_locker_id ON locker_verifications(locker_id);
CREATE INDEX IF NOT EXISTS idx_verifications_verified_by ON locker_verifications(verified_by);
CREATE INDEX IF NOT EXISTS idx_verifications_status ON locker_verifications(status);
CREATE INDEX IF NOT EXISTS idx_verifications_created_at ON locker_verifications(created_at DESC);

CREATE INDEX IF NOT EXISTS idx_verification_items_verification_id ON locker_verification_items(verification_id);
CREATE INDEX IF NOT EXISTS idx_verification_items_jewellery_id ON locker_verification_items(jewellery_id);

CREATE INDEX IF NOT EXISTS idx_users_is_active ON users(is_active);

-- Enable Row Level Security
ALTER TABLE jewellery ENABLE ROW LEVEL SECURITY;
ALTER TABLE lockers ENABLE ROW LEVEL SECURITY;
ALTER TABLE locker_verifications ENABLE ROW LEVEL SECURITY;
ALTER TABLE locker_verification_items ENABLE ROW LEVEL SECURITY;

-- RLS Policies for jewellery
CREATE POLICY "Authenticated users can SELECT jewellery"
  ON jewellery FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authenticated users can INSERT jewellery"
  ON jewellery FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated users can UPDATE jewellery"
  ON jewellery FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Authenticated users can DELETE jewellery"
  ON jewellery FOR DELETE
  TO authenticated
  USING (true);

-- RLS Policies for lockers
CREATE POLICY "Authenticated users can SELECT lockers"
  ON lockers FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authenticated users can INSERT lockers"
  ON lockers FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated users can UPDATE lockers"
  ON lockers FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Authenticated users can DELETE lockers"
  ON lockers FOR DELETE
  TO authenticated
  USING (true);

-- RLS Policies for locker_verifications
CREATE POLICY "Authenticated users can SELECT verifications"
  ON locker_verifications FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authenticated users can INSERT verifications"
  ON locker_verifications FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated users can UPDATE verifications"
  ON locker_verifications FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Authenticated users can DELETE verifications"
  ON locker_verifications FOR DELETE
  TO authenticated
  USING (true);

-- RLS Policies for locker_verification_items
CREATE POLICY "Authenticated users can SELECT verification items"
  ON locker_verification_items FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authenticated users can INSERT verification items"
  ON locker_verification_items FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Authenticated users can UPDATE verification items"
  ON locker_verification_items FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Authenticated users can DELETE verification items"
  ON locker_verification_items FOR DELETE
  TO authenticated
  USING (true);
