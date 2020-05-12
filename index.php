<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
	
    <title>EU4 Power Calculator</title>
  </head>
  <body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<style>
	body 
	{
		padding:0px 10px;
	}
	a:link {
	  color: white;
	}
	a:visited {
	  color: white;
	}
	a:hover {
	  color: #DDD;
	}
	a:active {
	  color: white;
	}
	</style>
	<div style="margin: 20px 20px 20px 0px;">
		<div style="display:inline-block; margin: 0px 100px 0px 0px">
			<h1>EU4 Power Calculator</h1>
		</div>
		<a href="https://skanderbeg.pm/<?php if(isset($_GET['id'])) { echo "browse.php?id=".$_GET['id']; } ?>">
			<div style="display:inline-block; background:#444; padding:10px; border-radius:5px;">
				Powered by <img src="./sk_logo.png"/ height="20" width="20"> Skanderbeg
			</div>
		</a>
	</div>
    <?php
	require_once 'Country.php';
	require_once 'Save.php';
	$private_key = 'd7290e10b730afad0f2873e063dbf7f7';
	$sk_url = 'https://skanderbeg.pm/api.php';
	if(isset($_GET['id']))
	{
		include_once '_main.php';
	}
	else 
	{
		include_once '_noid.php';
	}

	?>
	
  </body>
</html>