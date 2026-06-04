<?php
// Incluir la librería NuSOAP
require_once('lib/nusoap.php');

$resultado = null;
$id_buscado = "";

// Verificar si se ha enviado el formulario web
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['id_estudiante'])) {
    $id_buscado = $_POST['id_estudiante'];
    
    // Instanciar el cliente SOAP apuntando directamente a la URL de tu servidor local
    $url_servidor = "http://localhost/Taller_WEB_SERVICES/servidor.php?wsdl";
    $client = new nusoap_client($url_servidor, true);

    // Verificar si ocurrieron errores al conectarse al WSDL
    $error = $client->getError();
    if ($error) {
        $resultado = array("error" => "Error de configuración: " . $error);
    } else {
        // Consumir el método remoto enviando el ID encapsulado
        $response = $client->call('consultarEstudiante', array('id' => $id_buscado));

        // Verificar fallos o respuestas vacías en la llamada
        if ($client->fault) {
            $resultado = array("error" => "Fallo en la comunicación SOAP.");
        } else {
            $error = $client->getError();
            if ($error) {
                $resultado = array("error" => "Error de respuesta: " . $error);
            } else {
                // Decodificar el JSON de respuesta enviado por el servidor
                $resultado = json_decode($response, true);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Estudiantes - Cliente SOAP (Modo Oscuro)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajuste personalizado para inputs en modo oscuro */
        .form-control-dark {
            background-color: #2b3035;
            border-color: #495057;
            color: #fff;
        }
        .form-control-dark:focus {
            background-color: #32383e;
            border-color: #0d6efd;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body class="bg-dark text-light" style="background-color: #121212 !important;">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card bg-secondary text-white shadow-lg border-0" style="background-color: #1e1e1e !important;">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0">🔍 Consulta de Estudiantes</h3>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="cliente.php">
                    <div class="mb-3">
                        <label for="id_estudiante" class="form-label text-light-50">Ingrese el ID del Estudiante (Ej: 101, 102):</label>
                        <input type="text" class="form-control form-control-dark" id="id_estudiante" name="id_estudiante" value="<?php echo htmlspecialchars($id_buscado); ?>" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Consultar Servidor SOAP</button>
                </form>

                <hr class="my-4 border-secondary">

                <?php if ($resultado !== null): ?>
                    <?php if (isset($resultado['error'])): ?>
                        <div class="alert alert-danger text-center border-0 bg-danger text-white" role="alert">
                            ⚠️ <?php echo $resultado['error']; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-3 rounded border border-secondary" style="background-color: #252525;">
                            <h5 class="text-info border-bottom border-secondary pb-2 mb-3">🎯 Estudiante Encontrado</h5>
                            <p class="mb-2"><strong>Nombre:</strong> <span class="text-white-50"><?php echo $resultado['nombre']; ?></span></p>
                            <p class="mb-2"><strong>Carrera:</strong> <span class="text-white-50"><?php echo $resultado['carrera']; ?></span></p>
                            <p class="mb-0"><strong>Promedio General:</strong> <span class="badge bg-info text-dark fs-6 fw-bold"><?php echo $resultado['promedio']; ?></span></p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer text-center border-secondary py-3">
                <a href="uddi.html" class="btn btn-sm btn-outline-light text-decoration-none px-3">Ir al Catálogo de Servicios (UDDI)</a>
            </div>
        </div>
    </div>
</body>
</html>