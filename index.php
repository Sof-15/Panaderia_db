<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Dash | Sistema de Gestión</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%) !important;
            padding: 40px !important;
            border-radius: 15px !important;
            margin-bottom: 40px !important;
        }
        .welcome-section h1 {
            color: white !important;
            font-size: 3rem !important;
            font-weight: 700 !important;
            margin: 0 0 10px 0 !important;
        }
        .welcome-section p {
            color: rgba(255,255,255,0.9) !important;
            font-size: 1.2rem !important;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">
            <h2>🥐 Bakery Dash</h2>
        </div>
        <ul class="nav-menu">
            <li class="active"><a href="index.php">📊 Dashboard</a></li>
            <li><a href="inventario.php">📦 Inventario</a></li>
            <li><a href="ventas.php">💰 Ventas</a></li>
            <li><a href="proveedores.php">🚚 Proveedores</a></li>
            <li><a href="clientes.php">👥 Clientes</a></li>
            <li><a href="#">⚙️ Configuración</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar productos, ventas...">
            </div>
            <div class="user-profile">
                <span>Admin Panadería</span>
                <div class="avatar">👤</div>
            </div>
        </header>

        <section class="welcome-section">
            <h1>Panel General</h1>
            <p>Bienvenido al sistema de gestión de tu panadería.</p>
        </section>

        <section class="stats-container">
            <?php
            $query_ventas = mysqli_query($conexion, "SELECT SUM(total) as total_dia FROM Ventas WHERE fecha = CURDATE()");
            $dato_venta = mysqli_fetch_assoc($query_ventas);
            $total_hoy = $dato_venta['total_dia'] ?? 0;
            ?>
            <div class="stat-card">
                <h3>Ventas Hoy</h3>
                <p class="stat-value">$<?php echo number_format($total_hoy, 0, ',', '.'); ?></p>
                <span class="stat-trend">↑ 12%</span>
            </div>

            <div class="stat-card">
                <h3>Stock Total</h3>
                <?php
                $query_stock = mysqli_query($conexion, "SELECT SUM(cantidad_disponible) as total_stock FROM Inventario");
                $dato_stock = mysqli_fetch_assoc($query_stock);
                ?>
                <p class="stat-value"><?php echo $dato_stock['total_stock']; ?></p>
            </div>

            <div class="stat-card alert">
                <h3>Alertas Stock</h3>
                <p class="stat-value">3</p>
                <span class="stat-note">Revisar proveedores</span>
            </div>
        </section>

        <h2>Productos en Vitrina</h2>
        
        <section class="product-grid">
            <?php
            $query_productos = mysqli_query($conexion, "SELECT * FROM Productos");
            while($producto = mysqli_fetch_assoc($query_productos)) {
            ?>
            <div class="product-card">
                <div class="product-img">🍞</div> <div class="product-info">
                    <h4><?php echo $producto['nombre']; ?></h4>
                    <span class="price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                    <button class="btn-add">Añadir al Carrito</button>
                </div>
            </div>
            <?php } ?>
        </section>

    </main>

</body>
</html>