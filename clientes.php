<?php 
include('conexion.php'); 

// 1. Consultas para los indicadores superiores
$res_total_clientes = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Clientes");
$total_clientes = mysqli_fetch_assoc($res_total_clientes)['total'] ?? 0;

// Consulta para ver quién es el cliente que más ha comprado (Top Customer)
$res_top = mysqli_query($conexion, "SELECT c.nombre, SUM(v.total) as gasto 
                                   FROM Clientes c 
                                   JOIN Ventas v ON c.id_cliente = v.id_cliente 
                                   GROUP BY c.id_cliente 
                                   ORDER BY gasto DESC LIMIT 1");
$top_cliente = mysqli_fetch_assoc($res_top);
$nombre_top = $top_cliente['nombre'] ?? "Sin ventas";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Bakery Dash</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%) !important;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            color: white;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .data-table th, .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .data-table th { background-color: #f3f0ff; color: #6c5ce7; }
        
        .email-link {
            color: #0984e3;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .email-link:hover { text-decoration: underline; }

        /* MODAL */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
        }
        .modal-content {
            background-color: #fff;
            margin: 12% auto;
            padding: 30px;
            border-radius: 20px;
            width: 400px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: bounce 0.4s ease;
        }
        @keyframes bounce {
            0% { transform: scale(0.8); }
            70% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">
            <h2>🥐 Bakery Dash</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">📊 Dashboard</a></li>
            <li><a href="inventario.php">📦 Inventario</a></li>
            <li><a href="ventas.php">💰 Ventas</a></li>
            <li><a href="proveedores.php">🚚 Proveedores</a></li>
            <li class="active"><a href="clientes.php">👥 Clientes</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar por nombre o correo...">
            </div>
            <div class="user-profile">
                <span>Admin Panadería</span>
                <div class="avatar">👤</div>
            </div>
        </header>

        <section class="welcome-section">
            <h1>Gestión de Clientes</h1>
            <p>Conoce a las personas que prefieren tus productos cada día.</p>
        </section>

        <section class="stats-container">
            <div class="stat-card">
                <h3>Total Registrados</h3>
                <p class="stat-value"><?php echo $total_clientes; ?></p>
            </div>
            <div class="stat-card">
                <h3>Cliente Estrella ⭐</h3>
                <p class="stat-value" style="font-size: 1.2rem;"><?php echo $nombre_top; ?></p>
            </div>
        </section>

        <h2>Base de Datos de Clientes</h2>
        
        <section class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_c = "SELECT * FROM Clientes";
                    $res_c = mysqli_query($conexion, $query_c);

                    while($c = mysqli_fetch_assoc($res_c)) {
                    ?>
                    <tr>
                        <td>#<?php echo $c['id_cliente']; ?></td>
                        <td><strong><?php echo $c['nombre']; ?></strong></td>
                        <td><a href="mailto:<?php echo $c['correo']; ?>" class="email-link">📧 <?php echo $c['correo']; ?></a></td>
                        <td><?php echo $c['telefono']; ?></td>
                        <td>
                            <button class="btn-edit" style="background: #6c5ce7; color: white;" onclick="perfilCliente('<?php echo $c['nombre']; ?>', '<?php echo $c['direccion']; ?>', '<?php echo $c['telefono']; ?>')">
                                📍 Ubicación
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="modalCliente" class="modal">
        <div class="modal-content">
            <div style="font-size: 50px; margin-bottom: 10px;">🏠</div>
            <h3 id="cli_nombre" style="margin: 0; color: #6c5ce7;"></h3>
            <p style="color: #666; margin-top: 15px;"><strong>Dirección de Entrega:</strong></p>
            <p id="cli_dir" style="font-style: italic; background: #f8f9fa; padding: 10px; border-radius: 8px;"></p>
            <p><strong>WhatsApp:</strong> <span id="cli_tel"></span></p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <button class="btn-primary" style="background: #6c5ce7; border: none; width: 100%;" onclick="cerrarCli()">Entendido</button>
        </div>
    </div>

    <script>
        function perfilCliente(nombre, direccion, tel) {
            document.getElementById('cli_nombre').innerText = nombre;
            document.getElementById('cli_dir').innerText = direccion;
            document.getElementById('cli_tel').innerText = tel;
            document.getElementById('modalCliente').style.display = "block";
        }
        function cerrarCli() {
            document.getElementById('modalCliente').style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalCliente')) {
                cerrarCli();
            }
        }
    </script>
</body>
</html>