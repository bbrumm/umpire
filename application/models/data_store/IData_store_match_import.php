<?php
interface IData_store_match_import
{
    //Match_import
    public function findSeasonToUpdate();

    public function findLatestImportedFile();

    public function runETLProcedure($pSeason, $pImportedFileID);
}