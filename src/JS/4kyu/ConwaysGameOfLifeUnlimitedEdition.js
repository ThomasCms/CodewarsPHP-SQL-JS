function getGeneration(cells, generations) {
    let field = JSON.parse(JSON.stringify(cells));
    for (let i = 0; i < generations; i++) {
        field = generationStep(field);
    }
    return cropField(field);
}

function generationStep(field) {
    prepareField(field);
    return field.map((row, i) => {
        return row.map((cell, j) => {
            let prevRow = field[i - 1] || [];
            let nextRow = field[i + 1] || [];
            let neighbours = (row[j - 1] || 0) + (row[j + 1] || 0) +
                (prevRow[j - 1] || 0) + (prevRow[j] || 0) + (prevRow[j + 1] || 0) +
                (nextRow[j - 1] || 0) + (nextRow[j] || 0) + (nextRow[j + 1] || 0);
            return neighbours === 3 || (neighbours === 2 && cell) ? 1 : 0;
        });
    });
}

function prepareField(field) {
    if (field[0].some(cell => cell)) {
        field.unshift(field[0].map(() => 0));
    }
    if (field[field.length - 1].some(cell => cell)) {
        field.push(field[0].map(() => 0));
    }
    if (field.some(row => row[0])) {
        field.forEach(row => row.unshift(0));
    }
    if (field.some(row => row[row.length - 1])) {
        field.forEach(row => row.push(0));
    }
}

function cropField(field) {
    let top, left, bottom, right;
    for (let i = 0; i < field.length; i++) {
        for (let j = 0; j < field[i].length; j++) {
            if (top === undefined && field[i][j]) {
                top = i;
            }
            if (bottom === undefined && field[field.length - 1 - i][j]) {
                bottom = field.length - 1 - i;
            }
        }
    }

    for (let i = 0; i < field[0].length; i++) {
        for (let j = 0; j < field.length; j++) {
            if (left === undefined && field[j][i]) {
                left = i;
            }
            if (right === undefined && field[j][field[0].length - 1 - i]) {
                right = field[0].length - 1 - i;
            }
        }
    }
    if (top > bottom || left > right) return [[]]; {
        field = field.slice(top, bottom + 1).map(row => row.slice(left, right + 1));
    }
    return field;
}
