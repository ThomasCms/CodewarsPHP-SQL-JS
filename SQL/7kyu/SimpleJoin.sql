SELECT
  products.id,
  products.name,
  products.isbn,
  products.company_id,
  products.price,
  companies.name as company_name
FROM
  products
JOIN
  companies on companies.id = products.company_id
