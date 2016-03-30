<div class='maxDateOutput'>
<?php
echo $maxDateOutput;
$error = "";
?>
</div>


<h2>Select a Report</h2>
<?php
$startDirName = "http://localhost/umpire/umpire";

//echo "<form action='".APPPATH."index.php\report' method='POST'>";
echo form_open('report', array('id'=>'submitForm'));

//$data['reportSelectionParameters']

$countReportParameters = count($reportSelectionParameters);

?>


<div class="reportSelectorRow">
	<span class="reportSelectorLabel">Season:</span>
	<span class="reportSelectorControl">
		<select name="season">
		<option value="2015">2015</option>
		<option value="2016" selected>2016</option>
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


<?php
echo "<div class='validationError' id='validationError'>". $error ."</div>";
?>


	<div class="regularUserDetails">
		<div class="allOptionsSections"  id='JaneDoeOptions'>
			<p class="regularUserOptionsHeading">Report Options</p>

<?php 
for ($i=0; $i < $countReportParameters; $i++) {
    $currentReportSelectionParameter = $reportSelectionParameters[$i];
    
    
?>
			<div class="optionsSection">
			<?php 
			
			echo "<div class='optionsSubHeading'>". $currentReportSelectionParameter->getParameterName() ."</div> <br />";
			
			
			$countReportParameterSelections = count($currentReportSelectionParameter->getSelectableReportOptions());
			echo "<div class='optionsLabelsSection'>";
			if ($currentReportSelectionParameter->getAllowMultipleSelections() == 1) {
			    echo "<input type='checkbox' id='". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll' " .
			    "onClick='toggle(this, \"chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."[]\")'></input>";
			    echo "<label class='reportControlLabel' for='". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll'>Select All</label> <br /><br />";
			}
				
			for ($j=0; $j < $countReportParameterSelections; $j++) {
			    $currentReportSelectableReportOption = $currentReportSelectionParameter->getSelectableReportOptions()[$j];
			    if ($currentReportSelectionParameter->getAllowMultipleSelections() == 0) {
			         echo "<input type='radio' id='". $currentReportSelectableReportOption->getOptionValue() ."' " .
    		         "name='rd". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."' " .
    		         "value='". $currentReportSelectableReportOption->getOptionValue() ."'";
			         if ($j==0) {
			             //Mark the first radio as selected
			             echo " checked";
			         }
			         echo ">";
			         echo "<label class='reportControlLabel' for='". $currentReportSelectableReportOption->getOptionValue() ."'>". $currentReportSelectableReportOption->getOptionValue() ."</label> <br />";
			    } else {
			        echo "<input type='checkbox' id='". $currentReportSelectableReportOption->getOptionValue() .
			         "' name='chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."[]' " .
			         "value='". $currentReportSelectableReportOption->getOptionValue() ."' " .
			         "onClick='toggleSelectAll(". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll, this)'></input>";
			        
			        echo "<label class='reportControlLabel' for='". $currentReportSelectableReportOption->getOptionValue() ."'>". $currentReportSelectableReportOption->getOptionValue() ."</label> <br />";
			    }
			}
			echo "</div>";
			?>
			</div>

<?php 
			}
?>
		</div>
	</div>

	<br/>



<BR />

<div class="reportSelectorRow">
<input type="button" value="View Report" class="btn" onClick='validateReportSelections()'>
<!--
Why are these here?
They are needed for reports where the drop-downs are disabled, as the next page needs values for these.

<input type='hidden' id='umpireTypeHidden' name='umpireType' value='All' />
<input type='hidden' id='ageHidden' name='age' value='All' />
<input type='hidden' id='leagueHidden' name='league' value='All' />
-->

</div>
<?php echo form_close(); ?>
<BR />
<BR />
<BR />
<div class='support'>
Need support? Contact us at <a href='mailto:support@umpirereporting.com'>support@umpirereporting.com</a>.
</div>

<script>
disableSelectBoxes();
</script>
