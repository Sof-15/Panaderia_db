-- Base de datos para Panadería Bakery Dash

CREATE DATABASE IF NOT EXISTS panaderia_db;
USE panaderia_db;

-- Tabla de Clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion VARCHAR(200),
    compras_totales INT DEFAULT 0,
    gasto_total DECIMAL(10, 2) DEFAULT 0,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Inventario
CREATE TABLE IF NOT EXISTS inventario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 10,
    precio DECIMAL(10, 2) NOT NULL,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de Proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    especialidad VARCHAR(100),
    debito_acumulado DECIMAL(10, 2) DEFAULT 0,
    estado VARCHAR(20) DEFAULT 'Activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Órdenes de Compra
CREATE TABLE IF NOT EXISTS ordenes_compra (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_orden VARCHAR(50) UNIQUE NOT NULL,
    proveedor_id INT NOT NULL,
    productos TEXT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_orden TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATE,
    estado VARCHAR(20) DEFAULT 'Pendiente',
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE
);

-- Tabla de Ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente VARCHAR(100),
    monto_total DECIMAL(10, 2) NOT NULL,
    metodo_pago VARCHAR(50),
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'Completada'
);

-- Tabla de Detalle de Ventas
CREATE TABLE IF NOT EXISTS detalle_ventas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    venta_id INT NOT NULL,
    producto VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE
);

-- Insertar datos de ejemplo en Clientes
INSERT INTO clientes (nombre, telefono, email, direccion, compras_totales, gasto_total) VALUES
('Juan García López', '+34 612 345 678', 'juan.garcia@email.com', 'Calle Mayor, 15 - Apt 3A', 23, 12450),
('María Rodríguez Pérez', '+34 623 456 789', 'maria.rodriguez@email.com', 'Avenida Principal, 42', 18, 8920),
('Carlos Martínez Sánchez', '+34 634 567 890', 'carlos.martinez@email.com', 'Paseo del Parque, 7', 31, 15680),
('Ana Fernández Gómez', '+34 645 678 901', 'ana.fernandez@email.com', 'Plaza Central, 20', 15, 7350),
('Pedro Jiménez Torres', '+34 656 789 012', 'pedro.jimenez@email.com', 'Calle Comercial, 55', 42, 21120),
('Laura Díaz Ruiz', '+34 667 890 123', 'laura.diaz@email.com', 'Avenida Flores, 33', 19, 9640),
('Roberto Moreno Iglesias', '+34 678 901 234', 'roberto.moreno@email.com', 'Calle Nueva, 12', 27, 13890),
('Sofía López Cabrera', '+34 689 012 345', 'sofia.lopez@email.com', 'Plaza Mercado, 8', 11, 5720);

-- Insertar datos de ejemplo en Inventario
INSERT INTO inventario (codigo, nombre, categoria, stock, stock_minimo, precio) VALUES
('PAN-001', 'Pan de Queso', 'Panes', 35, 20, 2500),
('PAN-002', 'Pan Integral', 'Panes', 28, 15, 3200),
('CRO-001', 'Croissant', 'Repostería', 12, 20, 3500),
('DUL-001', 'Donut de Chocolate', 'Dulces', 45, 25, 1800),
('TOR-001', 'Torta Chocolate', 'Tortas', 8, 10, 5000),
('CUP-001', 'Cupcake Vainilla', 'Repostería', 52, 30, 2200),
('BUN-001', 'Buñuelo', 'Dulces', 5, 15, 1500),
('PAN-003', 'Pan de Ajo', 'Panes', 22, 15, 2800),
('Gall-001', 'Galletas Integrales', 'Galletas', 68, 40, 800),
('TOR-002', 'Torta Frutos Rojos', 'Tortas', 6, 8, 6000);

-- Insertar datos de ejemplo en Proveedores
INSERT INTO proveedores (nombre, contacto, telefono, email, especialidad, debito_acumulado, estado) VALUES
('Harinas Gourmet S.A.', 'Fernando González', '+34 912 345 678', 'ventas@harinaspremium.es', 'Harinas y Cereales', 12500, 'Activo'),
('Mantequilla Selecta Ibérica', 'Isabel Rodríguez', '+34 923 456 789', 'pedidos@mantequillaselecta.com', 'Productos Lácteos', 8750, 'Activo'),
('Frutas y Frutos Frescos', 'Carlos Mendoza', '+34 934 567 890', 'compras@frutasfrescas.es', 'Frutas Frescas', 5320, 'Activo'),
('Azúcares Puros España', 'Antonio Pérez', '+34 945 678 901', 'info@azucarespuros.com', 'Azúcares y Siropes', 6890, 'Activo'),
('Huevos Campesinos Premium', 'María García', '+34 956 789 012', 'ventas@huevoscampesinos.es', 'Huevos Frescos', 4150, 'Activo'),
('Chocolate Artesanal Europeo', 'Pierre Dubois', '+34 967 890 123', 'pedidos@chocolateartesanal.com', 'Chocolate Premium', 11200, 'Activo'),
('Levadura y Fermentos', 'Roberto López', '+34 978 901 234', 'info@levadurafermentos.es', 'Levaduras y Aditivos', 3420, 'Activo'),
('Sal Gourmet Atlántica', 'Patricia Jiménez', '+34 989 012 345', 'compras@salgourmet.com', 'Sales Especiales', 2100, 'Activo'),
('Empaques Premium Eco', 'David Martínez', '+34 990 123 456', 'ventas@empaqueseco.es', 'Empaques y Bolsas', 7600, 'En Revisión'),
('Frutos Secos y Frutos Rojos', 'Elena Sánchez', '+34 991 234 567', 'pedidos@frutossecos.com', 'Frutos Secos', 3870, 'Activo');

-- Crear índices
CREATE INDEX idx_clientes_email ON clientes(email);
CREATE INDEX idx_inventario_codigo ON inventario(codigo);
CREATE INDEX idx_inventario_categoria ON inventario(categoria);
CREATE INDEX idx_proveedores_nombre ON proveedores(nombre);
CREATE INDEX idx_ventas_fecha ON ventas(fecha_hora);
CREATE INDEX idx_ventas_cliente ON ventas(cliente);
