<!DOCTYPE html>
<html>

<head>
	<title>Star Hoapital | Our Doctors</title>
	<link rel="stylesheet" type="text/css" href="home1.css">
	<link rel="icon" type="image" href="images/logo.jpg">
</head>

<body background="images/tag2.jpg">
	<div class="main">&nbsp;&nbsp;&nbsp;STAR HOSPITAL
		<span>
			<a href="login.html" class="navi">Admin Log In</a>&nbsp;|&nbsp;
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>
	<div align="center">
		<p>
			Our Doctors
		</p><br>
		<!-- <button id="ldoc">Show</button> -->
		<table border="1">
			<?php
            	require('dbinfo.php');

            	$query = <<< EOT
SELECT * FROM doctor natural join employee natural join department WHERE emp_type='doc';
EOT;
				$ret = pg_query($db, $query);
				
				echo "<thead>";
				
				$row = pg_fetch_assoc($ret);
				foreach($row as $key => $val) {
					echo "<th>$key</th>";
				}

				echo "</thead>";

				pg_result_seek($ret, 0);

				while ($row = pg_fetch_assoc($ret)) {
					echo "<tr>";
					foreach($row as $key => $val) {
						echo <<< EOT
						<td>
							<a href='info_doctor.php?doc_id={$row["doc_id"]}' style="color:blue">
							$val
							</a>
						</td>
EOT;
					}
					echo "</tr>";
				}
			?>
		</table>
	</div>
</body>

</html>