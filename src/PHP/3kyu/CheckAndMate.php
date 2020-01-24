<?php

const BLACK_PIECE = 1; // start on top
const WHITE_PIECE = 0; // start on bottom

/**
 * @param callable $func
 * @param array $pieces
 * @return array|false
 */
function findPieceFunc(callable $func, array $pieces)
{
    foreach ($pieces as $piece) {
        if (call_user_func($func, $piece)) {
            return $piece;
        }
    }
    return false;
}

/**
 * @param array $pieceAttributes
 * @param array $pieces
 * @return array|false
 */
function findPieceAttr(array $pieceAttributes, array $pieces)
{
    foreach ($pieces as $piece) {
        $valid = true;
        foreach ($pieceAttributes as $attr => $value) {
            if ($piece[$attr] !== $value) {
                $valid = false;
                break;
            }
        }
        if ($valid) {
            return $piece;
        }
    }
    return false;
}

/**
 * @param array $pieces
 * @param array $piecesToRemove
 * @return array
 */
function removePieces(array $pieces, array $piecesToRemove): array
{
    $result = [];
    foreach ($pieces as $piece) {
        if (!in_array($piece, $piecesToRemove)) {
            $result[] = $piece;
        }
    }
    return $result;
}

/**
 * @param array $pieceAttack
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 * @throws Exception
 */
function canAttack(array $pieceAttack, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    if ($pieceAttack['owner'] === $pieceDefence['owner']) {
        return false;
    }
    switch ($pieceAttack['piece']) {
        case 'pawn':
            return pawnCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
        case 'rook':
            return rookCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
        case 'knight':
            return knightCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
        case 'bishop':
            return bishopCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
        case 'queen':
            return queenCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
        case 'king':
            return kingCanAttack($pieceAttack, $pieceDefence, $otherPieces, $newPieces);
    }
    throw new Exception(sprintf('Invalid piece: %s', $pieceAttack['piece']));
}

/**
 * @param array $piece
 * @param array $position
 * @return array
 */
function at(array $piece, array $position)
{
    $newPiece = $piece;
    $newPiece['x'] = $position['x'];
    $newPiece['y'] = $position['y'];
    return $newPiece;
}

/**
 * @param array $pawn
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function pawnCanAttack(array $pawn, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    $attackDirection = $pawn['owner'] === BLACK_PIECE ? +1 : -1;

    if ($pieceDefence['piece'] === 'pawn' && isset($pieceDefence['prevY']) &&
        abs($pieceDefence['y'] - $pieceDefence['prevY']) === 2) {
        $passantDirection = $pieceDefence['owner'] === BLACK_PIECE ? +1 : -1;
        $passantY = $pieceDefence['prevY'] + $passantDirection;
        $passantX = $pieceDefence['prevX'];
        $canAttack = ($passantY === $pawn['y'] + $attackDirection) &&
            abs($passantX - $pawn['x']) === 1;
        if ($canAttack) {
            $newPieces = array_merge($otherPieces, [at($pawn, ['x' => $passantX, 'y' => $passantY])]);
            return true;
        }
    }

    $canAttack = ($pieceDefence['y'] === $pawn['y'] + $attackDirection) &&
        abs($pieceDefence['x'] - $pawn['x']) === 1;

    if ($canAttack) {
        $newPieces = array_merge($otherPieces, [at($pawn, $pieceDefence)]);
    }
    return $canAttack;
}

/**
 * @param array $king
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function kingCanAttack(array $king, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    $canAttack = abs($pieceDefence['x'] - $king['x']) <= 1
        && abs($pieceDefence['y'] - $king['y']) <= 1;
    if ($canAttack) {
        $newPieces = array_merge($otherPieces, [at($king, $pieceDefence)]);
    }
    return $canAttack;
}

/**
 * @param array $rook
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function rookCanAttack(array $rook, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    $sameColumnBetweenLines = function (array $p) use ($rook, $pieceDefence): bool {
        $minY = min($rook['y'], $pieceDefence['y']);
        $maxY = max($rook['y'], $pieceDefence['y']);
        return $p['x'] === $rook['x'] && $p['y'] > $minY && $p['y'] < $maxY;
    };
    $sameLineBetweenColumns = function (array $p) use ($rook, $pieceDefence): bool {
        $minX = min($rook['x'], $pieceDefence['x']);
        $maxX = max($rook['x'], $pieceDefence['x']);
        return $p['y'] === $rook['y'] && $p['x'] > $minX && $p['x'] < $maxX;
    };

    $canAttack = ($rook['x'] === $pieceDefence['x'] && !findPieceFunc($sameColumnBetweenLines, $otherPieces))
        || ($rook['y'] === $pieceDefence['y'] && !findPieceFunc($sameLineBetweenColumns, $otherPieces));
    if ($canAttack) {
        $newPieces = array_merge($otherPieces, [at($rook, $pieceDefence)]);
    }
    return $canAttack;
}

/**
 * @param array $knight
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function knightCanAttack(array $knight, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    $xDist = abs($knight['x'] - $pieceDefence['x']);
    $yDist = abs($knight['y'] - $pieceDefence['y']);
    $canAttack = min($xDist, $yDist) === 1 && max($xDist, $yDist) === 2;
    if ($canAttack) {
        $newPieces = array_merge($otherPieces, [at($knight, $pieceDefence)]);
    }
    return $canAttack;
}

/**
 * @param array $piece1
 * @param array $piece2
 * @return bool
 */
function inDiagonal(array $piece1, array $piece2): bool
{
    return abs($piece1['x'] - $piece2['x']) === abs($piece1['y'] - $piece2['y']);
}

/**
 * @param array $bishop
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function bishopCanAttack(array $bishop, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    $betweenPieces = function (array $p) use ($bishop, $pieceDefence): bool {
        $minX = min($bishop['x'], $pieceDefence['x']);
        $maxX = max($bishop['x'], $pieceDefence['x']);
        $minY = min($bishop['y'], $pieceDefence['y']);
        $maxY = max($bishop['y'], $pieceDefence['y']);
        return inDiagonal($p, $bishop)
            && inDiagonal($p, $pieceDefence)
            && $p['x'] > $minX && $p['x'] < $maxX
            && $p['y'] > $minY && $p['y'] < $maxY;
    };
    $canAttack = inDiagonal($bishop, $pieceDefence) && !findPieceFunc($betweenPieces, $otherPieces);
    if ($canAttack) {
        $newPieces = array_merge($otherPieces, [at($bishop, $pieceDefence)]);
    }
    return $canAttack;
}

/**
 * @param array $queen
 * @param array $pieceDefence
 * @param array $otherPieces
 * @param array $newPieces
 * @return bool
 */
function queenCanAttack(array $queen, array $pieceDefence, array $otherPieces, array &$newPieces): bool
{
    return rookCanAttack($queen, $pieceDefence, $otherPieces, $newPieces)
        || bishopCanAttack($queen, $pieceDefence, $otherPieces, $newPieces);
}

/**
 * @param array $piece
 * @param array $position
 * @param array $otherPieces
 * @return bool
 * @throws Exception
 */
function canMove(array $piece, array $position, array $otherPieces): bool
{
    if ($piece['piece'] === 'pawn') {
        if (findPieceAttr($position, $otherPieces)) {
            return false;
        }
        $attackDirection = $pawn['owner'] === BLACK_PIECE ? +1 : -1;
        if ($piece['x'] === $position['x']) {
            return $piece['y'] + $attackDirection === $position['y']
                || ($piece['y'] + 2 * $attackDiraction === $position['y'] &&
                    !findPieceAttr(['x' => $position['x'], 'y' => $piece['y'] + $attackDirection], $otherPieces));
        }
    }

    $fakeOponent = array_merge(
        $position,
        ['owner' => 1 - $piece['owner']]
    );

    $newPieces = [];
    return canAttack($piece, $fakeOponent, $otherPieces, $newPieces);
}

/**
 * @param array $pieces
 * @param int $player
 * @return array|bool
 * @throws Exception
 */
function isCheck(array $pieces, int $player)
{
    $king = findPieceAttr(['piece' => 'king', 'owner' => $player], $pieces);

    $result = [];
    foreach ($pieces as $piece) {
        $otherPieces = removePieces($pieces, [$king, $piece]);
        $newPieces = [];
        if (canAttack($piece, $king, $otherPieces, $newPieces)) {
            $result[] = $piece;
        }
    }

    return empty($result) ? false: $result;
}

/**
 * @param array $piece
 * @return bool
 */
function validPosition(array $piece): bool
{
    return $piece['x'] >= 0 && $piece['x'] <= 7 && $piece['y'] >= 0 && $piece['y'] <= 7;
}

/**
 * @param array $king
 * @param array $pieces
 * @return bool
 * @throws Exception
 */
function kingCanMove(array $king, array $pieces): bool
{
    for ($x = -1; $x <= 1; $x++) {
        for ($y = -1; $y <= 1; $y++) {
            if ($x === 0 && $y === 0) {
                continue;
            }

            $newKing = $king;
            $newKing['x'] += $x;
            $newKing['y'] += $y;

            if (!validPosition($newKing)) {
                continue;
            }

            $newPieces = removePieces($pieces, [$king]);

            $samePosition = [
                'x' => $newKing['x'],
                'y' => $newKing['y'],
            ];
            $pieceSamePosition = findPieceAttr($samePosition, $newPieces);

            if ($pieceSamePosition) {
                if ($pieceSamePosition['owner'] == $newKing['owner']) {
                    continue;
                } else {
                    $newPieces = removePieces($newPieces, [$pieceSamePosition]);
                    $newPieces[] = $newKing;
                }
            } else {
                $newPieces[] = $newKing;
            }

            if (!isCheck($newPieces, $newKing['owner'])) {
                return true;
            }
        }
    }
    return false;
}

/**
 * @param array $piece1
 * @param array $piece2
 * @return array
 */
function positionsBetweenPieces(array $piece1, array $piece2): array
{
    if ($piece1['y'] === $piece2['y']) {
        $result = [];
        $minX = min($piece1['x'], $piece2['x']);
        $maxX = max($piece1['x'], $piece2['x']);
        for ($x = $minX + 1; $x < $maxX; $x++) {
            $result[] = ['x' => $x, 'y' => $piece1['y']];
        }
        return $result;
    }

    if ($piece1['x'] === $piece2['x']) {
        $result = [];
        $minY = min($piece1['y'], $piece2['y']);
        $maxY = max($piece1['y'], $piece2['y']);
        for ($y = $minY + 1; $y < $maxY; $y++) {
            $result[] = ['x' => $piece1['x'], 'y' => $y];
        }
        return $result;
    }

    if (inDiagonal($piece1, $piece2)) {
        $result = [];
        $minX = min($piece1['x'], $piece2['x']);
        $maxX = max($piece1['x'], $piece2['x']);
        $minY = min($piece1['y'], $piece2['y']);
        $maxY = max($piece1['y'], $piece2['y']);

        for ($x = $minX + 1, $y = $minY + 1; $x < $maxX; $x++, $y++) {
            $result[] = ['x' => $x, 'y' => $y];
        }
        return $result;
    }

    return [];
}

/**
 * @param array $pieces
 * @param int $player
 * @return bool
 * @throws Exception
 */
function isMate(array $pieces, int $player): bool
{
    $piecesAttack = isCheck($pieces, $player);
    if (!$piecesAttack) {
        return false;
    }

    $king = findPieceAttr(['piece' => 'king', 'owner' => $player], $pieces);
    if (kingCanMove($king, $pieces)) {
        return false;
    }

    if (count($piecesAttack) === 1) {
        $pieceAttack = $piecesAttack[0];
        $teammateFunc = function ($p) use ($player) {
            return $p['owner'] === $player;
        };
        $playerPieces = array_filter($pieces, $teammateFunc);

        foreach ($playerPieces as $playerPiece) {
            $newPieces = [];
            if (canAttack(
                $playerPiece,
                $pieceAttack,
                removePieces(
                    $pieces,
                    [$playerPiece, $pieceAttack]
                ),
                $newPieces
            )) {
                if (!isCheck($newPieces, $player)) {
                    return false;
                }
            }
        }

        if (in_array($pieceAttack['piece'], ['queen', 'bishop', 'rook'])) {
            $safePositions = positionsBetweenPieces($king, $pieceAttack);
            foreach ($playerPieces as $playerPiece) {
                $otherPieces = removePieces($pieces, [$playerPiece]);
                foreach ($safePositions as $safePosition) {
                    if (canMove($playerPiece, $safePosition, $otherPieces)) {
                        $newPlayerPiece = at($playerPiece, $safePosition);

                        $newPieces = $otherPieces;
                        $newPieces[] = $newPlayerPiece;

                        if (!isCheck($newPieces, $player)) {
                            return false;
                        }
                    }
                }
            }
        }
    }

    return true;
}
