<?php
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');
    $sector = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["sector"] ?? '');

    $sectoresValidos = ["Sector1", "Sector2", "Sector3"];

    if (!in_array($sector, $sectoresValidos)) {
        http_response_code(403);
        exit("Sector no permitido.");
    }

    if ($codigo !== '' && $cantidad !== '') {
        $fecha = date("d/m/Y H:i:s");
        $linea = "$fecha | Sector: $sector | Código: *$codigo | Cantidad: *$cantidad" . PHP_EOL;

        $archivo = "registros/codigos.txt";
        if (!is_dir("registros")) {
            mkdir("registros", 0777, true);
        }

        file_put_contents($archivo, $linea, FILE_APPEND);
    }
}
?>
