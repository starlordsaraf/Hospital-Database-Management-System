<!DOCTYPE html>
<?php
require('dbinfo.php');
?>
<html>
<head>
	<title>Star Hospital | Medicines</title>
	<link rel="stylesheet" type="text/css" href="register.css">
	<link rel="icon" type="image" href="logo.jpg">
</head>
<body background="tag2.jpg">
	<div class="main">&nbsp;&nbsp;&nbsp;STAR HOSPITAL
		<span>
			<a href="reg_pat.php" class="navi">Register Patient</a>&nbsp;|&nbsp;
			<a href="reg_emp.php" class="navi">Register Employee</a>&nbsp;|&nbsp;
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="tests.php" class="navi">Tests</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>

	<div align="center">
		<h1>Medicine Selection</h1>
		<form id="emp_form" method="POST">
			<label>
				Patient ID:
				<input type="text" name="pid">
			</label><br><br>
			
			<label>
				Medicine Name:
				<select name="med">
					<?php
						$query = "SELECT med_name FROM medicine;";
						$ret = pg_query($db, $query);
						$meds = pg_fetch_all_columns($ret);

						for ($i=0; $i < count($meds); $i++) { 
							$medname = $meds[$i];
							echo "<option>$medname</option>";
						}
					?>
				</select>
			</label><br><br>

			<label>
				Quantity:
				<input type="number" name="qty">
			</label><br><br>


			<label>
				Purchase Date:
				<input type="text" name="mdate" placeholder="mm-dd-yyyy">
			</label><br><br>

			<input type="submit" name="submit">
			
		</form>
	</div>	

	<?php
	if(isset($_POST['submit']))	
	{
		$pid="$_POST[pid]";
		$mdate="$_POST[mdate]";
		$medicine="$_POST[med]";
		$qty="$_POST[qty]";

		$check_query="select pid from patient";
		$check=pg_query($db,$check_query);
		$check_result=pg_fetch_all_columns($check);

		if(in_array("$pid", $check_result)===FALSE)
			echo "Enter Valid Patient ID!!";

		else
		{
			$medid_query="SELECT med_id FROM medicine WHERE med_name='$medicine'";
			$m=pg_query($db,$medid_query);
			$medid=pg_fetch_assoc($m);

			$query="INSERT INTO takes VALUES('$medid[med_id]','$pid','$mdate','$qty')";
			$result=pg_query($db,$query);

			echo "New Medicine Purchased";
		}

	}

	?>

</body>
</html>