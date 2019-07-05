SELECT 'US' as location, *
  FROM ussales
  WHERE price > 50
UNION ALL
SELECT 'EU' as location, *
  FROM eusales
  WHERE price > 50