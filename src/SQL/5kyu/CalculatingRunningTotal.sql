SELECT created_at::date AS "date", COUNT(*) AS count,
    SUM(COUNT(*)::int) OVER (ORDER BY created_at::date) AS total
FROM posts
GROUP BY created_at::date
ORDER BY created_at::date