<?php
// 1️⃣ Establece la zona horaria (puedes cambiar "America/Santiago" por la que necesites)
date_default_timezone_set("America/Santiago");

// 2️⃣ Verifica que la solicitud sea por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 3️⃣ Captura el código enviado desde el formulario (por fetch)
    $codigo = trim($_POST["codigo"] ?? '');

    // 4️⃣ Si no está vacío, se procesa
    if ($codigo !== '') {

        // 5️⃣ Formato personalizado de la fecha
        // Puedes cambiarlo, por ejemplo:
        // date("d/m/Y H:i:s") → 30/05/2025 11:40:21
        // date("Y-m-d\TH:i:s") → 2025-05-30T11:40:21 (ISO 8601)
         $fecha = date("d/m/Y H:i:s");

        // 6️⃣ Construcción de la línea a guardar
        $linea = "$fecha-$codigo" . PHP_EOL;

        // 7️⃣ Guarda la línea al final del archivo
        file_put_contents("codigos.txt", $linea, FILE_APPEND);
    }
}
?>