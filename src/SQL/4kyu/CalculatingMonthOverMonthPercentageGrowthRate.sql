SELECT
    DATE(date_trunc('month', created_at)) AS date,
    COUNT(*) AS count,TO_CHAR(100*(COUNT(*) * 1.0 / LAG(COUNT(*)) OVER (ORDER BY date_trunc('month', created_at)) - 1.0), 'FM9990D0%') AS percent_growth
FROM posts
GROUP BY date_trunc('month', created_at);