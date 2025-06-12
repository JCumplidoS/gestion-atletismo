<?php
// 1. Registrar el CPT: Evento de Atletismo
add_action('init', function() {
    register_post_type('evento_atlet', [
        'labels' => [
            'name' => 'Eventos de Atletismo',
            'singular_name' => 'Evento de Atletismo',
            'add_new_item' => 'Añadir nuevo evento',
        ],
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_rest' => true, // <-- ¡IMPORTANTE!
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-calendar',
    ]);
});
