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

        //Uncomment this when I run the full suite
        $this->importData();
    }

    private function importData() {
        $filename = "2018_Appointments_Master 2018_08_08 Dedupe.xls";
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
        $queryStringDWNotInMatchImport = "
SELECT q1.*
FROM (
	SELECT d.last_first_name,
	d.umpire_type,
	d.short_league_name,
	d.age_group,
	d.region_name,
	d.club_name,
	d.match_count
	FROM dw_mv_report_01 d
	WHERE d.season_year = 2018
) q1
LEFT JOIN (
	SELECT 
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
) q2
ON  q1.last_first_name = q2.umpire_full_name
AND q1.umpire_type = q2.umpire_type
AND q1.short_league_name = q2.short_league_name
AND q1.age_group = q2.age_group
AND q1.region_name = q2.region_name
AND q1.club_name = q2.club_name
AND q1.match_count = q2.match_count
WHERE q2.umpire_full_name IS NULL;";

        $queryStringMatchImportNotInDW = "
SELECT q1.*
FROM (
	SELECT d.last_first_name,
	d.umpire_type,
	d.short_league_name,
	d.age_group,
	d.region_name,
	d.club_name,
	d.match_count
	FROM dw_mv_report_01 d
	WHERE d.season_year = 2018
) q1
RIGHT JOIN (
	SELECT 
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
) q2
ON  q1.last_first_name = q2.umpire_full_name
AND q1.umpire_type = q2.umpire_type
AND q1.short_league_name = q2.short_league_name
AND q1.age_group = q2.age_group
AND q1.region_name = q2.region_name
AND q1.club_name = q2.club_name
AND q1.match_count = q2.match_count
WHERE q1.last_first_name IS NULL;";

        $queryDWNotInMatchImport =$this->dbLocal->query($queryStringDWNotInMatchImport);
        $queryMatchImportNotInDW =$this->dbLocal->query($queryStringMatchImportNotInDW);

        $resultArrayDWNotInMatchImport = $queryDWNotInMatchImport->result();
        $resultArrayMatchImportNotInDW = $queryMatchImportNotInDW->result();

        $this->assertEquals(0, count($resultArrayDWNotInMatchImport));
        $this->assertEquals(0, count($resultArrayMatchImportNotInDW));


    }

    public function test_MatchImportToMV02ExcludingTwoGames() {
        $queryStringDWNotInMatchImport = "SELECT q1.*
FROM (
	SELECT last_first_name,
	short_league_name,
	age_group,
	age_sort_order,
	league_sort_order,
	region_name,
	umpire_type,
	match_count,
	season_year
	FROM dw_mv_report_02
	WHERE season_year = 2018
	AND two_ump_flag = 0
) q1
LEFT JOIN (
	SELECT 
	umpire_full_name,
	l.short_league_name,
	age_group,
	a.display_order AS age_sort_order,
	sl.display_order AS league_sort_order,
	region_name,
	umpire_type,
	COUNT(*) AS match_count,
	2018 AS season_year
	FROM (
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_1,LENGTH(m.field_umpire_1)-InStr(m.field_umpire_1,' ')),
			', ',
			LEFT(m.field_umpire_1,InStr(m.field_umpire_1,' ')-1)
		) AS umpire_full_name,
		'Field' AS umpire_type,
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_2,LENGTH(m.field_umpire_2)-InStr(m.field_umpire_2,' ')),
			', ',
			LEFT(m.field_umpire_2,InStr(m.field_umpire_2,' ')-1)
		),
		'Field',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_3,LENGTH(m.field_umpire_3)-InStr(m.field_umpire_3,' ')),
			', ',
			LEFT(m.field_umpire_3,InStr(m.field_umpire_3,' ')-1)
		),
		'Field',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_1,LENGTH(m.boundary_umpire_1)-InStr(m.boundary_umpire_1,' ')),
			', ',
			LEFT(m.boundary_umpire_1,InStr(m.boundary_umpire_1,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_2,LENGTH(m.boundary_umpire_2)-InStr(m.boundary_umpire_2,' ')),
			', ',
			LEFT(m.boundary_umpire_2,InStr(m.boundary_umpire_2,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_3,LENGTH(m.boundary_umpire_3)-InStr(m.boundary_umpire_3,' ')),
			', ',
			LEFT(m.boundary_umpire_3,InStr(m.boundary_umpire_3,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_4,LENGTH(m.boundary_umpire_4)-InStr(m.boundary_umpire_4,' ')),
			', ',
			LEFT(m.boundary_umpire_4,InStr(m.boundary_umpire_4,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_5,LENGTH(m.boundary_umpire_5)-InStr(m.boundary_umpire_5,' ')),
			', ',
			LEFT(m.boundary_umpire_5,InStr(m.boundary_umpire_5,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_6,LENGTH(m.boundary_umpire_6)-InStr(m.boundary_umpire_6,' ')),
			', ',
			LEFT(m.boundary_umpire_6,InStr(m.boundary_umpire_6,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.goal_umpire_1,LENGTH(m.goal_umpire_1)-InStr(m.goal_umpire_1,' ')),
			', ',
			LEFT(m.goal_umpire_1,InStr(m.goal_umpire_1,' ')-1)
		),
		'Goal',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.goal_umpire_2,LENGTH(m.goal_umpire_2)-InStr(m.goal_umpire_2,' ')),
			', ',
			LEFT(m.goal_umpire_2,InStr(m.goal_umpire_2,' ')-1)
		),
		'Goal',
		m.competition_name
		FROM match_import m
	) AS sub
	LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
	LEFT JOIN league l ON c.league_id = l.ID
	LEFT JOIN region r ON l.region_id = r.id
	LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
	LEFT JOIN age_group a ON agd.age_group_id = a.id
	LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
	WHERE umpire_full_name IS NOT NULL
	GROUP BY umpire_full_name, umpire_type, l.short_league_name, a.age_group, region_name
) q2
ON  q1.last_first_name = q2.umpire_full_name
AND q1.short_league_name = q2.short_league_name
AND q1.age_group = q2.age_group
AND q1.age_sort_order = q2.age_sort_order
AND q1.league_sort_order = q2.league_sort_order
AND q1.region_name = q2.region_name
AND q1.umpire_type = q2.umpire_type
AND q1.match_count = q2.match_count
AND q1.season_year = q2.season_year
WHERE q2.umpire_full_name IS NULL;";

        $queryStringMatchImportNotInDW = "SELECT q1.*
FROM (
	SELECT last_first_name,
	short_league_name,
	age_group,
	age_sort_order,
	league_sort_order,
	region_name,
	umpire_type,
	match_count,
	season_year
	FROM dw_mv_report_02
	WHERE season_year = 2018
	AND two_ump_flag = 0
) q1
RIGHT JOIN (
	SELECT 
	umpire_full_name,
	l.short_league_name,
	age_group,
	a.display_order AS age_sort_order,
	sl.display_order AS league_sort_order,
	region_name,
	umpire_type,
	COUNT(*) AS match_count,
	2018 AS season_year
	FROM (
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_1,LENGTH(m.field_umpire_1)-InStr(m.field_umpire_1,' ')),
			', ',
			LEFT(m.field_umpire_1,InStr(m.field_umpire_1,' ')-1)
		) AS umpire_full_name,
		'Field' AS umpire_type,
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_2,LENGTH(m.field_umpire_2)-InStr(m.field_umpire_2,' ')),
			', ',
			LEFT(m.field_umpire_2,InStr(m.field_umpire_2,' ')-1)
		),
		'Field',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.field_umpire_3,LENGTH(m.field_umpire_3)-InStr(m.field_umpire_3,' ')),
			', ',
			LEFT(m.field_umpire_3,InStr(m.field_umpire_3,' ')-1)
		),
		'Field',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_1,LENGTH(m.boundary_umpire_1)-InStr(m.boundary_umpire_1,' ')),
			', ',
			LEFT(m.boundary_umpire_1,InStr(m.boundary_umpire_1,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_2,LENGTH(m.boundary_umpire_2)-InStr(m.boundary_umpire_2,' ')),
			', ',
			LEFT(m.boundary_umpire_2,InStr(m.boundary_umpire_2,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_3,LENGTH(m.boundary_umpire_3)-InStr(m.boundary_umpire_3,' ')),
			', ',
			LEFT(m.boundary_umpire_3,InStr(m.boundary_umpire_3,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_4,LENGTH(m.boundary_umpire_4)-InStr(m.boundary_umpire_4,' ')),
			', ',
			LEFT(m.boundary_umpire_4,InStr(m.boundary_umpire_4,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_5,LENGTH(m.boundary_umpire_5)-InStr(m.boundary_umpire_5,' ')),
			', ',
			LEFT(m.boundary_umpire_5,InStr(m.boundary_umpire_5,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.boundary_umpire_6,LENGTH(m.boundary_umpire_6)-InStr(m.boundary_umpire_6,' ')),
			', ',
			LEFT(m.boundary_umpire_6,InStr(m.boundary_umpire_6,' ')-1)
		),
		'Boundary',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.goal_umpire_1,LENGTH(m.goal_umpire_1)-InStr(m.goal_umpire_1,' ')),
			', ',
			LEFT(m.goal_umpire_1,InStr(m.goal_umpire_1,' ')-1)
		),
		'Goal',
		m.competition_name
		FROM match_import m
		UNION ALL
		SELECT
		CONCAT(
			RIGHT(m.goal_umpire_2,LENGTH(m.goal_umpire_2)-InStr(m.goal_umpire_2,' ')),
			', ',
			LEFT(m.goal_umpire_2,InStr(m.goal_umpire_2,' ')-1)
		),
		'Goal',
		m.competition_name
		FROM match_import m
	) AS sub
	LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
	LEFT JOIN league l ON c.league_id = l.ID
	LEFT JOIN region r ON l.region_id = r.id
	LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
	LEFT JOIN age_group a ON agd.age_group_id = a.id
	LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
	WHERE umpire_full_name IS NOT NULL
	GROUP BY umpire_full_name, umpire_type, l.short_league_name, a.age_group, region_name
) q2
ON  q1.last_first_name = q2.umpire_full_name
AND q1.short_league_name = q2.short_league_name
AND q1.age_group = q2.age_group
AND q1.age_sort_order = q2.age_sort_order
AND q1.league_sort_order = q2.league_sort_order
AND q1.region_name = q2.region_name
AND q1.umpire_type = q2.umpire_type
AND q1.match_count = q2.match_count
AND q1.season_year = q2.season_year
WHERE q2.last_first_name IS NULL;";

        $queryDWNotInMatchImport =$this->dbLocal->query($queryStringDWNotInMatchImport);
        $queryMatchImportNotInDW =$this->dbLocal->query($queryStringMatchImportNotInDW);

        $resultArrayDWNotInMatchImport = $queryDWNotInMatchImport->result();
        $resultArrayMatchImportNotInDW = $queryMatchImportNotInDW->result();

        $this->assertEquals(0, count($resultArrayDWNotInMatchImport));
        $this->assertEquals(0, count($resultArrayMatchImportNotInDW));

        $this->dbLocal->close();
    }

    public function test_MatchImportToMV04() {
        $queryStringDWNotInMatchImport = "SELECT q1.*
        FROM (
            SELECT
            club_name,
            age_group,
            short_league_name,
            region_name,
            umpire_type,
            age_sort_order,
            league_sort_order,
            match_count
            FROM dw_mv_report_04
            WHERE season_year = 2018
        ) q1 LEFT JOIN (
            SELECT
            cl.club_name,
            a.age_group,
            sl.short_league_name,
            r.region_name,
            sub.umpire_type,
            a.display_order AS age_sort_order,
            sl.display_order AS league_sort_order,
            COUNT(DISTINCT sub.id) AS match_count
            FROM (
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team_name,
                'Field' AS umpire_type
                FROM match_import m
                WHERE m.field_umpire_1 IS NULL
                AND m.field_umpire_2 IS NULL
                AND m.field_umpire_3 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Field'
                FROM match_import m
                WHERE m.field_umpire_1 IS NULL
                AND m.field_umpire_2 IS NULL
                AND m.field_umpire_3 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team,
                'Boundary'
                FROM match_import m
                WHERE m.boundary_umpire_1 IS NULL
                AND m.boundary_umpire_2 IS NULL
                AND m.boundary_umpire_3 IS NULL
                AND m.boundary_umpire_4 IS NULL
                AND m.boundary_umpire_5 IS NULL
                AND m.boundary_umpire_6 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Boundary'
                FROM match_import m
                WHERE m.boundary_umpire_1 IS NULL
                AND m.boundary_umpire_2 IS NULL
                AND m.boundary_umpire_3 IS NULL
                AND m.boundary_umpire_4 IS NULL
                AND m.boundary_umpire_5 IS NULL
                AND m.boundary_umpire_6 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team,
                'Goal'
                FROM match_import m
                WHERE m.goal_umpire_1 IS NULL
                AND m.goal_umpire_2 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Goal'
                FROM match_import m
                WHERE m.goal_umpire_1 IS NULL
                AND m.goal_umpire_2 IS NULL
                AND season = 2018
            ) AS sub
            LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
            LEFT JOIN league l ON c.league_id = l.ID
            LEFT JOIN region r ON l.region_id = r.id
            LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            LEFT JOIN age_group a ON agd.age_group_id = a.id
            LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
            LEFT JOIN team t ON sub.team_name = t.team_name
            LEFT JOIN club cl ON t.club_id = cl.id
            GROUP BY cl.club_name, a.age_group, sl.short_league_name, r.region_name, sub.umpire_type, a.display_order, sl.display_order
        ) q2
        ON q1.club_name = q2.club_name
        AND q1.age_group = q2.age_group
        AND q1.short_league_name = q2.short_league_name
        AND q1.region_name = q2.region_name
        AND q1.umpire_type = q2.umpire_type
        AND q1.match_count = q2.match_count
        WHERE q2.club_name IS NULL;";

        $queryStringMatchImportNotInDW = "SELECT q1.*
        FROM (
            SELECT
            club_name,
            age_group,
            short_league_name,
            region_name,
            umpire_type,
            age_sort_order,
            league_sort_order,
            match_count
            FROM dw_mv_report_04
            WHERE season_year = 2018
        ) q1 RIGHT JOIN (
            SELECT
            cl.club_name,
            a.age_group,
            sl.short_league_name,
            r.region_name,
            sub.umpire_type,
            a.display_order AS age_sort_order,
            sl.display_order AS league_sort_order,
            COUNT(DISTINCT sub.id) AS match_count
            FROM (
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team_name,
                'Field' AS umpire_type
                FROM match_import m
                WHERE m.field_umpire_1 IS NULL
                AND m.field_umpire_2 IS NULL
                AND m.field_umpire_3 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Field'
                FROM match_import m
                WHERE m.field_umpire_1 IS NULL
                AND m.field_umpire_2 IS NULL
                AND m.field_umpire_3 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team,
                'Boundary'
                FROM match_import m
                WHERE m.boundary_umpire_1 IS NULL
                AND m.boundary_umpire_2 IS NULL
                AND m.boundary_umpire_3 IS NULL
                AND m.boundary_umpire_4 IS NULL
                AND m.boundary_umpire_5 IS NULL
                AND m.boundary_umpire_6 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Boundary'
                FROM match_import m
                WHERE m.boundary_umpire_1 IS NULL
                AND m.boundary_umpire_2 IS NULL
                AND m.boundary_umpire_3 IS NULL
                AND m.boundary_umpire_4 IS NULL
                AND m.boundary_umpire_5 IS NULL
                AND m.boundary_umpire_6 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.home_team AS team,
                'Goal'
                FROM match_import m
                WHERE m.goal_umpire_1 IS NULL
                AND m.goal_umpire_2 IS NULL
                AND season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                m.away_team AS team,
                'Goal'
                FROM match_import m
                WHERE m.goal_umpire_1 IS NULL
                AND m.goal_umpire_2 IS NULL
                AND season = 2018
            ) AS sub
            LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
            LEFT JOIN league l ON c.league_id = l.ID
            LEFT JOIN region r ON l.region_id = r.id
            LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            LEFT JOIN age_group a ON agd.age_group_id = a.id
            LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
            LEFT JOIN team t ON sub.team_name = t.team_name
            LEFT JOIN club cl ON t.club_id = cl.id
            GROUP BY cl.club_name, a.age_group, sl.short_league_name, r.region_name, sub.umpire_type, a.display_order, sl.display_order
        ) q2
        ON q1.club_name = q2.club_name
        AND q1.age_group = q2.age_group
        AND q1.short_league_name = q2.short_league_name
        AND q1.region_name = q2.region_name
        AND q1.umpire_type = q2.umpire_type
        AND q1.match_count = q2.match_count
        WHERE q1.club_name IS NULL;";

        $queryDWNotInMatchImport =$this->dbLocal->query($queryStringDWNotInMatchImport);
        $queryMatchImportNotInDW =$this->dbLocal->query($queryStringMatchImportNotInDW);

        $resultArrayDWNotInMatchImport = $queryDWNotInMatchImport->result();
        $resultArrayMatchImportNotInDW = $queryMatchImportNotInDW->result();

        $this->assertEquals(0, count($resultArrayDWNotInMatchImport));
        $this->assertEquals(0, count($resultArrayMatchImportNotInDW));

        $this->dbLocal->close();
    }

    public function test_MatchImportToMV05() {
        $queryStringDWNotInMatchImport = "SELECT q1.*
        FROM (
            SELECT t.umpire_type, t.age_group, t.short_league_name, t.region_name, t.match_no_ump, t.total_match_count, t.match_pct
            FROM dw_mv_report_05 t
            WHERE t.season_year = 2018
        ) q1 LEFT JOIN (
            SELECT
            sub.umpire_type,
            a.age_group,
            sl.short_league_name,
            r.region_name,
            SUM(sub.missing_umps) AS match_no_ump,
            COUNT(DISTINCT sub.id) AS total_match_count,
            FLOOR(SUM(sub.missing_umps) / COUNT(DISTINCT sub.id) * 100) AS match_pct
            FROM (
                SELECT
                m.id,
                m.competition_name,
                'Field' AS umpire_type,
                CASE WHEN m.field_umpire_1 IS NULL
                    AND m.field_umpire_2 IS NULL
                    AND m.field_umpire_3 IS NULL
                    THEN 1 ELSE 0 END AS missing_umps
                FROM match_import m
                WHERE season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                'Boundary',
                CASE WHEN m.boundary_umpire_1 IS NULL
                    AND m.boundary_umpire_2 IS NULL
                    AND m.boundary_umpire_3 IS NULL
                    AND m.boundary_umpire_4 IS NULL
                    AND m.boundary_umpire_5 IS NULL
                    AND m.boundary_umpire_6 IS NULL
                    THEN 1 ELSE 0 END
                FROM match_import m
                WHERE season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                'Goal',
                CASE WHEN m.goal_umpire_1 IS NULL
                    AND m.goal_umpire_2 IS NULL
                    THEN 1 ELSE 0 END
                FROM match_import m
                WHERE season = 2018
            ) AS sub
            LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
            LEFT JOIN league l ON c.league_id = l.ID
            LEFT JOIN region r ON l.region_id = r.id
            LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            LEFT JOIN age_group a ON agd.age_group_id = a.id
            LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
            GROUP BY a.age_group, sl.short_league_name, r.region_name, sub.umpire_type
        ) q2
        ON q1.umpire_type = q2.umpire_type
        AND q1.age_group = q2.age_group
        AND q1.short_league_name = q2.short_league_name
        AND q1.region_name = q2.region_name
        AND q1.match_no_ump = q2.match_no_ump
        AND q1.total_match_count = q2.total_match_count
        AND q1.match_pct = q2.match_pct
        WHERE q2.umpire_type IS NULL;";

        $queryStringMatchImportNotInDW = "SELECT q1.*
        FROM (
            SELECT t.umpire_type, t.age_group, t.short_league_name, t.region_name, t.match_no_ump, t.total_match_count, t.match_pct
            FROM dw_mv_report_05 t
            WHERE t.season_year = 2018
        ) q1 RIGHT JOIN (
            SELECT
            sub.umpire_type,
            a.age_group,
            sl.short_league_name,
            r.region_name,
            SUM(sub.missing_umps) AS match_no_ump,
            COUNT(DISTINCT sub.id) AS total_match_count,
            FLOOR(SUM(sub.missing_umps) / COUNT(DISTINCT sub.id) * 100) AS match_pct
            FROM (
                SELECT
                m.id,
                m.competition_name,
                'Field' AS umpire_type,
                CASE WHEN m.field_umpire_1 IS NULL
                    AND m.field_umpire_2 IS NULL
                    AND m.field_umpire_3 IS NULL
                    THEN 1 ELSE 0 END AS missing_umps
                FROM match_import m
                WHERE season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                'Boundary',
                CASE WHEN m.boundary_umpire_1 IS NULL
                    AND m.boundary_umpire_2 IS NULL
                    AND m.boundary_umpire_3 IS NULL
                    AND m.boundary_umpire_4 IS NULL
                    AND m.boundary_umpire_5 IS NULL
                    AND m.boundary_umpire_6 IS NULL
                    THEN 1 ELSE 0 END
                FROM match_import m
                WHERE season = 2018
                UNION ALL
                SELECT
                m.id,
                m.competition_name,
                'Goal',
                CASE WHEN m.goal_umpire_1 IS NULL
                    AND m.goal_umpire_2 IS NULL
                    THEN 1 ELSE 0 END
                FROM match_import m
                WHERE season = 2018
            ) AS sub
            LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
            LEFT JOIN league l ON c.league_id = l.ID
            LEFT JOIN region r ON l.region_id = r.id
            LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            LEFT JOIN age_group a ON agd.age_group_id = a.id
            LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
            GROUP BY a.age_group, sl.short_league_name, r.region_name, sub.umpire_type
        ) q2
        ON q1.umpire_type = q2.umpire_type
        AND q1.age_group = q2.age_group
        AND q1.short_league_name = q2.short_league_name
        AND q1.region_name = q2.region_name
        AND q1.match_no_ump = q2.match_no_ump
        AND q1.total_match_count = q2.total_match_count
        AND q1.match_pct = q2.match_pct
        WHERE q1.umpire_type IS NULL;";

        $queryDWNotInMatchImport =$this->dbLocal->query($queryStringDWNotInMatchImport);
        $queryMatchImportNotInDW =$this->dbLocal->query($queryStringMatchImportNotInDW);

        $resultArrayDWNotInMatchImport = $queryDWNotInMatchImport->result();
        $resultArrayMatchImportNotInDW = $queryMatchImportNotInDW->result();

        $this->assertEquals(0, count($resultArrayDWNotInMatchImport));
        $this->assertEquals(0, count($resultArrayMatchImportNotInDW));

        $this->dbLocal->close();
    }

    public function test_MatchImportToMV06() {
        $queryStringTempTable1 = "CREATE TEMPORARY TABLE umpire_list_rpt6
SELECT
	sub.id,
	sub.umpire_type,
	a.age_group,
	r.region_name,
	sl.short_league_name,
	CONCAT(RIGHT(sub.umpire_name,Length(sub.umpire_name)-InStr(sub.umpire_name,' ')),', ',LEFT(sub.umpire_name,InStr(sub.umpire_name,' ')-1)) AS umpire_name
	FROM (
		SELECT 
		m.id, m.competition_name, 'Field' AS umpire_type, m.field_umpire_1 AS umpire_name
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Field', m.field_umpire_2
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Field', m.field_umpire_3
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_1
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_2
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_3
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_4
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_5
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_6
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Goal', m.goal_umpire_1
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Goal', m.goal_umpire_2
		FROM match_import m
	) AS sub
	LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
	LEFT JOIN league l ON c.league_id = l.ID
	LEFT JOIN region r ON l.region_id = r.id
	LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
	LEFT JOIN age_group a ON agd.age_group_id = a.id
	LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
	WHERE sub.umpire_name IS NOT NULL;";

        $queryStringTempTable2 = "CREATE TEMPORARY TABLE umpire_list_rpt6_b
SELECT
	sub.id,
	sub.umpire_type,
	a.age_group,
	r.region_name,
	sl.short_league_name,
	CONCAT(RIGHT(sub.umpire_name,Length(sub.umpire_name)-InStr(sub.umpire_name,' ')),', ',LEFT(sub.umpire_name,InStr(sub.umpire_name,' ')-1)) AS umpire_name
	FROM (
		SELECT 
		m.id, m.competition_name, 'Field' AS umpire_type, m.field_umpire_1 AS umpire_name
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Field', m.field_umpire_2
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Field', m.field_umpire_3
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_1
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_2
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_3
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_4
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_5
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Boundary', m.boundary_umpire_6
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Goal', m.goal_umpire_1
		FROM match_import m
		UNION ALL
		SELECT m.id, m.competition_name, 'Goal', m.goal_umpire_2
		FROM match_import m
	) AS sub
	LEFT JOIN competition_lookup c ON sub.competition_name = c.competition_name
	LEFT JOIN league l ON c.league_id = l.ID
	LEFT JOIN region r ON l.region_id = r.id
	LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.ID
	LEFT JOIN age_group a ON agd.age_group_id = a.id
	LEFT JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
	WHERE sub.umpire_name IS NOT NULL;";

        $queryStringDWNotInMatchImport = "SELECT q1.*
        FROM (
            SELECT t.umpire_type, t.age_group, t.region_name, t.short_league_name, t.first_umpire, t.second_umpire, t.match_count
            FROM dw_mv_report_06 t
            WHERE t.season_year = 2018
        ) q1 LEFT JOIN (
            SELECT
            ump1.umpire_type,
            ump1.age_group,
            ump1.region_name,
            ump1.short_league_name,
            ump1.umpire_name AS first_umpire,
            ump2.umpire_name AS second_umpire,
            COUNT(*) AS match_count
            FROM umpire_list_rpt6 AS ump1
            INNER JOIN umpire_list_rpt6_b AS ump2  
            ON ump1.id = ump2.id
            AND ump1.umpire_type = ump2.umpire_type
            AND ump1.age_group = ump2.age_group
            AND ump1.region_name = ump2.region_name
            AND ump1.short_league_name = ump2.short_league_name
            AND ump1.umpire_name <> ump2.umpire_name
            GROUP BY ump1.umpire_type, ump1.age_group, ump1.region_name, ump1.short_league_name, ump1.umpire_name, ump2.umpire_name
        ) q2
        ON q1.umpire_type = q2.umpire_type
        AND q1.age_group = q2.age_group
        AND q1.region_name = q2.region_name
        AND q1.short_league_name = q2.short_league_name
        AND q1.first_umpire = q2.first_umpire
        AND q1.second_umpire = q2.second_umpire
        AND q1.match_count = q2.match_count
        WHERE q2.umpire_type IS NULL;";


        $queryStringMatchImportNotInDW = "SELECT q1.*
        FROM (
            SELECT t.umpire_type, t.age_group, t.region_name, t.short_league_name, t.first_umpire, t.second_umpire, t.match_count
            FROM dw_mv_report_06 t
            WHERE t.season_year = 2018
        ) q1 RIGHT JOIN (
            SELECT
            ump1.umpire_type,
            ump1.age_group,
            ump1.region_name,
            ump1.short_league_name,
            ump1.umpire_name AS first_umpire,
            ump2.umpire_name AS second_umpire,
            COUNT(*) AS match_count
            FROM umpire_list_rpt6 AS ump1
            INNER JOIN umpire_list_rpt6_b AS ump2  
            ON ump1.id = ump2.id
            AND ump1.umpire_type = ump2.umpire_type
            AND ump1.age_group = ump2.age_group
            AND ump1.region_name = ump2.region_name
            AND ump1.short_league_name = ump2.short_league_name
            AND ump1.umpire_name <> ump2.umpire_name
            GROUP BY ump1.umpire_type, ump1.age_group, ump1.region_name, ump1.short_league_name, ump1.umpire_name, ump2.umpire_name
        ) q2
        ON q1.umpire_type = q2.umpire_type
        AND q1.age_group = q2.age_group
        AND q1.region_name = q2.region_name
        AND q1.short_league_name = q2.short_league_name
        AND q1.first_umpire = q2.first_umpire
        AND q1.second_umpire = q2.second_umpire
        AND q1.match_count = q2.match_count
        WHERE q1.umpire_type IS NULL;";

        //Create and populate temp tables first
        $this->dbLocal->query($queryStringTempTable1);
        $this->dbLocal->query($queryStringTempTable2);

        $queryDWNotInMatchImport =$this->dbLocal->query($queryStringDWNotInMatchImport);
        $queryMatchImportNotInDW =$this->dbLocal->query($queryStringMatchImportNotInDW);

        $resultArrayDWNotInMatchImport = $queryDWNotInMatchImport->result();
        $resultArrayMatchImportNotInDW = $queryMatchImportNotInDW->result();

        $this->assertEquals(0, count($resultArrayDWNotInMatchImport));
        $this->assertEquals(0, count($resultArrayMatchImportNotInDW));

        $this->dbLocal->close();
    }

    //Deliberately not testing reports 7 and 8. Tests from the other reports should identify any issues with these

    public function test_DisplayReport01() {
        $postArray = array(
            'reportName'=>'1',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>01 - Umpires and Clubs (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport02() {
        $postArray = array(
            'reportName'=>'2',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>02 - Umpire Names by League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport03() {
        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport04() {
        $postArray = array(
            'reportName'=>'4',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>04 - Summary by Club (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport05() {
        $postArray = array(
            'reportName'=>'5',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>05 - Games with Zero Umpires For Each League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport06() {
        $postArray = array(
            'reportName'=>'6',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>06 - Umpire Pairing (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport07() {
        $postArray = array(
            'reportName'=>'7',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>07 - Games with 2 or 3 Field Umpires (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport08() {
        $postArray = array(
            'reportName'=>'8',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>08 - Umpire Games Tally</h1>";
        $this->assertContains($expected, $output);
    }


}