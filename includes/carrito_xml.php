<?php

function obtenerRuta(){
    return __DIR__ . '/../data/cart.xml';
}

function obtenerXML(){

    $archivo = obtenerRuta();

    /* Crear XML si no existe */
    if(!file_exists($archivo)){
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><carrito></carrito>');
        $xml->asXML($archivo);
        return $xml;
    }

    /* Cargar XML */
    $xml = simplexml_load_file($archivo);

    /* Si el XML está corrupto lo recreamos */
    if($xml === false){
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><carrito></carrito>');
        $xml->asXML($archivo);
    }

    return $xml;
}

function guardarXML($xml){
    $archivo = obtenerRuta();
    $xml->asXML($archivo);
}

/* =========================
AGREGAR PRODUCTO
========================= */

function agregarProducto($session,$id,$nombre,$precio,$cantidad,$imagen){

    $xml = obtenerXML();

    foreach($xml->item as $item){

        if((string)$item->session == (string)$session && (string)$item->id == (string)$id){

            $item->cantidad = (int)$item->cantidad + (int)$cantidad;
            guardarXML($xml);
            return;
        }
    }

    $nuevo = $xml->addChild("item");

    $nuevo->addChild("session",$session);
    $nuevo->addChild("id",$id);
    $nuevo->addChild("nombre",$nombre);
    $nuevo->addChild("precio",$precio);
    $nuevo->addChild("cantidad",$cantidad);
    $nuevo->addChild("imagen",$imagen);

    guardarXML($xml);
}

/* =========================
LEER CARRITO
========================= */

function obtenerCarrito($session){

    $xml = obtenerXML();
    $items = [];

    foreach($xml->item as $item){

        if((string)$item->session == (string)$session){
            $items[] = $item;
        }
    }

    return $items;
}

/* =========================
ACTUALIZAR CANTIDAD
========================= */

function actualizarCantidad($session,$id,$cantidad){

    $xml = obtenerXML();

    foreach($xml->item as $item){

        if((string)$item->session == (string)$session && (string)$item->id == (string)$id){

            $item->cantidad = (int)$cantidad;
        }
    }

    guardarXML($xml);
}

/* =========================
ELIMINAR PRODUCTO
========================= */

function eliminarProducto($session,$id){

    $xml = obtenerXML();

    for($i = count($xml->item) - 1; $i >= 0; $i--){

        if((string)$xml->item[$i]->session == (string)$session && (string)$xml->item[$i]->id == (string)$id){

            unset($xml->item[$i]);
        }
    }

    guardarXML($xml);
}

/* =========================
VACIAR CARRITO
========================= */

function vaciarCarrito($session){

    $xml = obtenerXML();

    for($i = count($xml->item) - 1; $i >= 0; $i--){

        if((string)$xml->item[$i]->session == (string)$session){

            unset($xml->item[$i]);
        }
    }

    guardarXML($xml);
}

?>
