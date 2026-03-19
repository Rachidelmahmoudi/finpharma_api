<?php

namespace App\State;

enum OpenMode : string {
    case NIGHT = 'night';
    case DAY = 'day';
    case OPEN_24 = 'open_24';
}