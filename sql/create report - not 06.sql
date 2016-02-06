--Query for report (qryUmpireNames)

SELECT a.umpire_type_name, a.ID, a.age_group, a.short_league_name, a.full_name, a.CountOfID
FROM qryUmpireNamesOnly a
UNION ALL SELECT b.umpire_type_name, b.ID, b.age_group.age_group, b.dummy_league, b.full_name, b.CountOfID
FROM qryUmpireNamesTwoMatches b;



--qryUmpireNamesOnly

SELECT umpire_type.umpire_type_name, age_group.ID, age_group.age_group, league.short_league_name, [last_name] & ", " & [first_name] AS full_name, Count(match.ID) AS CountOfID
FROM umpire
INNER JOIN (umpire_type 
INNER JOIN (umpire_name_type 
INNER JOIN (((age_group 
INNER JOIN (age_group_division 
INNER JOIN league ON age_group_division.ID = league.age_group_division_id) 
ON age_group.ID = age_group_division.age_group_id) 
INNER JOIN round ON league.ID = round.league_id) 
INNER JOIN ([match] 
INNER JOIN umpire_name_type_match ON match.ID = umpire_name_type_match.match_id) 
ON round.ID = match.round_id) 
ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id) 
ON umpire_type.ID = umpire_name_type.umpire_type_id) 
ON umpire.ID = umpire_name_type.umpire_id
GROUP BY umpire_type.umpire_type_name, age_group.ID, age_group.age_group, league.short_league_name, [last_name] & ", " & [first_name]
ORDER BY [last_name] & ", " & [first_name];



--qryUmpireNamesTwoMatches

SELECT umpire_type.umpire_type_name, age_group.ID, age_group.age_group, "2 Umpires" AS dummy_league, [last_name] & ", " & [first_name] AS full_name, Count(match.ID) AS CountOfID
FROM umpire 
INNER JOIN (umpire_type 
INNER JOIN (umpire_name_type 
INNER JOIN (((age_group 
INNER JOIN (age_group_division 
INNER JOIN league ON age_group_division.ID = league.age_group_division_id) 
ON age_group.ID = age_group_division.age_group_id) 
INNER JOIN round ON league.ID = round.league_id) 
INNER JOIN (([match] 
INNER JOIN qryMatchesWithTwoUmpires ON match.ID = qryMatchesWithTwoUmpires.match_id) 
INNER JOIN umpire_name_type_match ON match.ID = umpire_name_type_match.match_id) 
ON round.ID = match.round_id) 
ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id) 
ON umpire_type.ID = umpire_name_type.umpire_type_id) 
ON umpire.ID = umpire_name_type.umpire_id
GROUP BY umpire_type.umpire_type_name, age_group.ID, age_group.age_group, "2 Umpires", [last_name] & ", " & [first_name]
HAVING (((umpire_type.umpire_type_name)="Field") AND ((age_group.age_group)="Seniors"))
ORDER BY [last_name] & ", " & [first_name];


--qryUmpireNamesOnly Improved for MySQL
SELECT umpire_type.umpire_type_name, age_group.ID, age_group.age_group, league.short_league_name, CONCAT(last_name, ", ", first_name) AS full_name, Count(match_played.ID) AS CountOfID
FROM umpire
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire_name_type_match ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN match_played ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
GROUP BY umpire_type.umpire_type_name, age_group.ID, age_group.age_group, league.short_league_name, CONCAT(last_name, ", ", first_name)
ORDER BY CONCAT(last_name, ", ", first_name);