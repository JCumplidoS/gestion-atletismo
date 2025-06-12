<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class GA_Widget_Formulario_Inscripcion extends Widget_Base {
    public function get_name() { return 'ga_formulario_inscripcion'; }
    public function get_title() { return 'Formulario de Inscripción (Atletismo)'; }
    public function get_icon() { return 'eicon-form-horizontal'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => 'Campos del formulario',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('campo', [
            'label' => 'Campo',
            'type' => Controls_Manager::SELECT,
            'options' => [
                'nombre' => 'Nombre',
                'apellidos' => 'Apellidos',
                'dni' => 'DNI',
                'correo' => 'Correo',
                'telefono' => 'Teléfono',
                'fecha_nacimiento' => 'Fecha de nacimiento',
                'genero' => 'Género',
                'club' => 'Club',
                'vacio' => 'Campo vacío / Separador',
            ],
            'default' => 'nombre',
        ]);
        $repeater->add_control('visible', [
            'label' => 'Visible',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $repeater->add_control('fila', [
            'label' => 'Fila',
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
        ]);

        $this->add_control('campos_formulario', [
            'label' => 'Campos del formulario',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => 'Fila {{ fila }} - {{{ campo }}}',
            'default' => [
                ['campo' => 'nombre', 'visible' => 'yes', 'fila' => 1],
                ['campo' => 'apellidos', 'visible' => 'yes', 'fila' => 1],
                ['campo' => 'dni', 'visible' => 'yes', 'fila' => 1],
                ['campo' => 'correo', 'visible' => 'yes', 'fila' => 2],
                ['campo' => 'telefono', 'visible' => 'yes', 'fila' => 2],
                ['campo' => 'fecha_nacimiento', 'visible' => 'yes', 'fila' => 3],
            ],
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
                '{{WRAPPER}} .ga-form-inscripcion-list' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('columnas', [
            'label' => 'Columnas',
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1 columna',
                '2' => '2 columnas',
                '3' => '3 columnas',
            ],
            'default' => '1',
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
                '{{WRAPPER}} .ga-form-label' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'campo_typography',
            'selector' => '{{WRAPPER}} .ga-form-label',
        ]);
        $this->end_controls_section();

        // Estilo de input
        $this->start_controls_section('input_style', [
            'label' => 'Input',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('input_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-form-input' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('input_bg', [
            'label' => 'Color de fondo',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-form-input' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'input_typography',
            'selector' => '{{WRAPPER}} .ga-form-input',
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'input_border',
            'selector' => '{{WRAPPER}} .ga-form-input',
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'input_shadow',
            'selector' => '{{WRAPPER}} .ga-form-input',
        ]);
        $this->end_controls_section();

        // Estilo del botón
        $this->start_controls_section('button_style', [
            'label' => 'Botón',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('button_color', [
            'label' => 'Color del texto',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-form-btn' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('button_bg', [
            'label' => 'Color de fondo',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ga-form-btn' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'button_typography',
            'selector' => '{{WRAPPER}} .ga-form-btn',
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'selector' => '{{WRAPPER}} .ga-form-btn',
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'button_shadow',
            'selector' => '{{WRAPPER}} .ga-form-btn',
        ]);
        $this->add_control('button_padding', [
            'label' => 'Relleno',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default' => [
                'top' => 10,
                'right' => 20,
                'bottom' => 10,
                'left' => 20,
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .ga-form-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('button_radius', [
            'label' => 'Radio de borde',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 5, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ga-form-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('button_width', [
            'label' => 'Ancho',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 50, 'max' => 500]],
            'selectors' => [
                '{{WRAPPER}} .ga-form-btn' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('button_align', [
            'label' => 'Alineación',
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
                '{{WRAPPER}} .ga-form-inscripcion-list' => 'align-items: {{VALUE}};',
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $campos = $settings['campos_formulario'];
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // Ejemplo visual en el editor con estilos y columnas reales
        if ($is_editor) {
            // Datos de ejemplo
            $ejemplo = [
                'nombre' => 'Juan',
                'apellidos' => 'Pérez',
                'dni' => '12345678A',
                'correo' => 'juan@example.com',
                'telefono' => '600123123',
                'fecha_nacimiento' => '1990-01-01',
                'genero' => 'Masculino',
                'club' => 'Atletismo Ejemplo',
            ];

            // Agrupa por fila
            $filas = [];
            foreach ($campos as $campo) {
                if (($campo['visible'] ?? 'yes') !== 'yes') continue;
                $fila_num = intval($campo['fila'] ?? 1);
                if (!isset($filas[$fila_num])) $filas[$fila_num] = [];
                $filas[$fila_num][] = $campo;
            }

            // Columnas
            $columnas = isset($settings['columnas']) ? intval($settings['columnas']) : 1;
            $column_class = 'ga-cols-' . $columnas;

            echo '<form class="ga-form ga-form-inscripcion">';
            echo '<div class="ga-form-inscripcion-list ' . esc_attr($column_class) . '" style="display: flex; flex-direction: column; gap: 14px;">';

            foreach ($filas as $fila) {
                // Calcula el número de columnas reales en esta fila
                $cols_en_fila = count($fila);
                echo '<div class="ga-form_row" style="display: flex; gap: 1em; margin-bottom: 10px;">';
                foreach ($fila as $campo) {
                    $name = $campo['campo'];
                    if ($name === 'vacio') {
                        echo '<div style="flex:1;"></div>';
                        continue;
                    }
                    $label = ucfirst(str_replace('_', ' ', $name));
                    echo '<div style="flex:1;">';
                    echo '<label class="ga-form-label" for="ga_' . esc_attr($name) . '">' . esc_html($label) . ':</label>';
                    if ($name === 'genero') {
                        echo '<select class="ga-form-input" disabled>';
                        echo '<option value="">Selecciona</option>';
                        echo '<option value="masculino" selected>Masculino</option>';
                        echo '<option value="femenino">Femenino</option>';
                        echo '</select>';
                    } elseif ($name === 'fecha_nacimiento') {
                        echo '<input class="ga-form-input" type="date" value="' . esc_attr($ejemplo[$name]) . '" disabled>';
                    } elseif ($name === 'correo') {
                        echo '<input class="ga-form-input" type="email" value="' . esc_attr($ejemplo[$name]) . '" disabled>';
                    } elseif ($name === 'telefono') {
                        echo '<input class="ga-form-input" type="text" value="' . esc_attr($ejemplo[$name]) . '" disabled>';
                    } elseif ($name === 'club') {
                        echo '<input class="ga-form-input" type="text" value="' . esc_attr($ejemplo[$name]) . '" disabled>';
                        echo '<small style="display:block;color:#666;">Si no pertenece a ningún club, dejar vacío</small>';
                    } else {
                        echo '<input class="ga-form-input" type="text" value="' . esc_attr($ejemplo[$name]) . '" disabled>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
            echo '<button type="submit" class="ga-form-btn" disabled style="margin-top:10px;">Proceder al pago</button>';
            echo '</div></form>';
            return;
        }

        // Mostrar datos tras el pago si está la confirmación en la URL
        if (isset($_GET['confirmar_pago']) && $_GET['confirmar_pago'] == 1 && isset($_GET['inscripcion_id'])) {
            $inscripcion_id = intval($_GET['inscripcion_id']);
            $pagado = get_post_meta($inscripcion_id, '_pagado', true);
            $dorsal = get_post_meta($inscripcion_id, '_dorsal', true);

            // OBTENER evento y categoría desde la inscripción, NO desde la URL
            $evento_id = intval(get_post_meta($inscripcion_id, '_evento_id', true));
            $cat_index = intval(get_post_meta($inscripcion_id, '_categoria_id', true));

            if ($pagado === 'si' && !empty($dorsal)) {
                $evento_url = get_permalink($evento_id);
                echo '<div class="ga-confirmacion-inscripcion" style="border:1px solid #cfc;padding:20px;background:#f8fff8;margin-bottom:20px;">';
                echo '<h3 style="color:green;">¡Inscripción pagada correctamente!</h3>';
                echo '<p><strong>Tu dorsal asignado es el número <span style="font-size:1.3em;color:#0073aa;">' . esc_html($dorsal) . '</span></strong></p>';
                echo '<h4>Datos de tu inscripción:</h4>';
                echo '<ul style="list-style:none;padding:0;">';
                foreach ($campos as $campo) {
                    if (($campo['visible'] ?? 'yes') !== 'yes') continue;
                    $name = $campo['campo'];
                    if ($name === 'vacio') continue;
                    $label = ucfirst(str_replace('_', ' ', $name));
                    $valor = get_post_meta($inscripcion_id, '_' . $name, true);
                    if ($name === 'fecha_nacimiento' && $valor) {
                        $valor = date('d/m/Y', strtotime($valor));
                    }
                    echo '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($valor) . '</li>';
                }
                echo '</ul>';
                echo '<p style="color:#b85c00;font-weight:bold;margin-top:1em;">Si alguno de sus datos está erroneo póngase en contacto con nosotros.</p>';
                echo '<a href="' . esc_url($evento_url) . '" class="ga-btn-volver-evento" style="display:inline-block;margin-top:20px;padding:10px 24px;background:#0073aa;color:#fff;border-radius:5px;text-decoration:none;">Volver al evento</a>';
                echo '</div>';
                return;
            } else {
                echo '<div style="color:orange;">Tu pago está siendo procesado. Esta página se actualizará automáticamente hasta que tu dorsal esté disponible.</div>';
                echo '<script>setTimeout(function(){ window.location.reload(); }, 5000);</script>';
                return;
            }
        }

        // Recoge evento y categoría de la URL
        $evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0;
        $cat_index = isset($_GET['cat']) ? intval($_GET['cat']) : null;
        if (!$evento_id || $cat_index === null) {
            echo '<div style="color:red;">Evento o categoría no válida.</div>';
            return;
        }

        // Agrupa por fila
        $filas = [];
        foreach ($campos as $campo) {
            if (($campo['visible'] ?? 'yes') !== 'yes') continue;
            $fila_num = intval($campo['fila'] ?? 1);
            if (!isset($filas[$fila_num])) $filas[$fila_num] = [];
            $filas[$fila_num][] = $campo;
        }

        // Mostrar errores si los hay (por JS)
        echo '<div id="ga-form-inscripcion-errores" style="color:red;margin-bottom:10px;"></div>';

        // Formulario
        echo '<form id="ga-form-inscripcion" class="ga-form-inscripcion-list" method="post" style="display:flex;flex-direction:column;">';
        // Campos ocultos para evento y categoría
        echo '<input type="hidden" name="evento_id" value="' . esc_attr($evento_id) . '">';
        echo '<input type="hidden" name="cat_index" value="' . esc_attr($cat_index) . '">';
        foreach ($filas as $fila) {
            echo '<div class="ga-form_row" style="display:flex;gap:1em;margin-bottom:10px;">';
            foreach ($fila as $campo) {
                $name = $campo['campo'];
                if ($name === 'vacio') {
                    echo '<div style="flex:1;"></div>';
                    continue;
                }
                $label = ucfirst(str_replace('_', ' ', $name));
                echo '<div style="flex:1;">';
                echo '<label class="ga-form-label" for="ga_' . esc_attr($name) . '">' . esc_html($label) . ':</label>';
                if ($name === 'genero') {
                    echo '<select class="ga-form-input" name="' . esc_attr($name) . '" id="ga_' . esc_attr($name) . '" required>';
                    echo '<option value="">Selecciona</option>';
                    echo '<option value="masculino">Masculino</option>';
                    echo '<option value="femenino">Femenino</option>';
                    echo '</select>';
                } elseif ($name === 'fecha_nacimiento') {
                    echo '<input class="ga-form-input" type="date" name="' . esc_attr($name) . '" id="ga_' . esc_attr($name) . '" required>';
                } elseif ($name === 'correo') {
                    echo '<input class="ga-form-input" type="email" name="' . esc_attr($name) . '" id="ga_' . esc_attr($name) . '" required>';
                } elseif ($name === 'telefono') {
                    echo '<input class="ga-form-input" type="text" name="' . esc_attr($name) . '" id="ga_' . esc_attr($name) . '">';
                } elseif ($name === 'club') {
                    echo '<input class="ga-form-input" type="text" name="club" id="ga_club">';
                    echo '<small style="display:block;color:#666;">Si no pertenece a ningún club, dejar vacío</small>';
                } else {
                    echo '<input class="ga-form-input" type="text" name="' . esc_attr($name) . '" id="ga_' . esc_attr($name) . '" required>';
                }
                echo '</div>';
            }
            echo '</div>';
        }
        echo '<button type="submit" id="ga-btn-inscripcion" class="ga-form-btn">Proceder al pago</button>';
        echo '</form>';

        // JS para AJAX (igual que el shortcode)
        ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
        document.getElementById('ga-form-inscripcion').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('ga-btn-inscripcion');
            btn.disabled = true;
            btn.textContent = 'Procesando...';
            const erroresDiv = document.getElementById('ga-form-inscripcion-errores');
            erroresDiv.innerHTML = '';
            const formData = new FormData(this);
            formData.append('action', 'ga_crear_sesion_stripe');
            // AJAX a admin-ajax.php
            const response = await fetch(<?= json_encode(admin_url('admin-ajax.php')) ?>, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.url) {
                window.location.href = data.url;
            } else if (data.error) {
                erroresDiv.innerHTML = data.error.replace(/\n/g, '<br>');
                btn.disabled = false;
                btn.textContent = 'Proceder al pago';
            }
        });
        </script>
        <?php
    }
}