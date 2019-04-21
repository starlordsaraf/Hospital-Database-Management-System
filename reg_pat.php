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
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>
	<div align="center">
		<h1>Patient Registration</h1>
		<form id="emp_form" method="POST">
			<label>
				Patient Name:
				<input type="text" name="name">
			</label><br><br>
			<label>
				Gender:
				<input type="text" name="gender" placeholder="m/f">
			</label><br><br>
			<label>
				Date Of Birth:
				<input type="text" name="dob" placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Phone Number:
				<input type="Number" name="phno">
			</label><br><br>
			Address:
			<label>
				<input type="text" name="addr">
			</label><br><br>

			<label>
				Disease:
				<input type="text" name="disease">
			</label><br><br>

			<label>
				Patient Type:
				<input type="text" name="p_type" placeholder="opd/ipd">
			</label><br><br>

			<label>
				Arrival Date:
				<input type="text" name="arrdate" placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Discharge Date:
				<input type="text" name="disdate" placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Room Number:
				<input type="text" name="room">
			</label><br><br>

			<input type="submit" name="submit">
		</form>
	</div>	

	<?php
		require('dbinfo.php');
		$count="SELECT count(*) from patient";
		$c=pg_query($db,$count);
		$ans=pg_fetch_assoc($c);
		//echo "$ans[count]";
		$srno="$ans[count]"+1;
		$pid='PA0'."$srno";
		//echo "$pid";

		$query="INSERT INTO patient VALUES ('$pid','$_POST[phno]'
		,'$_POST[name]','$_POST[dob]','$_POST[addr]','$_POST[gender]','$_POST[p_type]')";

		$result=pg_query($db,$query);
		echo"RECORD ADDED for patient!!";

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
			echo"RECORD ADDED for OPD patient!!";

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
			$i_result=pg_query($db,$i_query);
			echo"RECORD ADDED for IPD patient!!";

		}

	?>
</body>
</html>