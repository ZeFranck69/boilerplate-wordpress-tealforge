<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();
$context['posts'] = Timber\Timber::get_posts();

tealforge_render('index.twig', $context);
