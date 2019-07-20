SELECT
  id, heads, legs, arms, tails,
  (CASE
    WHEN heads > arms OR tails > legs THEN 'BEAST'
    ELSE 'WEIRDO'
  END) species
FROM top_half
INNER JOIN bottom_half USING(id)
ORDER BY species;