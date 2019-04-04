SELECT p.*, COUNT(t.id) AS toy_count
FROM people p
LEFT JOIN toys t
  ON t.people_id = p.id
GROUP BY p.id;
