CREATE EXTENSION tablefunc;

SELECT *
FROM  crosstab(
      'SELECT p.name, detail, COUNT(d.id)
      FROM products p
      JOIN details d
      ON p.id = d.product_id
      GROUP BY p.name, d.detail
      ORDER BY 1,2')
AS ct (name text, bad bigint, good bigint, ok bigint);
