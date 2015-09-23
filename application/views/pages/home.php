<h2>Reports</h2>
<?php
$startDirName = "http://localhost/umpire/umpire";
echo "<form action='$startDirName/index.php/report' method='POST'>";
?>

<p>Report:
	<select name="reportName">
	<option value="05">05 - Umpires and Clubs</option>
	</select>
</p>
<p>Season:
	<select name="season">
	<option value="2015">2015</option>
	</select>
</p>
<p>Umpire Discipline:
	<select name="umpireType">
	<option value="All">All</option>
	<option value="Field">Field</option>
	<option value="Boundary">Boundary</option>
	<option value="Goal">Goal</option>
	</select>
</p>
<p>Age Group:
	<select name="age">
	<option value="All">All</option>
	<option value="Seniors">Seniors</option>
	<option value="Reserves">Reserves</option>
	<option value="Colts">Colts</option>
	<option value="Under 16">Under 16</option>
	<option value="Under 14">Under 14</option>
	<option value="Junior Girls">Junior Girls</option>
	<option value="Youth Girls">Youth Girls</option>
	</select>
</p>
<p>League:
	<select name="league">
	<option value="All">All</option>
	<option value="BFL">BFL</option>
	<option value="GFL">GFL</option>
	<option value="GDFL">GDFL</option>
	</select>
</p>

<input type="submit" value="View Report">
</form>