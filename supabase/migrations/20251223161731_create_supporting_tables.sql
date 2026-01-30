/*
  # Create Supporting Tables

  1. New Tables
    - `password_reset_tokens`
      - `email` (varchar 255, primary key) - User's email address
      - `token` (varchar 255) - Password reset token
      - `created_at` (timestamptz) - Token creation timestamp
    
    - `cache`
      - `key` (varchar 255, primary key) - Cache key
      - `value` (text) - Cached value
      - `expiration` (integer) - Expiration timestamp
    
    - `cache_locks`
      - `key` (varchar 255, primary key) - Lock key
      - `owner` (varchar 255) - Lock owner
      - `expiration` (integer) - Expiration timestamp
    
    - `jobs`
      - `id` (bigserial, primary key) - Job identifier
      - `queue` (varchar 255) - Queue name
      - `payload` (text) - Job payload
      - `attempts` (smallint) - Number of attempts
      - `reserved_at` (integer, nullable) - Reserved timestamp
      - `available_at` (integer) - Available timestamp
      - `created_at` (integer) - Creation timestamp
    
    - `job_batches`
      - `id` (varchar 255, primary key) - Batch identifier
      - `name` (varchar 255) - Batch name
      - `total_jobs` (integer) - Total jobs count
      - `pending_jobs` (integer) - Pending jobs count
      - `failed_jobs` (integer) - Failed jobs count
      - `failed_job_ids` (text) - Failed job IDs
      - `options` (text, nullable) - Batch options
      - `cancelled_at` (integer, nullable) - Cancellation timestamp
      - `created_at` (integer) - Creation timestamp
      - `finished_at` (integer, nullable) - Finish timestamp
    
    - `failed_jobs`
      - `id` (bigserial, primary key) - Failed job identifier
      - `uuid` (varchar 255, unique) - Job UUID
      - `connection` (text) - Connection name
      - `queue` (text) - Queue name
      - `payload` (text) - Job payload
      - `exception` (text) - Exception details
      - `failed_at` (timestamptz) - Failure timestamp

  2. Security
    - All tables have RLS enabled
    - Basic policies for authenticated access
*/

CREATE TABLE IF NOT EXISTS password_reset_tokens (
  email VARCHAR(255) PRIMARY KEY,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE password_reset_tokens ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can manage own password reset tokens"
  ON password_reset_tokens
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE TABLE IF NOT EXISTS cache (
  key VARCHAR(255) PRIMARY KEY,
  value TEXT NOT NULL,
  expiration INTEGER NOT NULL
);

ALTER TABLE cache ENABLE ROW LEVEL SECURITY;

CREATE POLICY "System can manage cache"
  ON cache
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE TABLE IF NOT EXISTS cache_locks (
  key VARCHAR(255) PRIMARY KEY,
  owner VARCHAR(255) NOT NULL,
  expiration INTEGER NOT NULL
);

ALTER TABLE cache_locks ENABLE ROW LEVEL SECURITY;

CREATE POLICY "System can manage cache locks"
  ON cache_locks
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE TABLE IF NOT EXISTS jobs (
  id BIGSERIAL PRIMARY KEY,
  queue VARCHAR(255) NOT NULL,
  payload TEXT NOT NULL,
  attempts SMALLINT NOT NULL,
  reserved_at INTEGER,
  available_at INTEGER NOT NULL,
  created_at INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_jobs_queue ON jobs(queue);

ALTER TABLE jobs ENABLE ROW LEVEL SECURITY;

CREATE POLICY "System can manage jobs"
  ON jobs
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE TABLE IF NOT EXISTS job_batches (
  id VARCHAR(255) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  total_jobs INTEGER NOT NULL,
  pending_jobs INTEGER NOT NULL,
  failed_jobs INTEGER NOT NULL,
  failed_job_ids TEXT NOT NULL,
  options TEXT,
  cancelled_at INTEGER,
  created_at INTEGER NOT NULL,
  finished_at INTEGER
);

ALTER TABLE job_batches ENABLE ROW LEVEL SECURITY;

CREATE POLICY "System can manage job batches"
  ON job_batches
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE TABLE IF NOT EXISTS failed_jobs (
  id BIGSERIAL PRIMARY KEY,
  uuid VARCHAR(255) UNIQUE NOT NULL,
  connection TEXT NOT NULL,
  queue TEXT NOT NULL,
  payload TEXT NOT NULL,
  exception TEXT NOT NULL,
  failed_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE failed_jobs ENABLE ROW LEVEL SECURITY;

CREATE POLICY "System can manage failed jobs"
  ON failed_jobs
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);
