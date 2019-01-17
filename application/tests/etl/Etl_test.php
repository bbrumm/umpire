<?php
class Etl_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Array_library');
        $this->arrayLibrary = new Array_library();
        //Connect to the ci_prod database in the "testing/database" file
        $this->db = $this->CI->load->database('ci_prod', TRUE);
        //This connects to the localhost database
        $this->dbLocal = $this->CI->load->database('default', TRUE);
        //$this->importData();
    }

    private function importData() {
        $filename = "2018_Appointments_Master 2018_08_08.xls";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );

        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
    }

    public function test_MatchImportToMV01() {
        $queryStringMatchImport = "SELECT 
umpire_full_name,
umpire_type,
short_league_name,
age_group,
region_name,
club_name,
COUNT(*) AS match_count
FROM (
	SELECT
	CONCAT(
		RIGHT(m.field_umpire_1,LENGTH(m.field_umpire_1)-InStr(m.field_umpire_1,' ')),
		', ',
		LEFT(m.field_umpire_1,InStr(m.field_umpire_1,' ')-1)
	) AS umpire_full_name,
	'Field' AS umpire_type,
    m.competition_name,
    m.home_team AS team_name
	FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.field_umpire_2,LENGTH(m.field_umpire_2)-InStr(m.field_umpire_2,' ')),
		', ',
		LEFT(m.field_umpire_2,InStr(m.field_umpire_2,' ')-1)
	),
	'Field',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.field_umpire_3,LENGTH(m.field_umpire_3)-InStr(m.field_umpire_3,' ')),
		', ',
		LEFT(m.field_umpire_3,InStr(m.field_umpire_3,' ')-1)
	),
	'Field',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_1,LENGTH(m.boundary_umpire_1)-InStr(m.boundary_umpire_1,' ')),
		', ',
		LEFT(m.boundary_umpire_1,InStr(m.boundary_umpire_1,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_2,LENGTH(m.boundary_umpire_2)-InStr(m.boundary_umpire_2,' ')),
		', ',
		LEFT(m.boundary_umpire_2,InStr(m.boundary_umpire_2,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_3,LENGTH(m.boundary_umpire_3)-InStr(m.boundary_umpire_3,' ')),
		', ',
		LEFT(m.boundary_umpire_3,InStr(m.boundary_umpire_3,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_4,LENGTH(m.boundary_umpire_4)-InStr(m.boundary_umpire_4,' ')),
		', ',
		LEFT(m.boundary_umpire_4,InStr(m.boundary_umpire_4,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_5,LENGTH(m.boundary_umpire_5)-InStr(m.boundary_umpire_5,' ')),
		', ',
		LEFT(m.boundary_umpire_5,InStr(m.boundary_umpire_5,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_6,LENGTH(m.boundary_umpire_6)-InStr(m.boundary_umpire_6,' ')),
		', ',
		LEFT(m.boundary_umpire_6,InStr(m.boundary_umpire_6,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.goal_umpire_1,LENGTH(m.goal_umpire_1)-InStr(m.goal_umpire_1,' ')),
		', ',
		LEFT(m.goal_umpire_1,InStr(m.goal_umpire_1,' ')-1)
	),
	'Goal',
	m.competition_name,
    m.home_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.goal_umpire_2,LENGTH(m.goal_umpire_2)-InStr(m.goal_umpire_2,' ')),
		', ',
		LEFT(m.goal_umpire_2,InStr(m.goal_umpire_2,' ')-1)
	),
	'Goal',
	m.competition_name,
    m.home_team
    FROM match_import m
    UNION ALL
    SELECT
	CONCAT(
		RIGHT(m.field_umpire_1,LENGTH(m.field_umpire_1)-InStr(m.field_umpire_1,' ')),
		', ',
		LEFT(m.field_umpire_1,InStr(m.field_umpire_1,' ')-1)
	) AS umpire_full_name,
	'Field' AS umpire_type,
    m.competition_name,
    m.away_team
	FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.field_umpire_2,LENGTH(m.field_umpire_2)-InStr(m.field_umpire_2,' ')),
		', ',
		LEFT(m.field_umpire_2,InStr(m.field_umpire_2,' ')-1)
	),
	'Field',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.field_umpire_3,LENGTH(m.field_umpire_3)-InStr(m.field_umpire_3,' ')),
		', ',
		LEFT(m.field_umpire_3,InStr(m.field_umpire_3,' ')-1)
	),
	'Field',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_1,LENGTH(m.boundary_umpire_1)-InStr(m.boundary_umpire_1,' ')),
		', ',
		LEFT(m.boundary_umpire_1,InStr(m.boundary_umpire_1,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_2,LENGTH(m.boundary_umpire_2)-InStr(m.boundary_umpire_2,' ')),
		', ',
		LEFT(m.boundary_umpire_2,InStr(m.boundary_umpire_2,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_3,LENGTH(m.boundary_umpire_3)-InStr(m.boundary_umpire_3,' ')),
		', ',
		LEFT(m.boundary_umpire_3,InStr(m.boundary_umpire_3,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_4,LENGTH(m.boundary_umpire_4)-InStr(m.boundary_umpire_4,' ')),
		', ',
		LEFT(m.boundary_umpire_4,InStr(m.boundary_umpire_4,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_5,LENGTH(m.boundary_umpire_5)-InStr(m.boundary_umpire_5,' ')),
		', ',
		LEFT(m.boundary_umpire_5,InStr(m.boundary_umpire_5,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.boundary_umpire_6,LENGTH(m.boundary_umpire_6)-InStr(m.boundary_umpire_6,' ')),
		', ',
		LEFT(m.boundary_umpire_6,InStr(m.boundary_umpire_6,' ')-1)
	),
	'Boundary',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.goal_umpire_1,LENGTH(m.goal_umpire_1)-InStr(m.goal_umpire_1,' ')),
		', ',
		LEFT(m.goal_umpire_1,InStr(m.goal_umpire_1,' ')-1)
	),
	'Goal',
	m.competition_name,
    m.away_team
    FROM match_import m
	UNION ALL
	SELECT
	CONCAT(
		RIGHT(m.goal_umpire_2,LENGTH(m.goal_umpire_2)-InStr(m.goal_umpire_2,' ')),
		', ',
		LEFT(m.goal_umpire_2,InStr(m.goal_umpire_2,' ')-1)
	),
	'Goal',
	m.competition_name,
    m.away_team
    FROM match_import m
) AS sub

LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
LEFT JOIN league l ON c.league_id = l.ID
LEFT JOIN region r ON l.region_id = r.id
LEFT JOIN team t ON sub.team_name = t.team_name
LEFT JOIN club hc ON t.club_id = hc.id
LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
LEFT JOIN age_group a ON agd.age_group_id = a.id

WHERE umpire_full_name IS NOT NULL
GROUP BY umpire_full_name, umpire_type, short_league_name, age_group, region_name, club_name
ORDER BY umpire_full_name, short_league_name, club_name, age_group, region_name, umpire_type";

        $queryStringDW = "SELECT d.last_first_name,
d.umpire_type,
d.short_league_name,
d.age_group,
d.region_name,
d.club_name,
d.match_count
FROM dw_mv_report_01 d
WHERE d.season_year = 2018
ORDER BY d.last_first_name, d.short_league_name, d.club_name, d.age_group, d.region_name, d.umpire_type;";

        $queryMatchImport =$this->dbLocal->query($queryStringMatchImport);
        $queryDW =$this->dbLocal->query($queryStringDW);

        $resultArrayMatchImport = $queryMatchImport->result();
        $resultArrayDW = $queryDW->result();

        //$arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayMatchImport, $resultArrayDW, 'season_year');
        $arrayDifferences = $this->arrayLibrary->findMultiArrayDiff($resultArrayMatchImport, $resultArrayDW);
        $this->assertEmpty($arrayDifferences);
        //$this->assertEquals()
        $this->dbLocal->close();


    }



}