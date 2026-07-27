<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$tealforge_autoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($tealforge_autoload)) {
    require_once $tealforge_autoload;
}

if (class_exists(Timber\Timber::class)) {
    Timber\Timber::init();
}

function tealforge_setup(): void
{
    load_theme_textdomain('tealforge', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('menus');

    register_nav_menus([
        'primary' => __('Menu principal', 'tealforge'),
        'footer' => __('Menu pied de page', 'tealforge'),
    ]);
}

add_action('after_setup_theme', 'tealforge_setup');

function tealforge_get_asset_manifest(): array
{
    static $manifest = null;

    if (null !== $manifest) {
        return $manifest;
    }

    $manifest = [];
    $manifest_path = get_theme_file_path('dist/manifest.json');

    if (! is_readable($manifest_path)) {
        return $manifest;
    }

    $manifest_json = file_get_contents($manifest_path);

    if (false === $manifest_json) {
        return $manifest;
    }

    $decoded_manifest = json_decode($manifest_json, true);

    if (is_array($decoded_manifest)) {
        $manifest = $decoded_manifest;
    }

    return $manifest;
}

function tealforge_enqueue_assets(): void
{
    $entry = tealforge_get_asset_manifest()['assets/scripts/main.js'] ?? null;
    $theme_version = wp_get_theme()->get('Version');

    if (is_array($entry) && ! empty($entry['file'])) {
        if (! empty($entry['css']) && is_array($entry['css'])) {
            foreach ($entry['css'] as $index => $css_file) {
                wp_enqueue_style(
                    'tealforge-main-' . $index,
                    get_theme_file_uri('dist/' . ltrim((string) $css_file, '/')),
                    [],
                    null
                );
            }
        }

        wp_enqueue_script(
            'tealforge-main',
            get_theme_file_uri('dist/' . ltrim((string) $entry['file'], '/')),
            [],
            null,
            true
        );

        return;
    }

    wp_enqueue_style(
        'tealforge-main',
        get_theme_file_uri('assets/styles/main.css'),
        [],
        $theme_version
    );

    wp_enqueue_script(
        'tealforge-main',
        get_theme_file_uri('assets/scripts/app.js'),
        [],
        $theme_version,
        true
    );
}

add_action('wp_enqueue_scripts', 'tealforge_enqueue_assets');

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
