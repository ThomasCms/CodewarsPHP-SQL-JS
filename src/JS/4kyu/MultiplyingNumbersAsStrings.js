function multiply(a, b) {
    return a.split('').reduceRight((p, a, i) =>
            b.split('').reduceRight((p, b, j) => {
                let mul = (a - '0') * (b - '0');
                let p1 = i + j;
                let p2 = p1 + 1;
                let sum = mul + valOrZero(p[p2]);

                p[p1] = valOrZero(p[p1]) + Math.floor(sum / 10);
                p[p2] = sum % 10;

                return p;
            }, p)
        , []).join('').replace(/^0+(?=\d)/, '');
}

function valOrZero(v) {
    return v || 0;
}
