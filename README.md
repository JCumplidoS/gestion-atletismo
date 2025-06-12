# Gestión de Eventos de Atletismo

Este plugin de WordPress permite gestionar eventos de atletismo, incluyendo la creación de eventos, categorías, inscripciones, control de dorsales y pagos online.

## Características

- Gestión de eventos y categorías personalizadas.
- Inscripción online con pago mediante Stripe.
- Consulta de dorsal por DNI.
- Administración de inscripciones y dorsales desde el panel de WordPress.
- Widgets de Elementor para personalizar formularios y consultas visualmente.

## Instalación

1. Sube la carpeta del plugin a `/wp-content/plugins/`.
2. Actívalo desde el panel de plugins de WordPress.
3. Asegúrate de tener instalado y activado Elementor para usar los widgets visuales.

## Uso

- **Crear eventos**: Desde el menú "Eventos Atletismo" en el admin, añade un nuevo evento y configura sus datos, categorías y archivos.
- **Widgets de Elementor**:
    - Usa los widgets:
        - **Formulario de inscripción (Atletismo)**
        - **Consulta dorsal (Atletismo)**
        - **Datos de evento (Atletismo)**
        - **Categorías de evento (Atletismo)**
    - Arrástralos a cualquier página desde el editor de Elementor y personaliza su diseño, disposición y estilos visualmente.
- **Pagos**: El usuario es redirigido a Stripe y, tras el pago, vuelve a la página de inscripción donde se muestra el dorsal asignado.
- **Administración**:  
  - Filtra inscripciones por evento y categoría.
  - Consulta el estado de pago y dorsal desde el listado de inscripciones.

## Personalización

- Puedes modificar los estilos en `assets/css/admin.css` y la lógica JS en `assets/js/admin.js`.
- El código está organizado en la carpeta `includes/` para facilitar la extensión y mantenimiento.

## Personalización con Elementor

- Todos los formularios y listados usan clases CSS (`.ga-form`, `.ga-evento`, etc.).
- Personaliza el diseño desde Elementor usando los widgets incluidos y aplicando estilos desde el editor visual.
- Si no usas Elementor, el plugin carga una hoja de estilos básica (`assets/css/public.css`).

## Estructura del Proyecto

```
gestion-atletismo
├── includes
│   ├── admin-columns.php        # Columnas y filtros personalizados en el admin.
│   ├── cpt-evento.php           # Registro del CPT de eventos.
│   ├── cpt-inscripcion.php      # Registro del CPT de inscripciones.
│   ├── meta-boxes.php           # Metaboxes y guardado de campos personalizados.
│   ├── shortcodes.php           # Otros shortcodes menores (por ejemplo, imagen de evento).
│   ├── filters.php              # Filtros y utilidades.
│   ├── pasarela-pago.php        # Integración con Stripe.
│   ├── stripe-webhook.php       # Webhook de Stripe para pagos.
│   ├── utils.php                # Funciones utilitarias.
│   └── elementor-widgets/
│       ├── register.php
│       ├── widget-categorias-evento.php
│       ├── widget-consulta-dorsal.php
│       ├── widget-datos-evento.php
│       └── widget-formulario-inscripcion.php
├── assets
│   ├── css
│   │   └── admin.css
│   └── js
│       └── admin.js
├── gestion-atletismo.php        # Archivo principal del plugin.
└── README.md
```

## Contribuciones

Las contribuciones son bienvenidas. Si deseas mejorar el plugin, abre un issue o envía un pull request.


## Licencia

Este plugin está licenciado bajo la [Licencia GPL v2](http://www.gnu.org/licenses/gpl-2.0.html).
