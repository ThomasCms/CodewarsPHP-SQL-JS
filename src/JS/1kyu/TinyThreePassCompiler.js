function Compiler() {
    this.params = [];
};

Compiler.prototype.compile = function (program) {
    return this.pass3(this.pass2(this.pass1(program)));
};

Compiler.prototype.tokenize = function (program) {
    let regex = /\s*([-+*/\(\)\[\]]|[A-Za-z]+|[0-9]+)\s*/g;

    return program.replace(regex, ":$1").substring(1).split(':').map(function (tok) {
        return isNaN(tok) ? tok : tok | 0;
    });
};

Compiler.prototype.pass1 = function (program, inner = false) {
    let tokens = this.tokenize(program);
    let sidx = tokens.indexOf('[');
    let eidx = tokens.indexOf(']');

    if (sidx >= 0 && eidx >= 0) {
        this.params = tokens.splice(sidx, eidx - sidx + 1);
        this.params.splice(0, 1);
        this.params.splice(this.params.length - 1, 1);
    }

    while (tokens.includes('(')) {
        let pcnt = 0;
        pstart = -1;
        pend = -1;

        for (let i = 0; i < tokens.length; i++) {
            if (tokens[i] === '(') {
                if (pcnt === 0)
                    pstart = i;
                pcnt++;
            }
            if (tokens[i] === ')') {
                if (pcnt === 1) {
                    pend = i;
                    break;
                }

                pcnt--;
            }
        }
        let v = tokens.splice(pstart, pend - pstart + 1);
        v.splice(0, 1);
        v.splice(v.length - 1, 1);
        let nv = this.pass1(v.join(''), true);
        tokens.splice(pstart, 0, nv);
    }

    for (i = 0; i < tokens.length; i++) {
        if (tokens[i] === '*' || tokens[i] === '/' || tokens[i] === '+' || tokens[i] === '-') {
            continue;
        }

        v = tokens[i];

        if (typeof v === 'object') {
            continue;
        }

        if (Number.isNaN(Number(v))) {
            tokens.splice(i, 1, { "op": "arg", "n": this.params.indexOf(v) });
        } else {
            tokens.splice(i, 1, { "op": "imm", "n": Number(v) });
        }

    }
    for (i = 0; i <tokens.length; i++) {
        if (tokens[i] === '*' || tokens[i] === '/' ) {
            let prev = tokens[i - 1];
            let next = tokens[i + 1];
            nv = { "op": tokens[i], "a": prev, "b": next };
            tokens.splice(i - 1, 3, nv);
            i--;
        }
    }
    for (i = 0; i < tokens.length; i++) {
        if (tokens[i] === '+' || tokens[i] === '-') {
            prev = tokens[i - 1];
            next = tokens[i + 1];
            nv = { "op": tokens[i], "a": prev, "b": next };
            tokens.splice(i - 1, 3, nv);
            i--;
        }
    }
    return tokens[0];
};

Compiler.prototype.pass2_part = function (ast, inner = false) {
    if (!inner) {
        this.stopPart2 = true;
    }
    if (ast.op === 'imm' || ast.op === 'arg')
        return ast;
    let a = ast.a;
    let b = ast.b;
    if (a.op === 'imm' && b.op === "imm") {
        this.stopPart2 = false;
        let obj = { 'op': 'imm' };
        switch (ast.op) {
            case '+':
                obj.n = a.n + b.n;
                break;
            case '-':
                obj.n = a.n - b.n;
                break;
            case '*':
                obj.n = a.n * b.n;
                break;
            case '/':
                obj.n = a.n / b.n;
                break;
        }
        ast.op = obj.op;
        ast.n = obj.n;
        delete ast.a;
        delete ast.b;
        return ast;
    }
    ast.a = this.pass2_part(ast.a, true);
    ast.b = this.pass2_part(ast.b, true);
    return ast;
};

Compiler.prototype.pass2 = function (ast) {
    while (!this.stopPart2)
        ast = this.pass2_part(ast);
    return ast;
};

Compiler.prototype.pass3 = function (ast, inner = false) {
    let res = [];
    switch (ast.op) {
        case 'imm':
            return "IM " + ast.n;
        case 'arg':
            return "AR " + ast.n;
        case '+':
            res = res.concat(this.pass3(ast.a, false));
            res.push('PU');
            res = res.concat(this.pass3(ast.b, false));
            res.push('SW');
            res.push('PO');
            res.push('AD');
            return res;
        case '-':
            res = res.concat(this.pass3(ast.a, false));
            res.push('PU');
            res = res.concat(this.pass3(ast.b, false));
            res.push('SW');
            res.push('PO');
            res.push('SU');
            return res;
        case '*':
            res = res.concat(this.pass3(ast.a, false));
            res.push('PU');
            res = res.concat(this.pass3(ast.b, false));
            res.push('SW');
            res.push('PO');
            res.push('MU');
            return res;
        case '/':
            res = res.concat(this.pass3(ast.a, false));
            res.push('PU');
            res = res.concat(this.pass3(ast.b, false));
            res.push('SW');
            res.push('PO');
            res.push('DI');
            return res;
    }
};
