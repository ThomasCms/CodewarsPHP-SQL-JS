SELECT c.id category_id, c.category category, mostViewed.title title, mostViewed.views as views, mostViewed.id post_id
FROM categories c LEFT JOIN LATERAL
  (SELECT * FROM posts p WHERE p.category_id=c.id ORDER BY p.views desc LIMIT 2) as mostViewed ON true
ORDER BY category, views desc, post_id;