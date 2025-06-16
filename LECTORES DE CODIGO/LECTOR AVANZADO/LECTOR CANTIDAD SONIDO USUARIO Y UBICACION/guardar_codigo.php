<?php
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos desde el POST
    $codigo = trim($_POST["codigo"] ?? '');
    $sector = trim($_POST["sector"] ?? '');
    $bodega = trim($_POST["bodega"] ?? '');
    $usuario = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["usuario"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');

    // Validaciones de listas permitidas
    $usuariosValidos = ["Cristobal", "Ruben", "Andrea", "Juan", "Sebastian", "Jhoan", "Matias", "Claudio", "Renato"];
    $bodegasValidas = ["Primer Piso", "Segundo Piso", "Bodega G3", "Pintura y Lubricante", "Vidrios", "Container", "Vulcanizacion"];
    $sectoresValidos = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "Piso o Pared"];

    // Validación completa
    if (
        !in_array($usuario, $usuariosValidos) ||
        !in_array($bodega, $bodegasValidas) ||
        !in_array($sector, $sectoresValidos) ||
        !is_numeric($cantidad) || $cantidad <= 0 ||
        $codigo === ''
    ) {
        http_response_code(400);
        echo "Datos inválidos o incompletos.";
        exit;
    }

    // Preparar línea a guardar
    $fecha = date("d/m/Y");
    $hora = date("H:i:s");
    $linea = "$fecha;$hora;$codigo;$cantidad;$usuario;$bodega;$sector" . PHP_EOL;
    //  $linea = "Código: *$codigo* | Cantidad: *$cantidad* | Fecha: *$fecha* | Usuario: *$usuario* | Bodega: *$bodega* | Zona: *$sector*" .  // LINEA ANTERIOR
    // Guardar en archivo
    $archivo = "registros/codigos.csv";

    if (!is_dir("registros")) {
        mkdir("registros", 0777, true);
    }
     
    // ✅ Si el archivo no existe, escribir cabecera primero
    if (!file_exists($archivo)) {
        $cabecera = "Fecha;Hora;Codigo;Cantidad;Usuario;Bodega;Zona" . PHP_EOL;
        file_put_contents($archivo, $cabecera, FILE_APPEND);
    }

// ESCRIBIR AL FINAL, SI EL ARCHIVO YA EXISTE AL FINAL DEL ARCHIVO
    file_put_contents($archivo, $linea, FILE_APPEND);

    // Confirmación simple
    http_response_code(200);
    echo "Guardado correctamente.";
}
?>
