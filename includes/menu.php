<?php
add_action('admin_menu', function() {
    // Menú principal
    add_menu_page(
        'Gestión Atletismo',
        'Gestión Atletismo',
        'manage_options',
        'gestion_atletismo',
        '',
        'dashicons-awards',
        25
    );

    // Submenú: Eventos de Atletismo
    add_submenu_page(
        'gestion_atletismo',
        'Eventos de Atletismo',
        'Eventos de Atletismo',
        'manage_options',
        'edit.php?post_type=evento_atlet'
    );

    // Submenú: Inscripciones
    add_submenu_page(
        'gestion_atletismo',
        'Inscripciones',
        'Inscripciones',
        'manage_options',
        'edit.php?post_type=inscripcion_atlet'
    );

    // Eliminar el submenú duplicado
    remove_submenu_page('gestion_atletismo', 'gestion_atletismo');
});