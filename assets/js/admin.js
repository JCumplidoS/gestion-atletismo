// This file contains JavaScript code for handling admin-specific functionality, such as media uploads and dynamic form elements.

jQuery(document).ready(function($) {
    // Media uploader for event image selection
    let frame;
    $('#boton_seleccionar_imagen').on('click', function(e) {
        e.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'Selecciona o sube la imagen del evento',
            button: { text: 'Usar esta imagen' },
            multiple: false
        });
        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            $('#imagen_evento_id').val(attachment.id);
            $('#preview_imagen_evento').attr('src', attachment.url).show();
            $('#boton_borrar_imagen').show();
        });
        frame.open();
    });

    $('#boton_borrar_imagen').on('click', function(e) {
        e.preventDefault();
        $('#imagen_evento_id').val('');
        $('#preview_imagen_evento').hide();
        $(this).hide();
    });

    // Dynamic categories management
    const contenedor = document.getElementById('contenedor-categorias');
    const inputJSON = document.getElementById('categorias_json');
    let categorias = [];

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

    $('#agregar_archivo').on('click', function() {
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