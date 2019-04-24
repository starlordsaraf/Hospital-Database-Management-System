<!DOCTYPE html>
<?php require("dbinfo.php"); ?>
<html>
<head>
	<title>Medical History</title>
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
			<a href="tests.php" class="navi">Tests</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>

	<div align="center">
		<h1>Search Medical History</h1>
		<form id="emp_form" method="POST">
			<label>
				Patient Name:
				<input type="text" name="name">
			</label><br><br>
			<label>
			<input type="submit" name="submit">
		</form>
	</div>	

	<?php
	if(isset($_POST['submit']))
	{
	$pname="$_POST[name]";
	$query="SELECT pid,pname,ptype FROM patient WHERE pname LIKE '%{$pname}%'";
	$ret=pg_query($db,$query);
	$patinfo=pg_fetch_all($ret);
	if(!$patinfo) {
		die("No Patients Found!!");
		}

	if(count($patinfo) > 0) {
				echo "<br><br> Patient(s) being treated:";

				echo "<table border='1'>";
				echo <<< EOT
				<tr>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Patient Type</th>
				</tr>
EOT;
				for ($i=0; $i < count($patinfo); $i++) { 
					$pat = $patinfo[$i];
					echo <<< EOT
					<tr>
						<td><a style='color:blue;' href='info_patient.php?pat_id={$pat["pid"]}'>{$pat["pid"]}</a></td>
						<td>{$pat["pname"]}</td>
						<td>{$pat["ptype"]}</td>
					</tr>
EOT;
				}
				echo "</table>";
			}
		}




	?>
</body>
</html>