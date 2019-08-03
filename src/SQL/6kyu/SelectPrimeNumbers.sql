SELECT
  prime
FROM generate_series(2, 100) AS prime
WHERE 0 <> ALL (
  SELECT prime % n
  FROM generate_series(2, prime - 1) AS n);