<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

if (class_exists(Timber\Timber::class)) {
    Timber\Timber::init();
}

function tealforge_get_context(): array
{
    if (! class_exists(Timber\Timber::class)) {
        return [];
    }

    $context = Timber\Timber::context();

    $context['menus'] = [
        'primary' => has_nav_menu('primary') ? Timber\Timber::get_menu('primary') : null,
        'footer' => has_nav_menu('footer') ? Timber\Timber::get_menu('footer') : null,
    ];

    return $context;
}

function tealforge_render(string $template, array $context = []): void
{
    if (! class_exists(Timber\Timber::class)) {
        wp_die(
            esc_html__(
                'Les dependances Composer du theme Tealforge sont absentes. Lancez composer install dans le theme.',
                'tealforge'
            )
        );
    }

    Timber\Timber::render($template, $context);
}
