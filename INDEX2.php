<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "cafeteria507"; 

$mensaje_alerta = ""; 

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Registrar cliente / mensaje
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $mensaje = $_POST['mensaje'] ?? '';

        if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
            $sql = "INSERT INTO clientes (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':nombre' => $nombre, ':correo' => $correo, ':mensaje' => $mensaje]);
            
            $mensaje_alerta = "¡Datos guardados con éxito!";
        }
    }

    // 2. Eliminar cliente por correo
    if (isset($_GET['eliminar_correo'])) {
        $correo_eliminar = $_GET['eliminar_correo'];

        $sql = "DELETE FROM clientes WHERE correo = :correo";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':correo' => $correo_eliminar]);

        $mensaje_alerta = "¡Registro eliminado con éxito!";
    }

    // 3. Buscar clientes por nombre en la tabla 'clientes'
    $busqueda = $_GET['busqueda'] ?? '';
    if (!empty($busqueda)) {
        $stmt = $conexion->prepare("SELECT * FROM clientes WHERE nombre LIKE :busqueda");
        $stmt->execute([':busqueda' => '%' . $busqueda . '%']);
    } else {
        $stmt = $conexion->prepare("SELECT * FROM clientes");
        $stmt->execute();
    }
    $lista_contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $mensaje_alerta = "Error: " . $e->getMessage();
    $lista_contactos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cafetería Escolar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        header, footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }

        .titulo {
            display: flex;
            align-items: center;
            width: max-content;
            margin: auto;
            gap: 10px;
        }

        .titulo img {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
        }

        .titulo h1 { margin: 0; }
        .titulo p { margin: 8px 0 0; }

        nav {
            background-color: #ddd;
            padding: 10px;
            text-align: center;
        }

        nav a {
            margin: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        section {
            background-color: white;
            padding: 20px;
            margin-top: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        table {
            width: 80%;
            margin: 15px auto;
            border-collapse: collapse;
        }

        th {
            background-color: #333;
            color: white;
            padding: 10px;
        }

        td {
            border: 1px solid #555;
            padding: 8px;
            text-align: center;
        }

        .buscador-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .buscador-container input {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .buscador-container button {
            width: auto;
            padding: 8px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .buscador-container button:hover {
            background-color: #555;
        }

        .btn-reset {
            color: #007bff;
            text-decoration: none;
            margin-left: 10px;
            font-weight: bold;
        }

        form.contacto-form {
            width: 350px;
            margin: auto;
            padding: 20px;
            background-color: #eee;
            border-radius: 10px;
        }

        input[type="text"], input[type="email"], textarea {
            width: 95%;
            padding: 8px;
            margin-top: 5px;
        }

        textarea { height: 80px; }

        button.btn-enviar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        button.btn-enviar:hover { background-color: #555; }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }
        .btn-eliminar:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <div class="titulo">
            <div>
                <img src="cafeteria.png" alt="Cafetería">
            </div>
            <div>
                <h1>Cafetería Escolar</h1>
                <p>Alimentos y bebidas para estudiantes</p>
            </div>
        </div>
        <nav>
            <a href="#menu">Menú</a> |
            <a href="#contacto">Contacto</a> |
            <a href="#admin">Administrar Clientes</a>
        </nav>
    </header>

    <main>
        <!-- SECCIÓN MENÚ -->
        <section id="menu">
            <h2>Menú</h2>
            <table>
                <tr><th>Producto</th><th>Precio</th></tr>
                <tr><td>Hamburguesa</td><td>$45</td></tr>
                <tr><td>Jugo</td><td>$20</td></tr>
                <tr><td>Sándwich</td><td>$30</td></tr>
            </table>
        </section>

        <!-- SECCIÓN CONTACTO -->
        <section id="contacto">
            <h2>Contacto</h2>
            <form class="contacto-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#contacto" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required><br><br>

                <label>Correo:</label>
                <input type="email" name="correo" required><br><br>

                <label>Mensaje:</label>
                <textarea name="mensaje" required></textarea><br><br>

                <button type="submit" name="registrar" class="btn-enviar">Enviar</button>
            </form>
        </section>

        <!-- SECCIÓN ADMINISTRAR CLIENTES CON BUSCADOR -->
        <section id="admin">
            <h2>Clientes Registrados (Base de Datos)</h2>
            
            <div class="buscador-container">
                <form method="GET" action="#admin" style="background: none; padding: 0; box-shadow: none; width: auto;">
                    <input type="text" name="busqueda" placeholder="Buscar cliente por nombre..." value="<?php echo htmlspecialchars($busqueda); ?>">
                    <button type="submit">Buscar</button>
                    <?php if (!empty($busqueda)): ?>
                        <a href="?" class="btn-reset">Ver todos</a>
                    <?php endif; ?>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Mensaje</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_contactos)): ?>
                        <?php foreach ($lista_contactos as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                                <td>
                                    <a class="btn-eliminar" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?eliminar_correo=<?php echo urlencode($row['correo']); ?>#admin" onclick="return confirm('¿Seguro que deseas eliminar el registro de este correo?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="color: #777;">No se encontraron registros en la tabla clientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>Cafetería Escolar © 2026</p>
    </footer>

    <script>
        <?php if (!empty($mensaje_alerta)): ?>
            alert("<?php echo $mensaje_alerta; ?>");
            window.location.href = "<?php echo strtok($_SERVER['PHP_SELF'], '?'); ?>#admin";
        <?php endif; ?>
    </script>
</body>
</html>