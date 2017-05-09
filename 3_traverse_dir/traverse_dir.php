<?php

$path = '/vagrant_data/www/notes';
$files = [];

getfile_glob($path);
getfile_scandir($path);
getfile_opendir($path);
getfile_dir($path);

print_r($files);


function getfile_glob($path) {
	//查找指定类型文件:
	//glob($path.'/*.txt');
	//glob($path.'/{*.txt,*.jpg,*.zip,...}', GLOB_BRACE);
	//只查找当前目录下的文件, 不包含文件夹:
	//glob($path.'/*.*');
	foreach (glob($path.'/*') as $file) {
		if (is_dir($file)) {
			getfile_glob($file);
		}
		else {
			global $files;
			$files['glob'][] = $file;
		}
	}
}

function getfile_scandir($path) {
    foreach (scandir($path) as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (is_dir($path.'/'.$file)) {
			getfile_scandir($path.'/'.$file);
		}
		else {
			global $files;
			$files['scandir'][] = $path.'/'.$file;
		}
	}
}

function getfile_opendir($path) {
	$handle = opendir($path.'/');
	while (false !== ($file = readdir($handle))) {
		if ($file == '.' || $file == '..') {
			continue;
		}
		if (is_dir($path.'/'.$file)) {
			getfile_opendir($path.'/'.$file);
		}
		else {
			global $files;
			$files['opendir'][] = $path.'/'.$file;
		}
	}
	closedir($handle);
}

function getfile_dir($path) {
	$dir = dir($path);
	while (false !== ($file = $dir->read())){
		if ($file == '.' || $file == '..') {
			continue;
		}
		if (is_dir($path.'/'.$file)) {
			getfile_dir($path.'/'.$file);
		}
		else {
			global $files;
			$files['dir'][] = $path.'/'.$file;
		}
	}
	$dir->close();
}
