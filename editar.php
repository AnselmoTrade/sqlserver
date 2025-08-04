<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.html");
    exit();
}

require 'conexion.php';

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $archivoNuevo = $_FILES['archivo'];

    // Obtener archivo actual
    $stmt = $conn->prepare("SELECT archivo FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $doc = $resultado->fetch_assoc();
    $archivoActual = $doc['archivo'];

    // Si se subió nuevo archivo, reemplazarlo
    if ($archivoNuevo['error'] === 0) {
        $nuevoNombreArchivo = basename($archivoNuevo['name']);
        $rutaNueva = 'documentos/' . $nuevoNombreArchivo;

        // Mover archivo nuevo
        if (move_uploaded_file($archivoNuevo['tmp_name'], $rutaNueva)) {
            // Borrar el anterior
            $rutaAnterior = 'documentos/' . $archivoActual;
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            // Actualizar BD con nuevo nombre y archivo
            $stmt = $conn->prepare("UPDATE documentos SET nombre = ?, archivo = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre, $nuevoNombreArchivo, $id);
            $stmt->execute();
        } else {
            echo "❌ Error al subir el nuevo archivo.";
            exit();
        }
    } else {
        // Solo actualizar el nombre
        $stmt = $conn->prepare("UPDATE documentos SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
    }

    header("Location: admin.php");
    exit();
}

// Si se accede con GET, mostrar formulario de edición
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $doc = $resultado->fetch_assoc();
    } else {
        echo "Documento no encontrado.";
        exit();
    }
} else {
    echo "ID no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Documento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f6f8;
        }

        form {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px;
        }

        input[type="text"], input[type="file"], button {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #4DB6AC;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #35797F;
        }
    </style>
</head>
<body>

<h2>Editar Documento</h2>

<form action="editar.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
    <label>Nombre del documento:</label>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($doc['nombre']); ?>" required>

    <label>Archivo actual:</label>
    <p><?php echo $doc['archivo']; ?></p>

    <label>Reemplazar archivo (opcional):</label>
    <input type="file" name="archivo">

    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
