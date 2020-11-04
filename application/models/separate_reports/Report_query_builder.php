<?php
class Report_query_builder extends CI_Model {

    public function __construct() {

    }

    private function replaceBindVariables($sqlQuery, $pReportInstance) {
        $sqlQuery = $this->replaceRegionInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceLeagueInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceSeasonYearInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceAgeGroupInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance);
        return $sqlQuery;
    }

    private function replaceRegionInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pRegion", $pReportInstance->filterParameterRegion->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceLeagueInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pLeague", $pReportInstance->filterParameterLeague->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceSeasonYearInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pSeasonYear", $pReportInstance->requestedReport->getSeason(), $sqlQuery);
    }

    private function replaceAgeGroupInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pAgeGroup", $pReportInstance->filterParameterAgeGroup->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pUmpireType", $pReportInstance->filterParameterUmpireType->getFilterSQLValues(), $sqlQuery);
    }

    public function constructReportQuery($pSQLFilename, $pReportInstance) {
        $sqlQuery = file_get_contents(SQL_REPORT_FILE_PATH . $pSQLFilename);
        $sqlQuery = $this->replaceBindVariables($sqlQuery, $pReportInstance);
        return $sqlQuery;
    }
}