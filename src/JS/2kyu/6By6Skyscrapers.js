const generateAllPossibleRows = numbers => {
    const result = [];

    const permute = (arr, m = []) => {
        if (arr.length) {
            arr.forEach((x, i) => {
                const curr = arr.slice();
                const next = curr.splice(i, 1);
                permute(curr.slice(), m.concat(next))
            })
        } else {
            result.push(m)
        }
    };

    permute(numbers);
    return result
};

const housesInRow = row => row.reduce((acc, current, i) => current > Math.max(...row.slice(0, i)) ? ++acc : acc, 0);

const getGroupedPossibleRows = numbers => {
    const all = generateAllPossibleRows(numbers);
    const result = all.reduce((a, b) => {
        const houses = housesInRow(b);
        if (!a[houses]) {
            a[houses] = []
        }
        a[houses].push(b);
        return a;
    }, {});
    result[0] = all;
    return result
};

const buildBoard = size => {
    let a = 0
    const CELL = {
        value: null,
        possibleValues: Array.from(new Array(size)).map((d, i) => i + 1)
    };
    return Array.from(new Array(size)).map(row => Array.from(new Array(size)).map(() => Object.assign({}, CELL, {index: a++})))
};

const getRow = (board, index) => {
    const sideWidth = board.length
    const side = Math.trunc(index / sideWidth)
    const sideIndex = index - side * board.length
    switch (side) {
        case 0:
            return getColumn(board, sideIndex)
        case 1:
            return board[sideIndex].slice().reverse()
        case 2:
            return getColumn(board, sideWidth - sideIndex - 1).reverse()
        case 3:
            return board[sideWidth - sideIndex - 1]
    }
};

const getColumn = (array, index) => {
    return array.reduce((acc, current) => {
        acc.push(current[index]);
        return acc
    }, [])
};

const setRow = (board, index, newRow) => {
    const row = getRow(board, index)
    row.forEach((cell, index) => Object.assign(cell, newRow[index]))
};

const populateRowWithPossibleValues = (row, possibleValues) => {
    return row.map((cell, index) => {
        const values = Array.from(new Set(getColumn(possibleValues, index))).sort()
        const mergedValues = cell.possibleValues.filter((value) => values.includes(value))
        const value = mergedValues.length === 1 ? mergedValues[0] : null
        return Object.assign({}, cell, {possibleValues: mergedValues, value})
    })
};

const getPossibleRows = (row, possibleRows, board, debug) =>
    possibleRows.filter(possibleRow =>
        possibleRow.every((value, index) => {
            if (debug && row[index].index === 24) {
                console.log(value)
                console.log(row[index].possibleValues.includes(value))
            }
            if (row[index].value) {
                return row[index].possibleValues.includes(value)
            }
            const cellIndex = row[index].index;
            const firstRowValues = getRow(board, cellIndex % board.length)
                .map(x => x.value)
                .filter(x => x);
            const secondRowValues = getRow(board, Math.trunc(cellIndex / board.length) + board.length)
                .map(x => x.value)
                .filter(x => x);
            const mergedValues = new Set([...firstRowValues, ...secondRowValues])
            return row[index].possibleValues.includes(value) && !mergedValues.has(value)
        }))

const setPossibleRows = (board, value, index, possibleRows, array) => {
    const row = getRow(board, index)
    const rows = getPossibleRows(row, possibleRows[value], board)
    const populatedRow = populateRowWithPossibleValues(row, rows)

    if (value) {
        const sideWidth = board.length;
        const side = Math.trunc(index / sideWidth);
        const sideIndex = index - side * board.length;
        const oppositeIndex = sideWidth * (side + 2) + sideWidth - sideIndex - 1;
        const oppositeValue = array[oppositeIndex];
        if (oppositeValue) {
            const oppositeRow = getRow(board, oppositeIndex);
            const oppositeRows = getPossibleRows(oppositeRow, possibleRows[oppositeValue], board).map(x => x.slice().reverse());
            const mergedValues = rows.filter((row) => oppositeRows.some(oppositeRow => isArraysEqual(row, oppositeRow)));
            const populatedRow = populateRowWithPossibleValues(row, mergedValues);
            return setRow(board, index, populatedRow)
        }
    }

    setRow(board, index, populatedRow)
};

const isArraysEqual = (a, b) => a.every((val, i) => val === b[i]);

const isSolved = board => board.every(row => row.every(cell => cell.value));

const solvePuzzle = (array) => {
    const SIZE = array.length / 4;
    const possibleRows = getGroupedPossibleRows(Array.from(new Array(SIZE)).map((d, i) => i + 1));
    const board = buildBoard(SIZE);

    array.forEach((value, index) => {
        if (value) {
            const row = getRow(board, index)
            const populatedRow = populateRowWithPossibleValues(row, possibleRows[value]);
            setRow(board, index, populatedRow)
        }
    });

    array.forEach((value, index) => {
        if (value) {
            setPossibleRows(board, value, index, possibleRows, array)
        }
    });

    while(!isSolved(board)) {
        array.forEach((value, index) => {
            setPossibleRows(board, value, index, possibleRows, array)
        })
    }
    return board.map(row => row.map(cell => cell.value))
};
