<?php

$params = $_GET;
unset($params);

$params = array(
	'name' => $_POST["name"], 
	'surname' => $_POST["surname"],
	'grad' => $_POST['grad'],
	'letter' => $_POST['letter']
);

$new_query_string = http_build_query($params);

$file = "./task";
$handle = fopen($file, "r");
$task = fread($handle, filesize($file));
fclose($handle);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Отправка программы</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="main.css">
        <script defer src="check.js"></script>
    </head>

    <body>
		<div class="main-panel">
			
			<div class="panel panel-success">
				<div class="panel-body head-text">
					Name: <?= $params['name']." ".$params['surname'] ?> <br>
					Class: <?=  $params['grad']." ".$params['grad'] ?>
				</div>
			</div>

	        <form name="myForm" action="upload.php<?= $new_query_string ?>" method="post" enctype="multipart/form-data">

				<p class="head-text">Ваша задача:<br><?= $task ?></p>

		        <div class="head-text code-panel">
		           	<font>Вставьте свой код:</font><br>
		            <textarea class="codearea" name="code" title="" autofocus id="code" rows=15></textarea>
		        </div>

		        <div>
		            <br><br><font id="txt2">Или выберите файл:</font> <br>
		            <input type="file" id="file" name="userfile">
		        </div>

			    <div class="send-div"> 
			    <input class="send-butt" class="btn btn-success" type="button" value="Отправить" id="send" onclick="validateAndSend()"> 
			    </div>
	        
	        </form>
		</div>
    </body>
</html>
