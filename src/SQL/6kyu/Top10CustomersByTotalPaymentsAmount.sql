SELECT customer.customer_id, customer.email, COUNT(payment_id) AS "payments_count", CAST(SUM(amount) AS FLOAT) AS "total_amount"
FROM customer JOIN payment ON customer.customer_id = payment.customer_id
GROUP BY customer.customer_id
ORDER BY total_amount desc
LIMIT 10
