<?php

require_once './connection.php';

class Movimiento
{
  private string $id;
  private string $asunto;
  private string $descripcion;
  private string $status;
  private string $fecha;

  public function __construct(string $asunto, string $descripcion, string $status, string $id = null, string $fecha = null)
  {
    $this->asunto = $asunto;
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

  public function to_array()
  {
    $movimiento_array = [
      'asunto' => $this->asunto,
      'descripcion' => $this->descripcion,
      'status' => $this->status,
    ];

    if (isset($this->id)) $movimiento_array['id'] = $this->id;
    if (isset($this->fecha)) $movimiento_array['fecha'] = $this->fecha;

    return $movimiento_array;
  }


  public static function array_to_movimiento(array $movimiento_data, bool $completo = false)
  {
    $complete_keys = ['id', 'fecha'];
    $necesary_keys = ['asunto', 'descripcion', 'status'];
    global $g_movimiento_data;
    $g_movimiento_data = $movimiento_data;

    $isValid = array_every($completo ? array_merge($necesary_keys, $complete_keys) : $necesary_keys, function ($property) {
      return in_array($property, array_keys($GLOBALS['g_movimiento_data']));
    });

    if (!$isValid) {
      throw new MovimientoException('No se puede creear el movimiento con datos faltantes');
      return;
    }

    $movimiento = new Movimiento($movimiento_data['asunto'], $movimiento_data['descripcion'], $movimiento_data['status']);

    return $movimiento;
  }
}


/**
 * Takes one array with keys and another with values and combines them
 *
 * @template T
 * 
 * @param array<array-key, T> $array
 * @param callable(T $value): bool $condition
 */
function array_every(array $array, callable $condition)
{
  foreach ($array as $item) {
    if (!$condition($item)) return false;
  }

  return true;
}


class MovimientoException extends Exception
{
  public function __construct($message, $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
