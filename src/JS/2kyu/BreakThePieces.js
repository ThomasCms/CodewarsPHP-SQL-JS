function fill (lines, i, j, old, newvalue) {
    if ((lines[i]||[])[j] === old) {
        lines[i][j] = newvalue;
        for (let di=-1; di<=1; di++) {
            for (let dj=-1; dj<=1; dj++) {
                if (di == 0 && dj == 0) {
                    continue;
                }
                fill(lines, i+di, j+dj, old, newvalue);
            }
        }
    }
}

function normalize (piece) {
    while (piece.every(function(line) {
        return line[0]==' ';
    })) {
        piece.forEach(function(line, i) {
            piece[i] = piece[i].slice(1);
        });
    }
    return piece.filter(function(line) {
        return !line.every(function(place) {
            return place==' ';
        });
    });
}

function extractX (lines, i, j) {
    function neigh (array, i,j) {
        return (array[i] || [])[j];
    }
    let piece = lines.map(function(line, i) {
        return line.map(function(value, j) {
            let result;
            if (neigh(lines, i-1,j-1) == 'x' || neigh(lines, i-1,j) == 'x' || neigh(lines, i-1,j+1) == 'x' ||
                neigh(lines, i,  j-1) == 'x' || neigh(lines, i,  j) == 'x' || neigh(lines, i  ,j+1) == 'x' ||
                neigh(lines, i+1,j-1) == 'x' || neigh(lines, i+1,j) == 'x' || neigh(lines, i+1,j+1) == 'x' ) {
                return value;
            } else
                return ' ';
        });
    });

    piece.forEach(function(line,i) {
        line.forEach(function(value,j) {
            if (piece[i][j] == '+') {
                if (neigh(piece, i-1,j) != '|' && neigh(piece, i+1,j) != '|') {
                    piece[i][j] = '-';
                }
                else if (neigh(piece, i,j-1) != '-' && neigh(piece, i,j+1) != '-') {
                    piece[i][j] = '|';
                }
            }
        });
    });
    return normalize(piece);
}


function breakPieces (shape) {
    let lines = [];
    shape.split("\n").forEach(function(line,i) {
        lines[i] = line.split('');
    });

    let pieces = [];

    for (let i=0; i<lines.length; i++) {
        fill (lines, i, 0, ' ', 'y');
        fill (lines, i, lines[i].length-1, ' ', 'y');
    }
    for (let j=0; j<lines[0].length; j++) {
        fill (lines, 0, j, ' ', 'y');
    }
    for (let j=0; j<lines[lines.length-1].length; j++) {
        fill (lines, lines.length-1, j, ' ', 'y');
    }

    for (let i=0; i<lines.length; i++) {
        for (let j=0; j<lines[i].length; j++) {
            if (lines[i][j] != ' ') {
                continue;
            }
            let piece;
            fill (lines, i, j,' ', 'x');
            piece = normalize(extractX(lines, i, j));
            pieces.push(piece.map(function(line) {
                return line.join('').replace(/\s+$/, '');
            }).join("\n").replace(/x/g, ' '));
            fill (lines, i, j, 'x', 'y');
        }
    }
    return pieces;
}