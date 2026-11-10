<?php
// 1. CONFIGURACIÓN DE LA CONEXIÓN A LA BASE DE DATOS
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "cafeteria507"; 

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión con la base de datos: " . $e->getMessage());
}

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    if (!empty($nombre) && !empty($precio)) {
        $sql = "INSERT INTO productos (nombre, precio) VALUES (:nombre, :precio)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
if (isset($_GET['eliminar_id'])) {
    $id_eliminar = $_GET['eliminar_id'];

    $sql = "DELETE FROM productos WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_eliminar);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM productos");
$stmt->execute();
$lista_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cafetería - Control de Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; color: #333; }
        .contenedor { max-width: 700px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { color: #4e342e; border-bottom: 2px solid #d7ccc8; padding-bottom: 8px; }
        label { font-weight: bold; display: block; margin-top: 12px; }
        input[type="text"], input[type="number"] { width: 96%; padding: 10px; margin: 6px 0 14px 0; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        input[type="submit"] { background-color: #6d4c41; color: white; border: none; padding: 12px 20px; cursor: pointer; border-radius: 4px; font-size: 15px; font-weight: bold; width: 100%; }
        input[type="submit"]:hover { background-color: #4e342e; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #e0e0e0; padding: 12px; text-align: left; }
        th { background-color: #d7ccc8; color: #4e342e; }
        tr:nth-child(even) { background-color: #fdfbfc; }
        .btn-eliminar { color: #e53935; text-decoration: none; font-weight: bold; }
        .btn-eliminar:hover { color: #b71c1c; text-decoration: underline; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>☕ Registro de Productos - Cafetería</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label>Nombre del Producto:</label>
        <input type="text" name="nombre" placeholder="Ej. Capuchino Grande" required>

        <label>Precio ($):</label>
        <input type="number" step="0.01" name="precio" placeholder="Ej. 45.50" required>

        <input type="submit" name="registrar" value="Agregar al Menú">
    </form>

    <h2>📋 Menú de la Cafetería</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_productos as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td>$<?php echo htmlspecialchars($producto['precio']); ?></td>
                    <td>
                        <a class="btn-eliminar" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?eliminar_id=<?php echo $producto['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            
            <?php if (empty($lista_productos)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #777;">No hay productos en el menú actualmente.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
