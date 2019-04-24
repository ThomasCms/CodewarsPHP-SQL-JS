SELECT age,
       COUNT(id) AS people_count
FROM people
GROUP BY age;
