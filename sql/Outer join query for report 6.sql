	SELECT 
    umpire_type_name,
    age_group,
    first_umpire,
    second_umpire,
    match_id
FROM
    mv_report_06_staging
WHERE
    umpire_type_name = 'Field'
        AND age_group = 'Seniors'
ORDER BY first_umpire , second_umpire;



SELECT 
f.first_umpire,
f.first_umpire as second_umpire_forced,
COUNT(s.match_id)
FROM mv_report_06_staging f
LEFT OUTER JOIN mv_report_06_staging s ON (f.first_umpire = s.first_umpire AND f.second_umpire = s.second_umpire)
GROUP BY f.first_umpire, s.second_umpire
ORDER BY f.first_umpire, s.second_umpire;

/*
Find a list of umpires that apply to the selected criteria
Use this umpire list for both first and second umpire
Then, find the COUNT of matches where the first matches the first and second matches the second



*/

        

SELECT u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name, COUNT(s.match_id)
FROM mv_umpire_list u1
INNER JOIN mv_umpire_list u2 ON u1.umpire_type_name = u2.umpire_type_name AND u1.age_group = u2.age_group
LEFT OUTER JOIN mv_report_06_staging s ON u1.umpire_name = s.first_umpire AND u2.umpire_name = s.second_umpire
GROUP BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name
ORDER BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name;



SELECT umpire_type_name, age_group, first_umpire, second_umpire, match_count
FROM mv_report_06;



SELECT first_umpire, second_umpire, match_count 
FROM mv_report_06 WHERE age_group = 'Seniors' AND umpire_type_name = 'Field';


SELECT u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name, COUNT(s.match_id)
FROM mv_umpire_list u1
INNER JOIN mv_umpire_list u2 ON u1.umpire_type_name = u2.umpire_type_name AND u1.age_group = u2.age_group
LEFT OUTER JOIN mv_report_06_staging s ON u1.umpire_name = s.first_umpire AND u2.umpire_name = s.second_umpire
GROUP BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name
ORDER BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name;
