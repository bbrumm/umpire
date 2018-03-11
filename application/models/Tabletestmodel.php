<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Tabletestmodel extends CI_Model
{
    
    var $debugMode;
    var $online = true;
    
    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        
        
        $this->load->model("Umpire_match_record");
    }
    
    public function runAllTests() {
        
        $this->debugMode = $this->config->item('debug_mode');
        $outputArray['tableNames'] = $this->getMissingTableNames();
        $outputArray['missingColumns'] = $this->getMissingColumnsInTables();
        $outputArray['columnDifferences'] = $this->getColumnDifferences();
        
        $outputArray['data_differences'] = $this->getAllDataDifferences();
        
        return $outputArray;
        
    }
    
    /*
    private function refreshTempTables() {
        $queryResultArray = array();
        
        $queryString = "TRUNCATE TABLE test_mi_all;";
        
        $query = $this->db->query($queryString);
        //$query->free_result();
        
        $queryString = "INSERT INTO test_mi_all (match_import_id, season, round, date, competition_name, ground, time, team, umpire_name, umpire_type)
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.field_umpire_1, 'Field'
FROM match_import m
WHERE m.field_umpire_1 IS NOT NULL";
        
        $query = $this->db->query($queryString);
        //$query->free_result();
    }
    */
    
    private function runQueryIntoArray($queryString) {
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
        
    }
    
    private function getMissingTableNames() {
        $queryResultArray = array();
        
        if ($this->online) {
        $queryString = "SELECT t.table_name,
            t.engine,
            t.table_collation
            FROM information_schema.tables t
            WHERE t.table_name NOT IN (
              SELECT r.table_name
              FROM fed_table_list r
            )
            AND t.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN ('tables_to_validate');";
        } else {
        $queryString = "SELECT t.table_name,
            t.engine,
            t.table_collation
            FROM information_schema.tables t
            WHERE t.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            LIMIT 5;";
        }
        return $this->runQueryIntoArray($queryString);
        
    }
    
    private function getMissingColumnsInTables() {
        $queryResultArray = array();
        
        if ($this->online) {
        $queryString = "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            c.is_nullable,
            c.data_type,
            c.character_maximum_length,
            c.numeric_precision,
            c.numeric_scale,
            c.datetime_precision,
            c.character_set_name,
            c.collation_name,
            c.column_type,
            c.column_key,
            c.extra
            FROM information_schema.columns c
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE c.table_schema = 'databas6'
            AND NOT EXISTS (
              SELECT 1
              FROM fed_table_cols r
              WHERE r.table_name = c.table_name
              AND r.column_name = c.column_name
            )
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN ('tables_to_validate')
            ORDER BY c.table_name, c.column_name;";
        } else {
        $queryString = "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            c.is_nullable,
            c.data_type,
            c.character_maximum_length,
            c.numeric_precision,
            c.numeric_scale,
            c.datetime_precision,
            c.character_set_name,
            c.collation_name,
            c.column_type,
            c.column_key,
            c.extra
            FROM information_schema.columns c
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE c.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            LIMIT 5;";
        }
        return $this->runQueryIntoArray($queryString);
        
    }
    
    private function getColumnDifferences() {
        $queryResultArray = array();
        
        if ($this->online) {
         $queryString = "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            r.column_default,
            c.is_nullable,
            r.is_nullable,
            c.data_type,
            r.data_type,
            c.character_maximum_length,
            r.character_maximum_length,
            c.numeric_precision,
            r.numeric_precision,
            c.numeric_scale,
            r.numeric_scale,
            c.datetime_precision,
            r.datetime_precision,
            c.character_set_name,
            r.character_set_name,
            c.collation_name,
            r.collation_name,
            c.column_type,
            r.column_type,
            c.column_key,
            r.column_key,
            c.extra,
            r.extra
            FROM information_schema.columns c
            INNER JOIN fed_table_cols r ON c.table_name = r.table_name AND c.column_name = r.column_name
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE c.table_schema = 'databas6'
            AND (
            	c.column_default != r.column_default OR
                c.is_nullable != r.is_nullable OR
                c.data_type != r.data_type OR
                c.character_maximum_length != r.character_maximum_length OR
                c.numeric_precision != r.numeric_precision OR
                c.numeric_scale != r.numeric_scale OR
                c.datetime_precision != r.datetime_precision OR
                c.character_set_name != r.character_set_name OR
                c.collation_name != r.collation_name OR
                c.column_type != r.column_type OR
                c.column_key != r.column_key OR
                c.extra != r.extra
            )
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN ('tables_to_validate')
            ORDER BY c.table_name, c.column_name;";
        } else {
        $queryString = "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            r.column_default AS column_default_r,
            c.is_nullable,
            r.is_nullable AS is_nullable_r,
            c.data_type,
            r.data_type AS data_type_r,
            c.character_maximum_length,
            r.character_maximum_length AS character_maximum_length_r,
            c.numeric_precision,
            r.numeric_precision AS numeric_precision_r,
            c.numeric_scale,
            r.numeric_scale AS numeric_scale_r,
            c.datetime_precision,
            r.datetime_precision AS datetime_precision_r,
            c.character_set_name,
            r.character_set_name AS character_set_name_r,
            c.collation_name,
            r.collation_name AS collation_name_r,
            c.column_type,
            r.column_type AS column_type_r,
            c.column_key,
            r.column_key AS column_key_r,
            c.extra,
            r.extra AS extra_r
            FROM information_schema.columns c
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            INNER JOIN information_schema.columns r ON c.table_name = r.table_name AND c.column_name = r.column_name
            WHERE c.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            LIMIT 5;";
        }
        return $this->runQueryIntoArray($queryString);
        
    }
    
    
    private function getAllDataDifferences() {
        /*
         This function should start with a list of tables that I provide.
         Then it should look up the columns in each of these tables
         Then use these columns to build a SELECT query that finds the differences in data between two tables (local and remote)
         (perhaps using a NOT EXISTS with a correlated subquery)
         Then run the select query
         Then return the data
         Return should be a single array, with nested arrays, each of which contain the data differences in each table.
         
         This is done so I don't have to specify the columns in each table's query, as well as in the View file.
         It also makes it easier when I create new tables in the future.
         */
        
        $tableDataToValidate = $this->getTableNamesAndColsToValidate();
        $tableNamesAndColumnsToValidate = $this->transformToMultiArray($tableDataToValidate);
        
        //Turn table and columns into SELECT query
        $selectQueryArray = $this->transformTablesAndColsIntoQuery($tableNamesAndColumnsToValidate);
        
        //Run SELECT queries and create array
        $tableDifferencesArray = $this->findDifferencesInTables($selectQueryArray);
        
        return $tableDifferencesArray;
        
        
    }
    
    private function getTableNamesAndColsToValidate() {
        
        $queryString = "SELECT
            t.table_name,
            c.column_name
            FROM tables_to_validate t
            INNER JOIN information_schema.columns c
            ON t.table_name = c.table_name
            ORDER BY t.table_name, c.ordinal_position;";
        //TODO: Add a LIMIT 1 for testing, and remove this LIMIT 1 when I have finished testing
        
        return $this->runQueryIntoArray($queryString);
    }
    
    private function transformToMultiArray($tableDataArray) {
        $tableNameAndColumnArray = array();
        
        for ($i=0; $i < count($tableDataArray); $i++) {
            //Transform a (table)(col) array into a (table) parent and (col) child array
            /*
            echo "<pre>tableDataArray $i:<br />";
            print_r($tableDataArray[$i]);
            echo "</pre>";
            */
            $tableNameAndColumnArray[$tableDataArray[$i]['table_name']][] = $tableDataArray[$i]['column_name'];
            /*
            echo "<pre>tableNameAndColumnArray so far:<br />";
            print_r($tableNameAndColumnArray);
            echo "</pre>";
            */
        }
        
        return $tableNameAndColumnArray;
    }
    
    private function transformTablesAndColsIntoQuery($tablesAndColsArray) {
        foreach ($tablesAndColsArray as $key => $value) {
            //Loop through each table
            $localFocusQueryString = "SELECT ";
            $remoteFocusQueryString = "SELECT ";
            $whereClause = "WHERE ";
            
            for ($i=0; $i < count($tablesAndColsArray[$key]); $i++) {
                //Loop through each column
                $localFocusQueryString .= " l." . $tablesAndColsArray[$key][$i];
                $remoteFocusQueryString .= " r." . $tablesAndColsArray[$key][$i];
                $whereClause .= "IFNULL(l." . $tablesAndColsArray[$key][$i] . ",'') = IFNULL(r." . $tablesAndColsArray[$key][$i] . ",'')";
                
                if ($i < count($tablesAndColsArray[$key]) - 1) {
                    //Not yet last value, add comma
                    $localFocusQueryString .= ",";
                    $remoteFocusQueryString.= ",";
                    $whereClause .= " AND ";
                }
            }
            
            if ($this->online) {
                $localFocusQueryString.= " FROM " . $key . " l WHERE NOT EXISTS (SELECT 1 FROM fed_" . $key . " r " . $whereClause . ")";
                $remoteFocusQueryString.= " FROM fed_" . $key . " r WHERE NOT EXISTS (SELECT 1 FROM " . $key . " l " . $whereClause . ")";
            } else {
                //Add a limit to just get a small sample
                $localFocusQueryString.= " FROM " . $key . " l WHERE NOT EXISTS (SELECT 1 FROM " . $key . " r " . $whereClause . ") LIMIT 5;";
                $remoteFocusQueryString.= " FROM " . $key . " r WHERE NOT EXISTS (SELECT 1 FROM " . $key . " l " . $whereClause . ") LIMIT 5;";
            }
            
            $queryStringArray[$key]['local'] = $localFocusQueryString;
            $queryStringArray[$key]['remote'] = $remoteFocusQueryString;
            
            echo $key . " local: " . $localFocusQueryString . "<br />";
            echo $key . " remote: " . $remoteFocusQueryString . "<br />";
        }
        
        return $queryStringArray;
        
    }
    
    private function findDifferencesInTables($selectQueryArray) {
        foreach ($selectQueryArray AS $key => $value) {
            try {
                $queryResultsArray[$key]['local'] = $this->runQueryIntoArray($value['local']);
                $queryResultsArray[$key]['remote'] = $this->runQueryIntoArray($value['remote']);
            } catch (Exception $e) {
                echo "Caught exception",  $e->getMessage(), "\n";
            
            }
        }
        return $queryResultsArray;
        
    }
    
    
}
?>
        
        