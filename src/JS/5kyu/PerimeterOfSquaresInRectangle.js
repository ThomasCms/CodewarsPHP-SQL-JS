function perimeter(n) {
    let i, fib = [1, 1];

    for(i = 2; i <= n; i++) {
        fib[i] = fib[i-2] + fib[i-1];
    }

    let total = fib.reduce((a, b) => a + b);

    return 4 * total;
}
