<?php 

$list = new SplDoublyLinkedList();
$list->push('a');
$list->push('b');
$list->push('c');
$list->push('d');

// $list->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);

// for ($list->rewind(); $list->valid(); $list->next()) { 
// 	echo $list->current() . "\n";
// }

// echo $list->top();
// echo $list->bottom();
// echo $list->pop();

$arr = ['a', 'b', 'c'];
$obj = new ArrayObject($arr);

$it = $obj->getIterator();

// foreach ($it as $key=>$val)
// 	echo $key.":".$val."\n";

// var_dump(iterator_to_array($obj, true));

// 遍历多层数组
$arr1 = [
    "a" => "hi",
    "b" => "ah ya, nice",
    "c" => "wow, I love it!"
];
$arr2 = [
	"d" => "chips",
	"e" => "soup"
];
$arr = array($arr1, $arr2);
$obj = new ArrayObject($arr);
$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));

foreach ($it as $key=>$val) {
	echo $key . " = " . $val . "\n";
}
