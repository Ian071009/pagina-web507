<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "clientes";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
        $sql = "INSERT INTO clientes (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':mensaje', $mensaje);
        
        if ($stmt->execute()) {
            echo "exito";
        } else {
            echo "error";
        }
    } else {
        echo "campos_vacios";
    }

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
