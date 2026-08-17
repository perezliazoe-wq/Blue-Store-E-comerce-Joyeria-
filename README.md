BlueStore

BlueStore es un sitio web de comercio electrónico especializado en la venta de piezas de joyería, desarrollado con el objetivo de ofrecer a los usuarios una plataforma digital segura, elegante y con una navegación altamente intuitiva.

El sitio permite a los usuarios explorar el catálogo completo de productos sin necesidad de iniciar sesión, reduciendo la fricción en el proceso de descubrimiento y favoreciendo una mayor tasa de conversión.

Objetivo del proyecto

Comercializar piezas de joyería de manera digital, ofreciendo a los usuarios una plataforma segura, elegante y con una navegación altamente intuitiva, similar a la experiencia de una tienda de joyería física pero accesible desde cualquier dispositivo.

Público objetivo
Perfil demográfico: Hombres y mujeres, principalmente entre 25 y 55 años, con nivel socioeconómico medio a medio-alto.
Intereses: Moda, diseño minimalista, marcas con identidad visual fuerte.
Motivo de compra: Adquisición personal (lujo/premio) o regalo para ocasiones especiales (aniversarios, cumpleaños, compromisos, Día de la Madre, etc.).
Hábitos digitales: Usuarios familiarizados con el comercio electrónico, que valoran velocidad de carga, imágenes claras y un proceso de carrito/login transparente.
Características principales
Catálogo de productos navegable sin necesidad de registro
Carrito de compras
Proceso de checkout/compra
Generación de documentos (PDF) mediante la librería FPDF
Diseño enfocado en presentación elegante y navegación clara
Tecnologías utilizadas
Backend: PHP
Base de datos: MySQL
Frontend: HTML, CSS, JavaScript
Librerías: FPDF (generación de PDFs)
Entorno de desarrollo: XAMPP
Instalación y uso local
Clona este repositorio dentro de la carpeta htdocs de tu instalación de XAMPP:
bash
   git clone https://github.com/tu-usuario/bluestore.git
Abre XAMPP y activa los módulos Apache y MySQL.
Crea una base de datos en phpMyAdmin (por ejemplo, bluestore) e importa el archivo .sql incluido en el proyecto (si aplica).
Revisa el archivo de configuración de conexión a la base de datos (por ejemplo config.php o similar) y actualiza usuario, contraseña y nombre de la base de datos según tu entorno.
Abre tu navegador y entra a:
   http://localhost/bluestore

Estructura del proyecto
bluestore/
├── data/           # Archivos de datos (ej. cart.xml)
├── fpdf/           # Librería FPDF para generación de PDFs
├── (otras carpetas del proyecto)
└── index.php

(Ajusta esta sección según la estructura real de tus carpetas)

Estado actual

Este proyecto se entrega como un MVP (producto mínimo viable) sólido y funcional, cubriendo las funcionalidades esenciales de una tienda en línea: catálogo, carrito y proceso de compra.

Próximas mejoras
Buscador de productos
Filtros por precio
Carrito persistente en base de datos (en lugar de manejo actual)
Sistema de reseñas de productos

Autora

Lia Zoe Pérez Villaseñor
