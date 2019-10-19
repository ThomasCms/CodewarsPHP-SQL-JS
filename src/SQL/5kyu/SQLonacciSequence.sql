WITH RECURSIVE fibs (idx, fib, next_fib ) AS
(
   SELECT 0, 0 :: BIGINT, 1 :: BIGINT

   UNION ALL

   SELECT idx + 1, next_fib, fib + next_fib
   FROM fibs
   WHERE idx < 89
)
SELECT fib AS number
FROM fibs;