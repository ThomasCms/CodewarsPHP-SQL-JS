SELECT
  date_trunc('day', created_at) as day,
  description,
  COUNT(*) as count
FROM events WHERE name = 'trained'
GROUP BY day, description ORDER BY day ASC;