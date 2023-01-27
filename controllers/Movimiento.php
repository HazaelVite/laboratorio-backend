<?php
require_once dirname(__FILE__, 2) . '/Response.php';
require_once dirname(__FILE__, 2) . '/Request.php';
require_once dirname(__FILE__, 2) . '/Connection.php';
require_once dirname(__FILE__, 2) . '/Movimiento.php';

class MovimientoControlller
{

  public static $connection = null;

  public function __construct()
  {
    if (isset(self::$connection)) return;
    MovimientoControlller::$connection = Connection::connect();
  }

  public function createMovimiento($request, ResponseHTTP $response)
  {

    try {
      $movimiento = Movimiento::array_to_movimiento($request);

      $sqlQuery = 'INSERT INTO movimiento (idAsunto, idStatus, descripcion) VALUES (id, status, descripcion)';

      $statement = self::$connection->prepare($sqlQuery);

      $statement->execute(
        $movimiento->to_array()
      );

      echo $response->status200(['generated_id' => self::$connection->lastInsertId()]);
    } catch (MovimientoException $exception) {
      die($response->status400($exception->getMessage()));
    } catch (MovimientoException $exception) {
      die($response->status500($exception->getMessage()));
    }
  }

  public function updateMovimiento($request, ResponseHTTP $response)
  {
  }

  public function deleteMovimiento($request, ResponseHTTP $response)
  {
  }

  public function getMovimiento($request, ResponseHTTP $response)
  {
    try {
      $movimiento_id = $request['id'] ?? null;

      if (!isset($movimiento_id) || empty($movimiento_id)) {
        die($response->status400(['message' => 'No se ha recibido el id del movimiento']));
      }

      $querySQL = 'SELECT * FROM movimiento WHERE idMovimiento = :id';

      $statement = MovimientoControlller::$connection->prepare($querySQL);
      $statement->execute([
        ':id' => $movimiento_id
      ]);

      $data = null;

      foreach ($statement as $row) {
        $data = $row;
      }

      if (!isset($data)) {
        die($response->status404("No se ha encontrado el movimiento con el id $movimiento_id"));
      }

      echo $response->status200([
        'data' => $data
      ]);
    } catch (Exception $exception) {
      die($response->status500($exception->getMessage()));
    }
  }

  public function getMovimientos($request, ResponseHTTP $response)
  {
    try {

      $querySQL = 'SELECT * FROM movimiento';

      $statement = MovimientoControlller::$connection->prepare($querySQL);

      $statement->execute();

      $data = [];

      foreach ($statement as $row) {
        $data[] = $row;
      }

      if (count($data) === 0) {
        die($response->status404('No se han encontrado movimientos'));
      }

      echo $response->status200([
        'data' => $statement,
        'method' => $_SERVER['REQUEST_METHOD']
      ]);
    } catch (Exception $exception) {
      die($response->status500($exception->getMessage()));
    }
  }
}
