<?php
	require('dbinfo.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Star Hoapital | Patient Register</title>
	<link rel="stylesheet" type="text/css" href="register.css">
	<link rel="icon" type="image" href="logo.jpg">
</head>
<body background="tag2.jpg">
	<div class="main">&nbsp;&nbsp;&nbsp;STAR HOSPITAL
		<span>
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="medicines.php" class="navi">Medicines</a>&nbsp;|&nbsp;
			<a href="tests.php" class="navi">Tests</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>
	<div align="center">
		<h1>Patient Registration</h1>
		<form id="emp_form" method="POST">
			<label>
				Patient Name:
				<input type="text" name="name" required>
				<br><br>
			</label>

			<label>
				Gender:
				<label>
					<input type="radio" name="gender" value="m" checked>Male
				</label>
				<label>
					<input type="radio" name="gender" value="f">Female
				</label>
				<br><br>
			</label>

			<label>
				Date Of Birth:
				<input type="text" name="dob" placeholder="mm-dd-yyyy" required>
				<br><br>
			</label>

			<label>
				Phone Number:
				<input type="tel" name="phno" required>
				<br><br>
			</label>

			<label>
				Address:
				<input type="text" name="addr" required>
				<br><br>
			</label>

			<label>
				Disease:
				<input type="text" name="disease" required>
				<br><br>
			</label>

			<label>
				Patient Type:
				<label onclick="showOPD();">
					<input type="radio" name="p_type" value="opd" checked>OPD
				</label>
				<label onclick="showIPD();">
					<input type="radio" name="p_type" value="ipd">IPD
				</label>
				<br><br>
			</label>

			<label>
				Arrival Date:
				<input type="text" name="arrdate" placeholder="mm-dd-yyyy" required>
				<br><br>
			</label>

			<label class="IPD">
				Discharge Date:
				<input type="text" name="disdate" placeholder="mm-dd-yyyy" class="required-IPD">
				<br><br>
			</label>

			<label class="IPD">
				Room Number:
				<!-- <input type="text" name="room"> -->

				<select name="room" class="required-IPD">
					<?php
						$query = "SELECT room_id, room_type FROM room;";
						$ret = pg_query($db, $query);

						$rooms = pg_fetch_all($ret);

						for ($i=0; $i < count($rooms); $i++) { 
							$rinfo = $rooms[$i];
							echo "<option value='{$rinfo["room_id"]}'>{$rinfo["room_id"]} - {$rinfo["room_type"]} </option>";
						}
					?>
				</select>
				<br><br>
			</label>

			<label>
				Doctors treating the patient:
				<select name="doctors[]" multiple required>
					<?php
						$query = "SELECT doc_id, ename FROM doctor natural join employee;";
						$ret = pg_query($db, $query);
						$doctors = pg_fetch_all($ret);
						if($doctors)
						{
							for ($i=0; $i < count($doctors); $i++) { 
								$doc = $doctors[$i];
								echo <<< EOT
								<option value='$doc[doc_id]'>$doc[ename]</option>
EOT;
							}
						}
					?>
				</select>
				<br><br>
			</label>

			<label class="IPD">
				Nurses assisting the patient:
				<select name="nurses[]" multiple class="required-IPD">
					<?php
						$query = "SELECT nurseid, ename FROM nurse natural join employee;";
						$ret = pg_query($db, $query);
						$nurses = pg_fetch_all($ret);
						if($nurses)
						{
							for ($i=0; $i < count($nurses); $i++) { 
								$nur = $nurses[$i];
								echo <<< EOT
								<option value='$nur[nurseid]'>$nur[ename]</option>
EOT;
							}
						}
					?>
				</select>
				<br><br>
			</label>

			<input type="submit" name="submit">
		</form>
	</div>	

	<?php
		
		$count="SELECT count(*) from patient";
		$c=pg_query($db,$count);
		$ans=pg_fetch_assoc($c);
		// echo "$ans[count]";
		$srno="$ans[count]"+1;
		$pid='PA0'."$srno";
		//echo "$pid";

		if(isset($_POST["submit"]))
		{
			// var_dump($_POST);
			// echo "<br>";
			$query="INSERT INTO patient VALUES ('$pid','$_POST[phno]'
			,'$_POST[name]','$_POST[dob]','$_POST[addr]','$_POST[gender]','$_POST[p_type]')";
			$result=pg_query($db,$query);
			echo"RECORD ADDED for patient!!";

			for ($i=0; $i < count($_POST["doctors"]); $i++) { 
				$docid = $_POST["doctors"][$i];
				// echo "$docid<br>";

				$doc_query = "INSERT INTO doctor_assigned VALUES('$docid', '$pid');";
				pg_query($db, $doc_query);
				// echo "$doc_query<br>";
			}
	
			if("$_POST[p_type]"==="opd")
			{
				$o_count="SELECT count(*) from out_patient";
				$o_c=pg_query($db,$o_count);
				$o_ans=pg_fetch_assoc($o_c);
				//echo "$o_ans[count]";
				$o_srno="$o_ans[count]"+1;
				$opd_id='OPD'."$o_srno";
	
				$o_query="INSERT INTO out_patient VALUES('$opd_id','$_POST[arrdate]','$_POST[disease]','$pid')";
				$o_result=pg_query($db,$o_query);

				// echo"RECORD ADDED for OPD patient $pid";
	
			}
	
			if("$_POST[p_type]"==="ipd")
			{
				$i_count="SELECT count(*) from in_patient";
				$i_c=pg_query($db,$i_count);
				$i_ans=pg_fetch_assoc($i_c);
				//echo "$i_ans[count]";
				$i_srno="$i_ans[count]"+1;
				$ipd_id='IPD0'."$i_srno";
	
				$i_query="INSERT INTO in_patient VALUES('$ipd_id','$_POST[disease]','$_POST[arrdate]','$_POST[disdate]','$pid','$_POST[room]')";
				// echo "$i_query<br>";
				$i_result=pg_query($db,$i_query);
				// echo"RECORD ADDED for IPD patient $pid";

				for ($i=0; $i < count($_POST["nurses"]); $i++) { 
					$nurid = $_POST["nurses"][$i];
					$nur_query = "INSERT INTO nurse_assigned VALUES('$nurid', '$ipd_id');";
					pg_query($db, $nur_query);
					echo "$nur_query<br>";
				}
	
			}

			header("Location: info_patient.php?pat_id=$pid");
		}

	?>

	<script>

		var IPDElements = document.querySelectorAll(".IPD");
		var IPDReq = document.querySelectorAll(".required-IPD");

		//based on what's clicked on patient type, display the apt fields
		function showOPD() {
			//hide nurses, discharge date, room number
			console.log("opd");
			IPDElements.forEach(element => {
				element.setAttribute("hidden", "true");
			});

			IPDReq.forEach(element => {
				element.removeAttribute("required");
			});
		}

		function showIPD() {
			console.log("ipd");
			IPDElements.forEach(element => {
				element.removeAttribute("hidden");
			});

			IPDReq.forEach(element => {
				element.setAttribute("required", "true");
			});
		}

		showOPD();

	</script>
</body>
</html>