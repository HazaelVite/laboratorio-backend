<?php

class Connection
{
  private static $host = 'sql155.main-hosting.eu';
  private static $user = 'u531053240_movimiento';
  private static $password = '+9rK:+eVyC4';
  private static $database_name = 'u531053240_movimiento';

  public static function connect()
  {
    try {
      $connection = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$database_name, self::$user, self::$password);

      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $connection->exec('set names utf8');

      return $connection;
    } catch (PDOException $exception) {
      die('Ha ocurrido un error al conectar con la base de datos' + $exception);
    } catch (Exception $exception) {
      die('Ha ocurrido un error' + $exception);
    }
  }
}
