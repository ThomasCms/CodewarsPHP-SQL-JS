function findUniq(arr) {
    let [a,b,c] = arr.slice(0,3);

    if (!similar(a,b) && !similar(a,c)) return a;
    for (d of arr) if (!similar(a,d)) return d;
}

function similar (x, y) {
    x = new Set(x.toLowerCase());
    y = new Set(y.toLowerCase());

    if (x.size !== y.size) return false;
    for (z of x) if (!y.has(z)) return false;

    return true;
}
