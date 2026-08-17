<?php
// includes/carrito_json.php

// Ruta al archivo JSON
$jsonFile = 'data/cart.json'; 

// Función para inicializar el archivo JSON si no existe
function initJson() {
    global $jsonFile;
    if (!file_exists($jsonFile)) {
        // Crea el archivo con un arreglo vacío
        file_put_contents($jsonFile, json_encode([])); 
    }
}

// LEER (Read): Función para obtener los productos de la sesión actual
function obtenerCarrito($session) {
    global $jsonFile;
    initJson();
    
    // Leer y decodificar el archivo JSON
    $data = json_decode(file_get_contents($jsonFile), true);
    
    $items = [];
    if (is_array($data)) {
        foreach ($data as $item) {
            if ($item['session_id'] === $session) {
                // Convertimos a objeto con nombres compatibles con el código existente
                $obj = new stdClass();
                $obj->id = $item['id_producto'];
                $obj->id_producto = $item['id_producto'];
                $obj->nombre = $item['nombre'];
                $obj->precio = $item['precio'];
                $obj->cantidad = $item['cantidad'];
                $obj->imagen = isset($item['imagen']) ? $item['imagen'] : '';
                $items[] = $obj;
            }
        }
    }
    return $items;
}

// CREAR/ACTUALIZAR (Create/Update): Función para agregar un producto al carrito
function agregarAlCarrito($session, $id_producto, $nombre, $precio, $cantidad, $imagen = '') {
    global $jsonFile;
    initJson();
    
    $data = json_decode(file_get_contents($jsonFile), true);
    $encontrado = false;
    
    // Buscar si el producto ya existe para sumarle la cantidad
    foreach ($data as &$item) {
        if ($item['session_id'] === $session && $item['id_producto'] == $id_producto) {
            $item['cantidad'] += $cantidad;
            $encontrado = true;
            break;
        }
    }
    
    // Si no existe, agregarlo como nuevo
    if (!$encontrado) {
        $data[] = [
            'session_id' => $session,
            'id_producto' => $id_producto,
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad,
            'imagen' => $imagen
        ];
    }
    
    // Guardar cambios en el archivo usando JSON_PRETTY_PRINT para que quede ordenado
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}

// ELIMINAR (Delete): Función para eliminar un producto del carrito
function eliminarDelCarrito($session, $id_producto) {
    global $jsonFile;
    initJson();
    
    $data = json_decode(file_get_contents($jsonFile), true);
    $newData = [];
    
    // Guardamos todos excepto el que queremos borrar
    foreach ($data as $item) {
        if (!($item['session_id'] === $session && $item['id_producto'] == $id_producto)) {
            $newData[] = $item;
        }
    }
    
    file_put_contents($jsonFile, json_encode($newData, JSON_PRETTY_PRINT));
}

// VACIAR: Función para vaciar todo el carrito tras la compra
function vaciarCarrito($session) {
    global $jsonFile;
    initJson();
    
    $data = json_decode(file_get_contents($jsonFile), true);
    $newData = [];
    
    foreach ($data as $item) {
        if ($item['session_id'] !== $session) {
            $newData[] = $item; // Solo conservamos los carritos de otros usuarios
        }
    }
    
    file_put_contents($jsonFile, json_encode($newData, JSON_PRETTY_PRINT));
}

// ACTUALIZAR CANTIDAD: Función para actualizar la cantidad de un producto
function actualizarCantidad($session, $id_producto, $nueva_cantidad) {
    global $jsonFile;
    initJson();
    
    $data = json_decode(file_get_contents($jsonFile), true);
    
    foreach ($data as &$item) {
        if ($item['session_id'] === $session && $item['id_producto'] == $id_producto) {
            $item['cantidad'] = (int)$nueva_cantidad;
            break;
        }
    }
    
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}
?>