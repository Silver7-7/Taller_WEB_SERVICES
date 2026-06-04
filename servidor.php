<?php
// 1. Incluir de forma correcta la librería manual de NuSOAP
require_once('lib/nusoap.php');

// 2. Inicializar la instancia del servidor SOAP
$server = new soap_server();

// 3. Configurar el WSDL asignándole un nombre al servicio y un Espacio de Nombres (Namespace)
$namespace = "urn:estudiantes_soap";
$server->configureWSDL("SistemaConsultaEstudiantes", $namespace);

// 4. Registrar el método "consultarEstudiante" definiendo sus entradas y salidas
$server->register(
    'consultarEstudiante',                         // Nombre del método/función
    array('id' => 'xsd:string'),                    // Parámetro de entrada (ID solicitado)
    array('return' => 'xsd:string'),                // Parámetro de salida (String codificado o estructurado)
    $namespace,                                     // Namespace definido previamente
    $namespace . '#consultarEstudiante',            // Acción SOAP (SOAPAction)
    'rpc',                                          // Estilo de llamada (Remote Procedure Call)
    'encoded',                                      // Tipo de codificación
    'Busca y devuelve los datos de un estudiante por su ID.' // Descripción técnica
);

// 5. Lógica del negocio: Función que procesa la consulta
function consultarEstudiante($id) {
    // Base de datos simulada mediante un Array Asociativo
    $estudiantes = array(
        "101" => array("nombre" => "Bryan Espinoza", "carrera" => "Lic. en Desarrollo de Software", "promedio" => "2.8"),
        "102" => array("nombre" => "José Gonzales", "carrera" => "Lic. en Desarrollo de Software", "promedio" => "2.9"),
        "103" => array("nombre" => "Carlitos Ramirez", "carrera" => "Lic. en Ciberseguridad", "promedio" => "2.5")
    );

    // Comprobar si el ID existe en el arreglo
    if (array_key_exists($id, $estudiantes)) {
        // Retornamos los datos estructurados en formato JSON para facilitar su manejo seguro
        return json_encode($estudiantes[$id]);
    } else {
        // En caso de que no exista el ID, se devuelve una estructura de error controlada
        return json_encode(array("error" => "Estudiante no encontrado."));
    }
}

// 6. Publicar y procesar la petición HTTP entrante
$server->service(file_get_contents("php://input"));
?>