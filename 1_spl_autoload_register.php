<?php

class SPLtest
{
  static public function init() {
    $obj = new SC;
  }

  static public function load($class) {
    if ($class == 'School') {
      echo "School\n";
    }
    else {
      echo "Other\n";
    }
    exit;
  }
}

echo "Hello\n";
spl_autoload_register(array('SPLtest', 'load'));
