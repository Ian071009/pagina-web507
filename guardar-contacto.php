<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "cafeteria507";

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
            $stmt->execute([':nombre' => $nombre, ':correo' => $correo, ':mensaje' => $mensaje]);
            
            // Redirigir de regreso a index.html
            header("Location: index.html?status=exito#contacto");
            exit();
        } else {
            header("Location: index.html?status=campos_vacios#contacto");
            exit();
        }

    } catch(PDOException $e) {
        header("Location: index.html?status=error#contacto");
        exit();
    }

} else {
    header("Location: index.html");
    exit();
}
?>