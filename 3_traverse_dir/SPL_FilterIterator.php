<?php

//遍历个某个目录抽取其中的图片文件

class RecursiveFileFilterIterator extends FilterIterator {
	protected $ext = ['jpg', 'gif'];

	public function __construct($path) {
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
