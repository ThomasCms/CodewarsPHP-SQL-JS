/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 19:24
 */

SELECT CASE WHEN SUM(number1) % 2 > 0 THEN MIN(number1)
  ELSE MAX(number1) END AS number1
  , CASE WHEN SUM(number2) % 2 > 0 THEN MIN(number2)
  ELSE MAX(number2) END AS number2

FROM numbers
