<?php

class ResponseHTTP
{

  private static $response = [
    'status' => null,
    'message' => null
  ];

  public static function headerHTTP()
  {
    $white_list = [];

    if ($_SERVER['HTTP_HOST'] === 'localhost') {
      $white_list[] = 'http://localhost';
    }

    $http_origin = $white_list[0];

    if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $white_list)) {
      $http_origin = $_SERVER['HTTP_ORIGIN'];
    }

    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Credentials: true");

    $is_options_request_method = $_SERVER['REQUEST_METHOD'] === 'OPTIONS';

    if ($is_options_request_method && isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

    if ($is_options_request_method && isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    if ($is_options_request_method) exit(0);
  }

  public static function status200(string | array $response)
  {
    $default_message = 'Operación exitosa';
    return self::status(200, $response, $default_message);
  }

  public static function status400(string | array $response)
  {
    $default_message = 'Error en la petición';
    return self::status(400, $response, $default_message);
  }

  public static function status401(string | array $response)
  {
    $default_message = 'No tiene privilegios para acceder al recurso solicitado';
    return self::status(401, $response, $default_message);
  }

  public static function status404(string | array $response)
  {
    $default_message = 'Recurso no encontrado';
    return self::status(404, $response, $default_message);
  }

  public static function status500(string | array $response)
  {
    $default_message = 'Ha ocurrido un error en el servidor';
    return self::status(500, $response, $default_message);
  }

  public static function status(int $status, string | array $response, string $default_message)
  {
    $type = gettype($response);

    http_response_code($status);
    self::$response['status'] = $status;

    if ($type === 'string') {
      self::$response['message'] = $response;

      return json_encode(self::$response);
    }

    self::$response['message'] = $response['message'] ?? $default_message;
    self::$response = array_merge(self::$response, $response ?? []);

    return json_encode(self::$response);
  }
}
