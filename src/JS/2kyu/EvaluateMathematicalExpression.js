let calc = function(expression) {
    let tokens = expression.match(/\d+(?:\.\d+)?|[-+*/\(\)]/g).map(x => (isNaN(x) ? x : +x));

    let math = (op, x, y) => {
        switch (op) {
            case '/':
                return x / y;
            case '*':
                return x * y;
            case '+':
                return x + y;
            case '-':
                return x - y;
        }
        return x;
    };

    let accept = (...args) => args.some(t => t === tokens[0]) && tokens.shift();
    let acceptN = () => !isNaN(tokens[0]) && tokens.shift();

    let unit = () => (accept('(') ? ((e = start()), accept(')'), e) : acceptN());
    let number = () => (accept('-') ? -unit() : unit());

    let fac = () => accept('/', '*');
    let sum = () => accept('+', '-');

    let multi = () => {
        let n = number();
        let op;
        while ((op = fac())) {
            n = math(op, n, number());
        }
        return n;
    };
    let start = () => {
        let n = multi();
        let op;
        while ((op = sum())) {
            n = math(op, n, multi());
        }
        return n;
    };

    return start();
};
