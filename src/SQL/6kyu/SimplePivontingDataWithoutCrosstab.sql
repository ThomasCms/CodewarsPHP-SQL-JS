SELECT p.name,
  COUNT(CASE WHEN d.detail = 'good' THEN 1 END) AS good,
  COUNT(CASE WHEN d.detail = 'ok'  THEN 1 END) AS ok,
  COUNT(CASE WHEN d.detail = 'bad' THEN 1 END) AS bad
FROM products p
INNER JOIN details d ON p.id = d.product_id
GROUP BY p.name
ORDER BY p.name;