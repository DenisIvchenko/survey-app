<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Stop Words
    |--------------------------------------------------------------------------
    |
    | Comments containing any of these words (case-insensitive) are rejected
    | during automatic moderation.
    |
    */

    'stop_words' => [
        'spam',
        'scam',
        'viagra',
        'casino',
        'click here',
        'free money',
        'спам',
        'мошенник',
        'казино',
    ],

];
