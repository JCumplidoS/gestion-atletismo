<?php
/**
 * Simulación de pasarela de pago (modo prueba/sandbox).
 * En el futuro aquí se integrará la pasarela real (Stripe, PayPal, Redsys, etc).
 */

require_once __DIR__ . '/stripe/init.php'; // Ajusta la ruta si usas Composer o carpeta propia

\Stripe\Stripe::setApiKey('sk_test_51RR7WnRonluEF3sP8PQEksemkUZbKkCQcB8LVJkJF3pxBVcGiL0IFOKbvYi7qFduq8Dq2bP6qCcIGDEvFhYAgMR3000ERslB66'); // Tu clave secreta de prueba

function iniciar_pago_prueba($monto, $datos_usuario, $return_url, $inscripcion_id) {
    // Crea una sesión de Stripe Checkout
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Inscripción evento',
                ],
                'unit_amount' => intval($monto * 100), // Stripe usa céntimos
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'customer_email' => $datos_usuario['correo'],
        'success_url' => $return_url . '&pago=ok',
        'cancel_url' => $return_url . '&pago=cancelado',
        'metadata' => [
            'inscripcion_id' => $inscripcion_id
        ]
    ]);

    while (ob_get_level()) {
        ob_end_clean();
    }
    wp_redirect($session->url);
    exit;
}

add_action('wp_ajax_ga_crear_sesion_stripe', 'ga_crear_sesion_stripe');
add_action('wp_ajax_nopriv_ga_crear_sesion_stripe', 'ga_crear_sesion_stripe');

function ga_crear_sesion_stripe() {
    $evento_id = intval($_POST['evento_id'] ?? 0);
    $cat_index = intval($_POST['cat_index'] ?? 0);
    $nombre = sanitize_text_field($_POST['nombre'] ?? '');
    $apellidos = sanitize_text_field($_POST['apellidos'] ?? '');
    $correo = sanitize_email($_POST['correo'] ?? '');
    $telefono = sanitize_text_field($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $genero = sanitize_text_field($_POST['genero'] ?? '');
    $dni = strtoupper(sanitize_text_field($_POST['dni'] ?? ''));
    $club = sanitize_text_field($_POST['club'] ?? '');

    $errores = [];

    // Validaciones
    if (empty($nombre)) $errores[] = 'El nombre es obligatorio.';
    if (empty($apellidos)) $errores[] = 'Los apellidos son obligatorios.';
    if (empty($fecha_nacimiento)) $errores[] = 'La fecha de nacimiento es obligatoria.';
    $edad = 0;
    if ($fecha_nacimiento) {
        $fn = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        $hoy = new DateTime();
        if ($fn && $fn < $hoy) $edad = $hoy->diff($fn)->y;
    }
    if ($edad <= 0) $errores[] = 'La fecha de nacimiento no es válida.';
    if (!in_array($genero, ['masculino', 'femenino'])) $errores[] = 'El género es obligatorio.';
    if (empty($dni)) {
        $errores[] = 'El DNI es obligatorio.';
    } else {
        // Validación de DNI español (8 números + letra)
        if (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
            $errores[] = 'El DNI introducido no es válido.';
        }
    }
    // Validación de teléfono (opcional, pero si se rellena debe ser correcto)
    if (!empty($telefono) && !preg_match('/^[6-9][0-9]{8}$/', $telefono)) {
        $errores[] = 'El teléfono introducido no es válido.';
    }
    if (!empty($correo) && !is_email($correo)) $errores[] = 'El correo electrónico no es válido.';
    if (!preg_match('/@.+\.[a-zA-Z]{2,}$/', $correo)) $errores[] = 'El correo debe tener un dominio válido con extensión, por ejemplo ".com".';

    // Validaciones de categoría
    $categorias_json = get_post_meta($evento_id, '_categorias_evento', true);
    $categorias = json_decode($categorias_json, true);
    $categoria = $categorias[$cat_index] ?? null;
    if (!$categoria) $errores[] = 'Categoría no válida.';
    if ($categoria) {
        if (!empty($categoria['edad_min']) && $edad < intval($categoria['edad_min'])) $errores[] = "Edad mínima para esta categoría son {$categoria['edad_min']} años.";
        if (!empty($categoria['edad_max']) && $edad > intval($categoria['edad_max'])) $errores[] = "Edad máxima para esta categoría son {$categoria['edad_max']} años.";
        if (!empty($categoria['genero']) && $categoria['genero'] !== $genero) $errores[] = 'El género no corresponde con esta categoría.';
        // Plazas disponibles
        $inscritos_query = new WP_Query([
            'post_type' => 'inscripcion_atlet',
            'post_status' => 'publish',
            'meta_query' => [
                ['key' => '_evento_id', 'value' => $evento_id],
                ['key' => '_categoria_id', 'value' => $cat_index]
            ],
            'fields' => 'ids',
            'nopaging' => true
        ]);
        $inscritos = count($inscritos_query->posts);
        $plazas = intval($categoria['plazas']);
        if ($inscritos >= $plazas) $errores[] = 'No quedan plazas disponibles para esta categoría.';
    }

    // DNI duplicado
    $dni_query = new WP_Query([
        'post_type' => 'inscripcion_atlet',
        'post_status' => 'publish',
        'meta_query' => [
            ['key' => '_evento_id', 'value' => $evento_id],
            ['key' => '_categoria_id', 'value' => $cat_index],
            ['key' => '_dni', 'value' => $dni],
        ],
        'fields' => 'ids',
        'nopaging' => true
    ]);
    if ($dni_query->have_posts()) $errores[] = 'El DNI ya está inscrito en esta categoría del evento.';

    if (!empty($errores)) {
        wp_send_json(['error' => implode("\n", $errores)]);
        wp_die();
    }

    // Crear inscripción pendiente de pago
    $postarr = [
        'post_title' => $nombre . ' ' . $apellidos,
        'post_type' => 'inscripcion_atlet',
        'post_status' => 'publish',
    ];
    $post_id = wp_insert_post($postarr);

    if ($post_id) {
        update_post_meta($post_id, '_nombre', $nombre);
        update_post_meta($post_id, '_apellidos', $apellidos);
        update_post_meta($post_id, '_correo', $correo);
        update_post_meta($post_id, '_telefono', $telefono);
        update_post_meta($post_id, '_fecha_nacimiento', $fecha_nacimiento);
        update_post_meta($post_id, '_genero', $genero);
        update_post_meta($post_id, '_dni', $dni);
        // Antes de guardar el campo club:
        $club = isset($_POST['club']) && trim($_POST['club']) !== '' ? sanitize_text_field($_POST['club']) : 'Independiente';
        // Guardar $club como meta
        update_post_meta($post_id, '_club', $club);
        update_post_meta($post_id, '_evento_id', $evento_id);
        update_post_meta($post_id, '_categoria_id', $cat_index);
        update_post_meta($post_id, '_pagado', 'no');
        // No asignar dorsal aún

        $precio = floatval(get_post_meta($evento_id, '_precio_evento', true));
        $datos_usuario = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'dni' => $dni,
        ];
        $return_url = add_query_arg([
            'evento_id' => $evento_id,
            'cat' => $cat_index,
            'confirmar_pago' => 1,
            'inscripcion_id' => $post_id
        ], home_url('/formulario-inscripcion/'));

        // Crea la sesión de Stripe
        require_once __DIR__ . '/stripe/init.php';
        \Stripe\Stripe::setApiKey('sk_test_51RR7WnRonluEF3sP8PQEksemkUZbKkCQcB8LVJkJF3pxBVcGiL0IFOKbvYi7qFduq8Dq2bP6qCcIGDEvFhYAgMR3000ERslB66');
        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Inscripción evento',
                        ],
                        'unit_amount' => intval($precio * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => $correo,
                'success_url' => $return_url . '&pago=ok',
                'cancel_url' => $return_url . '&pago=cancelado',
                'metadata' => [
                    'inscripcion_id' => $post_id
                ]
            ]);
            wp_send_json(['url' => $session->url]);
        } catch (Exception $e) {
            wp_send_json(['error' => 'Error al crear la sesión de pago: ' . $e->getMessage()]);
        }
    } else {
        wp_send_json(['error' => 'Error al guardar la inscripción.']);
    }
    wp_die();
}