<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licenced under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	$db = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$sql = "select * from va_parameters ";
	if (!$result = $db->query($sql)) {
		die('There was an error running the query [' . $db->error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$no_count_rejected = $row["no_count_rejected"];
	}
	if ($no_count_rejected==1)
	{
		$sql = "select callsign , name,surname, sum_time, g.transfered_hours , (v.sum_time + g.transfered_hours) as total_time from v_top_hours_rejected v inner join gvausers g on g.gvauser_id = v.pilot order by total_time desc limit 5";
	}
	else
	{
		$sql = "select callsign , name,surname, sum_time, g.transfered_hours , (v.sum_time + g.transfered_hours) as total_time from v_top_hours v inner join gvausers g on g.gvauser_id = v.pilot order by total_time desc limit 5";
	}
	if (!$result = $db->query($sql)) {
		die('There was an error running the query [' . $db->error . ']');
	}
	echo '<table class="table table-hover">';
	echo '<tr><th>' . STATISTICS_CALLSIGN . '</th><th>' . STATISTICS_PILOT . '</th><th>' . STATISTICS_HOURS . '</th></tr>';
	while ($row = $result->fetch_assoc()) {
		echo "<tr><td>";
		echo $row["callsign"] . '</td><td>';
		echo $row["name"] . ' ' . $row["surname"] . '</td><td>';
		echo convertTime($row["total_time"],$va_time_format) . '</td>';
		echo "</tr>";
	}
	echo "</table></br>";
	$db->close();
?>
