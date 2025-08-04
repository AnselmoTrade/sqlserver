<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.html");
    exit();
}

require 'conexion.php';

// Verificamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $archivo = $_FILES['archivo'];

    // Verifica si no hubo error al subir
    if ($archivo['error'] === 0) {
        $nombreArchivo = basename($archivo['name']);
        $rutaDestino = 'documentos/' . $nombreArchivo;

        // Mover el archivo a la carpeta "documentos"
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Guardar en la base de datos
            $stmt = $conn->prepare("INSERT INTO documentos (nombre, archivo) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $nombreArchivo);
            $stmt->execute();

            header("Location: admin.php");
            exit();
        } else {
            echo "❌ Error al mover el archivo al servidor.";
        }
    } else {
        echo "❌ Error al subir el archivo.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
