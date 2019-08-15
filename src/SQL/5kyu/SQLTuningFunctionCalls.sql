SELECT
  e.employee_id,
  e.first_name,
  e.last_name,
  d.department_name,
  e.salary AS old_salary,
  e.salary * (1 + d.pct_increase) AS new_salary
FROM employees AS e
INNER JOIN (
  SELECT
    department_id,
    department_name,
    pctIncrease(department_id) AS pct_increase
  FROM departments
) AS d ON e.department_id = d.department_id
ORDER BY 1;
