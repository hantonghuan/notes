<?php 

ob_start();

// echo函数的输出将一直被保存在输出缓冲区中直到调用ob_end_flush() 
echo "Hello\n";

// 对setcookie()的调用也成功存储了一个cookie, 而不会引起错误。 
//（正常情况下，在数据被发送到浏览器后，就不能再发送http头信息了）
setcookie("cookiename", "cookiedata");

ob_end_flush();




ob_start();
extract($this->assign);
include $template_dir;
$content = ob_get_clean();	//ob_get_contents() + ob_end_clean()
return $content;





//ob_start() 参数为回调函数，可以自定义回调函数统一处理要输出的内容
function callback($buffer)
{
	//buffer为要输出内容
	return 'hello';
}

ob_start('callback');
echo 'test';
ob_end_flush();

?>



