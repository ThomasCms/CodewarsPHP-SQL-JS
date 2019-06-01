/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 19:11
 */

 SELECT
  id,
  name,
  TRIM(',' FROM SPLIT_PART(characteristics, ' ', 1)) as characteristic
FROM monsters
ORDER BY id;
