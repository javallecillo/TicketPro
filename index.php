<?php
    /*session_start();

    require_once "Define.php";
    require_once "Config\JRequest.php";
    require_once "Config\JRouter.php";
    require_once "Config\AutoLoad.php";

    if(!isset($_SESSION["system"]["username"])){
        header("Location: /Login");
        exit();
    }

    Config\AutoLoad::run();
    include_once "Template\index.php";
    Config\JRouter::run(new Config\JRequest());*/

    session_start();

    require_once "Define.php";
    require_once "Config\JRequest.php";
    require_once "Config\JRouter.php";
    require_once "Config\AutoLoad.php";

    // 1. Obtener la ruta solicitada usando $_SERVER (la forma más genérica)
    // Esto debería devolver algo como '/Reporte/ReporteUsuarios'
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 

    // Limpiar la ruta de posibles parámetros de consulta (si existen)
    $path = strtok($path, '?');

    // 2. Comprobar si la solicitud actual es para un Reporte PDF
    // strpos verifica si '/Reporte/' existe en la ruta ($path).
    $is_pdf_report = (strpos($path, '/Reporte/') !== false);
    
    // Lógica de Autenticación
    if(!isset($_SESSION["system"]["username"]) && $path !== '/Login'){
        header("Location: /Login");
        exit();
    }

    Config\AutoLoad::run();
    
    // 3. INCLUSIÓN CONDICIONAL DE LA PLANTILLA
    // Solo incluye la plantilla HTML si NO es un reporte PDF
    if (!$is_pdf_report) {
        include_once "Template\index.php";
    }
    
    // 4. El Router siempre se ejecuta, y le pasamos una nueva instancia de JRequest
    // (Esto asegura que la lógica de tu router no se rompa)
    Config\JRouter::run(new Config\JRequest());
?>
