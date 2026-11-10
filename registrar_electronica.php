<?php
// Configuración de la conexión a MySQL
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "cafeteria507"; 

$mensaje = "";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Procesar el formulario cuando se envía por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre      = trim($_POST['nombre']);
        $precio      = trim($_POST['precio']);
        $descripcion = trim($_POST['descripcion']);

        if (!empty($nombre) && !empty($precio)) {
            // AQUÍ ESTÁ EL CAMBIO: Apunta a la tabla "productos"
            $sql = "INSERT INTO productos (nombre, descripcion, precio) 
                    VALUES (:nombre, :descripcion, :precio)";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'      => $nombre,
                ':descripcion' => $descripcion,
                ':precio'      => $precio
            ]);

            $mensaje = "<div class='alerta exito'>¡Producto registrado con éxito en la tabla 'productos'!</div>";
        } else {
            $mensaje = "<div class='alerta error'>Por favor completa los campos obligatorios.</div>";
        }
    }
} catch (PDOException $e) {
    $mensaje = "<div class='alerta error'>Error de conexión/registro: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .container { max-width: 550px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #34495e; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        textarea { resize: vertical; height: 80px; }
        button { width: 100%; background-color: #2980b9; color: white; padding: 12px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #1f6391; }
        .alerta { padding: 12px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
        .exito { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <h2>Registro en Tabla 'productos'</h2>
    
    <?php echo $mensaje; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Producto *</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. Laptop o Café Mocha" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio ($) *</label>
            <input type="number" step="0.01" id="precio" name="precio" placeholder="0.00" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Detalles del producto..."></textarea>
        </div>

        <button type="submit">Guardar en Base de Datos</button>
    </form>
</div>

</body>
</html>