<?php
add_action('elementor/widgets/register', function($widgets_manager) {
    require_once __DIR__ . '/widget-consulta-dorsal.php';
    $widgets_manager->register(new \GA_Widget_Consulta_Dorsal());

    require_once __DIR__ . '/widget-categorias-evento.php';
    $widgets_manager->register(new \GA_Widget_Categorias_Evento());

    require_once __DIR__ . '/widget-datos-evento.php';
    $widgets_manager->register(new \GA_Widget_Datos_Evento());

    require_once __DIR__ . '/widget-formulario-inscripcion.php';
    $widgets_manager->register(new \GA_Widget_Formulario_Inscripcion());
});
