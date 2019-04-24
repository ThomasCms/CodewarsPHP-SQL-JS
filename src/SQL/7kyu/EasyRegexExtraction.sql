SELECT name, greeting, SUBSTRING(SUBSTRING(greeting FROM '#[0-9]+') FROM '[0-9]+') AS user_id FROM greetings;
