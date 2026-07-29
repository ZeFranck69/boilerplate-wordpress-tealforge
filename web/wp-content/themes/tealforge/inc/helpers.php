<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_get_theme_file(string $relative_path): string
{
    return get_theme_file_path(ltrim($relative_path, '/'));
}

function tealforge_get_theme_file_uri(string $relative_path): string
{
    return get_theme_file_uri(ltrim($relative_path, '/'));
}

function tealforge_prepare_link_field(mixed $link): ?array
{
    if (! is_array($link) || empty($link['url'])) {
        return null;
    }

    return [
        'url' => esc_url((string) $link['url']),
        'title' => isset($link['title']) ? (string) $link['title'] : '',
        'target' => ! empty($link['target']) ? (string) $link['target'] : '_self',
    ];
}

function tealforge_prepare_image_field(mixed $image): ?array
{
    if (! is_array($image) || empty($image['ID'])) {
        return null;
    }

    $image_id = (int) $image['ID'];

    return [
        'id' => $image_id,
        'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
        'url' => wp_get_attachment_image_url($image_id, 'full'),
    ];
}
