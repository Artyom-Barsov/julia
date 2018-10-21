<?php 

$uploaddir = "./codes/";

$inputfile = "input";
$outputfile = "./output";

$uploadfile = $_GET["name"] . "_" . $_GET["surname"] . "_" . $_GET["grad"] . "_" . $_GET["letter"] . "_";

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir.$uploadfile))
	$uploaded = true;
else
	$uploaded = false;

$handle = fopen($uploadfile, "w");
$handleinp = fopen($inputfile, "r");

fread($handleinp, filesize($inputfile));
fwrite($handle, $_POST["code"]);

fclose($handleinp);
fclose($handle);

$run = "bash /var/www/html/compile_python.sh ".$uploaddir." ".$uploadfile;
exec($run);

$response = $uploaddir . $uploadfile . ".response";

if (file_exists($response)) {
   $handle = fopen($response, "r");
   
   $resp = fread($handle, filesize($response));
   
   $explode = explode('array=', $resp);
   $newexplode = explode('=end', $explode[1]);
   $explode = str_replace(",}", "}", $newexplode[0]);
   
   fclose($handle);
   fclose($response);

   $jsonresp = json_decode($explode, true);
   $count = count($jsonresp["status"]);
}

?>

<html>
    <head>
		<title>Ответ</title>
		<link rel="stylesheet" href="css/bootstrap.min.css"></style>
		<link rel="stylesheet" href="main.css"></style>
		<script src="var/www/html/js/bootstrap.min.js"></script>
    </head>

    <body>
		<div class="panel panel-default" class="main-panel">
			<div class="panel-body">
				<?php if($uploaded) : ?>
		        	<div class="alert alert-success" role="alert">Файл был успешно загружен</div>
		    	<?php else : ?>
					<div class="alert alert-danger" role="alert">Ошибка при загрузке файла</div> 
				<?php endif; ?>
			  
				<div class="panel panel-default">
			  		<!-- Table -->
			  		<table class="table">
		    			<tr>
							<td>Тест №</td>
							<td>Ввод</td>
							<td>Вывод</td>
							<td>Ответ</td>
							<td>Статус</td>
		    			</tr>

						<?php for($i = 1; $i <= $count; $i++) : ?>
			     			<tr>
				    			<td><?= $i ?></td>
				    			<td><?= $jsonresp["input"][$i] ?></td>
				    			<td><?= $jsonresp["program_output"][$i] ?></td>
				    			<td><?= $jsonresp["output"][$i] ?></td>
				    			<td><?= $jsonresp["status"][$i] ?></td>
							</tr>
						<?php endfor; ?>
					</table>
				   	<form action="login.html"><input class="btn btn-info" type="submit" value="again"></form>
				</div>
			</div>
		</div>
	</body>
</html>
