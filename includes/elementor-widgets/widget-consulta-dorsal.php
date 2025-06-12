<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class GA_Widget_Consulta_Dorsal extends Widget_Base {
    public function get_name() { return 'ga_consulta_dorsal'; }
    public function get_title() { return 'Consulta Dorsal (Atletismo)'; }
    public function get_icon() { return 'eicon-search'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        // Orden y visibilidad de los elementos
        $this->start_controls_section('section_order', [
            'label' => 'Orden y visibilidad',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        $repeater = new Repeater();
        $repeater->add_control('element', [
            'label' => 'Elemento',
            'type' => Controls_Manager::SELECT,
            'options' => [
                'titulo' => 'Título',
                'input' => 'Campo DNI',
                'button' => 'Botón',
                'result' => 'Resultados',
            ],
            'default' => 'input',
        ]);
        $repeater->add_control('visible', [
            'label' => 'Visible',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('form_order', [
            'label' => 'Orden y visibilidad de los elementos',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['element' => 'titulo', 'visible' => 'yes'],
                ['element' => 'input', 'visible' => 'yes'],
                ['element' => 'button', 'visible' => 'yes'],
                ['element' => 'result', 'visible' => 'yes'],
            ],
            'title_field' => '{{{ element }}}',
        ]);
        $this->end_controls_section();

        // Contenido personalizable
        $this->start_controls_section('section_content', [
            'label' => 'Contenido',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('titulo', [
            'label' => 'Título',
            'type' => Controls_Manager::TEXT,
            'default' => 'Consulta tu Dorsal',
        ]);
        $this->add_control('label_input', [
            'label' => 'Texto del label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Introduce tu DNI:',
        ]);
        $this->add_control('placeholder_input', [
            'label' => 'Placeholder del input',
            'type' => Controls_Manager::TEXT,
            'default' => 'DNI',
        ]);
        $this->add_control('boton_texto', [
            'label' => 'Texto del botón',
            'type' => Controls_Manager::TEXT,
            'default' => 'Consultar',
        ]);
        $this->add_control('resultados_titulo', [
            'label' => 'Título de resultados',
            'type' => Controls_Manager::TEXT,
            'default' => 'Resultados encontrados:',
        ]);
        $this->add_control('plantilla_resultado', [
            'label' => 'Plantilla de resultado',
            'type' => Controls_Manager::CODE,
            'language' => 'html',
            'default' => '<strong>Evento:</strong> {evento}<br><strong>Categoría:</strong> {categoria}<br><strong>Dorsal:</strong> <span class="ga-dorsal">{dorsal}</span>',
            'description' => 'Usa {evento}, {categoria} y {dorsal} para mostrar los datos.',
        ]);
        $this->add_control('texto_error', [
            'label' => 'Texto si no hay resultados',
            'type' => Controls_Manager::TEXT,
            'default' => 'No se encontraron inscripciones con ese DNI.',
        ]);
        $this->end_controls_section();

        // Estilos input
        $this->start_controls_section('style_input', [
            'label' => 'Estilo del campo DNI',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('input_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ga-input' => 'color: {{VALUE}};'],
        ]);
        $this->add_control('input_bg', [
            'label' => 'Fondo',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ga-input' => 'background-color: {{VALUE}};'],
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'input_border',
            'selector' => '{{WRAPPER}} .ga-input',
        ]);
        $this->end_controls_section();

        // Estilos botón
        $this->start_controls_section('style_button', [
            'label' => 'Estilo del botón',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('button_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ga-btn' => 'color: {{VALUE}};'],
        ]);
        $this->add_control('button_bg', [
            'label' => 'Fondo',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ga-btn' => 'background-color: {{VALUE}};'],
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'selector' => '{{WRAPPER}} .ga-btn',
        ]);
        $this->end_controls_section();

        // Estilos resultados
        $this->start_controls_section('style_result', [
            'label' => 'Estilo de los resultados',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('result_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ga-consulta-result' => 'color: {{VALUE}};'],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'result_typography',
            'selector' => '{{WRAPPER}} .ga-consulta-result',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $order = $settings['form_order'];
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // Datos de ejemplo para preview en editor
        $dni_consultado = '';
        $resultados = [];
        if ($is_editor) {
            $dni_consultado = '12345678A';
            $resultados = [
                [
                    'evento' => 'Carrera Popular',
                    'categoria' => 'Senior Masculino',
                    'dorsal' => '101'
                ],
                [
                    'evento' => 'Carrera Popular',
                    'categoria' => 'Senior Femenino',
                    'dorsal' => '102'
                ]
            ];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consulta_dorsales_dni'])) {
            $dni_consultado = strtoupper(sanitize_text_field($_POST['consulta_dorsales_dni']));
            $query = new \WP_Query([
                'post_type' => 'inscripcion_atlet',
                'post_status' => 'publish',
                'meta_query' => [
                    [
                        'key' => '_dni',
                        'value' => $dni_consultado,
                        'compare' => '='
                    ]
                ],
                'posts_per_page' => -1,
            ]);
            foreach ($query->posts as $post) {
                $evento_id = get_post_meta($post->ID, '_evento_id', true);
                $categoria_id = get_post_meta($post->ID, '_categoria_id', true);
                $dorsal = get_post_meta($post->ID, '_dorsal', true);
                $evento = get_post($evento_id);
                $categorias_json = get_post_meta($evento_id, '_categorias_evento', true);
                $categorias = json_decode($categorias_json, true);
                $nombre_categoria = isset($categorias[$categoria_id]['nombre']) ? $categorias[$categoria_id]['nombre'] : 'Desconocida';
                $resultados[] = [
                    'evento' => $evento ? $evento->post_title : 'Evento no encontrado',
                    'categoria' => $nombre_categoria,
                    'dorsal' => $dorsal ?: 'Sin asignar',
                ];
            }
        }
        ?>
        <div class="ga-form ga-consulta-dorsal" id="ga-consulta-dorsal-widget">
            <form method="POST" class="ga-form-inner">
                <?php
                foreach ($order as $item) {
                    if ($item['visible'] !== 'yes') continue;
                    switch ($item['element']) {
                        case 'titulo':
                            echo '<h2 class="ga-consulta-title">' . esc_html($settings['titulo']) . '</h2>';
                            break;
                        case 'input':
                            ?>
                            <label for="consulta_dorsales_dni" class="ga-label"><?= esc_html($settings['label_input']) ?></label>
                            <div class="ga-form-fields">
                                <input type="text" name="consulta_dorsales_dni" id="consulta_dorsales_dni" class="ga-input" required placeholder="<?= esc_attr($settings['placeholder_input']) ?>" value="<?= esc_attr($dni_consultado) ?>">
                            </div>
                            <?php
                            break;
                        case 'button':
                            ?>
                            <button type="submit" class="ga-btn"><?= esc_html($settings['boton_texto']) ?></button>
                            <?php
                            break;
                        case 'result':
                            if (!empty($resultados)) : ?>
                                <div class="ga-consulta-result">
                                    <h3><?= esc_html($settings['resultados_titulo']) ?></h3>
                                    <ul>
                                        <?php foreach ($resultados as $r) : ?>
                                            <li class="ga-consulta-item">
                                                <?= strtr($settings['plantilla_resultado'], [
                                                    '{evento}' => esc_html($r['evento']),
                                                    '{categoria}' => esc_html($r['categoria']),
                                                    '{dorsal}' => esc_html($r['dorsal']),
                                                ]) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php elseif ($dni_consultado) : ?>
                                <p class="ga-consulta-error"><?= esc_html($settings['texto_error']) ?></p>
                            <?php endif;
                            break;
                    }
                }
                ?>
            </form>
        </div>
        <?php
    }
}

// Handler AJAX (puedes dejarlo aquí o en includes/shortcodes.php, pero solo debe estar una vez)
add_action('wp_ajax_ga_consulta_dorsal_ajax', 'ga_consulta_dorsal_ajax');
add_action('wp_ajax_nopriv_ga_consulta_dorsal_ajax', 'ga_consulta_dorsal_ajax');
if (!function_exists('ga_consulta_dorsal_ajax')) {
function ga_consulta_dorsal_ajax() {
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ga_consulta_dorsal_nonce')) {
        wp_send_json_error(['error' => 'Nonce inválido'], 400);
    }
    $dni_consulta = strtoupper(sanitize_text_field(
        $_POST['consulta_dorsales_dni'] ?? $_POST['dni'] ?? ''
    ));
    $resultados = [];
    if ($dni_consulta) {
        $query = new WP_Query([
            'post_type' => 'inscripcion_atlet',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_dni',
                    'value' => $dni_consulta,
                    'compare' => '='
                ]
            ],
            'posts_per_page' => -1,
        ]);
        foreach ($query->posts as $post) {
            $evento_id = get_post_meta($post->ID, '_evento_id', true);
            $categoria_id = get_post_meta($post->ID, '_categoria_id', true);
            $dorsal = get_post_meta($post->ID, '_dorsal', true);
            $evento = get_post($evento_id);
            $categorias_json = get_post_meta($evento_id, '_categorias_evento', true);
            $categorias = json_decode($categorias_json, true);
            $cat_index = is_numeric($categoria_id) ? intval($categoria_id) : $categoria_id;
            $nombre_categoria = isset($categorias[$cat_index]['nombre']) ? $categorias[$cat_index]['nombre'] : 'Desconocida';
            $resultados[] = [
                'evento' => $evento ? $evento->post_title : 'Evento no encontrado',
                'categoria' => $nombre_categoria,
                'dorsal' => $dorsal ?: 'Sin asignar',
            ];
        }
    }
    wp_send_json(['resultados' => $resultados]);
}
}