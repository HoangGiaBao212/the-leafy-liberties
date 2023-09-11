<?php
function generatePermutations($arr, &$result, $index)
{
  if ($index === count($arr)) {
    $result[] = $arr;
    return;
  }

  $copy1 = $arr;
  $copy1[$index] = strtolower($arr[$index]);
  generatePermutations($copy1, $result, $index + 1);

  $copy2 = $arr;
  generatePermutations($copy2, $result, $index + 1);
}
function lowercasePermutations($arr)
{
  $result = [];
  generatePermutations($arr, $result, 0);
  return $result;
}

function autoloader($class)
{
  $class = str_replace("\\", "/", $class);
  $namespace = explode("/", $class);
  $class = array_pop($namespace);
  $namespaces = lowercasePermutations($namespace);
  foreach ($namespaces as $item) {
    $path = implode("/", $item);
    $file = __DIR__ . "/" . $path . "/" . $class . ".php";
    if (file_exists($file)) {
      require_once $file;
      return;
    } else {
      $file = __DIR__ . "/" . $path . "/" . strtolower($class) . ".php";
      if (file_exists($file)) {
        require_once $file;
        return;
      }
    }
  }
}

spl_autoload_register("autoloader");
