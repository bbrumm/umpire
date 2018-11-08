<?php
require_once 'IFile_loader.php';

class File_loader_test extends CI_Model implements IFile_loader
{

    private $matchImportArray = [];

    public function getImportedFilename($pDataArray) {
        return $pDataArray['upload_data']['file_name'];
    }

    public function getImportedFilePathAndName($pDataArray) {
        return "application/tests/import/" . $pDataArray['upload_data']['file_name'];
    }

    public function getMatchImportData() {
        return $this->matchImportArray;
    }

    public function clearMatchImportTable() {
        $this->matchImportArray = null;
    }

    public function insertMatchImportTable($data) {
        //Set an array on this object to be equal to the $data value
        $this->matchImportArray = $data;
        return true;
    }

    public function logImportedFile($importedFilename) {

    }

    public function runETLProcedure($season, $importedFileID) {

    }

}