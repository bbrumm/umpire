<?php
//LoadedReportItem is an object of type ReportInstance
//Formatted output
$formattedOutputArray = $loadedReportItem->getFormattedResultsForOutput();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<div class='reportInformation'><span class='boldedText'>Last Game Date</span>: ". $loadedReportItem->getDisplayOptions()->getLastGameDate() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Umpire Discipline</span>: ". $loadedReportItem->filterParameterUmpireType->getFilterDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>League</span>: ". $loadedReportItem->filterParameterLeague->getFilterDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Age Group</span>: ". $loadedReportItem->filterParameterAgeGroup->getFilterDisplayValues() ."</div>";
echo "<br />";
?>
<!-- 
<div style="width:3000px">
        some really really wide content goes here
    </div>
     -->
<div id='panelBelow'>
<div id='moveLeftDown'>
<table class='reportTable tableWithFloatingHeader'>

<?php
    $countRows = $loadedReportItem->getRowCount();
    for ($rowCounter=0; $rowCounter < $countRows; $rowCounter++) {
        echo $formattedOutputArray[$rowCounter];
    }
    ?>

</table>
</div>
</div>
<!--
</div>
</section>
-->

<BR />