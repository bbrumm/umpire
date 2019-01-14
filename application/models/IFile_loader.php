<?php

interface IFile_loader {

    public function getImportedFilename($pDataArray);

    public function getImportedFilePathAndName($pDataArray);

    public function clearMatchImportTable();

    public function insertMatchImportTable($data, $pColumnArray);

    public function logImportedFile($importedFilename);

    public function runETLProcedure($pDataStore, $season, $importedFileID);

}