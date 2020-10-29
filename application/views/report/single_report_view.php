<?php
if ($reportErrorMessage != '') {
    echo "<br/><div class='reportInformation'><span class='validationError'>". $reportErrorMessage ."</span></div>";
} else {
    //LoadedReportItem is an object of type ReportInstance
    //Formatted output
    $formattedOutputArray = $loadedReportItem->getFormattedResultsForOutput();
    echo "<h1 id='mainHeading'>" . $loadedReportItem->getReportTitle() . "</h1>";
    echo "<div class='reportInformation'><span class='boldedText'>Last Game Date</span>: " . $loadedReportItem->getDisplayOptions()->getLastGameDate() . "</div>";
    echo "<div class='reportInformation' id='reportInfoUmpireDiscipline'><span class='boldedText'>Umpire Discipline</span>: " . $loadedReportItem->filterParameterUmpireType->getFilterDisplayValues() . "</div>";
    echo "<div class='reportInformation' id='reportInfoLeague'><span class='boldedText'>League</span>: " . $loadedReportItem->filterParameterLeague->getFilterDisplayValues() . "</div>";
    echo "<div class='reportInformation' id='reportInfoAgeGroup'><span class='boldedText'>Age Group</span>: " . $loadedReportItem->filterParameterAgeGroup->getFilterDisplayValues() . "</div>";
    echo "<br />";
    ?>
    <!--
    <div style="width:3000px">
            some really really wide content goes here
        </div>
         -->

    <span class='boldedText' id='searchForRow'>Search for Row</span>:<input type="text" id="search"
                                                                            placeholder="Type to search">
    <div id='panelBelow'>
        <div id='moveLeftDown'>


            <table class='reportTable tableWithFloatingHeader' id='reportTable'>

                <?php
                $countRows = $loadedReportItem->getRowCount();
                for ($rowCounter = 0; $rowCounter < $countRows; $rowCounter++) {
                    echo $formattedOutputArray[$rowCounter];
                }
                ?>

            </table>
        </div>
    </div>


    <script type="text/javascript">
        var $rows = $('#reportTable tbody tr');
        $('#search').keyup(function () {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
    <!--
    </div>
    </section>
    -->
    <?php
}
?>

<BR />