<?php
// Endpoint para Stripe Webhook
add_action('rest_api_init', function () {
    register_rest_route('gestion-atletismo/v1', '/stripe-webhook', [
        'methods' => 'POST',
        'callback' => 'ga_stripe_webhook_handler',
        'permission_callback' => '__return_true', // Stripe no envía auth
    ]);
});

function ga_stripe_webhook_handler(WP_REST_Request $request) {
    $payload = $request->get_body();
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $secret = 'whsec_lenhnQmzwa6PuBer0wgBs5itzgvvtYeN'; // TU SECRET DE WEBHOOK DE STRIPE

    // Verifica la firma del webhook
    try {
        \Stripe\Stripe::setApiKey('sk_test_51RR7WnRonluEF3sP8PQEksemkUZbKkCQcB8LVJkJF3pxBVcGiL0IFOKbvYi7qFduq8Dq2bP6qCcIGDEvFhYAgMR3000ERslB66');
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $secret);
    } catch(\Exception $e) {
        return new WP_REST_Response(['error' => $e->getMessage()], 400);
    }

    // Solo nos interesa el evento de pago completado
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        // Recupera el ID de inscripción que enviaste como metadata
        $inscripcion_id = $session->metadata->inscripcion_id ?? null;
        file_put_contents(__DIR__.'/webhook_log.txt', date('c')." - Recibido para inscripcion_id=$inscripcion_id\n", FILE_APPEND);
        if ($inscripcion_id) {
            // Marca como pagado y asigna dorsal si no lo tiene
            $pagado = get_post_meta($inscripcion_id, '_pagado', true);
            $dorsal = get_post_meta($inscripcion_id, '_dorsal', true);
            $evento_id = intval(get_post_meta($inscripcion_id, '_evento_id', true));
            $cat_index = intval(get_post_meta($inscripcion_id, '_categoria_id', true));
            file_put_contents(__DIR__.'/webhook_log.txt', date('c')." - pagado=$pagado, dorsal=$dorsal, evento_id=$evento_id, cat_index=$cat_index\n", FILE_APPEND);
            if ($pagado !== 'si' || empty($dorsal)) {
                require_once __DIR__ . '/utils.php'; // Asegúrate de incluir la función si no está ya incluida
                $nuevo_dorsal = obtener_siguiente_dorsal($evento_id, $cat_index);
                update_post_meta($inscripcion_id, '_pagado', 'si');
                update_post_meta($inscripcion_id, '_dorsal', $nuevo_dorsal);
                file_put_contents(__DIR__.'/webhook_log.txt', date('c')." - Asignado dorsal $nuevo_dorsal a inscripcion $inscripcion_id\n", FILE_APPEND);
            }
        }
    }

    return new WP_REST_Response(['status' => 'ok'], 200);
}