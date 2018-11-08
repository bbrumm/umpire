<?php

interface IFile_loader {

    public function getImportedFilename($pDataArray);

    public function getImportedFilePathAndName($pDataArray);

    public function getMatchImportData();

    public function clearMatchImportTable();

    public function insertMatchImportTable($data);

    public function logImportedFile($importedFilename);

    public function runETLProcedure($season, $importedFileID);

}