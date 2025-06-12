<?php

// Shortcode para mostrar imagen del evento
add_shortcode('imagen_evento', function($atts) {
    $atts = shortcode_atts([
        'post_id' => get_the_ID(),
        'meta_key' => '_imagen_evento_id',
        'size' => 'large'
    ], $atts);

    $img_id = get_post_meta($atts['post_id'], $atts['meta_key'], true);
    if ($img_id) {
        return wp_get_attachment_image($img_id, $atts['size']);
    }
    return '';
});
?>