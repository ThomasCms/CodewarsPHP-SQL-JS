CREATE FUNCTION increment (IN age integer, OUT integer)
AS 'SELECT age + 1'
LANGUAGE SQL;
