SELECT
  CAST(s.transaction_date AS DATE) AS day,
  d.name AS department,
  COUNT(s.id) AS sale_count
  FROM department d
    INNER JOIN sale s ON d.id = s.department_id
  GROUP BY day, department
  ORDER BY day, department
