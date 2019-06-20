<?php

interface IData_store_reference {

    public function getRoleArray();

    public function getReportArray();

    public function getRegionArray();

    public function getUmpireDisciplineArray();

    public function getAgeGroupArray();

    public function getLeagueArray();

    public function getPermissionSelectionArray();

    public function loadPossibleLeaguesForComp();

    public function loadPossibleClubsForTeam();

    public function loadPossibleRegions();

    public function loadPossibleAgeGroups();

    public function loadPossibleShortLeagueNames();

    //Missing_data_updater
    public function loadPossibleDivisions();

}