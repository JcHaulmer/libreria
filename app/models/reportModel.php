<?php
    namespace app\models;

    class reportModel extends mainModel {

        protected function buscarProductosPorBodega($tipo, $inicio="", $registros="", $busqueda="") {
            if ($tipo == "Contar") {
                if ($busqueda != "") {
                    // Ajusta el conteo para reflejar el número de grupos en lugar de productos individuales
                    $consulta = "SELECT COUNT(*) FROM (
                                 SELECT DISTINCT b.Nombre, p.Tipo 
                                 FROM ProductoEnBodega peb 
                                 INNER JOIN Bodegas b ON peb.ID_Bodega = b.ID 
                                 INNER JOIN Productos p ON peb.ID_Producto = p.ID 
                                 WHERE (b.Nombre LIKE '%$busqueda%' OR p.Tipo LIKE '%$busqueda%')
                                 ) AS Subconsulta";
                } else {
                    // Ajusta el conteo sin búsqueda
                    $consulta = "SELECT COUNT(*) FROM (
                                 SELECT DISTINCT b.Nombre, p.Tipo 
                                 FROM ProductoEnBodega peb 
                                 INNER JOIN Bodegas b ON peb.ID_Bodega = b.ID 
                                 INNER JOIN Productos p ON peb.ID_Producto = p.ID 
                                 ) AS Subconsulta";
                }
            } elseif ($tipo == "Datos") {
                if ($busqueda != "") {
                    $consulta = "SELECT b.Nombre AS Bodega, p.Tipo AS TipoProducto, SUM(peb.Cantidad) AS CantidadProductos 
                                 FROM ProductoEnBodega peb 
                                 INNER JOIN Bodegas b ON peb.ID_Bodega = b.ID 
                                 INNER JOIN Productos p ON peb.ID_Producto = p.ID 
                                 WHERE (b.Nombre LIKE '%$busqueda%' OR p.Tipo LIKE '%$busqueda%')
                                 GROUP BY b.Nombre, p.Tipo 
                                 ORDER BY b.Nombre ASC 
                                 LIMIT $inicio, $registros";
                } else {
                    $consulta = "SELECT b.Nombre AS Bodega, p.Tipo AS TipoProducto, SUM(peb.Cantidad) AS CantidadProductos 
                                 FROM ProductoEnBodega peb 
                                 INNER JOIN Bodegas b ON peb.ID_Bodega = b.ID 
                                 INNER JOIN Productos p ON peb.ID_Producto = p.ID 
                                 GROUP BY b.Nombre, p.Tipo 
                                 ORDER BY b.Nombre ASC 
                                 LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }
    
        protected function buscarMovimientos($tipo, $inicio="", $registros="", $busqueda="") {
            if ($tipo == "Contar") {
                if ($busqueda != "") {
                    $consulta = "SELECT COUNT(DISTINCT m.ID) FROM Movimientos m 
                                 INNER JOIN Bodegas b1 ON m.ID_bodegaOrigen = b1.ID 
                                 INNER JOIN Bodegas b2 ON m.ID_bodegaDestino = b2.ID 
                                 INNER JOIN Usuarios u ON m.ID_Usuario = u.ID 
                                 WHERE (b1.Nombre LIKE '%$busqueda%' OR b2.Nombre LIKE '%$busqueda%' OR u.Nombre LIKE '%$busqueda%')";
                } else {
                    $consulta = "SELECT COUNT(DISTINCT m.ID) FROM Movimientos m";
                }
            } elseif ($tipo == "Datos") {
                if ($busqueda != "") {
                    $consulta = "SELECT m.Fecha, b1.Nombre AS BodegaOrigen, b2.Nombre AS BodegaDestino, u.Nombre AS Usuario, SUM(pm.Cantidad) AS CantidadProductos 
                                 FROM Movimientos m 
                                 INNER JOIN Bodegas b1 ON m.ID_bodegaOrigen = b1.ID 
                                 INNER JOIN Bodegas b2 ON m.ID_bodegaDestino = b2.ID 
                                 INNER JOIN Usuarios u ON m.ID_Usuario = u.ID 
                                 INNER JOIN ProductoEnMovimiento pm ON m.ID = pm.ID_Movimiento 
                                 WHERE (b1.Nombre LIKE '%$busqueda%' OR b2.Nombre LIKE '%$busqueda%' OR u.Nombre LIKE '%$busqueda%')
                                 GROUP BY m.Fecha, b1.Nombre, b2.Nombre, u.Nombre 
                                 ORDER BY m.Fecha ASC 
                                 LIMIT $inicio, $registros";
                } else {
                    $consulta = "SELECT m.Fecha, b1.Nombre AS BodegaOrigen, b2.Nombre AS BodegaDestino, u.Nombre AS Usuario, SUM(pm.Cantidad) AS CantidadProductos 
                                 FROM Movimientos m 
                                 INNER JOIN Bodegas b1 ON m.ID_bodegaOrigen = b1.ID 
                                 INNER JOIN Bodegas b2 ON m.ID_bodegaDestino = b2.ID 
                                 INNER JOIN Usuarios u ON m.ID_Usuario = u.ID 
                                 INNER JOIN ProductoEnMovimiento pm ON m.ID = pm.ID_Movimiento 
                                 GROUP BY m.Fecha, b1.Nombre, b2.Nombre, u.Nombre 
                                 ORDER BY m.Fecha ASC 
                                 LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

    }
    