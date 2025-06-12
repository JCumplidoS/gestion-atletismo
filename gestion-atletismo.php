<?php
/**
 * Plugin Name: Gestión de Eventos de Atletismo
 * Description: Plugin personalizado para gestionar eventos de atletismo con categorías, inscripciones y control de dorsales.
 * Version: 1.2
 * Author: Studio128K
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/cpt-evento.php';
require_once plugin_dir_path(__FILE__) . 'includes/cpt-inscripcion.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/utils.php';
require_once plugin_dir_path(__FILE__) . 'includes/pasarela-pago.php';
require_once plugin_dir_path(__FILE__) . 'includes/stripe-webhook.php';
require_once plugin_dir_path(__FILE__) . 'includes/menu.php';

add_action('wp_enqueue_scripts', function() {
    if (!defined('ELEMENTOR_VERSION')) {
        wp_enqueue_style('ga-public', plugins_url('assets/css/public.css', __FILE__));
    }
});

if (did_action('elementor/loaded')) {
    require_once plugin_dir_path(__FILE__) . 'includes/elementor-widgets/register.php';
}

