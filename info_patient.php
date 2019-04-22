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
            <a href="reg_pat.php" class="navi">Register Patient</a>&nbsp;|&nbsp;
			<a href="reg_emp.php" class="navi">Register Employee</a>&nbsp;|&nbsp;
			<a href="medicines.php" class="navi">Medicines</a>&nbsp;|&nbsp;
			<a href="tests.php" class="navi">Tests</a>&nbsp;|&nbsp;
			<a href="index.html" class="navi">Home</a>&nbsp;|&nbsp;
			<a href="doctors.php" class="navi">Our Doctors</a>&nbsp;|&nbsp;
			<a href="contactus.html" class="navi">Contact Us</a>&nbsp;
		</span>
	</div>
    <?php
        if(isset($_GET["pat_id"])) {
            require('dbinfo.php');

            $query = <<< EOT
SELECT * FROM patient WHERE pid = '{$_GET["pat_id"]}';
EOT;
            $ret = pg_query($db, $query);
            $patinfo = pg_fetch_assoc($ret);
            if(!$patinfo) {
                die("Could not find info");
            }

            echo "Name: {$patinfo["pname"]} <br>";
            echo "Patient_ID: {$patinfo["pid"]} <br>";
            echo "Gender: {$patinfo["sex"]} <br>";
            echo "Phone number: {$patinfo["phno"]} <br>";
            echo "DOB: {$patinfo["dob"]} <br>";
            echo "Address: {$patinfo["addres"]} <br>";
            echo "Patient type: {$patinfo["ptype"]} <br>";
            echo "<br>";
            //show the disease, date of arrival
            if($patinfo["ptype"] == "opd") {
                $query = <<< EOT
SELECT * FROM out_patient WHERE pid = '{$patinfo["pid"]}';
EOT;
                $ret = pg_query($db, $query);
                $outpatinfo = pg_fetch_assoc($ret);

                echo "OPD_ID: {$outpatinfo["opd_id"]} <br>";
                echo "Date of arrival: {$outpatinfo["d_arrival"]} <br>";
                echo "Disease: {$outpatinfo["disease"]} <br>";

            }
            else {
                $query = <<< EOT
SELECT * FROM in_patient WHERE pid = '{$patinfo["pid"]}';
EOT;
                $ret = pg_query($db, $query);
                $inpatinfo = pg_fetch_assoc($ret);

                echo "IPD_ID: {$inpatinfo["ipd_id"]} <br>";
                echo "Date of arrival: {$inpatinfo["d_arrival"]} <br>";
                echo "Date of discharge: {$inpatinfo["d_discharge"]} <br>";
                echo "Disease: {$inpatinfo["d_disease"]} <br>";

                //show the relative info
                $query = <<< EOT
SELECT * FROM relative WHERE patient_ipd_id = '{$inpatinfo["ipd_id"]}';
EOT;
                $ret = pg_query($db, $query);
                $relinfo = pg_fetch_all($ret);
                if($relinfo && count($relinfo) > 0) {
                    echo "<br>Relative(s) info: <br>";
                    for ($i=0; $i < count($relinfo); $i++) { 
                        $rel = $relinfo[$i];

                        echo "Relative name: {$rel["relative_name"]} <br>";
                        echo "Relation: {$rel["relation"]} <br>";
                        echo "Phone number: {$rel["phone_number"]} <br>";

                    }
                }

                //show which nurse is assisting him
                $query = <<< EOT
SELECT nurse_id, ename FROM (SELECT patient_ipd_id, nurse_id, empid FROM nurse_assigned equi join nurse ON nurse_id = nurseid WHERE patient_ipd_id = '{$inpatinfo["ipd_id"]}') as x natural join employee;
EOT;
                $ret = pg_query($db, $query);
                $nurinfo = pg_fetch_all($ret);
                if($nurinfo && count($nurinfo) > 0) {
                    echo "<br>Nurses assisting {$patinfo["pname"]} <br>";
                    
                    echo "<table border='1'>";
                    echo <<< EOT
                    <tr>
                        <th>Nurse ID</th>
                        <th>Nurse Name</th>
                    </tr>
EOT;
                    for ($i=0; $i < count($nurinfo); $i++) { 
                        $nur = $nurinfo[$i];
                        echo <<< EOT
                        <tr>
                            <td><a href='info_nurse.php?nur_id={$nur["nurse_id"]}'>{$nur["nurse_id"]}</a></td>
                            <td>{$nur["ename"]}</td>
                        </tr>
EOT;
                    }
                    echo "</table>";
                }


                //show room info
                $query = <<< EOT
SELECT * FROM room WHERE room_id = '{$inpatinfo["room_id"]}';
EOT;
                $ret = pg_query($db, $query);
                $roominfo = pg_fetch_assoc($ret);

                echo "<br>Room Info:<br>";
                echo "Room_ID: {$roominfo["room_id"]} <br>";
                echo "Room Type: {$roominfo["room_type"]} <br>";
                echo "Room Cost/day: {$roominfo["room_cost"]} <br>";

            }

            //show the medicines and tests he's taking
            $query = <<< EOT
SELECT * FROM takes natural join medicine WHERE patient_id = '{$patinfo["pid"]}';
EOT;
            $ret = pg_query($db, $query);
            $medinfo = pg_fetch_all($ret);

            if($medinfo && count($medinfo) > 0) {
                echo "<br>Medicines being taken:<br>";

                for ($i=0; $i < count($medinfo); $i++) { 
                    $med = $medinfo[$i];

                    echo "Medicine name: {$med["med_name"]} <br>";
                    echo "Medicine date: {$med["m_date"]} <br>";
                    echo "Medicine quantity: {$med["qty"]} <br>";
                    echo "Medicine cost: {$med["med_cost"]} <br><br>";

                }
            }
            
            
            // select * from has natural join test WHERE pid = 'PA002';
            $query = <<< EOT
SELECT * FROM has natural join test WHERE pid = '{$patinfo["pid"]}';
EOT;
            $ret = pg_query($db, $query);
            $testinfo = pg_fetch_all($ret);

            if($testinfo && count($testinfo) > 0) {
                echo "<br>Test(s) taken:<br>";

                for ($i=0; $i < count($testinfo); $i++) { 
                    $test = $testinfo[$i];

                    echo "Test name: {$test["test_name"]} <br>";
                    echo "Test date: {$test["tdate"]} <br>";
                    echo "Test cost: {$test["test_cost"]} <br><br>";

                }
            }

            //show which doctors is he being treated by
            $query = <<< EOT
SELECT doc_id, ename, consultn FROM (SELECT * FROM doctor_assigned natural join doctor WHERE patient_id = '{$patinfo["pid"]}') as x natural join employee;
EOT;
            $ret = pg_query($db, $query);
            $docinfo = pg_fetch_all($ret);
            if($docinfo && count($docinfo) > 0) {
                echo "<br>Doctor(s) treating {$patinfo["pname"]} <br>";
                
                echo "<table border='1'>";
                echo <<< EOT
                <tr>
                    <th>Doctor ID</th>
                    <th>Doctor Name</th>
                    <th>Doctor fee</th>
                </tr>
EOT;
                for ($i=0; $i < count($docinfo); $i++) { 
                    $doc = $docinfo[$i];
                    echo <<< EOT
                    <tr>
                        <td><a href='info_doctor.php?doc_id={$doc["doc_id"]}'>{$doc["doc_id"]}</a></td>
                        <td>{$doc["ename"]}</td>
                        <td>{$doc["consultn"]}</td>
                    </tr>
EOT;
                }
                echo "</table>";
            }
            

            //show his bill amount
            /*
            need to sum up the results from following queries
            
            SELECT sum(med_cost*qty) FROM takes natural join medicine WHERE patient_id = 'PA001';
            SELECT sum(test_cost) FROM has natural join test WHERE pid = 'PA001';
            SELECT sum(consultn) FROM (SELECT * FROM doctor_assigned natural join doctor WHERE patient_id = 'PA001') as x natural join employee;
            SELECT (d_discharge - d_arrival)*room_cost as room_total from in_patient natural join room WHERE pid = 'PA001';
            SELECT other_charges from bill WHERE pid = 'PA001';
            */

            $medcost = getMonVal($db, "SELECT sum(med_cost*qty)::numeric FROM takes natural join medicine WHERE patient_id = '{$patinfo["pid"]}';");
            $testcost = getMonVal($db, "SELECT sum(test_cost)::numeric FROM has natural join test WHERE pid = '{$patinfo["pid"]}';");
            $concharge = getMonVal($db, "SELECT sum(consultn)::numeric FROM (SELECT * FROM doctor_assigned natural join doctor WHERE patient_id = '{$patinfo["pid"]}') as x natural join employee;");
            $roomcharge = getMonVal($db, "SELECT (d_discharge - d_arrival)*room_cost::numeric as room_total from in_patient natural join room WHERE pid = '{$patinfo["pid"]}';");
            $othercharge = getMonVal($db, "SELECT other_charges::numeric from bill WHERE pid = '{$patinfo["pid"]}';");
            $totalcharge = $medcost + $testcost + $concharge + $roomcharge + $othercharge;

            echo "<br><br>";
            echo "Medicine costs: $medcost<br>";
            echo "Test(s) costs: $testcost<br>";
            echo "Doctor(s) consultation charges: $concharge<br>";
            echo "Room charges: $roomcharge<br>";
            echo "Other charges: $othercharge<br>";
            echo "Bill total: $totalcharge<br>";
        }

        function getMonVal($db, $query)
        {
            $ret = pg_query($db, $query);
            $val = pg_fetch_row($ret);
            // var_dump($val);
            return floatval($val[0]);
        }

        
    ?>
</body>
</html>