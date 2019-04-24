<?php
	require('dbinfo.php');
?>

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
			<a href="medicines.php" class="navi">Medicines</a>&nbsp;|&nbsp;
			<a href="tests.php" class="navi">Tests</a>&nbsp;|&nbsp;
			<a href="checkdetails.html" class="navi">Check Details</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us </a>&nbsp;
		</span>
	</div>
	<div align="center">
		<h1>Employee Registration</h1>
		<form id="emp_form" method="POST">
			<label>
				Employee Name:
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
				Join Date:
				<input type="text" name="joindate"  placeholder="mm-dd-yyyy" required>
				<br><br>
			</label>

			<label>
				Leave Date:
				<input type="text" name="leavedate" placeholder="mm-dd-yyyy">
				<br><br>
			</label>

			<label>
				Salary:
				<input type="Number" name="salary" required>
				<br><br>
			</label>

			<label>
				Employee Type:
				<label>
					<input type="radio" name="emp_type" value="doc" onclick="showDoc();" checked>Doctor
				</label>
				<label>
					<input type="radio" name="emp_type" value="nur" onclick="hideDoc();">Nurse
				</label>
				<br><br>
			</label>

			<label class="DOC">
				Doctor Qualification:
				<input type="text" name="qual" class="DOCReq">
				<br><br>
			</label>

			<label class="DOC">
				Department ID:
				<!-- <input type="text" name="deptid" class="DOCReq"> -->
				<select name="deptid" class="DOCReq">
				<?php
					$query = "SELECT deptid, deptname FROM department;";
					$ret = pg_query($db, $query);
					$depts = pg_fetch_all($ret);

					for ($i=0; $i < count($depts); $i++) { 
						$dept = $depts[$i];
						echo "<option value='{$dept["deptid"]}'>{$dept["deptid"]} - {$dept["deptname"]}</option>";
					}
				?>
				</select>
				<br><br>
			</label>

			<label class="DOC">
				Doctor Consultation Fee:
				<input type="Number" name="confee" class="DOCReq">
				<br><br>
			</label>
			<input type="submit" name="submit">
		</form>
	</div>

	<?php
		$count="SELECT count(*) from employee";
		$c=pg_query($db,$count);
		$ans=pg_fetch_assoc($c);
		//echo "$ans[count]";
		$srno="$ans[count]"+1;
		$empid='EM0'."$srno";
		//echo "$empid";
		if(isset($_POST["submit"]))
		{
			// var_dump($_POST);
			// echo "<br>";
			if(null==="$_POST[leavedate]")
			{
				$query="INSERT INTO employee (empid,ename,gender,joindate,dob,phno,salary,leave_date,emp_type,deptid) VALUES ('$empid','$_POST[name]','$_POST[gender]','$_POST[joindate]','$_POST[dob]','$_POST[phno]','$_POST[salary]','$_POST[leavedate]','$_POST[emp_type]','$_POST[deptid]')";
				$result=pg_query($db,$query);
				// echo "$query<br>";
				// echo"RECORD ADDED of non-existent employee!!";
			}
			else
			{
				$query="INSERT INTO employee (empid,ename,gender,joindate,dob,phno,salary,emp_type,deptid) VALUES ('$empid','$_POST[name]','$_POST[gender]','$_POST[joindate]','$_POST[dob]','$_POST[phno]','$_POST[salary]','$_POST[emp_type]','$_POST[deptid]')";
				$result=pg_query($db,$query);
				// echo "$query<br>";
				// echo"RECORD ADDED for existing employee!!";
			}
	
	
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
				// echo "$d_query<br>";
				header("Location: info_doctor.php?doc_id=$docid");

			}
			else if("$_POST[emp_type]"==="nur")
			{
				$n_count="SELECT count(*) from nurse";
				$n_c=pg_query($db,$n_count);
				$n_ans=pg_fetch_assoc($n_c);
				$n_srno="$n_ans[count]"+1;
				$nurid='NUR'."$n_srno";
				//echo "$nurid";
				$n_query="INSERT INTO nurse (nurseid,empid) VALUES ('$nurid','$empid')";
				$n_result=pg_query($db,$n_query);
				// echo "$n_query<br>";
				header("Location: info_nurse.php?nur_id=$nurid");
			}
		}
	?>

	<script>
		var DOCElements = document.querySelectorAll(".DOC");
		var DOCReq = document.querySelectorAll(".DOCReq");

		function showDoc() {
			DOCElements.forEach(element => {
				element.removeAttribute("hidden");
			});

			DOCReq.forEach(element => {
				element.setAttribute("required", "true");
			});
		}

		function hideDoc() {
			DOCElements.forEach(element => {
				element.setAttribute("hidden", "true");
			});

			DOCReq.forEach(element => {
				element.removeAttribute("required");
			});
		}

		showDoc();
	</script>
</body>
</html>