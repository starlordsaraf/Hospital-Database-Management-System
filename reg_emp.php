<!DOCTYPE html>
<html>
<head>
	<title>Star Hoapital | Employee Register</title>
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
		<h1>Employee Registration</h1>
		<form id="emp_form" method="POST">
			<label>
				Employee Name:
				<input type="text" name="name">
			</label><br><br>
			<label>
				Gender:
				<input type="text" placeholder="m/f" name="gender">
			</label><br><br>
			<label>
				Date Of Birth:
				<input type="text" name="dob" placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Phone Number:
				<input type="Number" name="phno">
			</label><br><br>

			<label>
				Join Date:
				<input type="text" name="joindate"  placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Leave Date:
				<input type="text" name="leavedate" placeholder="mm-dd-yyyy">
			</label><br><br>

			<label>
				Salary:
				<input type="Number" name="salary">
			</label><br><br>

			<label>
				Employee Type:
				<input type="text" name="emp_type" placeholder="doc/nur">
			</label><br><br>

			<label>
				Doctor Qualification:
				<input type="text" name="qual">
			</label><br><br>

			<label>
				Department ID:
				<input type="text" name="deptid">
			</label><br><br>

			<label>
				Doctor Consultation Fee:
				<input type="Number" name="confee">
			</label><br><br>
			<input type="submit" name="submit">
		</form>
	</div>

	<?php
		require('dbinfo.php');
		$count="SELECT count(*) from employee";
		$c=pg_query($db,$count);
		$ans=pg_fetch_assoc($c);
		//echo "$ans[count]";
		$srno="$ans[count]"+1;
		$empid='EM0'."$srno";
		//echo "$empid";
		if(null==="$_POST[leavedate]")
		{
			$query="INSERT INTO employee (empid,ename,gender,joindate,dob,phno,salary,leave_date,emp_type,deptid) VALUES ('$empid','$_POST[name]','$_POST[gender]','$_POST[joindate]','$_POST[dob]','$_POST[phno]','$_POST[salary]','$_POST[leavedate]','$_POST[emp_type]','$_POST[deptid]')";
			$result=pg_query($db,$query);
			echo"RECORD ADDED of non-existent employee!!";
		}

	else
	{
		$query="INSERT INTO employee (empid,ename,gender,joindate,dob,phno,salary,emp_type,deptid) VALUES ('$empid','$_POST[name]','$_POST[gender]','$_POST[joindate]','$_POST[dob]','$_POST[phno]','$_POST[salary]','$_POST[emp_type]','$_POST[deptid]')";
		$result=pg_query($db,$query);
		echo"RECORD ADDED for existing employee!!";


	if("$_POST[emp_type]"==="doc")
		{
			$d_count="SELECT count(*) from doctor";
			$d_c=pg_query($db,$d_count);
			$d_ans=pg_fetch_assoc($d_c);
			$d_srno="$d_ans[count]"+1;
			$docid='DOC'."$d_srno";
			//echo "$docid";
			$d_query="INSERT INTO doctor VALUES ('$docid','$_POST[qual]',$_POST[confee],'$empid')";
			$d_result=pg_query($db,$d_query);
			echo"DOCTOR ADDED!!";
		}


	if("$_POST[emp_type]"==="nur")
		{
			$n_count="SELECT count(*) from nurse";
			$n_c=pg_query($db,$n_count);
			$n_ans=pg_fetch_assoc($n_c);
			$n_srno="$n_ans[count]"+1;
			$nurid='NUR'."$n_srno";
			//echo "$nurid";
			$n_query="INSERT INTO nurse (nurseid,empid) VALUES ('$nurid','$empid')";
			$n_result=pg_query($db,$n_query);
			echo"NURSE ADDED!!";
		}

	}






	?>	
</body>
</html>