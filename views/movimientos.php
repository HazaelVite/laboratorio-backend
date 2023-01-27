<?php
require_once './connection.php';

try {
  $connection = Connection::connect();

  $statement = $connection->prepare('SELECT * FROM movimientos');
  $statement->execute();

  $movimientos = [];

  foreach ($statement as $row) {
    $movimientos[] = new Movimiento($row['nombre'], $row['descripcion'], $row['status'], $row['id']);
  }
} catch (Exception $exception) {
  die($exception);
}
