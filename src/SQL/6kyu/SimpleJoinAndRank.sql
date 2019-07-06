SELECT p.id, p.name, sc.cnt AS sale_count,
       RANK() OVER( ORDER BY sc.cnt DESC ) AS sale_rank
FROM people AS p
JOIN (SELECT people_id, COUNT(*) AS cnt
      FROM sales
      GROUP BY people_id) sc
ON sc.people_id = p.id
