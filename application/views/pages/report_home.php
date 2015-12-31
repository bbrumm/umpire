<div class='maxDateOutput'>
<?php
echo $maxDateOutput;
?>
</div>


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
		<option value="2016">2016</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Report:</span>
	<span class="reportSelectorControl">
		<select name="reportName" id="reportName" onchange="disableSelectBoxes()">
    		<option value="01" selected>01 - Umpires and Clubs</option>
    		<option value="02">02 - Umpire Names by League</option>
    		<option value="03">03 - Summary</option>
    		<option value="04">04 - Summary by Club</option>
    		<option value="05">05 - Summary by League</option>
    		<option value="06">06 - Pairings</option>
		</select>
	</span>
</div>
<BR />
<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Umpire Discipline:</span>
	<span class="reportSelectorControl">
		<select name="umpireType" id="umpireType">
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
		<select name="age" id="age">
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
		<select name="league" id="league">
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
<input type='hidden' id='umpireTypeHidden' name='umpireType' value='All' />
<input type='hidden' id='ageHidden' name='age' value='All' />
<input type='hidden' id='leagueHidden' name='league' value='All' />

</div>
</form>

