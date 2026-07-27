<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();

tealforge_render('pages/404.twig', $context);
