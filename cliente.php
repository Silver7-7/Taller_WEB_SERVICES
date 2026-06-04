<?php
$resultado = null;
$error = null;
$id_buscado = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id_buscado = $_POST['id'];
    
    try {
        $options = array(
            'uri' => 'http://localhost/Taller_WEB_SERVICES/',
            'location' => 'http://localhost/Taller_WEB_SERVICES/servidor.php',
            'trace' => true
        );
        
        $client = new SoapClient(null, $options);
        $respuesta = $client->__soapCall('obtenerEstudiantePorId', array($id_buscado));
        
        if ($respuesta->id == 0) {
            $error = "No se encontró estudiante con ID: $id_buscado";
        } else {
            $resultado = $respuesta;
        }
    } catch (Exception $e) {
        $error = "Error de conexión: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente SOAP - Consulta</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; padding: 40px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #3498db; color: white; border: none; cursor: pointer; }
        .exito { background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin-top: 20px; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔍 Consultar Estudiante</h2>
        <form method="POST">
            <label>ID del estudiante (1-5):</label>
            <input type="number" name="id" min="1" max="5" value="<?php echo $id_buscado; ?>" required>
            <button type="submit">Buscar</button>
        </form>
        
        <?php if ($resultado): ?>
            <div class="exito">
                <h3>✅ Estudiante encontrado</h3>
                <p><strong>ID:</strong> <?php echo $resultado->id; ?></p>
                <p><strong>Nombre:</strong> <?php echo $resultado->nombre; ?></p>
                <p><strong>Carrera:</strong> <?php echo $resultado->carrera; ?></p>
                <p><strong>Promedio:</strong> <?php echo $resultado->promedio; ?></p>
            </div>
        <?php elseif ($error): ?>
            <div class="error">
                <strong>❌ Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>