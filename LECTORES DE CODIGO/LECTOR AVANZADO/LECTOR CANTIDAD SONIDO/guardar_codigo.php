<?php
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');

    if ($codigo !== '' && $cantidad !== '') {
        $fecha = date("d/m/Y H:i:s");
        $linea = "$fecha Código: *$codigo Cantidad:*$cantidad" . PHP_EOL;
        file_put_contents("codigos.txt", $linea, FILE_APPEND);
    }
}
?>