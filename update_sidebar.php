<?php

$filePath = 'c:\laragon\www\collection-system\resources\views\layouts\app.blade.php';
$content = file_get_contents($filePath);

// We want to find:
// <div class="space-y-2">
//    <h3 class="...">
//        <span>TITLE</span>
//        <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
//    </h3>
//    <div class="space-y-1">
//       ... links
//    </div>
// </div>

// First, find all such groups.
// But we need the route checks. We'll extract them from the links inside.
$pattern = '/<div class="space-y-2">\s*<h3 class="px-4 text-\[11px\] font-black text-gray-400 dark:text-gray-500 uppercase tracking-\[2px\] mb-3 flex items-center gap-2 sidebar-group-header">\s*<span>([^<]+)<\/span>\s*<div class="h-px bg-gray-100 dark:bg-dark-border flex-1"><\/div>\s*<\/h3>\s*<div class="space-y-1">(.*?)<\/div>\s*<\/div>/s';

$content = preg_replace_callback($pattern, function ($matches) {
    $title = trim($matches[1]);
    $linksHtml = $matches[2];

    // Extract route checks from links. Example: request()->routeIs('foo.*')
    preg_match_all("/request\(\)->routeIs\('([^']+)'\)/", $linksHtml, $routeMatches);
    $routes = array_unique($routeMatches[1]);

    $routeCondition = 'false';
    if (! empty($routes)) {
        $routesList = implode("', '", $routes);
        $routeCondition = "{{ request()->routeIs('{$routesList}') ? 'true' : 'false' }}";
    }

    // Build the new collapsible structure
    $newHtml = <<<HTML
<div x-data="{ open: {$routeCondition} }" class="space-y-2">
    <button @click="open = !open" type="button" class="w-full px-4 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[2px] mb-3 flex items-center justify-between gap-2 sidebar-group-header hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
        <div class="flex items-center gap-2 flex-1">
            <span>{$title}</span>
            <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
        </div>
        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 opacity-50 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div x-show="open" x-transition.opacity.duration.300ms class="space-y-1">{$linksHtml}</div>
</div>
HTML;

    return $newHtml;
}, $content);

file_put_contents($filePath, $content);
echo 'Sidebar modifications completed successfully.';
