<?php
	
	$server_path='/home/users/a/antikhoof/domains/';
	$root_folder='C:\aibolit-for-windows\site/';
		
	if (!count($_SESSION['links']))
	{
		
		include './simple_html_dom.php';
		$data = file_get_contents('./AI-BOLIT-REPORT.html');
		
		$html = new simple_html_dom();
		$html->load($data, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false); 
		
		
		if($html->innertext!='' and count($html->find('#table_crit a.it'))){
			foreach($html->find('#table_crit a.it') as $a){
				$links[] = str_replace($root_folder,$server_path,$a->plaintext);    
			}
		}
		$_SESSION['links'] = $links;
	}
	
	
	
	if (!isset($_GET['file_num'])) $file_num=1;
	else $file_num = $_GET['file_num']+1;	
	
	if ($_POST['del']) 
	{
		
		if (!unlink($_SESSION['links'][$file_num-2])) echo 'Удаление не произошло!'; 
		$_SESSION['del'][] = $_SESSION['links'][$file_num-2];
	}
	if ($_POST['save']) 
	{
		file_put_contents($_SESSION['links'][$file_num-2], $_POST['fi']);
		$_SESSION['edit'][] = $_SESSION['links'][$file_num-2];
	}
	if ($_POST['next']) 
	{		
		$_SESSION['skip'][] = $_SESSION['links'][$file_num-2];
	}
	
	
	if (count($_SESSION['links'])>$file_num)
	{
	
	
	echo '<h1>Редактирование зараженных файлов</h1><p>Текущий файл: <b>'.$_SESSION['links'][$file_num-1].'</b> ('.$file_num.'/'.count($_SESSION['links']).')</p>';
	echo '<p>Удалено файлов - '.count($_SESSION['del']).'. Измененно файлов - '.count($_SESSION['edit']).'. Пропушенно файлов - '.count($_SESSION['skip']).'.</p>';
	$data = @file_get_contents($_SESSION['links'][$file_num-1]);
	echo '<form method="post" action="http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?file_num='.$file_num.'"><textarea name="fi" style="width:100%; height:350px;">'.$data.'</textarea>
	<div style="text-align:center;"><input type="submit" value="Сохранить" name="save" style="width:30%;margin-right:1%;">
	<input type="submit" value="Удалить" name="del" style="width:30%;margin-right:1%;">
	<input type="submit" value="Далее" name="next" style="width:30%;"></div>
	</form>';
	}
	else
	{
		echo '<p>Удалены файлы</p>';
		echo '<pre>';
		print_r($_SESSION['del']);
		echo '</pre>';
		
		echo '<p>Измененны файлы - </p>';
		echo '<pre>';
		print_r($_SESSION['edit']);
		echo '</pre>';
		
		echo '<p>Пропушенны файлы</p>';
		
		echo '<pre>';
		print_r($_SESSION['skip']);
		echo '</pre>';
		
	}	