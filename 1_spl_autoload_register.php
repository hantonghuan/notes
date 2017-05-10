<?php

class SPLtest
{
  static public function init() {
    $obj = new SC;
  }

  static public function load($class) {
    if ($class == 'School') {
      // includ 类 School所在的文件
      echo "School\n";
    }
    else {
      // include 其他类所在的文件
      echo "Other\n";
    }
    exit;
  }
}

// echo "Hello\n";
// 将函数注册到SPL __autoload函数队列中, 找不到类时依次调用
spl_autoload_register(array('SPLtest', 'load'));


// new School;
new L;