function isPawn(pawn, king) {
    return king.owner && Math.abs(pawn.x - king.x) === 1 && king.y + 1=== pawn.y
        || !king.owner && Math.abs(pawn.x - king.x) === 1 && king.y === pawn.y + 1;
}

function isRook(rook, king, pieces) {
    let xMax = Math.max(rook.x, king.x),
        xMin = Math.min(rook.x, king.x),
        yMax = Math.max(rook.y, king.y),
        yMin = Math.min(rook.y, king.y);

    return rook.x === king.x && pieces.every(p => p.x !== rook.x || p.y >= yMax || p.y <= yMin)
        || rook.y === king.y && pieces.every(p => p.y !== rook.y || p.x >= xMax || p.x <= xMin)
}

function isKnight(knight, king) {
    return Math.abs(knight.x - king.x) === 1 && Math.abs(knight.y - king.y) === 2
        || Math.abs(knight.x - king.x) === 2 && Math.abs(knight.y - king.y) === 1;
}

function isBishop(bishop, king, pieces) {
    let xMax = Math.max(bishop.x, king.x),
        xMin = Math.min(bishop.x, king.x),
        yMax = Math.max(bishop.y, king.y),
        yMin = Math.min(bishop.y, king.y);

    return bishop.x - king.x === bishop.y - king.y && pieces.every(p => bishop.x - p.x !== bishop.y - p.y || p.x >= xMax || p.x <= xMin)
        || bishop.x - king.x === king.y - bishop.y && pieces.every(p => bishop.x - p.x !== p.y - bishop.y || p.x >= xMax || p.x <= xMin);
}

function isQueen(queen, king, pieces) {
    return isRook(queen, king, pieces) || isBishop(queen, king, pieces);
}

function isKing(enemyKing, king) {
    return Math.abs(enemyKing.x - king.x) < 2 && Math.abs(enemyKing.y - king.y) < 2;
}

function isPiece(p, target, pieces) {
    return {
        'pawn': (p, target, pieces) => isPawn(p, target),
        'rook': (p, target, pieces) => isRook(p, target, pieces),
        'knight': (p, target, pieces) => isKnight(p, target),
        'bishop': (p, target, pieces) => isBishop(p, target, pieces),
        'queen': (p, target, pieces) => isQueen(p, target, pieces),
        'king': (p, target, pieces) => isKing(p, target),
    }[p.piece](p, target, pieces)
}

function isCheck(pieces, player)
{
    let king = pieces.filter(p => p.piece === 'king' && p.owner === player)[0];
    let threats = pieces.filter(p => p.owner !== player && isPiece(p, king, pieces));
    return threats.length ? threats : false;
}

function checkMove(pieces, piece, x, y) {
    if (x < 0 || x > 7 || y < 0 || y > 7 || pieces.some(p => p.x === x && p.y === y && p.owner === piece.owner)) {
        return false;
    }
    pieces = pieces.filter(p => p !== piece && (p.x !== x || p.y !== y));
    pieces.push(Object.assign({}, piece, { x: x, y: y }));
    return !isCheck(pieces, piece.owner);
}

function isMate(pieces, player)
{
    let threats = isCheck(pieces, player);
    if (!threats) {
        return false;
    } else {
        let king = pieces.filter(p => p.piece === 'king' && p.owner === player)[0];
        /* Dodge */
        if (checkMove(pieces, king, king.x + 1, king.y)
            || checkMove(pieces, king, king.x + 1, king.y + 1)
            || checkMove(pieces, king, king.x, king.y + 1)
            || checkMove(pieces, king, king.x - 1, king.y)
            || checkMove(pieces, king, king.x - 1, king.y - 1)
            || checkMove(pieces, king, king.x, king.y - 1)
            || checkMove(pieces, king, king.x + 1, king.y - 1)
            || checkMove(pieces, king, king.x - 1, king.y + 1)) {
            return false;
        }

        /*  En passant */
        let pawns = pieces.filter(p => p.piece === 'pawn' && p.owner !== player && 'prevY' in p && (p.owner === 0 && p.prevY === 6 && p.y === 4 || p.owner === 1 && p.prevY === 1 && p.y === 3));
        if (pawns.map(p => [p, ...pieces.filter(p2 => p2.piece === 'pawn' && p2.owner === player && p2.y === p.y && Math.abs(p2.x - p.x) === 1)]).some(combos => {
            if (combos.length === 1) {
                return false;
            }
            return combos.slice(1).some(p => checkMove(pieces.filter(p2 => p2 !== combos[0]), p, combos[0].x, player ? p.y + 1 : p.y - 1));
        })) {
            return false;
        }
        /* Unblockable threats */
        if (threats.length > 1 && threats.some(p => p.piece === 'knight' || Math.abs(p.x - king.x) < 1 && Math.abs(p.y - king.y) < 1)) {
            return true;
        }
        let ownPieces = pieces.filter(p => p.owner === player && p.piece !== 'king');
        /* Capture threats */
        if (threats.some(t => ownPieces.some(p => isPiece(p, t, pieces) && checkMove(pieces, p, t.x, t.y)))) {
            return false;
        }
        /* Intercept threats */
        if (threats.some(t => ownPieces.some(p => {
            switch (t.piece) {
                case 'bishop':
                    if (king.x - t.x === king.y - t.y) {
                        switch (p.piece) {
                            case 'pawn':
                                if (p.x > Math.min(king.x, t.x) && p.x < Math.max(king.x, t.x)) {
                                    return !player && (p.y - 1 === t.y + p.x - t.x && checkMove(pieces, p, p.x, p.y - 1)
                                        || p.y === 6 && p.y - 2 === t.y + p.x - t.x && pieces.every(p2 => p2.x !== p.x || p2.y !== p.y - 1) && checkMove(pieces, p, p.x, p.y - 2))
                                        || player && (p.y + 1 === king.y + p.x - king.x && checkMove(pieces, p, p.x, p.y + 1)
                                            || p.y === 1 && p.y + 2 === king.y + p.x - king.x && pieces.every(p2 => p2.x !== p.x || p2.y !== p.y + 1) && checkMove(pieces, p, p.x, p.y + 2));
                                }
                                break;
                            case 'knight':
                                return checkMove(pieces, p, p.x + 1, p.y + 2)
                                    || checkMove(pieces, p, p.x + 1, p.y - 2)
                                    || checkMove(pieces, p, p.x - 1, p.y + 2)
                                    || checkMove(pieces, p, p.x - 1, p.y - 2);
                        }
                    }
            }
        }))) {
            return false;
        }
        return true;
    }
}
