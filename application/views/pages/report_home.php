<h2>Select a Report</h2>
<?php
$startDirName = "http://localhost/umpire/umpire";
//echo APPPATH;
//echo "<form action='".APPPATH."index.php\report' method='POST'>";
echo form_open('report');

?>
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Season:</span>
	<span class="reportSelectorControl">
		<select name="season">
		<option value="2015" selected>2015</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Report:</span>
	<span class="reportSelectorControl">
		<select name="reportName">
		<option value="05" selected>05 - Umpires and Clubs</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Umpire Discipline:</span>
	<span class="reportSelectorControl">
		<select name="umpireType">
		<option value="All" selected>All</option>
		<option value="Field">Field</option>
		<option value="Boundary">Boundary</option>
		<option value="Goal">Goal</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Age Group:</span>
	<span class="reportSelectorControl">
		<select name="age">
		<option value="All" selected>All</option>
		<option value="Seniors">Seniors</option>
		<option value="Reserves">Reserves</option>
		<option value="Colts">Colts</option>
		<option value="Under 16">Under 16</option>
		<option value="Under 14">Under 14</option>
		<option value="Junior Girls">Junior Girls</option>
		<option value="Youth Girls">Youth Girls</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">League:</span>
	<span class="reportSelectorControl">
		<select name="league">
		<option value="All" selected>All</option>
		<option value="BFL">BFL</option>
		<option value="GFL">GFL</option>
		<option value="GDFL">GDFL</option>
		</select>
	</span>
</div>
<BR />
<BR />

<div class="reportSelectorRow">
<input type="submit" value="View Report" class="btn">
</div>
</form>