function interpret(code) {
    code = code.split('\n').map(row => row.split(''));
    let output = "", stack = [], i = 0, j = 0, ch = code[0][0], dirs = [[0,1],[0,-1],[-1,0],[1,0]], dir = dirs[0];
    let next = () => {
        i = (i+dir[0]+code.length)%code.length;
        j = (j+dir[1]+code[i].length)%code[i].length;
        return code[i][j];
    };

    while (ch !== '@') {
        if (/[0-9]/.test(ch)) {
            stack.push(+ch);
        }
        if (/[\+\-\*%]/.test(ch)) {
            let a = stack.pop(), b = stack.pop(); stack.push(eval('b'+ch+'a'))
        }
        if ('/' === ch) {
            let a = stack.pop(), b = stack.pop();
            stack.push(a ? b/a>>0 : 0);
        }
        if ('!' === ch) {
            stack.push(stack.pop() ? 0 : 1);
        }
        if ('`' === ch) {
            let a = stack.pop(), b = stack.pop();
            stack.push(b > a ? 1 : 0);
        }
        if ('>' === ch) {
            dir = [0, 1];
        }
        if ('<' === ch) {
            dir = [0,-1];
        }
        if ('^' === ch) {
            dir = [-1,0];
        }
        if ('v' === ch) {
            dir = [ 1,0];
        }
        if ('?' === ch) {
            dir = dirs[Math.random()*4>>0];
        }
        if ('_' === ch) {
            dir = stack.pop() === 0 ? [0, 1] : [0,-1];
        }
        if ('|' === ch) {
            dir = stack.pop() === 0 ? [1, 0] : [-1,0];
        }
        if (':' === ch) {
            let a = stack.pop()||0;
            stack.push(a, a);
        }
        if ('\\' === ch) {
            let a = stack.pop(), b = stack.pop()||0; stack.push(a, b);
        }
        if ('$' === ch) {
            stack.pop();
        }
        if ('.' === ch) {
            output += stack.pop();
        }
        if (',' === ch) {
            output += String.fromCharCode(stack.pop());
        }
        if ('p' === ch) {
            let y = stack.pop(), x = stack.pop();
            code[y][x] = String.fromCharCode(stack.pop());
        }
        if ('g' === ch) {
            let y = stack.pop(), x = stack.pop();
            stack.push(code[y][x].charCodeAt(0));
        }
        if ('"' === ch) {
            while((ch = next()) !== '"') {
                stack.push(ch.charCodeAt(0));
            }
        }
        if ('#' === ch) {
            ch = next();
        }
        ch = next();
    }
    return output;
}
