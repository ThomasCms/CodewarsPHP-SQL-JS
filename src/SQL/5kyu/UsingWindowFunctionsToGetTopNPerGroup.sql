SELECT
  c.id category_id,
  c.category,
  p.title,
  p.views,
  p.post_id
FROM categories c LEFT JOIN (
  SELECT
    category_id,
    title,
    views,
    id post_id,
    row_number() over (partition BY category_id ORDER BY views DESC, id) rn
  FROM posts) p ON p.category_id = c.id AND p.rn < 3
ORDER BY category, views DESC, post_id;
