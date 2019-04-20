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
			<a href="doctors.php" class="navi">Our Doctors</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us</a>&nbsp;
		</span>
	</div>
    <?php
        if(isset($_GET["nur_id"])) {
            require('dbinfo.php');

            $query = <<< EOT
SELECT * FROM nurse natural join employee WHERE nurseid='{$_GET["nur_id"]}';
EOT;
            $ret = pg_query($db, $query);
            $nurinfo = pg_fetch_assoc($ret);
            if(!$nurinfo) {
                die("Could not find info");
            }

            

            $query = <<< EOT
SELECT pname, pid FROM patient natural join in_patient equi join nurse_assigned ON ipd_id = patient_ipd_id WHERE nurse_id = '{$_GET["nur_id"]}';
EOT;
            $ret = pg_query($db, $query);
            $patinfo = pg_fetch_all($ret);

            // var_dump($nurinfo);
            // echo "<br>";
            // var_dump($patinfo);
            // echo "<br>";

            echo "Name: {$nurinfo["ename"]} <br>";
            echo "Nurse_ID: {$nurinfo["nurseid"]} <br>";
            echo "Employee_ID: {$nurinfo["empid"]} <br>";
            echo "Gender: {$nurinfo["gender"]} <br>";
            echo "Salary: {$nurinfo["salary"]} <br>";
            echo "Phone number: {$nurinfo["phno"]} <br>";
            echo "DOB: {$nurinfo["dob"]} <br>";

            if(count($patinfo) > 0) {
				echo "<br><br>Patient(s) being assisted by {$nurinfo["ename"]}:";

				echo "<table border='1'>";
				echo <<< EOT
				<tr>
					<th>Patient ID</th>
					<th>Patient Name</th>
				</tr>
EOT;
				for ($i=0; $i < count($patinfo); $i++) { 
					$pat = $patinfo[$i];
					echo <<< EOT
					<tr>
						<td><a href='info_patient.php?pat_id={$pat["pid"]}'>{$pat["pid"]}</a></td>
						<td>{$pat["pname"]}</td>
					</tr>
EOT;
				}
				echo "</table>";
			}
        }
    ?>
</body>
</html>