SELECT
  j.job_title,
  ROUND(SUM(j.salary)/COUNT(p)::numeric,2)::float AS average_salary,
  COUNT(p.id) as total_people,
  ROUND(SUM(j.salary)::numeric,2)::float AS total_salary
  FROM people p JOIN job j ON p.id=j.people_id
  GROUP BY j.job_title
  ORDER BY average_salary DESC