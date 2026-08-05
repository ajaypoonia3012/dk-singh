<?php

$files = [
    'resources/views/builder/properties/homepage-cards.blade.php',
    'resources/views/livewire/builder/website-builder.blade.php',
    'resources/views/services/show.blade.php',
    'resources/views/workout-plans/show.blade.php',
];

$map = [
    'âž•' => '➕',
    'â¬†' => '⬆',
    'â¬‡' => '⬇',
    'ðŸ—‘' => '🗑',
    'ðŸ’¾' => '💾',
    'ðŸ‘' => '✅',
    'ðŸš«' => '🚫',

    'Ã°Å¸Å½Â¨' => '🎨',
    'Ã°Å¸â€“Â¥' => '🖥',
    'Ã°Å¸â€œÂ±' => '📱',
    'Ã°Å¸â€œÂ²' => '📲',

    'ÃƒÂ°Ã…Â¸Ã‚ÂÃ¢â‚¬Â¹ÃƒÂ¯Ã‚Â¸Ã‚Â' => '🔥',
    'Ãƒ°Ã…¸Ã‚Ã¢â‚¬¹Ãƒ¯Ã‚¸Ã‚' => '🔥',

    'ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¹' => '₹',
    'Ãƒ¢Ã¢â‚¬Å¡Ã‚¹' => '₹',

    'ÃƒÂ¢Ã…â€œÃ¢â‚¬Å“' => '✓',
    'Ãƒ¢Ã…â€œÃ¢â‚¬Å“' => '✓',
];

foreach ($files as $file) {

    $text = file_get_contents($file);

    foreach ($map as $bad => $good) {
        $text = str_replace($bad, $good, $text);
    }

    file_put_contents($file, $text);

    echo "Fixed: $file\n";
}