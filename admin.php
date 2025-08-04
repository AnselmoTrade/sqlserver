<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.html");
    exit();
}
require 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --color-principal: #1E2A38;
            --color-secundario: #4DB6AC;
            --color-hover: #35797F;
            --color-fondo: #F4F6F8;
            --color-blanco: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--color-fondo);
            padding: 2rem;
            color: var(--color-principal);
        }

        h2, h3 {
            color: var(--color-principal);
        }

        form, table {
            margin-top: 20px;
            background: var(--color-blanco);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.06);
        }

        input[type="file"],
        input[type="text"],
        button {
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: var(--color-secundario);
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--color-hover);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        a {
            color: var(--color-secundario);
            text-decoration: none;
        }

        a:hover {
            color: var(--color-hover);
        }

        .logout {
            margin-top: 20px;
            display: inline-block;
            color: red;
        }

        @media (max-width: 600px) {
            body {
                padding: 1rem;
            }

            table, form {
                font-size: 14px;
                padding: 15px;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<h2>Bienvenido, <?php echo $_SESSION['admin']; ?></h2>

<form action="subir.php" method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre del documento:</label>
    <input type="text" name="nombre" required>
    <input type="file" name="archivo" required>
    <button type="submit">Subir Documento</button>
</form>

<h3>Documentos subidos</h3>
<table>
    <tr>
        <th>Nombre</th>
        <th>Archivo</th>
        <th>Acciones</th>
    </tr>
    <?php
    $resultado = $conn->query("SELECT * FROM documentos");
    while ($row = $resultado->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
        <td><a href="documentos/<?php echo $row['archivo']; ?>" target="_blank">Ver</a></td>
        <td>
            <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a> |
            <a href="eliminar.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este archivo?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a class="logout" href="logout.php">Cerrar sesión</a>

</body>
</html>
