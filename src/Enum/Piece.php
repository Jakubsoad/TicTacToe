<?php

namespace App\Enum;

enum Piece: string
{
    case X = 'X';
    case O = 'O';
    case NONE = '';
}