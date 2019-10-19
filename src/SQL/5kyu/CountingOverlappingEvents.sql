SELECT COUNT(*) AS visits_count, v1.entry_time AS when_happened
FROM visits v1 JOIN visits v2 ON (v1.entry_time >= v2.entry_time and v1.entry_time < v2.exit_time)
GROUP BY v1.id
ORDER BY visits_count DESC, when_happened ASC LIMIT 1;