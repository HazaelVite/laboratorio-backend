<?php

class ResponseHTTP
{
  private static $response_data = [
    'status' => null,
    'message' => null
  ];

  private static $response = null;

  private function __construct()
  {
  }

  public static function getInstance(): ResponseHTTP
  {
    if (!isset(self::$response)) {
      self::$response = new ResponseHTTP();
    }

    return self::$response;
  }

  public function status200(string | array $response)
  {
    $default_message = 'Operación exitosa';
    return self::status(200, $response, $default_message);
  }

  public function status400(string | array $response)
  {
    $default_message = 'Error en la petición';
    return self::status(400, $response, $default_message);
  }

  public function status401(string | array $response)
  {
    $default_message = 'No tiene privilegios para acceder al recurso solicitado';
    return self::status(401, $response, $default_message);
  }

  public function status404(string | array $response)
  {
    $default_message = 'Recurso no encontrado';
    return self::status(404, $response, $default_message);
  }

  public function status500(string | array $response)
  {
    $default_message = 'Ha ocurrido un error en el servidor';
    return self::status(500, $response, $default_message);
  }

  public function status(int $status, string | array $response, string $default_message)
  {
    $type = gettype($response);

    http_response_code($status);
    self::$response_data['status'] = $status;

    if ($type === 'string') {
      self::$response_data['message'] = $response;

      return json_encode(self::$response_data);
    }

    self::$response_data['message'] = $response['message'] ?? $default_message;
    self::$response_data = array_merge(self::$response_data, $response ?? []);

    return json_encode(self::$response_data);
  }
}
