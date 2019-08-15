CREATE VIEW members_approved_for_voucher AS
SELECT m.id, m.name, m.email, SUM(p.price) AS total_spending
FROM members m
INNER JOIN sales s ON s.member_id = m.id
INNER JOIN products p ON p.id = s.product_id
WHERE s.department_id IN (
  SELECT s2.department_id
  FROM sales s2
  INNER JOIN products p2 ON p2.id = s2.product_id
  GROUP BY s2.department_id
  HAVING SUM(p2.price) > 10000
)
GROUP BY m.id, m.name, m.email
HAVING SUM(p.price) > 1000
ORDER BY m.id;

SELECT * FROM members_approved_for_voucher;
