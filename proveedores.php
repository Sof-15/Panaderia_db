<?php 
include('conexion.php'); 

// 1. Consultas para los indicadores superiores
$res_total_prov = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Proveedores");
$total_proveedores = mysqli_fetch_assoc($res_total_prov)['total'] ?? 0;

// Consultamos cuántas empresas distintas nos surten
$res_empresas = mysqli_query($conexion, "SELECT COUNT(DISTINCT empresa) as total_emp FROM Proveedores");
$total_empresas = mysqli_fetch_assoc($res_empresas)['total_emp'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores | Bakery Dash</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%) !important;
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
        .data-table th { background-color: #e8f8f5; color: #16a085; }
        
        .empresa-tag {
            background: #e1f5fe;
            color: #0288d1;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        /* MODAL */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 25px;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
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
            <li class="active"><a href="proveedores.php">🚚 Proveedores</a></li>
            <li><a href="clientes.php">👥 Clientes</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar proveedor o empresa...">
            </div>
            <div class="user-profile">
                <span>Admin Panadería</span>
                <div class="avatar">👤</div>
            </div>
        </header>

        <section class="welcome-section">
            <h1>Directorio de Proveedores</h1>
            <p>Gestiona los contactos de las empresas que surten tu negocio.</p>
        </section>

        <section class="stats-container">
            <div class="stat-card">
                <h3>Contactos</h3>
                <p class="stat-value"><?php echo $total_proveedores; ?></p>
            </div>
            <div class="stat-card">
                <h3>Empresas Aliadas</h3>
                <p class="stat-value"><?php echo $total_empresas; ?></p>
            </div>
        </section>

        <h2>Lista de Contactos</h2>
        
        <section class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Contacto</th>
                        <th>Empresa</th>
                        <th>Teléfono</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_p = "SELECT * FROM Proveedores";
                    $res_p = mysqli_query($conexion, $query_p);

                    while($p = mysqli_fetch_assoc($res_p)) {
                    ?>
                    <tr>
                        <td>#<?php echo $p['id_proveedor']; ?></td>
                        <td><strong><?php echo $p['nombre']; ?></strong></td>
                        <td><span class="empresa-tag"><?php echo $p['empresa']; ?></span></td>
                        <td><?php echo $p['telefono']; ?></td>
                        <td>
                            <button class="btn-edit" onclick="verProveedor('<?php echo $p['nombre']; ?>', '<?php echo $p['empresa']; ?>', '<?php echo $p['telefono']; ?>', '<?php echo $p['direccion']; ?>')">
                                📞 Contacto
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="modalProv" class="modal">
        <div class="modal-content">
            <h3 id="m_nombre" style="margin-top:0; color:#16a085;"></h3>
            <p><strong>Empresa:</strong> <span id="m_empresa"></span></p>
            <p><strong>Teléfono:</strong> <span id="m_tel"></span></p>
            <p><strong>Dirección:</strong> <span id="m_dir"></span></p>
            <hr>
            <button class="btn-primary" style="width:100%; background:#16a085; border:none;" onclick="cerrar()">Cerrar</button>
        </div>
    </div>

    <script>
        function verProveedor(nombre, empresa, tel, dir) {
            document.getElementById('m_nombre').innerText = nombre;
            document.getElementById('m_empresa').innerText = empresa;
            document.getElementById('m_tel').innerText = tel;
            document.getElementById('m_dir').innerText = dir;
            document.getElementById('modalProv').style.display = "block";
        }
        function cerrar() {
            document.getElementById('modalProv').style.display = "none";
        }
    </script>
</body>
</html>