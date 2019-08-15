function sudoku(puzzle) {
    for (let i = 0; i < 9; i++) {
        for (let j = 0; j < 9; j++) {
            if (puzzle[i][j] === 0) {
                for (let k = 1; k <= 9; k++) {
                    if (isValid(puzzle, i, j, k)) {
                        puzzle[i][j] = k;
                        if (sudoku(puzzle)) {
                            return puzzle;
                        } else {
                            puzzle[i][j] = 0;
                        }
                    }
                }
                return false;
            }
        }
    }
    return puzzle;
}

function isValid(board, row, col, k) {
    for (let i = 0; i < 9; i++) {
        const m = 3 * Math.floor(row / 3) + Math.floor(i / 3);
        const n = 3 * Math.floor(col / 3) + i % 3;
        if (board[row][i] === k || board[i][col] === k || board[m][n] === k) {
            return false;
        }
    }
    return true;
}
