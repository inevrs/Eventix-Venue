<?php
function getIcon($key) {
    $map = [
        'wedding' => '💍',
        'corporate' => '🏢',
        'outdoor' => '🌿',
        'party' => '🎉',
        'studio' => '🎬',
        'search' => '🔎',
        'filter' => '🛠️',
        'no_reviews' => '✨',
        'dark_mode' => '🌙',
        'light_mode' => '☀️',
        'location' => '📍',
        'budget' => '💰',
        'capacity' => '👥',
        'manager' => '🧑‍💼',
        'favorite' => '❤️',
        'book' => '🗓️',
    ];

    return $map[$key] ?? '❔';
}
