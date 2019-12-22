<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
 * Constants used for application logic
 *
 */


define('PARAM_REGION', 1);
define('PARAM_LEAGUE', 2);
define('PARAM_UMPIRE_DISCIPLINE', 3);
define('PARAM_AGE_GROUP', 4);


define('EMAIL_FROM', 'xxxx@gmail.com');      // e.g. email@example.com
define('EMAIL_BCC', 'xxxx@gmail.com');      // e.g. email@example.com
define('FROM_NAME', 'Umpire Reporting');    // Your system name
define('EMAIL_PASS', 'Your email password'); // Your email password
/*
define('PROTOCOL', 'smtp');                // mail, sendmail, smtp
define('SMTP_HOST', 'smtp.gmail.com');      // your smtp host e.g. smtp.gmail.com
define('SMTP_PORT', '25');                  // your smtp port e.g. 25, 587
define('SMTP_USER', 'Your smtp user');      // your smtp user
define('SMTP_PASS', 'Your smtp password');  // your smtp password
*/
define('PROTOCOL', 'sendmail');                // mail, sendmail, smtp
define('SMTP_HOST', '');      // your smtp host e.g. smtp.gmail.com
define('SMTP_PORT', '');                  // your smtp port e.g. 25, 587
define('SMTP_USER', '');      // your smtp user
define('SMTP_PASS', '');  // your smtp password

define('MAIL_PATH', '/usr/sbin/sendmail');

/*
 * Queries
 * 
 */


define('TEST_QUERY', 'SELECT 1;');
define('GET_MISSING_TABLE_NAMES', "SELECT t.table_name,
            t.engine,
            t.table_collation
            FROM information_schema.tables t
            WHERE t.table_name NOT IN (
              SELECT r.table_name
              FROM fed_table_list r
            )
            AND t.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN (
            'tables_to_validate',
            'log_active_changes',
            'log_privilege_changes',
            'log_role_changes'
            'password_reset_log',
            'password_reset_request'
            );");

define('GET_TABLE_NAMES_OFFLINE', "SELECT t.table_name,
            t.engine,
            t.table_collation
            FROM information_schema.tables t
            WHERE t.table_schema = 'databas6'
            AND t.engine != 'FEDERATED'
            LIMIT 5;");

define('GET_MISSING_COLUMNS', "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            c.is_nullable,
            c.data_type,
            c.character_maximum_length,
            c.numeric_precision,
            c.numeric_scale,
            c.datetime_precision,
            c.character_set_name,
            c.collation_name,
            c.column_type,
            c.column_key,
            c.extra
            FROM information_schema.columns c
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE c.table_schema = 'databas6'
            AND NOT EXISTS (
              SELECT 1
              FROM fed_table_cols r
              WHERE r.table_name = c.table_name
              AND r.column_name = c.column_name
            )
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN ('tables_to_validate')
            ORDER BY c.table_name, c.column_name;");

define('GET_COLUMN_DIFFERENCES', "SELECT
            c.table_name,
            c.column_name,
            c.column_default,
            r.column_default,
            c.is_nullable,
            r.is_nullable,
            c.data_type,
            r.data_type,
            c.character_maximum_length,
            r.character_maximum_length,
            c.numeric_precision,
            r.numeric_precision,
            c.numeric_scale,
            r.numeric_scale,
            c.datetime_precision,
            r.datetime_precision,
            c.character_set_name,
            r.character_set_name,
            c.collation_name,
            r.collation_name,
            c.column_type,
            r.column_type,
            c.column_key,
            r.column_key,
            c.extra,
            r.extra
            FROM information_schema.columns c
            INNER JOIN fed_table_cols r ON c.table_name = r.table_name AND c.column_name = r.column_name
            INNER JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE c.table_schema = 'databas6'
            AND (
            	c.column_default != r.column_default OR
                c.is_nullable != r.is_nullable OR
                c.data_type != r.data_type OR
                c.character_maximum_length != r.character_maximum_length OR
                c.numeric_precision != r.numeric_precision OR
                c.numeric_scale != r.numeric_scale OR
                c.datetime_precision != r.datetime_precision OR
                c.character_set_name != r.character_set_name OR
                c.collation_name != r.collation_name OR
                c.column_type != r.column_type OR
                c.column_key != r.column_key OR
                c.extra != r.extra
            )
            AND t.engine != 'FEDERATED'
            AND t.table_name NOT IN ('tables_to_validate')
            ORDER BY c.table_name, c.column_name;");

define('TABLES_TO_VALIDATE', "SELECT
            t.table_name,
            c.column_name
            FROM tables_to_validate t
            INNER JOIN information_schema.columns c
            ON t.table_name = c.table_name
            ORDER BY t.table_name, c.ordinal_position;");

define('QRY_MISSING_UMPIRES', "SELECT 
            l.ID,
            l.first_name,
            l.last_name,
            l.games_prior,
            l.games_other_leagues,
            'Missing in Remote' AS outcome
        FROM umpire l
        WHERE NOT EXISTS(
            SELECT 1
        	FROM rm_umpire r
        	WHERE l.first_name = r.first_name
        	AND l.last_name = r.last_name
        )
        UNION ALL
        SELECT 
            r.ID,
            r.first_name,
            r.last_name,
            r.games_prior,
            r.games_other_leagues,
            'Missing in Local' AS outcome
        FROM rm_umpire r
        WHERE NOT EXISTS(
            SELECT 1
        	FROM umpire l
        	WHERE l.first_name = r.first_name
        	AND l.last_name = r.last_name
        );");

define('QRY_UMPIRE_DIFF', "SELECT 
            l.ID,
            l.first_name,
            l.last_name,
            l.games_prior,
            l.games_other_leagues,
            r.games_prior,
            r.games_other_leagues
        FROM umpire l
        INNER JOIN rm_umpire r
        	ON  l.first_name = r.first_name
        	AND l.last_name = r.last_name
            WHERE l.games_prior <> r.games_prior
            OR l.games_other_leagues <> r.games_other_leagues;");

define('QRY_MISSING_LEAGUES', "SELECT
    l.league_name,
    l.sponsored_league_name,
    l.short_league_name,
    d.division_name,
    ag.age_group,
    r.region_name,
    c.competition_name,
    'Missing in Remote' AS outcome
    FROM league l
    LEFT JOIN age_group_division agd ON l.age_group_division_id = agd.id
    LEFT JOIN age_group ag ON agd.age_group_id = ag.id
    LEFT JOIN division d ON agd.division_id = d.id
    LEFT JOIN region r ON l.region_id = r.id
    LEFT JOIN competition_lookup c ON c.league_id = l.id
    WHERE NOT EXISTS (
    SELECT 1
    FROM rm_league fl
    	LEFT JOIN rm_age_group_division fagd ON fl.age_group_division_id = fagd.id
    	LEFT JOIN rm_division fd ON fagd.division_id = fd.id
    	LEFT JOIN rm_region fr ON fl.region_id = fr.id
    	LEFT JOIN rm_age_group fag ON fagd.age_group_id = fag.id
    	WHERE IFNULL(l.league_name, '') = IFNULL(fl.league_name, '')
    	AND IFNULL(l.sponsored_league_name, '') = IFNULL(fl.sponsored_league_name, '')
    	AND IFNULL(l.short_league_name, '') = IFNULL(fl.short_league_name, '')
    	AND IFNULL(d.division_name, '') = IFNULL(fd.division_name, '')
    	AND IFNULL(ag.age_group, '') = IFNULL(fag.age_group, '')
    	AND IFNULL(r.region_name, '') = IFNULL(fr.region_name, '')
    )
    UNION ALL
    SELECT
    l.league_name,
    l.sponsored_league_name,
    l.short_league_name,
    d.division_name,
    ag.age_group,
    r.region_name,
    c.competition_name,
    'Missing in Local'
    FROM rm_league l
    LEFT JOIN rm_age_group_division agd ON l.age_group_division_id = agd.id
    LEFT JOIN rm_age_group ag ON agd.age_group_id = ag.id
    LEFT JOIN rm_division d ON agd.division_id = d.id
    LEFT JOIN rm_region r ON l.region_id = r.id
    LEFT JOIN rm_competition_lookup c ON c.league_id = l.id
    WHERE NOT EXISTS (
    SELECT 1
    FROM league fl
    	LEFT JOIN age_group_division fagd ON fl.age_group_division_id = fagd.id
    	LEFT JOIN division fd ON fagd.division_id = fd.id
    	LEFT JOIN region fr ON fl.region_id = fr.id
    	LEFT JOIN age_group fag ON fagd.age_group_id = fag.id
    	WHERE IFNULL(l.league_name, '') = IFNULL(fl.league_name, '')
    	AND IFNULL(l.sponsored_league_name, '') = IFNULL(fl.sponsored_league_name, '')
    	AND IFNULL(l.short_league_name, '') = IFNULL(fl.short_league_name, '')
    	AND IFNULL(d.division_name, '') = IFNULL(fd.division_name, '')
    	AND IFNULL(ag.age_group, '') = IFNULL(fag.age_group, '')
    	AND IFNULL(r.region_name, '') = IFNULL(fr.region_name, '')
);");

define('QRY_LEAGUE_DIFF', "SELECT 
    c.competition_name,
    l.league_name AS league_local,
    rl.league_name AS league_remote,
    l.short_league_name AS short_league_local,
    rl.short_league_name AS short_league_remote,
    d.division_name AS div_local,
    rd.division_name AS div_remote,
    ag.age_group AS age_local,
    rag.age_group AS age_remote,
    r.region_name AS region_local,
    rr.region_name As region_remote
    FROM league l
    INNER JOIN age_group_division agd ON l.age_group_division_id = agd.id
    INNER JOIN age_group ag ON agd.age_group_id = ag.id
    INNER JOIN division d ON agd.division_id = d.id
    INNER JOIN region r ON l.region_id = r.id
    INNER JOIN competition_lookup c ON c.league_id = l.id
    INNER JOIN rm_competition_lookup rc ON c.competition_name = rc.competition_name
    INNER JOIN rm_league rl ON rc.league_id = rl.id
    INNER JOIN rm_age_group_division ragd ON rl.age_group_division_id = ragd.id
    INNER JOIN rm_age_group rag ON ragd.age_group_id = rag.id
    INNER JOIN rm_division rd ON ragd.division_id = rd.id
    INNER JOIN rm_region rr ON rl.region_id = rr.id
    WHERE c.competition_name = rc.competition_name
    AND (l.league_name <> rl.league_name
    OR l.short_league_name <> rl.short_league_name
    OR d.division_name <> rd.division_name
    OR ag.age_group <> rag.age_group
    OR r.region_name <> rr.region_name)");

define('QRY_MISSING_TEAMS', "SELECT 
	    l.ID,
	    l.team_name,
	    'Missing in Remote' AS outcome
	FROM team l
	WHERE NOT EXISTS(
	    SELECT 1
		FROM rm_team r
		WHERE l.team_name = r.team_name
	)
	UNION ALL
	SELECT 
	    r.ID,
	    r.team_name,
	    'Missing in Local'
	FROM rm_team r
	WHERE NOT EXISTS(
	    SELECT 1
		FROM team l
		WHERE l.team_name = r.team_name
	);");

define('QRY_TEAMCLUB_DIFF', "SELECT 
	    lt.ID,
	    lt.team_name,
	    lc.club_name AS local_club_name,
	    rc.club_name AS remote_club_name
	FROM team lt
	LEFT JOIN club lc ON lt.club_id = lc.id
	LEFT JOIN rm_team rt ON lt.team_name = rt.team_name
	LEFT JOIN rm_club rc ON rt.club_id = rc.id
	WHERE lc.club_name <> rc.club_name;");

define('QRY_MISSING_CLUBS', "SELECT 
	    l.ID,
	    l.club_name,
	    'Missing in Remote' AS outcome
	FROM club l
	WHERE NOT EXISTS(
	    SELECT 1
		FROM rm_club r
		WHERE l.club_name = r.club_name
	)
	UNION ALL
	SELECT 
	    r.ID,
	    r.club_name,
	    'Missing in Local'
	FROM rm_club r
	WHERE NOT EXISTS(
	    SELECT 1
		FROM club l
		WHERE l.club_name = r.club_name
	);");

define('QRY_MISSING_COMPETITIONS', "SELECT 
	    l.ID,
	    l.competition_name,
	    l.season_id,
	    l.league_id,
	    'Missing in Remote' AS outcome
	FROM competition_lookup l
	WHERE NOT EXISTS(
	    SELECT 1
		FROM rm_competition_lookup r
		WHERE l.competition_name = r.competition_name
	)
	UNION ALL
	SELECT 
	    r.ID,
	    r.competition_name,
	    r.season_id,
	    r.league_id,
	    'Missing in Local'
	FROM rm_competition_lookup r
	WHERE NOT EXISTS(
	    SELECT 1
		FROM competition_lookup l
		WHERE l.competition_name = r.competition_name
	);");

define('QRY_MISSING_GROUNDS', "SELECT 
	    l.ID,
	    l.main_name,
	    'Missing in Remote' AS outcome
	FROM ground l
	WHERE NOT EXISTS(
	    SELECT 1
		FROM rm_ground r
		WHERE l.main_name = r.main_name
	)
	UNION ALL
	SELECT 
	    r.ID,
	    r.main_name,
	    'Missing in Local'
	FROM rm_ground r
	WHERE NOT EXISTS(
	    SELECT 1
		FROM ground l
		WHERE l.main_name = r.main_name
	);");

define('QRY_MISSING_AGEGROUPS', "SELECT 
	    l.ID,
	    l.age_group,
	    l.display_order,
	    'Missing in Remote' AS outcome
	FROM age_group l
	WHERE NOT EXISTS(
	    SELECT 1
		FROM rm_age_group r
		WHERE l.age_group = r.age_group
	)
	UNION ALL
	SELECT 
	    r.ID,
	    r.age_group,
	    r.display_order,
	    'Missing in Local'
	FROM rm_age_group r
	WHERE NOT EXISTS(
	    SELECT 1
		FROM age_group l
		WHERE l.age_group = r.age_group);");

define("QRY_MV01_DIFF", "SELECT 
	l.last_first_name,
	l.short_league_name,
	l.club_name,
	l.age_group,
	l.region_name,
	l.umpire_type,
	l.season_year,
	l.match_count AS local_count,
	r.match_count AS remote_count
	FROM dw_mv_report_01 l
	INNER JOIN rm_dw_mv_report_01 r
	ON l.last_first_name = r.last_first_name
	AND l.short_league_name = r.short_league_name
	AND l.club_name = r.club_name
	AND l.age_group = r.age_group
	AND l.region_name = r.region_name
	AND l.umpire_type = r.umpire_type
	AND l.season_year = r.season_year
	WHERE l.match_count <> r.match_count;");

define("QRY_MV02_DIFF", "SELECT
	l.last_first_name,
	l.short_league_name,
	l.age_group,
	l.age_sort_order,
	l.league_sort_order,
	l.two_ump_flag,
	l.region_name,
	l.umpire_type,
	l.season_year,
	l.match_count AS local_count,
	r.match_count AS remote_count
	FROM dw_mv_report_02 l
	INNER JOIN rm_dw_mv_report_02 r
	ON l.last_first_name = r.last_first_name
	AND l.short_league_name = r.short_league_name
	AND l.age_group = r.age_group
	AND l.age_sort_order = r.age_sort_order
	AND l.league_sort_order = r.league_sort_order
	AND l.two_ump_flag = r.two_ump_flag
	AND l.region_name = r.region_name
	AND l.umpire_type =r.umpire_type
	AND l.season_year = r.season_year
	WHERE l.match_count <> r.match_count;");

define("QRY_MV04_DIFF", "SELECT
	l.club_name,
	l.age_group,
	l.short_league_name,
	l.region_name,
	l.umpire_type,
	l.age_sort_order,
	l.league_sort_order,
	l.season_year,
	l.match_count AS local_count,
	r.match_count AS remote_count
	FROM dw_mv_report_04 l
	INNER JOIN rm_dw_mv_report_04 r
	ON l.club_name = r.club_name
	AND l.age_group = r.age_group
	AND l.short_league_name = r.short_league_name
	AND l.region_name = r.region_name
	AND l.umpire_type =r.umpire_type
	AND l.age_sort_order = r.age_sort_order
	AND l.league_sort_order = r.league_sort_order
	AND l.season_year = r.season_year
	WHERE l.match_count <> r.match_count;");

define("QRY_MV05_DIFF", "SELECT
	l.umpire_type,
	l.age_group,
	l.age_sort_order,
	l.short_league_name,
	l.league_sort_order,
	l.region_name,
	l.season_year,
	l.match_no_ump AS local_match_no_ump,
	r.match_no_ump AS remote_match_no_ump,
	l.total_match_count AS local_total_mc,
	r.total_match_count AS remote_total_mc,
	l.match_pct AS local_match_pct,
	r.match_pct As remote_match_pct
	FROM dw_mv_report_05 l
	INNER JOIN rm_dw_mv_report_05 r
	ON l.umpire_type =r.umpire_type
	AND l.age_group = r.age_group
	AND l.age_sort_order = r.age_sort_order
	AND l.short_league_name = r.short_league_name
	AND l.league_sort_order = r.league_sort_order
	AND l.season_year = r.season_year
	WHERE l.match_no_ump <> r.match_no_ump
	OR l.total_match_count <> r.total_match_count
	OR l.match_pct <> r.match_pct;");

define("QRY_MV06_DIFF", "SELECT
	l.umpire_type,
	l.age_group,
	l.region_name,
	l.first_umpire,
	l.second_umpire,
	l.season_year,
	l.short_league_name,
	l.match_count AS local_match_count,
	r.match_count AS remote_match_count
	FROM dw_mv_report_06 l
	INNER JOIN rm_dw_mv_report_06 r
	ON l.umpire_type =r.umpire_type
	AND l.age_group = r.age_group
	AND l.region_name = r.region_name
	AND l.first_umpire = r.first_umpire
	AND l.second_umpire = r.second_umpire
	AND l.season_year = r.season_year
	AND l.short_league_name = r.short_league_name
	WHERE l.match_count <> r.match_count;");

define("QRY_MV07_DIFF", "SELECT
	l.umpire_type,
	l.age_group,
	l.short_league_name,
	l.season_year,
	l.age_sort_order,
	l.league_sort_order,
	l.umpire_count,
	l.match_count AS local_count,
	r.match_count AS remote_count
	FROM dw_mv_report_07 l
	INNER JOIN rm_dw_mv_report_07 r
	ON l.umpire_type =r.umpire_type
	AND l.age_group = r.age_group
	AND l.short_league_name = r.short_league_name
	AND l.season_year = r.season_year
	AND l.age_sort_order = r.age_sort_order
	AND l.league_sort_order = r.league_sort_order
	AND l.umpire_count = r.umpire_count
	WHERE l.match_count <> r.match_count;");

define("QRY_MV08_DIFF", "SELECT
	l.season_year,
	l.last_name,
	l.first_name,
	l.match_count AS local_count,
	r.match_count AS remote_count,
	l.total_match_count AS local_total_count,
	r.total_match_count AS remote_total_count
	FROM dw_mv_report_08 l
	INNER JOIN rm_dw_mv_report_08 r
	ON l.season_year = r.season_year
	AND l.full_name = r.full_name
	AND l.last_name = r.last_name
	AND l.first_name = r.first_name
	WHERE l.match_count <> r.match_count;");

/*
 * Constants for external SQL files
 *
 */

define ("SQL_REPORT_FILE_PATH", "application/models/separate_reports/");