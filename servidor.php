<?php
// servidor.php - Usando SOAP nativo de PHP (no requiere NuSOAP)

// Base de datos simulada
$estudiantes = array(
    1 => array('id' => 1, 'nombre' => 'Ana María Pérez', 'carrera' => 'Ingeniería Informática', 'promedio' => 8.5),
    2 => array('id' => 2, 'nombre' => 'Luis Fernando Gómez', 'carrera' => 'Ingeniería Civil', 'promedio' => 7.9),
    3 => array('id' => 3, 'nombre' => 'Carolina Mendoza Ríos', 'carrera' => 'Administración de Empresas', 'promedio' => 9.2),
    4 => array('id' => 4, 'nombre' => 'José David Torres', 'carrera' => 'Derecho', 'promedio' => 6.8),
    5 => array('id' => 5, 'nombre' => 'Valentina Suárez Ortiz', 'carrera' => 'Medicina', 'promedio' => 9.5)
);

function obtenerEstudiantePorId($id) {
    global $estudiantes;
    $id = (int)$id;
    
    if (isset($estudiantes[$id])) {
        return $estudiantes[$id];
    } else {
        return array(
            'id' => 0,
            'nombre' => 'No encontrado',
            'carrera' => 'No encontrado',
            'promedio' => 0
        );
    }
}

// Configurar servidor SOAP
$wsdl = false; // Usamos modo no-WSDL

// Si la petición es para obtener el WSDL
if (isset($_GET['wsdl'])) {
    header('Content-Type: application/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>
    <definitions xmlns="http://schemas.xmlsoap.org/wsdl/"
                 xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
                 xmlns:tns="http://localhost/Taller_WEB_SERVICES/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                 targetNamespace="http://localhost/Taller_WEB_SERVICES/">
      <types>
        <xsd:schema targetNamespace="http://localhost/Taller_WEB_SERVICES/">
          <xsd:complexType name="Estudiante">
            <xsd:sequence>
              <xsd:element name="id" type="xsd:int"/>
              <xsd:element name="nombre" type="xsd:string"/>
              <xsd:element name="carrera" type="xsd:string"/>
              <xsd:element name="promedio" type="xsd:float"/>
            </xsd:sequence>
          </xsd:complexType>
        </xsd:schema>
      </types>
      <message name="obtenerEstudiantePorIdRequest">
        <part name="id" type="xsd:string"/>
      </message>
      <message name="obtenerEstudiantePorIdResponse">
        <part name="return" type="tns:Estudiante"/>
      </message>
      <portType name="ServicioEstudiantesPortType">
        <operation name="obtenerEstudiantePorId">
          <input message="tns:obtenerEstudiantePorIdRequest"/>
          <output message="tns:obtenerEstudiantePorIdResponse"/>
        </operation>
      </portType>
      <binding name="ServicioEstudiantesBinding" type="tns:ServicioEstudiantesPortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="obtenerEstudiantePorId">
          <soap:operation soapAction="http://localhost/Taller_WEB_SERVICES/obtenerEstudiantePorId"/>
          <input><soap:body use="encoded" namespace="http://localhost/Taller_WEB_SERVICES/"/></input>
          <output><soap:body use="encoded" namespace="http://localhost/Taller_WEB_SERVICES/"/></output>
        </operation>
      </binding>
      <service name="ServicioEstudiantes">
        <port name="ServicioEstudiantesPort" binding="tns:ServicioEstudiantesBinding">
          <soap:address location="http://localhost/Taller_WEB_SERVICES/servidor.php"/>
        </port>
      </service>
    </definitions>';
    exit;
}

// Crear servidor SOAP
$options = array('uri' => 'http://localhost/Taller_WEB_SERVICES/');
$server = new SoapServer(null, $options);
$server->addFunction('obtenerEstudiantePorId');
$server->handle();
?>