SELECT
  MIN(score),
  percentile_cont(0.5) WITHIN GROUP (ORDER BY score) AS median,
  MAX(score)
FROM result;