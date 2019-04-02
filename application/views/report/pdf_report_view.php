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

ini_set('memory_limit', '1024M'); // or you could use 1G

//$columnHeadingLabels = $reportDisplayOptions->getColumnHeadingLabel();
//$columnHeadingSizeText = $reportDisplayOptions->getColumnHeadingSizeText();

echo "<div class='divTableOuter'>";
echo "<div class='divTable'>";
    $countRows = $loadedReportItem->getRowCount();
    for ($rowCounter=0; $rowCounter < $countRows; $rowCounter++) {
        echo $formattedOutputArray[$rowCounter];
    }
echo "</div>";
