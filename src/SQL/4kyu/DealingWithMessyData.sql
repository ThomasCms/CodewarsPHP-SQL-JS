WITH prospects_lower AS(
  SELECT LOWER(full_name) AS full_name,credit_limit FROM prospects
)

SELECT c.first_name, c.last_name, c.credit_limit as old_limit,
MAX(p.credit_limit) AS new_limit
FROM customers c JOIN prospects_lower p
ON POSITION(LOWER(c.first_name) in (p.full_name))>0 AND
POSITION(LOWER(c.last_name) in (p.full_name))>0 AND c.credit_limit<p.credit_limit
GROUP BY 1,2,3
ORDER BY 1,2;