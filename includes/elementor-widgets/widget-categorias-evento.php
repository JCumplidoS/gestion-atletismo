<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class GA_Widget_Categorias_Evento extends Widget_Base {
    public function get_name() { return 'ga_categorias_evento'; }
    public function get_title() { return 'Categorías del Evento (Atletismo)'; }
    public function get_icon() { return 'eicon-list-ul'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        // Contenido
        $this->start_controls_section('content_section', [
            'label' => 'Contenido',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('titulo', [
            'label' => 'Título',
            'type' => Controls_Manager::TEXT,
            'default' => 'Categorías',
        ]);
        $this->add_control('mostrar_plazas', [
            'label' => 'Mostrar plazas disponibles',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('mostrar_edad', [
            'label' => 'Mostrar edad mínima/máxima',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('mostrar_genero', [
            'label' => 'Mostrar género',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('disposicion', [
            'label' => 'Disposición',
            'type' => Controls_Manager::SELECT,
            'default' => 'vertical',
            'options' => [
                'vertical' => 'Vertical (filas)',
                'horizontal' => 'Horizontal (tarjetas)',
            ],
        ]);
        $this->end_controls_section();

        // Estilos generales
        $this->start_controls_section('style_section', [
            'label' => 'Estilo de la lista',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('gap', [
            'label' => 'Separación entre filas',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 48]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-categorias-filas' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('align', [
            'label' => 'Alineación de contenido',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => 'Izquierda',
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => 'Centro',
                    'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => 'Derecha',
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .ga-categorias-filas' => 'align-items: {{VALUE}};',
            ],
        ]);
        $this->end_controls_section();

        // Estilo de fila/celda
        $this->start_controls_section('fila_style', [
            'label' => 'Estilo de la Fila/Celda',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('fila_bg', [
            'label' => 'Color de fondo',
            'type' => Controls_Manager::COLOR,
            'default' => '#f7fafd',
            'selectors' => [
                '{{WRAPPER}} .ga-categoria-fila' => 'background: {{VALUE}};',
            ],
        ]);
        $this->add_control('fila_bg_completa', [
            'label' => 'Fondo (completa)',
            'type' => Controls_Manager::COLOR,
            'default' => '#f5f5f5',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-completa' => 'background: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'fila_border',
            'selector' => '{{WRAPPER}} .ga-categoria-fila',
        ]);
        $this->add_control('fila_radius', [
            'label' => 'Radio de borde',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 32]],
            'default' => ['size' => 8, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-categoria-fila' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'fila_shadow',
            'selector' => '{{WRAPPER}} .ga-categoria-fila',
        ]);
        $this->add_responsive_control('fila_padding', [
            'label' => 'Relleno (Padding)',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default' => [
                'top' => 18,
                'right' => 22,
                'bottom' => 18,
                'left' => 22,
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .ga-categoria-fila' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('fila_margin', [
            'label' => 'Margen exterior',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default' => [
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .ga-categoria-fila' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        // Estilo de nombre
        $this->start_controls_section('nombre_style', [
            'label' => 'Nombre de Categoría',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('nombre_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0073aa',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-nombre a' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'nombre_typography',
            'selector' => '{{WRAPPER}} .ga-cat-nombre a, {{WRAPPER}} .ga-cat-nombre span',
        ]);
        $this->end_controls_section();

        // Estilo de plazas
        $this->start_controls_section('plazas_style', [
            'label' => 'Plazas',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('plazas_color', [
            'label' => 'Color (disponibles)',
            'type' => Controls_Manager::COLOR,
            'default' => '#008a00',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-plazas' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('plazas_color_completa', [
            'label' => 'Color (completas)',
            'type' => Controls_Manager::COLOR,
            'default' => '#b30000',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-completa .ga-cat-plazas' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'plazas_typography',
            'selector' => '{{WRAPPER}} .ga-cat-plazas',
        ]);
        $this->end_controls_section();

        // Estilo info extra
        $this->start_controls_section('info_style', [
            'label' => 'Edad y Género',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('info_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#555',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-edad, {{WRAPPER}} .ga-cat-genero' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'info_typography',
            'selector' => '{{WRAPPER}} .ga-cat-edad, {{WRAPPER}} .ga-cat-genero',
        ]);
        $this->end_controls_section();

        // Estilo del botón de inscripción
        $this->start_controls_section('boton_style', [
            'label' => 'Botón de Inscripción',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->start_controls_tabs('boton_style_tabs');
        // --- Normal ---
        $this->start_controls_tab('boton_normal', [
            'label' => 'Normal',
        ]);
        $this->add_control('boton_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_bg', [
            'label' => 'Color de fondo',
            'type' => Controls_Manager::COLOR,
            'default' => '#0073aa',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_color_disabled', [
            'label' => 'Color del texto (desactivado)',
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn.ga-cat-btn-disabled' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_bg_disabled', [
            'label' => 'Color de fondo (desactivado)',
            'type' => Controls_Manager::COLOR,
            'default' => '#ccc',
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn.ga-cat-btn-disabled' => 'background-color: {{VALUE}};',
            ],
        ]);
        // El resto de controles compartidos:
        $this->add_control('boton_margin', [
            'label' => 'Margen izquierdo',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn' => 'margin-left: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('boton_padding', [
            'label' => 'Relleno',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default' => [
                'top' => 6,
                'right' => 18,
                'bottom' => 6,
                'left' => 18,
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('boton_radius', [
            'label' => 'Radio de borde',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 5, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'boton_typography',
            'selector' => '{{WRAPPER}} .ga-cat-btn',
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'boton_border',
            'selector' => '{{WRAPPER}} .ga-cat-btn',
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'boton_shadow',
            'selector' => '{{WRAPPER}} .ga-cat-btn',
        ]);
        $this->end_controls_tab();
        // --- Hover ---
        $this->start_controls_tab('boton_hover', [
            'label' => 'Hover',
        ]);
        $this->add_control('boton_color_hover', [
            'label' => 'Color del texto (activo)',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn:not(.ga-cat-btn-disabled):hover' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_bg_hover', [
            'label' => 'Color de fondo (activo)',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn:not(.ga-cat-btn-disabled):hover' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_color_disabled_hover', [
            'label' => 'Color del texto (desactivado)',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn.ga-cat-btn-disabled:hover' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('boton_bg_disabled_hover', [
            'label' => 'Color de fondo (desactivado)',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-cat-btn.ga-cat-btn-disabled:hover' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();
        $categorias_json = get_post_meta($post_id, '_categorias_evento', true);
        $categorias = json_decode($categorias_json, true);
        $settings = $this->get_settings_for_display();

        // Si no hay categorías y estamos en el editor de Elementor, mostrar ejemplo
        if ((!$categorias || !is_array($categorias)) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $categorias = [
                [
                    'nombre' => 'Juvenil Masculino',
                    'plazas' => 30,
                    'edad_min' => 14,
                    'edad_max' => 17,
                    'genero' => 'masculino'
                ],
                [
                    'nombre' => 'Juvenil Femenino',
                    'plazas' => 25,
                    'edad_min' => 14,
                    'edad_max' => 17,
                    'genero' => 'femenino'
                ],
                [
                    'nombre' => 'Senior Mixto',
                    'plazas' => 40,
                    'edad_min' => 18,
                    'edad_max' => 40,
                    'genero' => ''
                ]
            ];
            // Simular inscripciones para el ejemplo
            $ejemplo_inscritos = [10, 25, 40];
        }

        $disposicion = $settings['disposicion'] ?? 'vertical';
        ?>
        <div class="ga-categorias-widget">
            <h3><?= esc_html($settings['titulo']) ?></h3>
            <div class="ga-categorias-filas ga-categorias-<?= esc_attr($disposicion) ?>">
                <?php foreach ($categorias as $index => $cat): ?>
                    <?php
                    // Si estamos en modo ejemplo, usar datos simulados
                    if (isset($ejemplo_inscritos)) {
                        $inscritos = $ejemplo_inscritos[$index];
                    } else {
                        $inscritos_query = new WP_Query([
                            'post_type' => 'inscripcion_atlet',
                            'post_status' => 'publish',
                            'meta_query' => [
                                ['key' => '_evento_id', 'value' => $post_id],
                                ['key' => '_categoria_id', 'value' => $index]
                            ],
                            'fields' => 'ids',
                            'nopaging' => true
                        ]);
                        $inscritos = count($inscritos_query->posts);
                    }
                    $plazas = intval($cat['plazas'] ?? 0);
                    $disponibles = max(0, $plazas - $inscritos);
                    ?>
                    <div class="ga-categoria-fila<?= $disponibles === 0 ? ' ga-cat-completa' : '' ?>">
                        <div class="ga-cat-nombre">
                            <?php if ($disponibles > 0): ?>
                                <a href="#">
                                    <?= esc_html($cat['nombre'] ?? 'Sin nombre') ?>
                                </a>
                            <?php else: ?>
                                <span><?= esc_html($cat['nombre'] ?? 'Sin nombre') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="ga-cat-info">
                            <?php if ($settings['mostrar_plazas'] === 'yes'): ?>
                                <span class="ga-cat-plazas">
                                    <?= $disponibles > 0
                                        ? "Plazas: $disponibles/$plazas"
                                        : "Plazas: Completas"
                                    ?>
                                </span>
                            <?php endif; ?>
                            <?php
                            if ($settings['mostrar_edad'] === 'yes' && (!empty($cat['edad_min']) || !empty($cat['edad_max']))) {
                                echo '<span class="ga-cat-edad">';
                                echo (isset($cat['edad_min']) ? 'Edad mín: ' . esc_html($cat['edad_min']) : '');
                                echo (isset($cat['edad_max']) ? ' - máx: ' . esc_html($cat['edad_max']) : '');
                                echo '</span>';
                            }
                            if ($settings['mostrar_genero'] === 'yes' && !empty($cat['genero'])) {
                                echo '<span class="ga-cat-genero">[' . esc_html($cat['genero']) . ']</span>';
                            }
                            ?>
                            <?php if ($disponibles > 0): ?>
                                <a href="<?= esc_url(add_query_arg([
                                    'evento_id' => $post_id,
                                    'cat' => $index
                                ], home_url('/formulario-inscripcion/'))) ?>"
                                   class="ga-cat-btn">
                                    Inscribirse
                                </a>
                            <?php else: ?>
                                <span class="ga-cat-btn ga-cat-btn-disabled">
                                    Sin plazas
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <style>
        .ga-categorias-filas {
            display: flex;
            flex-direction: column;
        }
        .ga-categorias-horizontal {
            flex-direction: row;
            flex-wrap: wrap;
        }
        .ga-categoria-fila {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
            width: 100%;
        }
        .ga-categorias-horizontal .ga-categoria-fila {
            flex-direction: column;
            min-width: 220px;
            max-width: 320px;
            margin-right: 18px;
            margin-bottom: 18px;
        }
        </style>
        <?php
    }
}