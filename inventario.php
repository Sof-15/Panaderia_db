<?php 
// 1. Incluimos la conexión (Asegúrate de que conexion.php existe en la misma carpeta)
include('conexion.php'); 

// 2. Consultas para las estadísticas (Basadas en tus tablas SQL)
$res_total_prod = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos");
$total_productos = mysqli_fetch_assoc($res_total_prod)['total'];

$res_stock_total = mysqli_query($conexion, "SELECT SUM(cantidad_disponible) as total FROM Inventario");
$stock_total = mysqli_fetch_assoc($res_stock_total)['total'];

$res_valor = mysqli_query($conexion, "SELECT SUM(p.precio * i.cantidad_disponible) as valor_total 
                                     FROM Productos p 
                                     JOIN Inventario i ON p.id_producto = i.id_producto");
$valor_inventario = mysqli_fetch_assoc($res_valor)['valor_total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Bakery Dash</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos rápidos para asegurar que se vea bien */
        .welcome-section {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%) !important;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            color: white;
        }
        .status-good { background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 10px; font-size: 0.8rem; }
        .status-warning { background: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 10px; font-size: 0.8rem; }
        .status-danger { background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 10px; font-size: 0.8rem; }
        
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
        .data-table th { background-color: #f8f9fa; color: #333; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">
            <h2>🥐 Bakery Dash</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">📊 Dashboard</a></li>
            <li class="active"><a href="inventario.php">📦 Inventario</a></li>
            <li><a href="ventas.php">💰 Ventas</a></li>
            <li><a href="proveedores.php">🚚 Proveedores</a></li>
            <li><a href="clientes.php">👥 Clientes</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar en bodega...">
            </div>
            <div class="user-profile">
                <span>Admin Panadería</span>
                <div class="avatar">👤</div>
            </div>
        </header>

        <section class="welcome-section">
            <h1>Gestión de Inventario Real</h1>
            <p>Datos obtenidos directamente de la base de datos <b>panaderia_db</b>.</p>
        </section>

        <section class="stats-container">
            <div class="stat-card">
                <h3>Productos Registrados</h3>
                <p class="stat-value"><?php echo $total_productos; ?></p>
            </div>
            <div class="stat-card">
                <h3>Unidades en Stock</h3>
                <p class="stat-value"><?php echo $stock_total; ?></p>
            </div>
            <div class="stat-card">
                <h3>Valorización</h3>
                <p class="stat-value">$<?php echo number_format($valor_inventario, 0, ',', '.'); ?></p>
            </div>
        </section>

        <h2>Listado de Existencias</h2>
        
        <section class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta que une las tablas para mostrar nombre y stock al mismo tiempo
                    $query_inventario = "SELECT p.id_producto, p.nombre, p.categoria, p.precio, i.cantidad_disponible 
                                         FROM Productos p 
                                         INNER JOIN Inventario i ON p.id_producto = i.id_producto";
                    
                    $ejecutar = mysqli_query($conexion, $query_inventario);

                    while($fila = mysqli_fetch_assoc($ejecutar)) {
                        $cantidad = $fila['cantidad_disponible'];
                        $subtotal = $fila['precio'] * $cantidad;

                        // Semáforo de stock
                        if($cantidad >= 40) {
                            $clase = "status-good"; $texto = "Suficiente";
                        } elseif($cantidad >= 20) {
                            $clase = "status-warning"; $texto = "Bajo";
                        } else {
                            $clase = "status-danger"; $texto = "Crítico";
                        }
                    ?>
                    <tr>
                        <td><b>PROD-<?php echo $fila['id_producto']; ?></b></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['categoria']; ?></td>
                        <td><?php echo $cantidad; ?> unidades</td>
                        <td>$<?php echo number_format($fila['precio'], 0, ',', '.'); ?></td>
                        <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        <td><span class="<?php echo $clase; ?>"><?php echo $texto; ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>