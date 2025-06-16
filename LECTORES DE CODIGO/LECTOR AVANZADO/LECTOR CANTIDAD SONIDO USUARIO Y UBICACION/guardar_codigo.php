<?php
date_default_timezone_set("America/Santiago");
// SECCION PARA INCLUIR LOS MENSAJES QUE LLEGARAN DESDE HTML

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $sector = trim($_POST["sector"] ?? '');
    $bodega = trim($_POST["bodega"] ?? '');
    $usuario = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["usuario"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');
// FIN SECCION PARA INCLUIR LOS MENSAJES QUE LLEGARAN DESDE HTML

// SECCION PARA INCLUIR LOS USUARIOS BODEGA Y RACKS
$usuariosValidos = ["Cristobal", "Ruben", "Andrea", "Juan", "Sebastian", "Jhoan", "Matias","Claudio", "Renato"];
$bodegasValidas = ["Primer Piso", "Segundo Piso", "Bodega G3", "Pintura y Lubricante", "Vidrios", "Container", "Vulcanizacion"];
$sectoresValidos = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "Piso o Pared"];
// FIN SECCION PARA INCLUIR LOS USUARIOS BODEGA Y RACKS

    if (!in_array($usuario, $usuariosValidos) || !in_array($bodega, $bodegasValidas) || !in_array($sector, $sectoresValidos) || !is_numeric($cantidad) || $cantidad <= 0) {
        http_response_code(403);
        exit("Usuario, bodega o rack no permitido.");
    }
// SECCION PARA CORREGIR FORMATO DEL MENSAJE QUE LLEGARA AL ARCHIVO DE NOTAS
    if ($codigo !== '') {
        $fecha = date("d/m/Y H:i:s");
        $linea = "Código: *$codigo* | Cantidad: *$cantidad* Fecha: *$fecha* | Usuario: *$usuario* | Bodega: *$bodega* | Rack: *$sector*" . PHP_EOL;
// FIN SECCION PARA CORREGIR FORMATO DEL MENSAJE QUE LLEGARA AL ARCHIVO DE NOTAS
        $archivo = "registros/codigos.txt";
        if (!is_dir("registros")) {
            mkdir("registros", 0777, true);
        }

        file_put_contents($archivo, $linea, FILE_APPEND);
    }
}
?>
