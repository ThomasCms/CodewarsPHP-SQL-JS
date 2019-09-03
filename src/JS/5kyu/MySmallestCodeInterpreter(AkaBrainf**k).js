function brainLuck(code, input){
    code = code.split('');
    input = input.split('').map(char => char.charCodeAt(0));
    let data = [0], di = 0, output = '', jumps = {}, stack = [], i = 0;
    let fn = {
        '>' : (i) => { di++; if(di==data.length) data.push(0); return i; },
        '<' : (i) => { di--; if(di==-1) throw 'Out of memory.'; return i; },
        '+' : (i) => { data[di]++; return i; },
        '-' : (i) => { data[di]--; return i; },
        '.' : (i) => { output += String.fromCharCode(data[di]%256); return i; },
        ',' : (i) => { if (input.length == 0) throw 'Unexpected end of input.'; data[di] = input.shift(); return i; },
        '[' : (i) => (data[di]%256) ? i : jumps[i],
        ']' : (i) => (data[di]%256) ? jumps[i] : i
    };
    code.forEach((x,i) => {
        if (x === '[') stack.push(i);
        if (x === ']') {
            if (stack.length == 0) {
                throw 'Brackets is unbalanced.';
            }

            let j = stack.pop();
            jumps[i] = j; jumps[j] = i;
        }
    });
    if (stack.length !== 0) {
        throw 'Brackets is unbalanced.';
    }
    while(i<code.length) {
        i = fn[code[i]](i) + 1;
    }
    return output;
}
