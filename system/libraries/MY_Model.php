<?php
class MY_Model extends CI_Model {
 
   function __construct()
    {
          parent::__construct();
    }
 
    /**
     * A method to facilitate easy bulk inserts into a given table.
     * @param string $table_name
     * @param array $column_names A basic array containing the column names
     *  of the data we'll be inserting
     * @param array $rows A two dimensional array of rows to insert into the
     *  database.
     * @param bool $escape Whether or not to escape data
     *  that will be inserted. Default = true.
     * @author Kenny Katzgrau 
     */
    function insert_rows($table_name, $column_names, $rows, $escape = true)
    {
        /* Build a list of column names */
        $columns    = array_walk($column_names, array($this, 'prepare_column_name') );
        $columns    = implode(',', $column_names);
 
        /* Escape each value of the array for insertion into the SQL string */
        if( $escape ) array_walk_recursive( $rows, array( $this, 'escape_value' ) );
		
		//Convert empty strings to NULL
		array_walk_recursive( $rows, array( $this, 'convertToNullValue' ) );
		
 
        /* Collapse each rows of values into a single string */
        $length = count($rows);
        for($i = 0; $i < $length; $i++) {
			$rows[$i] = implode(',', $rows[$i]);
		}
 
        /* Collapse all the rows into something that looks like
         *  (r1_val_1, r1_val_2, ..., r1_val_n),
         *  (r2_val_1, r2_val_2, ..., r2_val_n),
         *  ...
         *  (rx_val_1, rx_val_2, ..., rx_val_n)
         * Stored in $values
         */
        $values = "(" . implode( '),(', $rows ) . ")";
 
		//echo "test 1<BR />";
		
		//echo $values . "<BR />";
 
        $sql = "INSERT INTO $table_name ( $columns ) VALUES $values";
 
		//echo $sql . "<BR />";
 
        return $this->db->simple_query($sql);
		
		
		
    }
 
    private function escape_value(& $value)
    {
        if( is_string($value) )
        {
            $value = $this->db->escape($value);
        }
    }
 
    private function prepare_column_name(& $name)
    {
        $name = "`$name`";
    }
	
	private function convertToNullValue(& $value) {
		if ($value == '') {
			$value = 'NULL';
		}
	}
}
?>