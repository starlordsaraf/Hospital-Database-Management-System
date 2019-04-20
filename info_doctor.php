<!DOCTYPE html>
<html>
<head>
	<title>Star Hoapital | Check Details</title>
	<link rel="stylesheet" type="text/css" href="home1.css">
	<link rel="icon" type="image" href="images/logo.jpg">
</head>
<body background="tag2.jpg">
	<div class="main">&nbsp;&nbsp;&nbsp;STAR HOSPITAL
		<span>
			<a href="login.html" class="navi">Admin Log In</a>&nbsp;|&nbsp;
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="doctors.html" class="navi">Our Doctors</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us</a>&nbsp;
		</span>
	</div>
	
	<?php
		if(isset($_GET["doc_id"])) {
			require('dbinfo.php');

			//get all of doctor's info, patients he's treating
			$query = <<< EOT
SELECT * FROM doctor natural join employee natural join department WHERE doc_id='{$_GET["doc_id"]}';
EOT;

			$ret = pg_query($db, $query);
			$docinfo = pg_fetch_assoc($ret);
			if(!$docinfo) {
				die("Could not find info");
			}


			$query = <<< EOT
SELECT * from doctor_assigned equi join patient ON pid=patient_id WHERE doc_id = '{$_GET["doc_id"]}';
EOT;
			$ret = pg_query($db, $query);
			$patinfo = pg_fetch_all($ret);

			// var_dump($docinfo);
			// echo "<br>";

			// // $patinfo = array();
			// var_dump($patinfo);
			// echo "<br>";


			echo "Name: {$docinfo["ename"]} <br>";
			echo "Doctor_ID: {$docinfo["doc_id"]} <br>";
			echo "Employee_ID: {$docinfo["empid"]} <br>";
			echo "Department: {$docinfo["deptname"]} <br>";
			echo "Gender: {$docinfo["gender"]} <br>";
			echo "Salary: {$docinfo["salary"]} <br>";
			echo "Consulation Cost: {$docinfo["consultn"]} <br>";
			echo "Phone number: {$docinfo["phno"]} <br>";
			echo "Qualifications: {$docinfo["qual"]} <br>";

			if(count($patinfo) > 0) {
				echo "<br><br>Patient(s) being treated by {$docinfo["ename"]}:";

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
						<td><a href='info_patient.php?pat_id={$pat["patient_id"]}'>{$pat["patient_id"]}</a></td>
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