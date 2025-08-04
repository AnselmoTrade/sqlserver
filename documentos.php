<?php
require 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Documentos Públicos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --color-principal: black;
      --color-secundario: #4DB6AC;
      --color-hover: #35797F;
      --color-fondo: #F4F6F8;
      --color-blanco: #FFFFFF;
      --color-texto: #000;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: var(--color-fondo);
      color: var(--color-texto);
    }

    .container {
      padding: 1rem 2rem;
    }

    h2 {
      font-size: 28px;
      color: black;
      margin-bottom: 1rem;
    }

    .documentos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .documento-card {
      background-color: var(--color-blanco);
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.05);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .documento-card h3 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 8px;
      color: var(--color-principal);
    }

    .documento-card p {
      font-size: 14px;
      margin-bottom: 10px;
      color: #444;
    }

    .btn-ver {
      display: inline-block;
      padding: 10px 15px;
      background-color: var(--color-secundario);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      text-align: center;
      transition: color 0.3s, transform 0.3s;
    }

    .btn-ver:hover {
      transform: translateY(-4px);
      background-color: black;
    }

    p a {
      display: inline-block;
      margin-bottom: 20px;
      text-decoration: none;
      color: var(--color-principal);
      font-size: 17px;
      transition: color 0.3s, transform 0.3s;
    }

    p a:hover {
      transform: translateY(-4px);
      color: var(--color-hover);
    }

    @media (max-width: 768px) {
      h2 {
        text-align: center;
        font-size: 22px;
      }

      .documentos-grid {
        justify-items: center;
      }

      .documento-card {
        width: 100%;
        max-width: 300px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <p><a href="index.html">Volver atrás</a></p>
    <h2>Documentos Disponibles</h2>
    
    <div class="documentos-grid">
      <?php
      $resultado = $conn->query("SELECT * FROM documentos");
      while ($row = $resultado->fetch_assoc()):
      ?>
      <div class="documento-card">
        <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
        <a class="btn-ver" href="documentos/<?php echo urlencode($row['archivo']); ?>" target="_blank">
          Ver documento
        </a>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
