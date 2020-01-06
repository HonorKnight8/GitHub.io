

<!DOCTYPE HTML> 
<html>
<head>
	<style>
		.error {color: #FF0000;}
		<!--设置出错时的样式。-->
	</style>
	</head>
<body> 

<h2>MAC地址归属厂商查询</h2>
<h4>资料更新时间：2020-01-06</h4>

<?php
	$macinput= "";	//定义变量并设置为空值

	function test_input($data) {	//定义函数，对输入进行预处理(下面else语句引用该函数，对变量进行赋值)
		$data = trim($data);
		//trim() 函数，移除字符串两侧的空白字符或其他预定义字符。
		$data = stripslashes($data);
		//stripslashes() 函数，删除反斜杠：
		$data = htmlspecialchars($data);
		//htmlspecialchars() 函数，将特殊字符转换为 HTML 实体
			//& (& 符号) 	&amp;
			//" (双引号) 	&quot;，除非设置了 ENT_NOQUOTES
			//' (单引号) 	设置了 ENT_QUOTES 后， &#039; (如果是 ENT_HTML401) ，或者 &apos; (如果是 //ENT_XML1、 ENT_XHTML 或 ENT_HTML5)。
			//< (小于) 	&lt;
			//> (大于) 	&gt;
		return $data;
	}
	
/*	
	function test_convert($data) {	//定义函数，对输入的字符串去除空格、tab，冒号转半角
		$data = preg_replace('/\s+/', '', $data);	//去掉任意空格
		$data = preg_replace('/\n+/', '', $data);	//去掉任意空格
		$data = preg_replace('/\r+/', '', $data);	//去掉任意空格
		$data = preg_replace('/\t+/', '', $data);	//去掉任意空格

		$data = substr ( $data , 0 , 64 );
		//截取前64位
		// substr ( string $string , int $start [, int $length ] )
		//全角冒号的字符串长度为3，6*2+5*3=27，为防止有空格，翻倍
		
		$data = preg_replace('/：/', ':', $data);	//将全角“：”转换为半角“:”
		
		return $data;
	
	}
*/


	if ($_SERVER["REQUEST_METHOD"] == "POST") {	//对输入进行验证
		if (empty($_POST["macinput"])) {
			$macinputErr = "错误：MAC地址不能为空";
		}	//验证是否为空
		
		else if (preg_match('/[^a-f0-9\-\:]/i', $_POST["macinput"])){
			$macinputErr = "错误：输入的MAC地址只能包含：“0至9”、“A至F”、“-”、“:”（注意冒号为半角）";
		}	//验证输入字符串是否包含不应该在MAC地址中出现的字符
			
		else if ((strlen($_POST["macinput"]) !== 8) and (strlen($_POST["macinput"]) !== 17)){
			$macinputErr = "错误：输入的MAC地址，长度应等于8或17";
		}
		else {
			$macinput = test_input($_POST["macinput"]);	
			
			//$macinput = test_convert($_POST["macinput"]);
			$mac_head = preg_replace('/:/', '-', $macinput);
			$mac_head = substr ( $mac_head , 0 , 8 );

			$handler=fopen("oui.txt","r");
			while(!feof($handler)){
				$line = fgets($handler,204800); //fgets逐行读取，204800最大长度，默认为1024
				if(substr_count($line,$mac_head)>0){//查找字符串
					$result = $line; //打印结果
				}
			}
			fclose($handler); //关闭文件



/*	fault			
			//首先采用“fopen”函数打开文件，得到返回值的就是资源类型。
			$file_handle = fopen("/WZ/Demo/p1_MAC/oui.txt","r");
			if ($file_handle){
				//接着采用while循环（后面语言结构语句中的循环结构会详细介绍）一行行地读取文件，然后输出每行的文字
				while (!feof($file_handle)) { //判断是否到最后一行
					$line = fgets($file_handle); //读取一行文本
					if (preg_match($macinput, $line)){
						$result = $line; 	//将该行赋值给result变量
					}
				}
			}
			fclose($file_handle);//关闭文件		
*/		

		}
	}

	//用于调试
	//echo strlen($_POST["macinput"]);
	//var_dump($_POST["macinput"]);
	//echo "<br />";

?>


<font>请在下面的框中输入要查询的MAC地址，支持的输入格式有：<br />
1、A1-B2-C3-D4-E5-F6<br />
2、A1:B2:C3:D4:E5:F6<br />
也可以只输入前三个字节：<br />
3、A1-B2-C3<br />
4、A1:B2:C3<br /></font><br />


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
	<b>MAC地址输入：</b><input type="text" name="macinput">
	<span class="error">*</span><span>必填项</span>
	<br /><br />
	<input type="submit" name="submit" value="提交">
</form>

<span class="error"><?php echo $macinputErr;?></span><br />


<font>你输入的MAC地址是：</font>
<font style="background-color:Lavender"><?php echo $macinput; ?></font>
<br /><font>经查询，该MAC地址被分配给如下厂商：</font><br /> 
<!-- <font><b>谷歌</b></font>	<!--调试-->
<font style="background-color:Khaki;color:darkblue;font-size: 24px;"><?php echo $result; ?></font>







<!--

?>
	//用于调试
	var_dump($macinput);
	echo "<br />";
?>
-->

</body>
</html>