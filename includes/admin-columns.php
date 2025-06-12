<?php
// This file contains functions to manage custom columns in the admin area for the "Inscripciones Atletismo" post type.

add_filter('manage_inscripcion_atlet_posts_columns', function($columns) {
    $columns['evento'] = 'Evento';
    $columns['categoria'] = 'Categoría';
    $columns['dorsal'] = 'Dorsal';
    $columns['dni'] = 'DNI';
    $columns['nombre'] = 'Nombre';
    $columns['apellidos'] = 'Apellidos';
    $columns['correo'] = 'Correo electrónico';
    $columns['telefono'] = 'Teléfono';
    $columns['club'] = 'Club';
    $columns['pagado'] = 'Pago'; // Añadido
    return $columns;
});

add_action('manage_inscripcion_atlet_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'evento':
            $evento_id = get_post_meta($post_id, '_evento_id', true);
            if ($evento_id) {
                echo '<a href="' . get_edit_post_link($evento_id) . '">' . esc_html(get_the_title($evento_id)) . '</a>';
            } else {
                echo '—';
            }
            break;

        case 'categoria':
            $evento_id = get_post_meta($post_id, '_evento_id', true);
            $cat_index = get_post_meta($post_id, '_categoria_id', true);
            if ($evento_id !== '' && $cat_index !== '') {
                $categorias_json = get_post_meta($evento_id, '_categorias_evento', true);
                $categorias = json_decode($categorias_json, true);
                echo isset($categorias[$cat_index]['nombre']) ? esc_html($categorias[$cat_index]['nombre']) : '—';
            } else {
                echo '—';
            }
            break;

        case 'dorsal':
            $dorsal = get_post_meta($post_id, '_dorsal', true);
            echo esc_html($dorsal ?: '—');
            break;

        case 'dni':
            $dni = get_post_meta($post_id, '_dni', true);
            echo esc_html($dni ?: '—');
            break;

        case 'nombre':
            $nombre = get_post_meta($post_id, '_nombre', true);
            echo esc_html($nombre ?: '—');
            break;

        case 'apellidos':
            $apellidos = get_post_meta($post_id, '_apellidos', true);
            echo esc_html($apellidos ?: '—');
            break;

        case 'correo':
            $correo = get_post_meta($post_id, '_correo', true);
            echo esc_html($correo ?: '—');
            break;

        case 'telefono':
            $telefono = get_post_meta($post_id, '_telefono', true);
            echo esc_html($telefono ?: '—');
            break;
        case 'club':
            $club = get_post_meta($post_id, '_club', true);
            echo esc_html($club ?: '—');
            break;
        case 'pagado':
            $pagado = get_post_meta($post_id, '_pagado', true);
            if ($pagado === 'si') {
                echo '<span style="color:green;font-weight:bold;">Pagado</span>';
            } elseif ($pagado === 'no') {
                echo '<span style="color:red;font-weight:bold;">Pendiente</span>';
            } else {
                echo '—';
            }
            break;
    }
}, 10, 2);

// Make columns sortable
add_filter('manage_edit-inscripcion_atlet_sortable_columns', function($columns) {
    $columns['dorsal'] = 'dorsal';
    $columns['dni'] = 'dni';
    $columns['nombre'] = 'nombre';
    $columns['club'] = 'club';
    return $columns;
});

// Apply sorting by meta fields
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'inscripcion_atlet') {
        return;
    }

    $orderby = $query->get('orderby');
    switch ($orderby) {
        case 'dorsal':
            $query->set('meta_key', '_dorsal');
            $query->set('orderby', 'meta_value_num');
            break;
        case 'dni':
            $query->set('meta_key', '_dni');
            $query->set('orderby', 'meta_value');
            break;
        case 'nombre':
            $query->set('meta_key', '_nombre');
            $query->set('orderby', 'meta_value');
            break;
        case 'club':
            $query->set('meta_key', '_club');
            $query->set('orderby', 'meta_value');
            break;
    }
});

// Filtro por evento y categoría en la tabla de inscripciones
add_action('restrict_manage_posts', function() {
    global $typenow;
    if ($typenow !== 'inscripcion_atlet') return;

    // Filtro por evento
    $eventos = get_posts([
        'post_type' => 'evento_atlet',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);
    $evento_selected = $_GET['filtro_evento'] ?? '';
    echo '<select name="filtro_evento"><option value="">Todos los eventos</option>';
    foreach ($eventos as $evento) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($evento->ID),
            selected($evento_selected, $evento->ID, false),
            esc_html($evento->post_title)
        );
    }
    echo '</select>';

    // Filtro por categoría (solo si hay evento seleccionado)
    $cat_selected = $_GET['filtro_categoria'] ?? '';
    if ($evento_selected) {
        $categorias_json = get_post_meta($evento_selected, '_categorias_evento', true);
        $categorias = json_decode($categorias_json, true);
        echo '<select name="filtro_categoria"><option value="">Todas las categorías</option>';
        if (is_array($categorias)) {
            foreach ($categorias as $index => $cat) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($index),
                    selected($cat_selected, $index, false),
                    esc_html($cat['nombre'] ?? 'Sin nombre')
                );
            }
        }
        echo '</select>';
    }
});

// Modificar la query para aplicar los filtros
add_filter('parse_query', function($query) {
    global $pagenow;
    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        ($query->get('post_type') === 'inscripcion_atlet')
    ) {
        if (!empty($_GET['filtro_evento'])) {
            $query->set('meta_query', array_merge(
                (array) $query->get('meta_query', []),
                [[
                    'key' => '_evento_id',
                    'value' => $_GET['filtro_evento'],
                    'compare' => '='
                ]]
            ));
        }
        if (
            isset($_GET['filtro_categoria']) &&
            $_GET['filtro_categoria'] !== ''
        ) {
            $query->set('meta_query', array_merge(
                (array) $query->get('meta_query', []),
                [[
                    'key' => '_categoria_id',
                    'value' => $_GET['filtro_categoria'],
                    'compare' => '='
                ]]
            ));
        }
    }
    return $query;
});
?>