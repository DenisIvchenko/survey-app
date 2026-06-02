<?php

return [
    // Длина текста
    'min_length'                     => env('MODERATION_MIN_LENGTH', 15),
    'max_length'                     => env('MODERATION_MAX_LENGTH', 1500),
    
    // Стоп-слова (массив)
    'stop_words'                     => env('MODERATION_STOP_WORDS', '') 
        ? explode(',', env('MODERATION_STOP_WORDS')) 
        : ['спам', 'казино', 'крипта'],
    
    // Пороговые значения скоринга
    'auto_approve_threshold'         => env('MODERATION_APPROVE_THRESHOLD', 0.8),
    'auto_reject_threshold'          => env('MODERATION_REJECT_THRESHOLD', 0.4),
    
    // Rate limiting
    'max_reviews_per_ip_per_hour'    => env('MODERATION_MAX_PER_IP_PER_HOUR', 10),
    'rate_limit_window_hours'        => env('MODERATION_RATE_LIMIT_WINDOW', 1),
    
    // Дубликаты
    'duplicate_window_hours'         => env('MODERATION_DUPLICATE_WINDOW', 24),
];