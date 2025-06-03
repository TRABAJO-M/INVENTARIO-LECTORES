<?php
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $sector = trim($_POST["sector"] ?? '');
    $usuario = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["usuario"] ?? '');

$usuariosValidos = ["Cristobal", "Ruben", "Andrea", "Juan", "Sebastian", "Jhoan", "Matias","Claudio", "Renato"];
$sectoresValidos = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13"];

    if (!in_array($usuario, $usuariosValidos) || !in_array($sector, $sectoresValidos)) {
        http_response_code(403);
        exit("Usuario o sector no permitido.");
    }

    if ($codigo !== '') {
        $fecha = date("d/m/Y H:i:s");
        $linea = "*$fecha* | Usuario: *$usuario* | Sector: *$sector* | Código: *$codigo" . PHP_EOL;

        $archivo = "registros/codigos.txt";
        if (!is_dir("registros")) {
            mkdir("registros", 0777, true);
        }

        file_put_contents($archivo, $linea, FILE_APPEND);
    }
}
?>
