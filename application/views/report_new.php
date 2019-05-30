<div class='maxDateOutput'>
<?php
echo $maxDateOutput;
$error = "";
?>
</div>
<h2>New Report</h2>
<?php
$startDirName = "http://localhost/umpire/umpire";
echo form_open('report', array('id'=>'submitForm'));

?>
<div class="reportSelectorRow">
    <span class="reportSelectorLabel">Season:</span>
    <span class="reportSelectorControl">
		<select name="season">

		<?php
        //TODO: Make these dropdown boxes bigger, now there is only two of them
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
</div>

<div class="reportSelectorRow">
    <span class="reportSelectorLabel">Region:</span>
    <span class="reportSelectorControl">
		<select name="region">
            <option value="Geelong">Geelong</option>
            <option value="Colac">Colac</option>

		<?php
        //TODO: set the default to whatever region this user has. Most of the time this will be one.
        ?>
		</select>
	</span>
</div>
<br />
<div class="reportSelectorRow">
<?php
//TODO: Make these dropdown boxes bigger, now there is only two of them
foreach ($reportList as $reportListItem) {
    echo "<input type='button' ".
    "value='Report ".$reportListItem->getReportName()."' ".
    "id='reportButton". $reportListItem->getReportId() ."' ".
    "class='btn' ".
    "onClick='validateReportSelectionsNew(". $reportListItem->getReportId() .")' ".
    "name='reportButton'>
    <br /><br />";
}
?>
<input type="hidden" name="reportID" id="reportID" value="" />
</div>

<?php echo form_close(); ?>