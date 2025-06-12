<?php
// File: /gestion-atletismo/gestion-atletismo/includes/cpt-inscripcion.php

// Seguridad básica
if (!defined('ABSPATH')) exit;

// Registrar el CPT: Inscripciones Atletismo
add_action('init', function() {
    register_post_type('inscripcion_atlet', [
        'labels' => [
            'name' => 'Inscripciones Atletismo',
            'singular_name' => 'Inscripción Atletismo',
            'add_new_item' => 'Añadir nueva inscripción',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // <--- Importante
        'supports' => ['title'],
        'menu_icon' => 'dashicons-id-alt',
    ]);
});
