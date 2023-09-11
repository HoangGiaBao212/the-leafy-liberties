<?php
interface Middleware
{
  public function execute(
    Request &$request,
    Response &$response,
    callable $next,
    $error = null
  );
}