<?php

enum ContentType
{
  case JSON;
  case FORM_DATA;
}

class RequestHTTP
{
  private static ContentType $content_type;
  private static $request = null;

  public static function getInstance(): RequestHTTP
  {
    if (!isset(self::$request)) {
      self::$request = new RequestHTTP();
    }

    return self::$request;
  }

  public static function setHeaders()
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

  public static function json_request()
  {
    header('Content-Type: application/json; charset: utf-8');

    self::$content_type = ContentType::JSON;
  }

  public static function  form_data_request()
  {
    header('Content-Type: multipart/form-data; charset: utf-8');

    self::$content_type = ContentType::FORM_DATA;
  }

  public function getRequest()
  {

    $request_data = null;

    if (self::$content_type === ContentType::FORM_DATA) {
      $method = $_SERVER['REQUEST_METHOD'];
      $request_data = $method === "GET" ? $_GET : $_POST;
    }

    if (self::$content_type === ContentType::JSON) {
      $request_data = file_get_contents('php://input');

      // if (!isset($request_data) || empty($request_data)) {
      // }

      $request_data = json_decode($request_data, true);
    }

    return $request_data;
  }
}
