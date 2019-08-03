SELECT f.title
  FROM film f
  INNER JOIN film_actor a ON f.film_id = a.film_id
  INNER JOIN film_actor b ON f.film_id = b.film_id
  WHERE a.actor_id = 105 AND b.actor_id = 122
  ORDER BY title;