<?php
$db['local'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'database' => 'databas6',
    'dbdriver' => 'mysqli'
);

$db['remote'] = array(
    'hostname' => 'localhost',
    'username' => 'databas6_ump',
    'password' => 'q9l1fterBB',
    'database' => 'databas6_umpire_data',
    'dbdriver' => 'mysqli'
);


//Connect to database local
$conn = new mysqli(
    $db['local']['hostname'], 
    $db['local']['username'], 
    $db['local']['password']
);

if ($conn->connect_errno) {
    printf("Connection failed: %s\n", $conn->connect_error);
}
//Connect to database remote


//Query table names
/*
$queryString = "SELECT t.table_name,
t.engine,
t.table_collation
FROM information_schema.tables t
WHERE table_schema = 'databas6'
ORDER BY t.table_name;";

$resultLocal = $conn->query($queryString);

while($row = $resultLocal->fetch_array())
{
    $resultArrayLocal[] = $row;
}
*/

/*
echo "<pre>ResultArrayLocal";
print_r($resultArrayLocal);
echo "</pre>";
*/


/*
$queryString = "SELECT t.table_name,
t.engine,
t.table_collation
FROM information_schema.tables t
WHERE table_schema != 'databas6'
ORDER BY t.table_name;";

$resultRemote = $conn->query($queryString);

while($row = $resultRemote->fetch_array())
{
    $resultArrayRemote[] = $row;
}
*/

/*
echo "<pre>ResultArrayRemote";
print_r($resultArrayRemote);
echo "</pre>";
*/


$queryString = "SELECT t.table_name,
t.engine,
t.table_collation,
r.engine AS remote_engine,
r.table_collation AS remote_table_collation
FROM information_schema.tables t
LEFT JOIN fed_table_list r
ON t.table_name = r.table_name
WHERE t.table_schema = 'databas6'
AND t.engine != 'FEDERATED';";

$resultTableQuery = $conn->query($queryString);


echo "<pre>resultTableQuery";
print_r($resultTableQuery);
echo "</pre>";

/*
while($row = $resultTableQuery->fetch_array())
{
    $resultArrayTableList[] = $row;
}
*/


/*
$resultTablesRemote = array(
    [1, 'apple'],
    [3, 'orange']
);
*/


//$resultTableDifferences = array();

//$resultTableDifferences = array_diff($resultArrayLocal, $resultArrayRemote);


/*
 echo "<pre>resultArrayTableList";
 print_r($resultArrayTableList);
 echo "</pre>";
 */


//Find differences
/*
for($i=0; $i < count($resultTablesLocal); $i++) {
    if($resultTablesLocal[$i] != $resultTablesRemote[$i]) {
        //Single record in results don't match. Append to array
        echo "Difference found: " . $i . "<BR />"; 
        $resultTableDifferences[] = $i;
    } else {
        echo "Same found: " . $i . "<BR />";
    }
}
*/



//Output differences
echo "<table border=1>";
//Output columns
/*
echo "<tr>";
for ($columnHeaderIncrement=0; $columnHeaderIncrement < count($resultArrayTableList[0]); $columnHeaderIncrement++) {
    echo "<td>" . "Column Local" . "</td>";
    echo "<td>" . "Column Remote". "</td>";
    
}
echo "</tr>";
*/

//Loop through rows and columns
/*
for($rowIncrement=0; $rowIncrement< count($resultArrayTableList); $rowIncrement++) {
    echo "<tr>";
    for($columnIncrement=0; $columnIncrement< count($resultArrayTableList[$columnIncrement]); $columnIncrement++) {
        echo "<td>" . $resultArrayTableList[$rowIncrement][$columnIncrement] . "</td>";
    }
    echo "</tr>";
    
}
*/
echo "</table>";

//Close connection
//$result->close();


mysqli_close($conn);


