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
    $usuariosValidos = ["Cristobal", "Ruben", "Andrea", "Juan", "Sebastian", "Jhoan", "Viberson","Matias","Claudio", "Renato"];
    $bodegasValidas = ["Primer Piso", "Segundo Piso", "Bodega G3", "Pintura y Lubricante", "Vidrios", "Container", "Vulcanizacion"];
    $sectoresValidos = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "Piso o Pared"];

    // Validación completa
    if (
        $codigo === '' ||
        !in_array($usuario, $usuariosValidos) ||
        !in_array($bodega, $bodegasValidas) ||
        !in_array($sector, $sectoresValidos) ||
        ($cantidad !== '' && (!is_numeric($cantidad) || $cantidad < 0))
    ) {
        http_response_code(400);
        echo "Datos inválidos o incompletos.";
        exit;
    }

    // Preparar línea a guardar
    $fecha = date("d/m/Y");
    $hora = date("H:i:s");
    // Asegúrate de que el orden de las columnas aquí coincida con el orden de la cabecera
    $linea = "$fecha;$hora;$codigo;$cantidad;$usuario;$bodega;$sector" . PHP_EOL;

    // Guardar en archivo
    $archivo = "registros/codigos.csv";

    // Crear la carpeta 'registros' si no existe
    if (!is_dir("registros")) {
        mkdir("registros", 0777, true);
    }
      
    // --- INICIO DE LA LÓGICA MODIFICADA PARA LA CABECERA ---
    // Verificar si el archivo no existe O si existe pero está vacío (tamaño 0)
    if (!file_exists($archivo) || filesize($archivo) == 0) {
        $cabecera = "Fecha;Hora;Codigo;Cantidad;Usuario;Bodega;Zona" . PHP_EOL;
        // Siempre usamos FILE_APPEND para evitar sobrescribir si por alguna razón el archivo ya tuviera algo
        file_put_contents($archivo, $cabecera, FILE_APPEND | LOCK_EX); // Añadido LOCK_EX para mejor seguridad
    }
    // --- FIN DE LA LÓGICA MODIFICADA PARA LA CABECERA ---

    // ESCRIBIR AL FINAL, SI EL ARCHIVO YA EXISTE AL FINAL DEL ARCHIVO
    // Añadido LOCK_EX aquí también para consistencia
    if (file_put_contents($archivo, $linea, FILE_APPEND | LOCK_EX) !== false) {
        // Confirmación simple
        http_response_code(200);
        echo "Guardado correctamente.";
    } else {
        http_response_code(500); // Internal Server Error
        error_log("Error al escribir en el archivo: " . $archivo); // Registrar el error para depuración
        echo "Error al guardar los datos en el archivo.";
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo "Método no permitido.";
}
?>