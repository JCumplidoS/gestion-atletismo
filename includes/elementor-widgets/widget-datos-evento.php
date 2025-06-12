<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class GA_Widget_Datos_Evento extends Widget_Base {
    public function get_name() { return 'ga_datos_evento'; }
    public function get_title() { return 'Datos del Evento (Atletismo)'; }
    public function get_icon() { return 'eicon-info-circle-o'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => 'Campos del evento',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('campo', [
            'label' => 'Campo',
            'type' => Controls_Manager::SELECT,
            'options' => [
                'deporte' => 'Deporte',
                'fecha' => 'Fecha',
                'hora' => 'Hora',
                'ubicacion' => 'Ubicación',
                'precio' => 'Precio',
            ],
            'default' => 'deporte',
        ]);
        $repeater->add_control('visible', [
            'label' => 'Visible',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('campos_evento', [
            'label' => 'Campos a mostrar y orden',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['campo' => 'deporte', 'visible' => 'yes'],
                ['campo' => 'fecha', 'visible' => 'yes'],
                ['campo' => 'hora', 'visible' => 'yes'],
                ['campo' => 'ubicacion', 'visible' => 'yes'],
                ['campo' => 'precio', 'visible' => 'yes'],
            ],
            'title_field' => '{{{ campo }}}',
        ]);
        $this->end_controls_section();

        // Estilos generales
        $this->start_controls_section('style_section', [
            'label' => 'Estilo general',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('row_gap', [
            'label' => 'Separación entre filas',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 48]],
            'default' => ['size' => 14, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-datos-evento-list' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        // Estilo de campo (nombre)
        $this->start_controls_section('campo_style', [
            'label' => 'Campo (nombre)',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('campo_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0073aa',
            'selectors' => [
                '{{WRAPPER}} .ga-dato-nombre' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'campo_typography',
            'selector' => '{{WRAPPER}} .ga-dato-nombre',
        ]);
        $this->end_controls_section();

        // Estilo de valor
        $this->start_controls_section('valor_style', [
            'label' => 'Valor',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('valor_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#222',
            'selectors' => [
                '{{WRAPPER}} .ga-dato-valor' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'valor_typography',
            'selector' => '{{WRAPPER}} .ga-dato-valor',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $campos = $settings['campos_evento'];

        // Cargar datos reales del evento
        $post_id = get_the_ID();
        $datos = [
            'deporte' => get_post_meta($post_id, '_deporte_evento', true),
            'fecha' => get_post_meta($post_id, '_fecha_evento', true),
            'hora' => get_post_meta($post_id, '_hora_evento', true),
            'ubicacion' => get_post_meta($post_id, '_ubicacion_evento', true),
            'precio' => get_post_meta($post_id, '_precio_evento', true)
        ];

        // Si estamos en el editor y no hay datos, mostrar ejemplo
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            foreach ($datos as $k => $v) {
                if (empty($v)) $datos[$k] = ucfirst($k) . ' de ejemplo';
            }
        }

        echo '<div class="ga-datos-evento-list" style="display:flex;flex-direction:column;">';
        foreach ($campos as $campo) {
            if ($campo['visible'] !== 'yes') continue;
            $nombre = ucfirst($campo['campo']);
            $valor = $datos[$campo['campo']] ?? '';
            if ($campo['campo'] === 'precio' && is_numeric($valor)) {
                $valor = number_format($valor, 2) . ' €';
            }
            echo '<div class="ga-dato-row" style="display:flex;gap:10px;align-items:center;">';
            echo '<span class="ga-dato-nombre">' . esc_html($nombre) . ':</span>';
            echo '<span class="ga-dato-valor">' . esc_html($valor) . '</span>';
            echo '</div>';
        }
        echo '</div>';
    }
}