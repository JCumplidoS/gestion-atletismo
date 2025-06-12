<?php
// 5. Añadir metabox para datos del evento (incluyendo selector de imagen)
add_action('add_meta_boxes', function() {
    add_meta_box('datos_evento', 'Datos del Evento', 'mostrar_datos_evento', 'evento_atlet', 'normal', 'high');
    add_meta_box('categorias_evento', 'Categorías del Evento', 'mostrar_categorias_evento', 'evento_atlet', 'normal', 'default');
    add_meta_box('archivos_evento', 'Archivos del Evento', 'mostrar_archivos_evento', 'evento_atlet', 'normal', 'default');
});

// 6. Mostrar campos de fecha/hora/ubicación y selector de imagen
function mostrar_datos_evento($post) {
    $deporte = get_post_meta($post->ID, '_deporte_evento', true);
    $fecha = get_post_meta($post->ID, '_fecha_evento', true);
    $hora = get_post_meta($post->ID, '_hora_evento', true);
    $ubicacion = get_post_meta($post->ID, '_ubicacion_evento', true);
    $precio = get_post_meta($post->ID, '_precio_evento', true);
    $imagen_id = get_post_meta($post->ID, '_imagen_evento_id', true);
    $imagen_url = $imagen_id ? wp_get_attachment_url($imagen_id) : '';
    $orden_dorsales = get_post_meta($post->ID, '_orden_dorsales', true) ?: 'compartido';

    ?>
    <p><label>Deporte: <input type="text" name="deporte_evento" value="<?= esc_attr($deporte) ?>" style="width: 100%;"></label></p>
    <p><label>Fecha: <input type="date" name="fecha_evento" value="<?= esc_attr($fecha) ?>"></label></p>
    <p><label>Hora: <input type="time" name="hora_evento" value="<?= esc_attr($hora) ?>"></label></p>
    <p><label>Ubicación: <input type="text" name="ubicacion_evento" value="<?= esc_attr($ubicacion) ?>" style="width: 100%;"></label></p>
    <p><label>Precio inscripción (€): <input type="number" step="0.01" min="0" name="precio_evento" value="<?= esc_attr($precio) ?>"></label></p>

    <p>
        <label><strong>Asignación de dorsales:</strong><br>
            <select name="orden_dorsales">
                <option value="compartido" <?= $orden_dorsales === 'compartido' ? 'selected' : '' ?>>Todas las categorías comparten dorsales</option>
                <option value="por_categoria" <?= $orden_dorsales === 'por_categoria' ? 'selected' : '' ?>>Cada categoría tiene sus dorsales independientes</option>
            </select>
        </label>
    </p>

    <p><strong>Imagen del Evento:</strong></p>
    <div>
        <img id="preview_imagen_evento" src="<?= esc_url($imagen_url) ?>" style="max-width: 200px; max-height: 200px; display: <?= $imagen_url ? 'block' : 'none' ?>;">
        <input type="hidden" id="imagen_evento_id" name="imagen_evento_id" value="<?= esc_attr($imagen_id) ?>">
        <button type="button" class="button" id="boton_seleccionar_imagen">Seleccionar imagen</button>
        <button type="button" class="button" id="boton_borrar_imagen" style="display: <?= $imagen_url ? 'inline-block' : 'none' ?>;">Eliminar imagen</button>
    </div>

    <script>
        jQuery(document).ready(function($){
            var frame;
            $('#boton_seleccionar_imagen').on('click', function(e){
                e.preventDefault();
                if(frame){
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Selecciona o sube la imagen del evento',
                    button: { text: 'Usar esta imagen' },
                    multiple: false
                });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#imagen_evento_id').val(attachment.id);
                    $('#preview_imagen_evento').attr('src', attachment.url).show();
                    $('#boton_borrar_imagen').show();
                });
                frame.open();
            });
            $('#boton_borrar_imagen').on('click', function(e){
                e.preventDefault();
                $('#imagen_evento_id').val('');
                $('#preview_imagen_evento').hide();
                $(this).hide();
            });
        });
    </script>
    <?php
}

// 7. Mostrar UI visual para categorías (igual que antes)
function mostrar_categorias_evento($post) {
    $categorias = get_post_meta($post->ID, '_categorias_evento', true);
    $categorias_array = json_decode($categorias, true);
    ?>
    <style>
        .fila-cat input, .fila-cat select { margin-right: 8px; margin-bottom: 4px; }
        .fila-cat { margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
    </style>
    <div id="contenedor-categorias"></div>
    <button type="button" onclick="agregarCategoria()">+ Añadir categoría</button>
    <input type="hidden" name="categorias_json" id="categorias_json">
    <script>
        const contenedor = document.getElementById('contenedor-categorias');
        const inputJSON = document.getElementById('categorias_json');
        let categorias = <?php echo json_encode($categorias_array ?: []); ?>;

        function renderizarCategorias() {
            contenedor.innerHTML = '';
            categorias.forEach((cat, i) => {
                const div = document.createElement('div');
                div.className = 'fila-cat';
                div.innerHTML = `
                    <input placeholder="Nombre" value="${cat.nombre || ''}" oninput="actualizar(${i}, 'nombre', this.value)">
                    <input type="number" placeholder="Plazas" min="0" value="${cat.plazas || ''}" oninput="actualizar(${i}, 'plazas', this.value)">
                    <input type="number" placeholder="Edad mín" min="0" value="${cat.edad_min || ''}" oninput="actualizar(${i}, 'edad_min', this.value)">
                    <input type="number" placeholder="Edad máx" min="0" value="${cat.edad_max || ''}" oninput="actualizar(${i}, 'edad_max', this.value)">
                    <select onchange="actualizar(${i}, 'genero', this.value)">
                        <option value="">Género</option>
                        <option value="masculino" ${cat.genero === 'masculino' ? 'selected' : ''}>Masculino</option>
                        <option value="femenino" ${cat.genero === 'femenino' ? 'selected' : ''}>Femenino</option>
                    </select>
                    <button type="button" onclick="eliminarCategoria(${i})">Eliminar</button>
                `;
                contenedor.appendChild(div);
            });
            inputJSON.value = JSON.stringify(categorias);
        }

        function agregarCategoria() {
            categorias.push({ nombre: '', plazas: '', edad_min: '', edad_max: '', genero: '' });
            renderizarCategorias();
        }

        function eliminarCategoria(i) {
            categorias.splice(i, 1);
            renderizarCategorias();
        }

        function actualizar(i, campo, valor) {
            categorias[i][campo] = valor;
            inputJSON.value = JSON.stringify(categorias);
        }
        document.addEventListener('DOMContentLoaded', function() {
            renderizarCategorias();
        });
    </script>
    <?php
}

// 8. Mostrar UI para archivos del evento
function mostrar_archivos_evento($post) {
    $archivos = get_post_meta($post->ID, '_archivos_evento', true);
    $archivos = is_array($archivos) ? $archivos : [];

    ?>
    <div id="archivos_evento_contenedor">
        <?php foreach ($archivos as $index => $archivo): ?>
            <div class="archivo-item" style="margin-bottom:10px;">
                <input type="text" name="archivos_evento[<?= $index ?>][nombre]" placeholder="Nombre del archivo" style="width:40%;" value="<?= esc_attr($archivo['nombre']) ?>" />
                <input type="hidden" class="archivo-id" name="archivos_evento[<?= $index ?>][id]" value="<?= intval($archivo['id']) ?>" />
                <input type="text" name="archivos_evento[<?= $index ?>][url]" readonly style="width:50%;" value="<?= esc_url(wp_get_attachment_url($archivo['id'])) ?>" />
                <button type="button" class="button seleccionar-archivo">Seleccionar archivo</button>
                <button type="button" class="button borrar-archivo">Eliminar</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="agregar_archivo">+ Añadir archivo</button>

    <script>
    jQuery(document).ready(function($){
        let frame;
        function abrirMediaUploader(el) {
            if(frame) frame.open();
            else {
                frame = wp.media({
                    title: 'Selecciona o sube un archivo',
                    button: { text: 'Usar este archivo' },
                    multiple: false
                });
            }
            frame.off('select');
            frame.on('select', function(){
                const attachment = frame.state().get('selection').first().toJSON();
                const contenedor = $(el).closest('.archivo-item');
                contenedor.find('.archivo-id').val(attachment.id);
                contenedor.find('input[type="text"]').last().val(attachment.url);
            });
            frame.open();
        }

        $('#archivos_evento_contenedor').on('click', '.seleccionar-archivo', function(e){
            e.preventDefault();
            abrirMediaUploader(this);
        });

        $('#archivos_evento_contenedor').on('click', '.borrar-archivo', function(e){
            e.preventDefault();
            $(this).closest('.archivo-item').remove();
        });

        $('#agregar_archivo').on('click', function(){
            const index = $('#archivos_evento_contenedor .archivo-item').length;
            const nuevo = `
                <div class="archivo-item" style="margin-bottom:10px;">
                    <input type="text" name="archivos_evento[${index}][nombre]" placeholder="Nombre del archivo" style="width:40%;" />
                    <input type="hidden" class="archivo-id" name="archivos_evento[${index}][id]" value="" />
                    <input type="text" name="archivos_evento[${index}][url]" readonly style="width:50%;" value="" />
                    <button type="button" class="button seleccionar-archivo">Seleccionar archivo</button>
                    <button type="button" class="button borrar-archivo">Eliminar</button>
                </div>
            `;
            $('#archivos_evento_contenedor').append(nuevo);
        });
    });
    </script>
    <?php
}

add_action('save_post_evento_atlet', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Guarda los campos personalizados
    update_post_meta($post_id, '_deporte_evento', sanitize_text_field($_POST['deporte_evento'] ?? ''));
    update_post_meta($post_id, '_fecha_evento', sanitize_text_field($_POST['fecha_evento'] ?? ''));
    update_post_meta($post_id, '_hora_evento', sanitize_text_field($_POST['hora_evento'] ?? ''));
    update_post_meta($post_id, '_ubicacion_evento', sanitize_text_field($_POST['ubicacion_evento'] ?? ''));
    update_post_meta($post_id, '_precio_evento', sanitize_text_field($_POST['precio_evento'] ?? ''));
    update_post_meta($post_id, '_imagen_evento_id', intval($_POST['imagen_evento_id'] ?? 0));
    update_post_meta($post_id, '_orden_dorsales', sanitize_text_field($_POST['orden_dorsales'] ?? 'compartido'));

    // Guardar categorías
    if (isset($_POST['categorias_json'])) {
        update_post_meta($post_id, '_categorias_evento', wp_unslash($_POST['categorias_json']));
    }

    // Guardar archivos
    if (isset($_POST['archivos_evento'])) {
        update_post_meta($post_id, '_archivos_evento', $_POST['archivos_evento']);
    }
});
?>