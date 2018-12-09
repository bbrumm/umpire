<?php
require_once 'IFile_loader.php';

class File_loader_import extends CI_Model implements IFile_loader
{

    public function getImportedFilename($pDataArray) {
        return $pDataArray['upload_data']['file_name'];
    }

    public function getImportedFilePathAndName($pDataArray) {
        return "application/import/" . $pDataArray['upload_data']['file_name'];
    }

    public function getMatchImportData() {

    }

    public function clearMatchImportTable() {
        /*
         * No trailing semicolon on DELETE queries, because CI adds a WHERE 1=1 to it.
         * See mysqli_driver: protected function _prep_query (line 318)
         */
        $queryString = "DELETE FROM match_import";
        $query = $this->db->query($queryString);
    }

    public function insertMatchImportTable($data, $pColumnArray) {
        return $this->db->insert_batch('match_import', $data);
    }

    public function logImportedFile($importedFilename) {
        $session_data = $this->session->userdata('logged_in');
        $username = $session_data['username'];

        $data = array(
            'filename' => $importedFilename,
            'imported_datetime' => date('Y-m-d H:i:s', time()),
            'imported_user_id' => $username,
            'etl_status' => 2 //2 = not yet started
        );

        $queryStatus = $this->db->insert('imported_files', $data);
        return $this->db->insert_id();
    }

    public function runETLProcedure($pDataStore, $season, $importedFileID) {
        $etlProc = new Run_etl_stored_proc();
        $etlProc->runETLProcedure($pDataStore, $season, $importedFileID);
    }



}