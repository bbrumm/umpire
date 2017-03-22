<?php


//LoadedReportItem is an object of type ReportInstance
$loadedColumnGroupings = $loadedReportItem->getColumnLabelResultArray();
$loadedResultArray = $loadedReportItem->getResultArray(); 
$resultOutputArray = $loadedReportItem->getResultOutputArray(); //this might be the only object I need if I use the DW method
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$colourCells = $reportDisplayOptions->getColourCells();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
//$debugLibrary = new DebugLibrary();
$debugMode = $this->config->item('debug_mode');
$reportID = $loadedReportItem->getReportID();
$countFirstLoadedColumnGroupings = count($columnLabels);
$useNewDWTables = $this->config->item('use_new_dw_tables');

$columnCountForHeadingCells = $loadedReportItem->getColumnCountForHeadingCells();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<div class='reportInformation'><span class='boldedText'>Last Game Date</span>: ". $reportDisplayOptions->getLastGameDate() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Umpire Discipline</span>: ". $loadedReportItem->getUmpireTypeDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>League</span>: ". $loadedReportItem->getLeagueDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Age Group</span>: ". $loadedReportItem->getAgeGroupDisplayValues() ."</div>";
echo "<br />";

?>
<!-- 
<div style="width:3000px">
        some really really wide content goes here
    </div>
     -->

<!-- This table is used to test the new Data Warehouse style output. -->
<div id='panelBelow'>
<div id='moveLeftDown'>
<table class='reportTable tableWithFloatingHeader'	>


<thead>

<?php 
//$debugLibrary->debugOutput("test");


if ($useNewDWTables) {

    if ($debugMode) {
        echo "<BR />resultOutputArray DW:<pre>";
        print_r($resultOutputArray);
        echo "</pre><BR />";
        
        echo "<BR />columnLabelResultArray DW:<pre>";
        print_r($loadedColumnGroupings);
        echo "</pre><BR />";
        
        echo "<BR />reportDisplayOptions DW:<pre>";
        print_r($reportDisplayOptions);
        echo "</pre><BR />";
        
        echo "Test Table Start <BR />";
    }
    
    
    $countItemsInColumnHeadingSet = count($loadedColumnGroupings[0]);
    //TODO: Replace this temp array with data from the database (report param tables)
    $tempArrayForColumnLabels = array('short_name', 'club_name');
    
    for ($i=0; $i < $countItemsInColumnHeadingSet; $i++) {
        echo "<tr class='header'>";
        
        $thOutput = "";
        $thClassNameToUse = "";
        
        if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
            $thClassNameToUse = "columnHeadingNormal cellNameSize";
        } elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
            $thClassNameToUse = "columnHeadingNormal cellDateSize";
        } else {
            $thClassNameToUse = "columnHeadingNormal cellNameSize";
        }
        
        
        $arrReportRowGroup = $reportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
        $arrReportColumnGroup = $reportDisplayOptions->getColumnGroup();
        /*
        if ($debugMode) {
            echo "<BR />arrReportRowGroup DW:<pre>";
            print_r($arrReportRowGroup);
            echo "</pre><BR />";
            
            echo "<BR />arrReportColumnGroup DW:<pre>";
            print_r($arrReportColumnGroup);
            echo "</pre><BR />";
        }
        */
        for ($r=0; $r < count($arrReportRowGroup); $r++) {
            $thOutput = "<th class='". $thClassNameToUse ."'>";
            if ($i == 0) {
                	
                $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
                $thOutput .= "<BR /><span class='columnSizeText'>". $arrReportRowGroup[$r]->getGroupSizeText() ."</span>";
            }
            $thOutput .= "</th>";
            echo $thOutput;
        }
        
        /*
        echo "<th>Row Label Title</th>";
        foreach ($loadedColumnGroupings as $columnHeadingSet) {
            echo "<th>";    
            echo $columnHeadingSet[$tempArrayForColumnLabels[$i]];        
            echo "</th>";
        }
        echo "</tr>";
        */
    
    

        $countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
        if ($debugMode) {
            echo "<BR />countLoadedColumnGroupings DW:" . $countLoadedColumnGroupings;
        }
        
        for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
            //Check if cell should be merged
            if ($j==0) {
                $proceed = true;
            }
            if ($proceed) {
                //print cell with colspan value
                if ($debugMode) {
                    /*
                    echo "<pre>columnLabels <BR/>";
                    print_r($columnLabels[$i]);
                    echo "</pre>";
                    */
                    echo $columnLabels[$i]->getFieldName() . "rotate cell 1<BR />";
                }
                if ($columnLabels[$i]->getFieldName() == 'club_name' || $reportID == 6) {
                    //Some reports have their headers displayed differently
                    $colspanForCell = 1;
                    if ($PDFLayout) {
                        //$cellClass = "rotatePDF";
                        $cellClass = "rotated_cell_pdf";
                        $divClass = "rotated_text_pdf";
        
                    } else {
                        if ($debugMode) {
                            echo "<BR />rotate cell YES<BR />";
                        }
                        $cellClass = "rotated_cell";
                        $divClass = "rotated_text";
                    }
                } else {
                    if ($debugMode) {
                        echo "dont rotate cell 2<BR />";
                    }
                    //Increase the colspan if this column group is to be merged
                    if ($arrReportColumnGroup[$i]->getMergeField() == 1) {
                        $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
                    } else {
                        $colspanForCell = 1;
                    }
                    $cellClass = "columnHeadingNormal";
                    $divClass = "normalHeadingText";
                }
                echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
            }
        }
    
    }
    
    echo "</thead>";
    
    $countRows = count($resultOutputArray);
    if ($reportID == 5) {
        //TODO: Fix bug where report 5 is not getting the right number of columns.
        //This happens because the COUNT here is only looking at data columns, not the row label columns,
        //and in report 5, there are two of them.
        $countColumns = count($loadedColumnGroupings) + 1;
    } else {
        $countColumns = count($loadedColumnGroupings);
    }
    
    if ($debugMode) {
        echo "<BR />loadedColumnGroupings DW:<pre>";
        print_r($loadedColumnGroupings);
        echo "</pre><BR />";
    }
    $cellClassToUse = "";
    for ($rowCounter=0; $rowCounter < $countRows; $rowCounter++) {
    
        $tableRowOutput = "<tr>";
        for ($columnCounter=0; $columnCounter <= $countColumns; $columnCounter++) {
            //$tableRowOutput .= "<td class='cellNormal'>";
            
            if(array_key_exists($columnCounter, $resultOutputArray[$rowCounter])) {
                
                
                if ($columnCounter == 0) { //First column
                    if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
                        $cellValue = $resultOutputArray[$rowCounter][$columnCounter];
                        $cellClassToUse = "cellText cellNormal";
                        /*if (count($rowLabels) > 1) {
                            //Output a second row label
                            $tableRowOutput .= $resultOutputArray[$rowCounter][$columnCounter];
                        }
                        */
                    } elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
                        //$weekDate = date_create($resultRow[$rowLabels[0]]);
                        //$weekDate = date_create($resultRow["weekdate"]);
                        $weekDate = date_create($resultOutputArray[$rowCounter][$columnCounter]);
                        $cellValue = date_format($weekDate, 'd/m/Y');
                        
                        /*if (count($rowLabels) > 1) {
                            //Output a second row label
                            $tableRowOutput .= date_format($weekDate, 'd/m/Y');
                        }*/
                        
                    } else {
                        
                    }
                
                } else {
                    if ($colourCells == 1) {
                    
                        /*
                        if ($reportID == 6) {
                            //$umpireTypeName = $resultRow['umpire_type_name'];
                            $umpireTypeName = $resultOutputArray[$rowCounter][0];
                        } else {
                            $umpireTypeName = NULL;
                        }
                        */
                        //$cellClassToUse = getCellClassNameFromOutputValue($resultRow[$loadedColumnGroupings[$i]["column_name"]], TRUE);
                        $cellClassToUse = getCellClassNameFromOutputValue($resultOutputArray[$rowCounter][$columnCounter], TRUE);
                    } elseif(is_numeric($resultOutputArray[$rowCounter][$columnCounter])) {
                        $cellClassToUse = "cellNumber cellNormal";
                    } else {
                        $cellClassToUse = "cellText cellNormal";
                    }
                    
                    /* TODO: Fix this and find the correct array reference
                    if ($loadedColumnGroupings[$i]["column_name"] == "Total") {
                        $cellClassToUse .= " cellTextTotal";
                    }*/
                    
                    $cellValue = $resultOutputArray[$rowCounter][$columnCounter];
                    
                    
                    /* TODO: Fix this and find the correct array reference
                    */
                    if ($reportID == 5) {
                        if ($columnCounter >= 2) {
                            if ($loadedColumnGroupings[$columnCounter-2]["subtotal"] == "Pct") {
                                $cellValue .= "%";
                            }
                        }
                    }
                    
                    
                    
                }
                
                
                
                //$tableRowOutput .=  "<td class='". $cellClassToUse ."'>" . $cellValue . "</td>";
                
                
                
                
                //$tableRowOutput = $resultOutputArray[$rowCounter][$columnCounter];
                /*
                if (count($rowLabels) > 1) {
                    //Output a second row label
                    $tableRowOutput .= $resultOutputArray[$rowCounter][$columnCounter];
                }
                */
                
            } else {
                $cellClassToUse = "cellNormal";
                $cellValue = "";
            }
            
            if ($reportID == 6 && $columnCounter > 0) {
            
                if ($resultOutputArray[$rowCounter][0] == $loadedColumnGroupings[$columnCounter-1]["second_umpire"]) {
                    //echo "ROW resultOutputArray $rowCounter matches COL loadedColumnGroupings $columnCounter <BR />";
                    $cellClassToUse .= " cellRowMatchesColumn";
                }
            }
            
            $tableRowOutput .= "<td class='$cellClassToUse'>$cellValue</td>";
            
                
        }
        
        
        
        $tableRowOutput .=  "</tr>";
        
        echo $tableRowOutput;
    }

    ?>
    </table>
    </div>
    </div>
<?php 
//End UseNew TRUE
?>
<!--
</div>
</section>
-->
<?php 

} else {

?>


    <!--<section class=''>
    <div class="container">-->
    <div id='panelBelow'>
    <div id='moveLeftDown'>
    <table class='reportTable tableWithFloatingHeader'>
    
    <thead>
    
    <?php 
    //$debugLibrary->debugOutput("test");
    
    if ($debugMode) {
        echo "<BR />rowLabels<pre>";
        print_r($rowLabels);
        echo "</pre><BR />";
        
        echo "<BR />columnLabels<pre>";
        print_r($columnLabels);
        echo "</pre><BR />";
     
    }
    
    //Show one header row for each group
    for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    	?>
    	
    	<tr class='header'>
    	
    	<?php
    	$thOutput;
    	$thClassNameToUse;
    	
    	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
    	    $thClassNameToUse = "columnHeadingNormal cellNameSize";
    	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
    	    $thClassNameToUse = "columnHeadingNormal cellDateSize";
    	} else {
    	    $thClassNameToUse = "columnHeadingNormal cellNameSize";
    	}
    	
    	$arrReportRowGroup = $reportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
    	$arrReportColumnGroup = $reportDisplayOptions->getColumnGroup();
    
    	for ($r=0; $r < count($arrReportRowGroup); $r++) {
        	$thOutput = "<th class='". $thClassNameToUse ."'>";
        	if ($i == 0) {
        	    
        	    $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
        	    $thOutput .= "<BR /><span class='columnSizeText'>". $arrReportRowGroup[$r]->getGroupSizeText() ."</span>";
        	}
        	$thOutput .= "</th>";
        	echo $thOutput;
    	}
    
    	$countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
    	
    	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
    		//Check if cell should be merged
    		if ($j==0) {
    			$proceed = true;
    		} 
    		if ($proceed) {
    			//print cell with colspan value
    			if ($columnLabels[$i] == 'club_name' || $reportID == 6) {
    				//Some reports have their headers displayed differently
    				$colspanForCell = 1;
    				if ($PDFLayout) {
    					//$cellClass = "rotatePDF";
    					$cellClass = "rotated_cell_pdf";
    					$divClass = "rotated_text_pdf";
    					
    				} else {
    					$cellClass = "rotated_cell";
    					$divClass = "rotated_text";
    				}
    			} else {
    			    //Increase the colspan if this column group is to be merged
    			    if ($arrReportColumnGroup[$i]->getMergeField() == 1) {
    			        /*TODO: There is a bug here. When a report has two column headings with the same value,
    			        but they don't need to be grouped (i.e. Report 2, for Seniors and Reservers, should show
    			        Seniors, then Reserves, then Seniors 2 Umpires), this code is grouping it anyway.
    			        This is because it counts the number of values in the heading group, and in this case, 
    			        it finds 2.
    			        How do I fix it? 
    			        Is it better to redesign this "column label" logic to improve the query,
    			        and add this config data into the database?
    			        What config would I need to add? It would need to be specific for a report (i.e. report 2)
    			        Columns in the report could have "merge groups", which are independent of the column labels.
    			        This way, a column with the heading of "Seniors" could be separated from other columns with 
    			        headings of "Seniors".
    			        When I do this, I can also move other report config data to the database.
    			        This would make maintenance easier and clean up the code.
    			        A lot of this information currently sits in UserReport.php.
    			        
    			        Possible Solution:
    			        - Add report parameters to database
    			        - Move this colspan count to a function
    			        - The function would not include the 2 Umpires field in the count of cells
    			        
    			        UPDATE: This has been resolved by updating the report_column.display_order of a column.
    			        Now, the report shows similar columns together. E.g. Seniors BFL, Seniors 2 Umpires, then Reserves BFL.
    			        Instead of Seniors BFL, Reserves BFL, Seniors 2 Umpires.
    			        
    			        */
    			        $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
    			    } else {
    			        $colspanForCell = 1;
    			    }
    				$cellClass = "columnHeadingNormal";
    				$divClass = "normalHeadingText";
    			}
    			echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
    			//TODO: Does a DIV need to go here??
    		}
    	}
    
    	?>
    	</tr>
    	
    <?php	
    }
    echo "</thead>";
    $loopCounter = 0;
    $matchFound = false;
    echo "<tbody>";
    
    //** Loop through rows and output them to the page **
    
    $tableRowOutput = "";
    if ($debugMode) {
        echo "<pre>rowLabels ";
        print_r($rowLabels);
        echo "</pre>";
    }
    
    if ($debugMode) {
        echo "loadedColumnGroupings From SRV:<pre>";
        print_r($loadedColumnGroupings);
        echo "</pre><BR />";
    }
    
    
    foreach ($loadedResultArray as $resultRow): 
    	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
    	   $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
    	   if (count($rowLabels) > 1) {
    	       //Output a second row label
    	       $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
    	   }
    	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
    	    //$weekDate = date_create($resultRow[$rowLabels[0]]);
    	    $weekDate = date_create($resultRow["weekdate"]);
    	    $tableRowOutput = "<tr><td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
    	    if (count($rowLabels) > 1) {
    	       //Output a second row label
    	       $tableRowOutput .= "<td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
    	   }
    	} else {
    	    $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
    	    if (count($rowLabels) > 1) {
    	        //Output a second row label
    	        $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
    	    }
    	}
    	
    	if ($debugMode) {
        	echo "resultRow:<pre>";
        	print_r($resultRow);
        	echo "</pre><BR />";
    	}
    
    	
    	//Loop through each of the columns to be displayed
    	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
    	    if(array_key_exists($loadedColumnGroupings[$i]["column_name"], $resultRow)) { 
    	    
        		if (
        		    ($resultRow[$loadedColumnGroupings[$i]["column_name"]] > 0) ||
        		    (($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== 0) &&
        		     ($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== '0'))
        		    ) {
        		        //Match found for columns. Write record
        		        if ($colourCells == 1) {
        		            if ($reportID == 6) {
        		                $umpireTypeName = $resultRow['umpire_type_name'];
        		            } else {
        		                $umpireTypeName = NULL;
        		            }
        		            $cellClassToUse = getCellClassNameFromOutputValue((int)$resultRow[$loadedColumnGroupings[$i]["column_name"]], TRUE);
        		        } elseif(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
        		            $cellClassToUse = "cellNumber cellNormal";
        		        } else {
        		            $cellClassToUse = "cellText cellNormal";
        		        }
        		        
        		        if ($loadedColumnGroupings[$i]["column_name"] == "Total") {
        		            $cellClassToUse .= " cellTextTotal";
        		        }
        		        $cellValue = $resultRow[$loadedColumnGroupings[$i]["column_name"]];
        		        if (strpos($loadedColumnGroupings[$i]["column_name"],"Pct") !== false) {
        		            $cellValue .= "%";
        		        }
        		} else {
        		    if(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
        		      $cellClassToUse = "cellNumber cellNormal";
        		    } else {
        		      $cellClassToUse = "cellNormal";
        		    }
        		    $cellValue = $noDataValueToDisplay;
        		}
        	} else {
        	    //Array key does not exist
        	    $cellClassToUse = "cellNormal";
        	    $cellValue = $noDataValueToDisplay;
        	}
    		//Add on formatting for cells if matrix values are the same (e.g. row = column)
    		if ($reportID == 6) {
    
    		    if ($resultRow["umpire_name"] == $loadedColumnGroupings[$i]["column_name"]) {
    		        $cellClassToUse .= " cellRowMatchesColumn";
    		    }
    		}
    		
    		$tableRowOutput .=  "<td class='". $cellClassToUse ."'>" . $cellValue . "</td>";
         
    	}    
    	
    	$tableRowOutput .= "</tr>";
    	echo $tableRowOutput;
    		        
    
    endforeach;
    
    echo "</tbody>";
    ?>
    
    </table>
    </div>
    </div>

<?php 
} //end useNew FALSE
?>
<!--
</div>
</section>
-->

