<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "clientes";

// 1. Verificar que el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
                // Redirigir de vuelta a INDEX2.php con estado exitoso
                header("Location: INDEX2.php?status=exito#contacto");
                exit();
            } else {
                header("Location: INDEX2.php?status=error#contacto");
                exit();
            }
        } else {
            // Si falta algún campo obligatorio
            header("Location: INDEX2.php?status=campos_vacios#contacto");
            exit();
        }

    } catch(PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }

} else {
    // Si intentan entrar a este archivo directamente sin llenar el formulario
    header("Location: INDEX2.php");
    exit();
}
?>