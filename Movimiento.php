<?php

require_once './connection.php';

class Movimiento
{
  private string $id;
  private string $nombre;
  private string $descripcion;
  private string $status;
  private string $fecha;

  public function __construct(string $nombre, string $descripcion, string $status, string $id = null, string $fecha = null)
  {
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
    $this->status = $status;

    if (isset($id)) {
      $this->id = $id;
    }

    if (isset($fecha)) {
      $this->fecha = $fecha;
    }
  }

  public function __get(string $name)
  {
    return $this->{$name};
  }

  public function __set(string $name, string $value)
  {
    $this->{$name} = $value;
  }

  public static function save(Movimiento $movimiento)
  {
    try {
      $connection = Connection::connect();
      $statement = $connection->prepare('INSERT INTO movimientos (nombre, descripcion, status) VALUES (:nombre, :descripcion, :status)');

      $movimiento_data = [
        ':nombre' => $movimiento->__get('nombre'),
        ':descripcion' => $movimiento->__get('descripcion'),
        ':status' => $movimiento->__get('status')
      ];

      $statement->execute($movimiento_data);

      $generated_id = $connection->lastInsertId();

      return $generated_id;
    } catch (Exception $exception) {
      die($exception);
    }
  }
}
