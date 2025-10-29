<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CSV File Upload</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			padding: 20px;
			background-color: #f5f5f5;
		}

		.container {
			max-width: 600px;
			margin: 0 auto;
			background-color: #fff;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			border-radius: 8px;

		}

		h2 {
			text-align: center;
		}

		.upload-form {
			text-align: center;
		}

		.upload-form input[type="file"] {
			padding: 10px;
			margin: 10px 0;
		}

		.upload-form button {
			padding: 10px 20px;
			background-color: #28a745;
			color: white;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}

		.upload-form button:hover {
			background-color: #218838;
		}

		.file-info {
			margin-top: 20px;
		}

		.error {
			color: red;
		}
	</style>
</head>
<body >
<div style="padding-top: 10px">
	<div class="container">
		<h2>Upload MT5 Users</h2>
		<div style="text-align: center">
			<?php
			if (isset($_SESSION['import_data'])){
				if (!empty($_SESSION['import_data'])){
					echo "<span>".$_SESSION['import_trading_data'].$count."</span>";
				}
			}
			?>
		</div>


		<form class="upload-form" action="<?php echo base_url('csv/upload/users'); ?>" method="POST" enctype="multipart/form-data">
			<input type="file" name="csv_file" accept=".csv" required>
			<br>
			<button type="submit">Upload CSV</button>
		</form>
	</div>
</div>

<div style="padding-top: 10px">
	<div class="container">

		<h2>Upload MT5 Group Name</h2>
		<div style="text-align: center">
			<?php
			if (isset($_SESSION['import_trading_data'])){
				if (!empty($_SESSION['import_trading_data'])){
					echo "<span>".$_SESSION['import_trading_data'].'( '.$count.' )'."</span>";
				}
			}
			?>
		</div>

		<form class="upload-form" action="<?php echo base_url('csv/upload/group'); ?>" method="POST" enctype="multipart/form-data">
			<input type="file" name="csv_file" accept=".csv" required>
			<br>
			<button type="submit">Upload CSV</button>
		</form>
	</div>
</div>

<div style="padding-top: 10px">
	<div class="container">

		<h2>Upload Trading Account</h2>
		<div style="text-align: center">
			<?php
			if (isset($_SESSION['import_trading_data_insert'])){
				if (!empty($_SESSION['import_trading_data_insert'])){
					echo "<span>".$_SESSION['import_trading_data_insert'].'( '.$count.' )'."</span>";
				}
			}
			?>
		</div>

		<form class="upload-form" action="<?php echo base_url('csv/upload/trading'); ?>" method="POST" enctype="multipart/form-data">
			<input type="file" name="csv_file" accept=".csv" required>
			<br>
			<button type="submit">Upload CSV</button>
		</form>
	</div>
</div>

<?php
unset($_SESSION['import_data']);
unset($_SESSION['import_trading_data']);
unset($_SESSION['import_trading_data_insert']);
?>
</body>
<body style="padding-top: 100px">

</body>
</html>
