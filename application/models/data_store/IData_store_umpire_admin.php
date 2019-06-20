<?php

interface IData_store_umpire_admin {

    public function getAllUmpiresAndValues();

    public function updateUmpireRecords($pUmpireArray);

    public function logUmpireGamesHistory($pChangedUmpireArray);

    public function updateDimUmpireTable();

    public function updateMVReport8Table();


}
