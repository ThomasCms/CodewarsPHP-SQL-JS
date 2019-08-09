function parseMolecule(formula) {
    var group, tokens, tokenExp = /([{(\[]|[})\]]|[A-Z][a-z]?)(\d*)/g, stack = [[]];
    while (tokens = tokenExp.exec(formula)) {
        tokens[2] = tokens[2] || 1;
        if (/^[A-Z]/.test(tokens[1])) {
            while (tokens[2]--) stack.push(stack.pop().concat([tokens[1]]));
        } else if (/[{\(\[]/.test(tokens[1])) {
            stack.push([]);
        } else {
            group = stack.pop();
            while (tokens[2]--) stack.push(stack.pop().concat(group))
        }
    }
    return stack[0].reduce(function (count, x) {
        count[x] = (count[x] || 0) + 1;
        return count;
    }, {});
}
