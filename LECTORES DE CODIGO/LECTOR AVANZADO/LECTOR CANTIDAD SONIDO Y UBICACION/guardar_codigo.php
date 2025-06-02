<?php
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');
    $usuario = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["usuario"] ?? '');
    $sector = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["sector"] ?? '');

    // Lista segura (debe coincidir con la del HTML)
    $usuariosValidos = ["Pedro", "Maria", "Juana"];
    $sectoresValidos = ["Sector1", "Sector2", "Sector3"];

    if (!in_array($usuario, $usuariosValidos) || !in_array($sector, $sectoresValidos)) {
        http_response_code(403);
        exit("Usuario o sector no permitido.");
    }

    if ($codigo !== '' && $cantidad !== '') {
        $fecha = date("d/m/Y H:i:s");
        $linea = "$fecha Código: *$codigo Cantidad: *$cantidad" . PHP_EOL;

        $directorio = "registros/$usuario/$sector";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $rutaArchivo = "$directorio/codigos.txt";
        file_put_contents($rutaArchivo, $linea, FILE_APPEND);
    }
}
?>
