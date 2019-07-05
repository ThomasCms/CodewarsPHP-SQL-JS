SELECT d.*
FROM departments d
WHERE EXISTS (SELECT 1 FROM sales s WHERE s.price>98 and s.department_id=d.id);