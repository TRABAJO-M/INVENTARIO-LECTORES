<?php
date_default_timezone_set("America/Santiago");

// CARGAR VARIABLES DESDE .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Saltar comentarios
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

// OBTENER DATOS DEL FORMULARIO
$codigo   = trim($_POST["codigo"] ?? '');
$sector   = trim($_POST["sector"] ?? '');
$bodega   = trim($_POST["bodega"] ?? '');
$usuario  = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["usuario"] ?? '');
$cantidad = trim($_POST["cantidad"] ?? '');

// VALIDACIÓN BÁSICA
if ($codigo === '') {
    http_response_code(400);
    echo "Código no válido";
    exit;
}

// DATOS DE FECHA Y HORA
$fecha = date("Y-m-d");
$hora  = date("H:i:s");

// FORMATO CSV
$linea = "$codigo;$sector;$bodega;$usuario;$cantidad;$fecha;$hora" . PHP_EOL;
$archivo = "codigos.csv";

// CREAR CABECERA SI EL ARCHIVO ESTÁ VACÍO O NO EXISTE
if (!file_exists($archivo) || filesize($archivo) == 0) {
    $cabecera = "Codigo;Sector;Bodega;Usuario;Cantidad;Fecha;Hora" . PHP_EOL;
    file_put_contents($archivo, $cabecera, FILE_APPEND | LOCK_EX);
}

// GUARDAR LÍNEA DE DATOS
file_put_contents($archivo, $linea, FILE_APPEND | LOCK_EX);

// GUARDAR EN MYSQL
try {
    $conn_mysql = new PDO(
        "mysql:host=" . getenv('MYSQL_HOST') . ";dbname=" . getenv('MYSQL_DB') . ";charset=utf8",
        getenv('MYSQL_USER'),
        getenv('MYSQL_PASS')
    );
    $conn_mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn_mysql->prepare("INSERT INTO codigos (codigo, sector, bodega, usuario, cantidad, fecha, hora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $sector, $bodega, $usuario, $cantidad, $fecha, $hora]);
} catch (PDOException $e) {
    error_log("⚠️ Error MySQL: " . $e->getMessage());
}

// GUARDAR EN POSTGRESQL
try {
    $conn_pg = new PDO(
        "pgsql:host=" . getenv('PGSQL_HOST') . ";port=" . getenv('PGSQL_PORT') . ";dbname=" . getenv('PGSQL_DB'),
        getenv('PGSQL_USER'),
        getenv('PGSQL_PASS')
    );
    $conn_pg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn_pg->prepare("INSERT INTO codigos (codigo, sector, bodega, usuario, cantidad, fecha, hora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $sector, $bodega, $usuario, $cantidad, $fecha, $hora]);
} catch (PDOException $e) {
    error_log("⚠️ Error PostgreSQL: " . $e->getMessage());
}

// RESPUESTA FINAL
echo "Guardado exitosamente";
?>
