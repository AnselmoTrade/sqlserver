<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.html");
    exit();
}

require 'conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buscar el nombre del archivo
    $stmt = $conn->prepare("SELECT archivo FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $doc = $resultado->fetch_assoc();
        $archivo = 'documentos/' . $doc['archivo'];

        // Eliminar archivo del servidor si existe
        if (file_exists($archivo)) {
            unlink($archivo); // Elimina el archivo del servidor
        }

        // Eliminar registro de la base de datos
        $stmt = $conn->prepare("DELETE FROM documentos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: admin.php");
        exit();
    } else {
        echo "❌ Documento no encontrado.";
    }
} else {
    echo "❌ Parámetro inválido.";
}
?>
