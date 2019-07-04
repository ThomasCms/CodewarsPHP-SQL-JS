SELECT age AS age,
  count(id) AS total_people
FROM people
GROUP BY age
HAVING count(id) >= 10;
