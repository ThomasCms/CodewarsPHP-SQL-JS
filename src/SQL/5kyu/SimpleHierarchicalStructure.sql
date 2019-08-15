WITH RECURSIVE employee_levels  AS (
     SELECT 1 AS level, id, first_name, last_name, manager_id
     FROM employees
     WHERE manager_id IS NULL

     UNION ALL

     SELECT level + 1 AS level, e.id, e.first_name, e.last_name, e.manager_id
     FROM employees AS e
     INNER JOIN employee_levels AS eL
       ON eL.id = e.manager_id
)
SELECT * FROM employee_levels;
