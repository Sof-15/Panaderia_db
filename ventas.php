<?php 
include('conexion.php'); 

// 1. Consultas para los indicadores superiores
$res_total = mysqli_query($conexion, "SELECT SUM(total) as gran_total FROM Ventas");
$gran_total = mysqli_fetch_assoc($res_total)['gran_total'] ?? 0;

$res_conteo = mysqli_query($conexion, "SELECT COUNT(*) as num_ventas FROM Ventas");
$num_ventas = mysqli_fetch_assoc($res_conteo)['num_ventas'] ?? 0;

$promedio = ($num_ventas > 0) ? ($gran_total / $num_ventas) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas | Bakery Dash</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos de la sección de bienvenida */
        .welcome-section {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%) !important;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            color: white;
        }

        /* Estilos de la tabla */
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
        .data-table th { background-color: #fcf3cf; color: #856404; }
        
        .method-tag {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            background: #eee;
            font-weight: bold;
        }

        /* ESTILOS DEL MODAL (VENTANA FLOTANTE) */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(3px);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 20px;
            width: 450px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close-btn:hover { color: #e74c3c; }

        .detalle-box {
            background: #fdfaf5;
            border: 1px solid #f3e5ab;
            padding: 20px;
            border-radius: 12px;
            margin-top: 15px;
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
            <li class="active"><a href="ventas.php">💰 Ventas</a></li>
            <li><a href="proveedores.php">🚚 Proveedores</a></li>
            <li><a href="clientes.php">👥 Clientes</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar factura...">
            </div>
            <div class="user-profile">
                <span>Admin Panadería</span>
                <div class="avatar">👤</div>
            </div>
        </header>

        <section class="welcome-section">
            <h1>Registro de Ventas</h1>
            <p>Monitorea los ingresos y facturación en tiempo real.</p>
        </section>

        <section class="stats-container">
            <div class="stat-card">
                <h3>Ingresos Totales</h3>
                <p class="stat-value">$<?php echo number_format($gran_total, 0, ',', '.'); ?></p>
            </div>
            <div class="stat-card">
                <h3>Nº Ventas</h3>
                <p class="stat-value"><?php echo $num_ventas; ?></p>
            </div>
            <div class="stat-card">
                <h3>Ticket Promedio</h3>
                <p class="stat-value">$<?php echo number_format($promedio, 0, ',', '.'); ?></p>
            </div>
        </section>

        <h2>Historial de Transacciones</h2>
        
        <section class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Método</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_v = "SELECT v.id_venta, v.fecha, c.nombre as cliente, v.metodo_pago, v.total 
                                FROM Ventas v 
                                JOIN Clientes c ON v.id_cliente = c.id_cliente 
                                ORDER BY v.id_venta DESC";
                    $res_v = mysqli_query($conexion, $query_v);

                    while($v = mysqli_fetch_assoc($res_v)) {
                        $total_formateado = number_format($v['total'], 0, ',', '.');
                    ?>
                    <tr>
                        <td><b>#<?php echo $v['id_venta']; ?></b></td>
                        <td><?php echo $v['fecha']; ?></td>
                        <td><?php echo $v['cliente']; ?></td>
                        <td><span class="method-tag"><?php echo $v['metodo_pago']; ?></span></td>
                        <td style="color: #27ae60; font-weight: bold;">$<?php echo $total_formateado; ?></td>
                        <td>
                            <button class="btn-edit" onclick="abrirModal('<?php echo $v['id_venta']; ?>', '<?php echo $v['fecha']; ?>', '<?php echo $v['cliente']; ?>', '<?php echo $total_formateado; ?>', '<?php echo $v['metodo_pago']; ?>')">
                                👁️ Ver Detalle
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="modalVenta" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="cerrarModal()">&times;</span>
            <h2 style="color: #d35400; margin-top: 0;">🥐 Comprobante de Venta</h2>
            <hr style="border: 0; border-top: 1px dashed #ccc;">
            
            <div id="infoDetalle" class="detalle-box">
                </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="btn-primary" style="flex: 1;" onclick="window.print()">🖨️ Imprimir</button>
                <button class="btn-secondary" style="flex: 1; background: #eee; border: none; cursor: pointer;" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        function abrirModal(id, fecha, cliente, total, metodo) {
            const box = document.getElementById('infoDetalle');
            box.innerHTML = `
                <p><strong>Número de Factura:</strong> FAC-00${id}</p>
                <p><strong>Fecha de Emisión:</strong> ${fecha}</p>
                <p><strong>Cliente:</strong> ${cliente}</p>
                <p><strong>Método de Pago:</strong> ${metodo}</p>
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #f3e5ab;">
                    <h3 style="margin: 0; color: #27ae60;">Total Pagado: $${total}</h3>
                </div>
            `;
            document.getElementById('modalVenta').style.display = "block";
        }

        function cerrarModal() {
            document.getElementById('modalVenta').style.display = "none";
        }

        // Cerrar si hace clic fuera del cuadro blanco
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalVenta')) {
                cerrarModal();
            }
        }
    </script>

</body>
</html>