<?php
/**
 * Utility functions for the Gestión de Eventos de Atletismo plugin.
 */

/**
 * Sanitize a string input.
 *
 * @param string $input The input string to sanitize.
 * @return string The sanitized string.
 */
function sanitize_string($input) {
    return sanitize_text_field($input);
}

/**
 * Validate a date input.
 *
 * @param string $date The date string to validate.
 * @return bool True if valid, false otherwise.
 */
function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validate an email input.
 *
 * @param string $email The email string to validate.
 * @return bool True if valid, false otherwise.
 */
function validate_email($email) {
    return is_email($email);
}

/**
 * Generate a unique dorsal number.
 *
 * @param int $evento_id The event ID.
 * @return int The generated dorsal number.
 */
function generate_dorsal($evento_id) {
    $dorsales_query = new WP_Query([
        'post_type' => 'inscripcion_atlet',
        'post_status' => 'publish',
        'meta_query' => [
            ['key' => '_evento_id', 'value' => $evento_id],
        ],
        'fields' => 'ids',
        'nopaging' => true,
    ]);

    $max_dorsal = 0;
    foreach ($dorsales_query->posts as $post_id_dorsal) {
        $dorsal_num = intval(get_post_meta($post_id_dorsal, '_dorsal', true));
        if ($dorsal_num > $max_dorsal) {
            $max_dorsal = $dorsal_num;
        }
    }
    return $max_dorsal + 1;
}

function obtener_siguiente_dorsal($evento_id, $cat_index = null) {
    $orden = get_post_meta($evento_id, '_orden_dorsales', true) ?: 'compartido';
    if ($orden === 'por_categoria' && $cat_index !== null) {
        // Dorsales independientes por categoría
        $dorsales_query = new WP_Query([
            'post_type' => 'inscripcion_atlet',
            'post_status' => 'publish',
            'meta_query' => [
                ['key' => '_evento_id', 'value' => $evento_id],
                ['key' => '_categoria_id', 'value' => $cat_index],
                ['key' => '_pagado', 'value' => 'si'],
            ],
            'fields' => 'ids',
            'nopaging' => true,
        ]);
    } else {
        // Dorsales compartidos entre todas las categorías
        $dorsales_query = new WP_Query([
            'post_type' => 'inscripcion_atlet',
            'post_status' => 'publish',
            'meta_query' => [
                ['key' => '_evento_id', 'value' => $evento_id],
                ['key' => '_pagado', 'value' => 'si'],
            ],
            'fields' => 'ids',
            'nopaging' => true,
        ]);
    }
    $max_dorsal = 0;
    foreach ($dorsales_query->posts as $post_id_dorsal) {
        $dorsal_num = intval(get_post_meta($post_id_dorsal, '_dorsal', true));
        if ($dorsal_num > $max_dorsal) {
            $max_dorsal = $dorsal_num;
        }
    }
    return $max_dorsal + 1;
}
?>