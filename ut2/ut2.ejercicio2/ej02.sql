-- 1 (C) Insertar Auriculares y ver su id
INSERT INTO producto (nombre, precio) VALUES ('Auriculares', 39.95);
SELECT LAST_INSERT_ID();

-- 2 (R) Productos de más de 20 €, del más caro al más barato
SELECT nombre, precio FROM producto WHERE precio > 20 ORDER BY precio DESC;

-- 3 (R) Pedidos del cliente 1
SELECT COUNT(*) FROM pedido WHERE cliente_id = 1;

-- 4 (JOIN) Pedidos de ana@ejemplo.es sin usar su id
SELECT p.id, p.fecha
FROM pedido p
JOIN cliente c ON c.id = p.cliente_id
WHERE c.email = 'ana@ejemplo.es';

-- 5 (JOIN) Producto y cantidad del pedido 1
SELECT pr.nombre, lp.cantidad
FROM linea_pedido lp
JOIN producto pr ON pr.id = lp.producto_id
WHERE lp.pedido_id = 1;

-- 6 (agregación) Total de cada pedido
SELECT lp.pedido_id, SUM(lp.cantidad * pr.precio) AS total
FROM linea_pedido lp
JOIN producto pr ON pr.id = lp.producto_id
GROUP BY lp.pedido_id
ORDER BY lp.pedido_id;

-- 7 (U) Subir un 10 % los productos de menos de 20 €
-- Primero prueba la condición:
SELECT id, nombre, precio FROM producto WHERE precio < 20;
UPDATE producto SET precio = precio * 1.10 WHERE precio < 20;
SELECT nombre, precio FROM producto WHERE nombre = 'Ratón';

-- 8 (D) Borrar Auriculares
SELECT id, nombre FROM producto WHERE nombre = 'Auriculares';
DELETE FROM producto WHERE nombre = 'Auriculares';

-- 9 (D, a propósito) Debe FALLAR con el error 1451. Ejecútala SOLA (Ctrl+Intro)
DELETE FROM producto WHERE id = 1;

-- 10 (Transacción) Ejecuta estas líneas juntas, seleccionándolas y con Alt+X
SELECT id, nombre, precio FROM producto;
START TRANSACTION;
UPDATE producto SET precio = 0;
SELECT id, nombre, precio FROM producto;
ROLLBACK;
SELECT id, nombre, precio FROM producto;