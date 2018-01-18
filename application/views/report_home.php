<div class='maxDateOutput'>
<?php
echo $maxDateOutput;
$error = "";
?>
</div>

<h2>Select a Report</h2>
<?php
$startDirName = "http://localhost/umpire/umpire";
echo form_open('report', array('id'=>'submitForm'));

$countReportParameters = count($reportSelectionParameters);

?>
<script type="text/javascript">
	window.onload = updateCheckboxEnabledStatus;
</script>
 <div class="reportSelectorRow">
	<span class="reportSelectorLabel">Season:</span>
	<span class="reportSelectorControl">
		<select name="season">
		
		<?php 
		foreach ($seasonList as $seasonListItem) {
		    
		    echo "<option value='". $seasonListItem->getSeasonYear()."'";
		    if (date('Y') == $seasonListItem->getSeasonYear()) {
		        echo " selected";
		    }
		    echo ">". $seasonListItem->getSeasonYear() ."</option>";
		}
		?>
		</select>
	</span>
	<span class="reportSelectorLabel">Report:</span>
	<span class="reportSelectorControl">
		<select name="reportName" id="reportName" onchange="updateCheckboxEnabledStatus()">
		<?php 
		foreach ($reportList as $reportSelectionItem) {
		    echo "<option value='". $reportSelectionItem->getReportID()."'>". $reportSelectionItem->getReportName() ."</option>";
		}
		?>
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
		    echo "<div class='optionsSelectAllRow'>";
		    echo "<input type='checkbox' id='". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll' " .
		    "onClick='toggle(this, \"chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."[]\")'></input>";
		    echo "<label class='reportControlLabel' for='". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll'>Select All</label>";
		    echo "</div>";
		}
			
		for ($j=0; $j < $countReportParameterSelections; $j++) {
		    $currentReportSelectableReportOption = $currentReportSelectionParameter->getSelectableReportOptions()[$j];
		    echo "<div class='optionsSelectionRow'>";
		    if ($currentReportSelectionParameter->getAllowMultipleSelections() == 0) {
		         echo "<input type='radio' id='". $currentReportSelectableReportOption->getOptionValue() ."' " .
		         "name='rd". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."' " .
		         "value='". $currentReportSelectableReportOption->getOptionValue() ."' " .
		         "onClick='updatePageFromCheckboxSelection(this)'";
		         
		         /*if ($j==0) {
		             //Mark the first radio as selected
		             echo " checked";
		         }
		         */
		         echo ">";
		         echo "<label class='reportControlLabel' for='". $currentReportSelectableReportOption->getOptionValue() ."'>". $currentReportSelectableReportOption->getOptionValue() ."</label>";
		         
		    } else {
		        echo "<input type='checkbox' id='". $currentReportSelectableReportOption->getOptionValue() .
		         "' name='chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."[]' " .
		         "value='". $currentReportSelectableReportOption->getOptionValue() ."' " .
		         "onClick='updatePageFromCheckboxSelection(this, ". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."SelectAll)'></input>";
		        
		        echo "<label class='reportControlLabel' for='". $currentReportSelectableReportOption->getOptionValue() ."'>". $currentReportSelectableReportOption->getOptionValue() ."</label>";
		        
		    }
		    echo "</div>";
		}
		
		//Add hidden value
		echo "<input type='hidden' id='chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."Hidden' " .
		    "name='chk". str_replace(' ', '', $currentReportSelectionParameter->getParameterName()) ."Hidden' " .
		    "value='' />";
		
		echo "</div>";
		?>
		</div>

<?php 
    }
?>
		</div>
	</div>
<br />

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
<script type="text/javascript">
    /*$(document).ready(function() {
		alert("ready");
    	updateCheckboxEnabledStatus();
    });*/
	window.onLoad = updateCheckboxEnabledStatus();
</script>
<?php echo form_close(); ?>