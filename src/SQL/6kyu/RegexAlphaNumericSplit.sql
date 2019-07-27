SELECT project,
       regexp_replace(address, '[^A-Za-z]', '', 'g') AS letters,
       regexp_replace(address, '[^0-9]', '', 'g') AS numbers
FROM repositories;