<?php

namespace Utils;

class Pagination
{
  public static function paginate(array $array, int $page = 1, int $perPage = 24): array
  {
    $offset = ($page - 1) * $perPage;
    return array_slice($array, $offset, $perPage);
  }
}