SELECT player_name, games, round(hits::numeric / at_bats, 3)::text as batting_average
FROM yankees
WHERE at_bats >= 100
ORDER BY batting_average DESC;