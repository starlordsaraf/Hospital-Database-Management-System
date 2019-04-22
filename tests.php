<!DOCTYPE html>
<?php
require('dbinfo.php');
?>

<html>
<head>
	<title>Star Hospital | Tests</title>
	<link rel="stylesheet" type="text/css" href="register.css">
	<link rel="icon" type="image" href="logo.jpg">
</head>

<body background="tag2.jpg">
	<div class="main">&nbsp;&nbsp;&nbsp;STAR HOSPITAL
		<span>
			<a href="reg_pat.php" class="navi">Register Patient</a>&nbsp;|&nbsp;
			<a href="reg_emp.php" class="navi">Register Employee</a>&nbsp;|&nbsp;
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="medicines.php" class="navi">Medicines</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>
	<div align="center">
		<h1>Test Selection</h1>
		<form id="emp_form" method="POST">
			<label>
				Patient ID:
				<input type="text" name="pid">
			</label><br><br>
			
			<label>
				Test Name:
				<select name="test">
					<?php
						$query = "SELECT test_name FROM test;";
						$ret = pg_query($db, $query);
						$tests = pg_fetch_all_columns($ret);

						for ($i=0; $i < count($tests); $i++) { 
							$testname = $tests[$i];
							echo "<option>$testname</option>";
						}
					?>
				</select>
			</label><br><br>
			
			<label>
				Test Date:
				<input type="text" name="tdate" placeholder="mm-dd-yyyy">
			</label><br><br>

			<input type="submit" name="submit">
			
		</form>
	</div>	

	<?php
	if(isset($_POST['submit']))	
	{
		$pid="$_POST[pid]";
		$tdate="$_POST[tdate]";
		$test="$_POST[test]";

		$check_query="select pid from patient";
		$check=pg_query($db,$check_query);
		$check_result=pg_fetch_all_columns($check);

		if(in_array("$pid", $check_result)===FALSE)
			echo "Enter Valid Patient ID!!";

		else
		{
			$testid_query="SELECT test_id FROM test WHERE test_name='$test'";
			$t=pg_query($db,$testid_query);
			$testid=pg_fetch_assoc($t);

			$query="INSERT INTO has VALUES('$testid[test_id]','$pid','$tdate')";
			$result=pg_query($db,$query);

			echo "New Test Record Added";
		}

	}

	?>


</body>
</html>