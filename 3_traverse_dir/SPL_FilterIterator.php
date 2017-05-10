<?php

//遍历个某个目录抽取其中的图片文件

//FilterIterator类可以对元素进行过滤，只要在accept()方法中设置过滤条件就可以了
class RecursiveFileFilterIterator extends FilterIterator {
	protected $ext = ['jpg', 'gif'];

	public function __construct($path) {
		//ArrayIterator类和ArrayObject类，只支持遍历一维数组。如果要遍历多维数组，必须先用Recursive(Iterator)Iterator生成一个Iterator，然后再对这个Iterator使用RecursiveIteratorIterator
		parent::__construct(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
	}

	public function accept() {
		$item = $this->getInnerIterator();
		if($item->isFile() &&
			in_array(pathinfo($item->getFilename(), PATHINFO_EXTENSION), $this->ext)) {
			return true;
		}
	}

}


foreach (new RecursiveFileFilterIterator('/vagrant_data/www/notes') as $item) {
	echo $item . PHP_EOL;
	//var_dump($item);
}
