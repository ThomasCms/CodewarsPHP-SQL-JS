snail = function(array) {
    let result;
    while (array.length) {
        // Steal the first row.
        result = (result ? result.concat(array.shift()) : array.shift());
        // Steal the right items.
        for (let i = 0; i < array.length; i++)
            result.push(array[i].pop());
        // Steal the bottom row.
        result = result.concat((array.pop() || []).reverse());
        // Steal the left items.
        for (let i = array.length - 1; i >= 0; i--)
            result.push(array[i].shift());
    }
    return result;
}
