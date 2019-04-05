SELECT name, sum(won) as won, sum(lost) as lost FROM fighters
INNER JOIN winning_moves ON fighters.move_id=winning_moves.id
WHERE NOT move IN ('Hadoken', 'Shouoken', 'Kikoken')
GROUP BY name
ORDER by won DESC
LIMIT 6;
