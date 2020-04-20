<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Report_UI_test extends TestCase
{

    const LEAGUE_BFL = "BFL";
    const LEAGUE_GFL = "GFL";
    const LEAGUE_GDFL = "GDFL";
    const LEAGUE_GJFL = "GJFL";
    const LEAGUE_CDFNL = "CDFNL";
    const LEAGUE_WOMEN = "Women";

    const UMP_TYPE_FIELD = "Field";
    const UMP_TYPE_BOUNDARY = "Boundary";
    const UMP_TYPE_GOAL = "Goal";

    const AGE_GROUP_SENIORS = "Seniors";
    const AGE_GROUP_RESERVES = "Reserves";
    const AGE_GROUP_COLTS = "Colts";
    const AGE_GROUP_U19 = "Under 19";
    const AGE_GROUP_U18 = "Under 18";
    const AGE_GROUP_U175 = "Under 17.5";
    const AGE_GROUP_U17 = "Under 17";
    const AGE_GROUP_U16 = "Under 16";
    const AGE_GROUP_U15 = "Under 15";
    const AGE_GROUP_U145 = "Under 14.5";
    const AGE_GROUP_U14 = "Under 14";
    const AGE_GROUP_U13 = "Under 13";
    const AGE_GROUP_U12 = "Under 12";
    const AGE_GROUP_U19G = "Under 19 Girls";
    const AGE_GROUP_U18G = "Under 18 Girls";
    const AGE_GROUP_U15G = "Under 15 Girls";
    const AGE_GROUP_U12G = "Under 12 Girls";
    const AGE_GROUP_YG = "Youth Girls";
    const AGE_GROUP_JG = "Junior Girls";


    const REGION_GEELONG = "Geelong";
    const REGION_COLAC = "Colac";
    const YEAR_2015 = 2015;
    const YEAR_2017 = 2017;
    const YEAR_2018 = 2018;

    const EXPECTED_DATA_REPORT1_TEST1 = array(
        array('Abbott, Trevor', 1, '', '', '', '', '', '', '', '', 1),
        array('Abrehart, Jack', 1, '', '', 1, 1, '', 1, '', '', ''),
        array('Abrehart, Tom', '', 1, '', 1, '', '', '', '', '', 2),
        array('Arnott, Tim', '', '', '', 1, '', '', 1, '', '', ''),
        array('Baensch, Darren', 1, 2, '', 1, 2, '', '', '', 1, 1),
        array('Barton, Lawrie', 2, 4, 4, 2, 3, 1, 2, 1, '', 1),
        array('Bennett, Ross', 2, 2, 1, 2, 1, 1, '', '', '', 1),
        array('Beveridge, Brendan', '', '', 1, '', '', '', '', 1, '', ''),
        array('Binyon, Callum', '', '', 1, '', '', '', '', '', 1, ''),
        array('Boom, Marcus', 1, '', 2, '', 1, 1, 2, 1, 1, 1),
        array('Brown, Joshua', 1, 1, 1, 2, '', 1, 1, 1, 2, ''),
        array('Burke, Luke', '', 1, '', 1, '', 1, '', '', 1, 2),
        array('Bury, Nathan', 1, '', 1, '', '', '', 2, '', '', ''),
        array('Bushfield, Mark', '', 1, '', '', 1, '', '', '', '', ''),
        array('Callander, Timothy', 1, 1, 1, '', '', 1, 1, 1, '', ''),
        array('Carruthers, Christopher', '', 1, 3, 1, 1, '', '', 1, '', 1),
        array('Chaston, David', 3, 1, '', '', 1, '', 2, 1, 1, 3),
        array('Chaston, Samuel', '', 3, '', 1, '', 1, 2, 1, '', ''),
        array('Crucitti, Jess', '', '', 1, '', 1, 1, '', '', 1, 2),
        array('Danaher, Ryan', 1, '', 1, '', 2, 1, 1, 1, 1, ''),
        array('Davison, Mark', '', 1, '', 3, '', 1, 2, '', 1, ''),
        array('Delaney, Emma', 1, 1, 1, '', 1, 1, 2, 1, 1, 1),
        array('Denney, Joshua', 2, 1, 1, '', 1, 1, 1, 1, 1, 1),
        array('Dorling, Daniel', '', '', 2, 1, 2, 1, '', '', '', ''),
        array('Elliott, Peter', '', '', 1, 1, 1, 1, '', 1, 1, 2),
        array('Fanning, Murray', '', 1, '', '', 1, '', '', '', '', ''),
        array('Formosa, Jason', '', '', 1, 1, '', '', 1, 1, '', ''),
        array('Frazer, David', '', '', 1, 1, '', 1, 3, 1, '', 1),
        array('Gahan, Darren', 1, 1, '', 2, '', 1, '', 3, '', ''),
        array('Gray, Mitch', '', '', 1, '', '', '', 1, 1, 1, ''),
        array('Grossman, Graham', '', '', '', '', '', '', 1, '', 1, ''),
        array('Groves, Sam', 1, 1, 2, '', 1, 1, 1, '', 2, 1),
        array('Guarnaccia, Steve', '', 1, '', 1, '', '', '', '', '', ''),
        array('Harrison, William', '', 1, 3, '', '', '', 1, 1, '', ''),
        array('Jones, Adam', '', '', '', 1, 2, 1, '', '', 1, 1),
        array('Keating, Steve', 1, '', 1, '', 1, 1, '', 3, 1, ''),
        array('Kosmetschke, Luke', 2, '', 1, '', 1, '', '', 3, 1, 2),
        array('Kozina, Josip', '', '', '', 1, '', '', '', 1, '', ''),
        array('Lunt, Jordan', 3, 1, '', 2, 2, '', 2, 3, 4, 3),
        array('Maddock, David', 1, 1, '', 2, '', '', '', 1, 1, 2),
        array('Mccarthy, Luke', '', '', 1, 1, 3, '', '', 1, '', ''),
        array('Mcdonald, Stephen', '', 1, 1, 2, 1, '', 1, '', '', 4),
        array('Mcdowell, Paul', '', 1, 1, '', '', '', '', '', '', ''),
        array('Mcelhinney, Paul', '', 2, '', 3, 1, 1, 1, '', '', 4),
        array('Mckenzie, Rodney', 3, 1, 1, '', 2, 1, 1, '', 1, ''),
        array('Mcmaster, Damian', 3, 3, 1, '', 1, 1, 2, '', '', 1),
        array('Milligan, James', '', 1, '', '', 2, 1, 1, '', '', 1),
        array('Murnane, Nicholas', 1, '', '', '', 1, '', 1, '', '', 1),
        array('Noble, Reece', '', 1, '', 1, 1, 1, 1, 1, '', ''),
        array('Nolan, Mark', 2, 3, 2, 4, 2, 1, 1, 5, 3, 1),
        array('Oldfield, Craig', 1, '', '', '', '', 2, 1, 2, '', 2),
        array('Palmer, Anthony', 1, '', '', '', '', '', '', '', '', 1),
        array('Parrello, Dean', 1, 2, '', 2, '', 2, 2, 1, '', ''),
        array('Place, Cameron', 1, 3, 1, 1, 2, 3, 2, '', 4, 3),
        array('Reid, Davin', '', 2, '', 2, '', '', '', '', '', 2),
        array('Riches, Aaron', '', '', '', 1, '', '', '', '', '', 1),
        array('Robertson, Joshua', 1, '', 2, 1, '', 1, 2, 2, 1, ''),
        array('Rooke, Lachlan', 1, '', 1, 1, 1, 1, 1, 1, 1, 2),
        array('Ross-watson, Nicholas', 1, 2, '', '', '', 1, 2, 1, 3, ''),
        array('Smith, Bradley', 2, '', 2, 3, 2, 2, '', 1, 1, 1),
        array('Stephenson, Adrian', '', 3, '', 2, '', '', 2, '', 1, ''),
        array('Tatnell, John', 1, '', 1, 1, 1, '', 1, 1, 1, 3),
        array('Verdichizzi, Jess', 2, 3, '', 3, '', '', 1, '', 4, 1),
        array('Ververs, Jack', '', '', 1, '', '', '', '', '', 1, ''),
        array('Ververs, Trent', '', '', '', 1, '', 1, 1, '', '', 1),
        array('Waight, Jarrod', '', 1, 1, '', '', '', 1, 1, '', ''),
        array('Watson, Nick', 2, 1, 3, 2, 2, 1, '', 2, 2, 1),
        array('West, Peter', 1, '', '', '', 1, '', '', '', 2, '')
    );

    const EXPECTED_DATA_REPORT2_TEST1 = array(
        array('Abbott, Trevor', 7, 1, 1, '', '', 8),
        array('Abrehart, Jack', '', 2, '', '', '', 2),
        array('Allcorn, Mason', 3, 2, '', '', '', 5),
        array('Amisse, Samira', 7, 1, '', '', '', 8),
        array('Armstrong, Dean', 3, '', '', '', '', 3),
        array('Armstrong, Wayne', 2, 9, '', '', '', 11),
        array('Ash, Bailey', 2, '', '', '', 1, 3),
        array('Barnett, Charlie', 2, '', '', '', '', 2),
        array('Barrand, Michael', '', 3, '', '', '', 3),
        array('Bell, Jack', 1, '', '', '', '', 1),
        array('Beste, Flynn', 3, '', '', '', '', 3),
        array('Beveridge, Brendan', 2, '', '', '', '', 2),
        array('Bisinella, Alex', 2, 2, '', '', '', 4),
        array('Bisinella, Tiana', 4, 2, '', '', '', 6),
        array('Blyton, Ezekiel', 1, 8, '', '', '', 9),
        array('Blyton, Melody', '', '', '', 3, '', 3),
        array('Boom, Marcus', 5, 6, 1, '', '', 11),
        array('Boseley, Isaac', '', '', '', 1, 1, 2),
        array('Brayshaw, James', 3, 7, '', '', '', 10),
        array('Brew, Alana', 5, 4, '', '', '', 9),
        array('Brew, Kaylie', 6, 5, '', '', '', 11),
        array('Burns, Noah', 4, '', '', '', '', 4),
        array('Cain, Jack', '', 6, '', '', '', 6),
        array('Callander, Timothy', 4, 8, '', '', '', 12),
        array('Cannard, Patrick', 2, 4, '', '', '', 6),
        array('Carruthers, Chris', 6, 3, 1, 1, '', 10),
        array('Chaston, David', 3, 8, '', '', '', 11),
        array('Clissold, Kelvin', 1, '', '', '', '', 1),
        array('Coxon, Xavier', '', 3, '', '', '', 3),
        array('Crawford, Blake', 1, '', '', '', '', 1),
        array('Crocker, Oscar', 9, 4, '', '', '', 13),
        array('Crucitti, Jess', 3, 2, '', '', '', 5),
        array('Curtis, Harper', 1, '', '', 1, '', 2),
        array('Curtis, Tye', 1, '', '', 1, '', 2),
        array('Davison, Mark', 1, 9, '', '', '', 10),
        array('De Been, Rebecca', 3, '', '', '', '', 3),
        array('Dean, Darryl', 4, 1, '', '', '', 5),
        array('Deigan, Thomas', '', 1, '', '', '', 1),
        array('Dines, Jessica', 1, 2, '', '', '', 3),
        array('Dorling, Daniel', 9, '', 1, '', '', 9),
        array('Dougherty, Liam', 5, 1, '', '', '', 6),
        array('Dyer, Reuben', 6, 1, '', '', '', 7),
        array('Edwards, Callum', 2, 8, '', '', '', 10),
        array('Elliott, Peter', 4, 7, '', '', '', 11),
        array('Ellis, Hudson', 1, '', '', '', '', 1),
        array('Elvey, Brendan', 3, 2, 1, '', '', 5),
        array('Facey, Joshua', '', 2, '', '', '', 2),
        array('Ferguson, Christopher', 3, '', '', '', '', 3),
        array('Fox, Jake', '', '', '', 1, '', 1),
        array('Gahan, Darren', 2, '', '', '', '', 2),
        array('Geall, Cooper', 3, 5, '', '', '', 8),
        array('Gower, Harry', 2, 7, '', 1, '', 10),
        array('Graham, Peter', 2, '', '', '', '', 2),
        array('Grills, Ethan', 4, 1, '', '', '', 5),
        array('Grills, Jonathan', 4, '', '', '', '', 4),
        array('Grist, Lachlan', 4, 3, '', '', '', 7),
        array('Grossman, Graham', 2, '', '', '', '', 2),
        array('Guarnaccia, Steve', 3, 7, '', '', '', 10),
        array('Guy, Jaymee', 2, 10, '', '', '', 12),
        array('Hall, Corvan', '', 9, '', '', '', 9),
        array('Hamill-beach, Rhys', 3, 2, '', 1, '', 6),
        array('Hamilton, Brodey', '', 2, '', '', '', 2),
        array('Hamilton, Jake', 2, 9, '', '', '', 11),
        array('Harbison, David', 5, '', '', '', '', 5),
        array('Harrison, William', 2, '', '', '', '', 2),
        array('Hastie, Jack', 6, 7, '', '', '', 13),
        array('Hauser-teasdale, Christopher', 1, 6, '', '', '', 7),
        array('Helwig, Kieren', 5, 4, '', 1, '', 10),
        array('Hollis, Paul', 4, '', '', 1, 1, 6),
        array('Holmes-henley, Jackson', 6, '', '', '', '', 6),
        array('James, Joshua', 4, 5, 1, '', '', 9),
        array('Jenning, Jackson', 1, '', '', '', '', 1),
        array('Jones, Adam', 2, 7, 1, '', '', 9),
        array('Jones, Christopher', 4, 8, '', '', '', 12),
        array('Jones, Paul', 3, '', '', '', '', 3),
        array('Jose, Connor', 2, 4, '', '', '', 6),
        array('Keating, Steve', 1, '', '', '', '', 1),
        array('Kennedy, Lloyd', '', '', '', '', 13, 13),
        array('Kerr, Craig', '', 4, '', '', '', 4),
        array('Knight, Rob', 3, '', 1, '', '', 3),
        array('Laffy, Finn', 1, '', '', '', '', 1),
        array('Lawrence, Roy', 11, 3, '', 2, 1, 17),
        array('Lobbe, Dean', 14, '', '', '', '', 14),
        array('Lunt, Jordan', '', 1, '', '', '', 1),
        array('Lynch, Ashliegh', 2, '', '', '', '', 2),
        array('Lyon, James', '', 4, '', 1, '', 5),
        array('Martin, Angus', 2, '', '', '', '', 2),
        array('Martin, Harvey', 4, 10, '', '', '', 14),
        array('Mazaraki, Oscar', 2, 11, '', '', '', 13),
        array('Mccosh, Jason', '', 5, '', '', '', 5),
        array('Mcdonald, Charlie', 2, '', '', '', '', 2),
        array('Mcdonald, Stephen', 13, '', '', '', '', 13),
        array('Mcelhinney, Paul', 6, 7, '', '', '', 13),
        array('Mcglade, Aaron', 3, 9, 1, '', '', 12),
        array('Mcgrath, Caleb', 2, '', 1, 1, 1, 4),
        array('Mckenzie, Molly', 6, '', '', '', '', 6),
        array('Mckenzie, Rodney', 9, '', '', '', '', 9),
        array('Mcmaster, Damian', 7, 3, '', 1, '', 11),
        array('Menzies, Luke', 1, '', '', '', '', 1),
        array('Millard, Caleb', 1, '', '', '', '', 1),
        array('Milligan, James', 3, 9, '', '', '', 12),
        array('Moynahan, Ethan', 4, 4, '', '', '', 8),
        array('Nelson, Regan', '', 2, '', '', '', 2),
        array('Nisbet, William', 1, 6, '', '', '', 7),
        array('Nolan, Mark', 5, 5, '', '', '', 10),
        array('Nuessler, Peter', 7, 1, '', 1, '', 9),
        array('Nuessler, Thomas', '', '', '', 1, '', 1),
        array('O\'Dwyer, Bernard', 5, 8, 1, '', '', 13),
        array('O\'Neill, Jack', 3, 9, '', '', '', 12),
        array('O\'Neill, Ruby', 3, '', '', '', '', 3),
        array('Oldfield, Craig', 1, '', '', '', '', 1),
        array('Ozols, Peter', 1, '', '', '', '', 1),
        array('Palmer, Tony', 1, '', '', 1, '', 2),
        array('Peck, Jonathan', 5, 8, '', '', '', 13),
        array('Peeler, Benjamin', 5, 5, 2, '', '', 10),
        array('Phillips, Tarik', 7, '', '', '', '', 7),
        array('Plumridge, David', '', '', '', 1, '', 1),
        array('Pratt, Matthew', 7, 2, '', 1, '', 10),
        array('Previti, Frank', 5, '', '', 1, '', 6),
        array('Rakas-hoare, Brandon', 8, '', '', '', '', 8),
        array('Rankin, Bradley', 1, '', 1, '', 1, 2),
        array('Richardson, James', 1, 1, '', '', '', 2),
        array('Riches, Aaron', 5, 9, '', '', '', 14),
        array('Richmond-craig, Brandon', 2, 8, '', '', '', 10),
        array('Roberts, Trae', 2, 4, 1, '', '', 6),
        array('Robertson, Joshua', '', 1, '', '', '', 1),
        array('Robinson, Daniel', 3, 5, '', 2, '', 10),
        array('Rofe, William', 1, 7, '', '', '', 8),
        array('Ross, Will', 3, 10, '', '', '', 13),
        array('Scott, Leopold', '', 4, '', '', '', 4),
        array('Scott, Lionel', '', 3, '', '', '', 3),
        array('Shannon, Lykeira', 12, 2, '', '', '', 14),
        array('Sirolli, Christopher', 4, 1, '', '', '', 5),
        array('Smith, Bradley', 8, 2, 1, '', '', 10),
        array('Sykstus, Kyle', 2, 6, 1, '', '', 8),
        array('Tate, Benjamin', '', 5, '', '', '', 5),
        array('Tatnell, John', 7, 2, 1, 1, '', 10),
        array('Tattersall, Austin', 5, 7, 2, '', '', 12),
        array('Thornton, Jesse', 3, '', '', '', '', 3),
        array('Tingiri, Benjamin', 5, 8, '', '', '', 13),
        array('Tingiri, Timothy', 5, 8, 1, '', '', 13),
        array('Verdichizzi, Jess', 4, 8, 1, '', '', 12),
        array('Waight, Jarrod', 1, 10, '', '', '', 11),
        array('Wallis, Tomek', 3, 8, '', '', '', 11),
        array('Watson, Nick', 6, '', 1, '', '', 6),
        array('Weber, Joel', 1, 4, '', '', '', 5),
        array('Wekwerth, Karen', 3, '', '', '', '', 3),
        array('West, Peter', 1, 2, '', '', '', 3),
        array('Whelan, Gerard', 5, 8, '', '', '', 13),
        array('Williams, Daniel', '', 2, '', '', '', 2),
        array('Witham, Jye', 5, 3, 1, '', '', 8),
        array('Wood, Taleitha', 4, '', '', '', '', 4)
    );

    const EXPECTED_DATA_REPORT3_TEST1 = array(
        array('01/04/2017', '', '', '', '', '', '', 'Anakie vs Werribee Centrals,Thomson vs Bannockburn', 'St Albans vs South Barwon', 3, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('08/04/2017', 'Newcomb vs Queenscliff', 'Werribee Centrals vs Winchelsea', '', 2, '', '', '', 'St Joseph\'s vs Bell Park', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('15/04/2017', '', '', '', '', '', 'Portarlington vs Newcomb', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('22/04/2017', '', 'Winchelsea vs Corio', '', 1, '', '', '', 'North Shore vs Colac', 1, '', '', '', '', '', '', '', '', '', 'Ogcc 2 vs Geelong West Giants 2,Ogcc 3 vs St Joseph\'s 3', 2, 'Aireys Eels vs Anglesea,Ammos Blue vs Ammos Green,Bell Post Hill vs Little River,Corio vs Seagulls,Drysdale Butcher vs Grovedale Dale,Drysdale Wilton vs Ogcc Orange,Flying Joeys vs Saints Blue,Giants vs Ammos White,Grovedale Smith vs Barwon Heads Gulls,Inverleigh vs Lethbridge,Leaping Joeys vs Tigers Gold,Leopold Brown vs Torquay Davies,Modewarre vs Barwon Heads Heads,Ogcc Blue vs Queenscliff,Ogcc White vs Grovedale Delaney,Portarlington Blue vs Swans Red,Portarlington Red vs Newcomb,Saints Green vs Eagles,St Albans Grinter vs Lara Hume,Swans Blue vs Lara Batman,Thomson vs Belmont Lions,Tigers Black vs Bell Park,Torquay Browning vs Lara Flinders,Torquay Bruce vs Leopold Dowsett,Torquay Milliken vs Swans White,Torquay Stone vs Torquay Coleman,Torquay Watson vs Winchelsea', 27),
        array('29/04/2017', 'Newcomb vs Geelong Amateur', '', '', 2, 'Drysdale vs Spotswood', '', '', 'Grovedale vs Leopold', 2, 'Drysdale vs Spotswood', '', '', '', '', '', '', '', '', '', '', 'Ammos White vs Flying Joeys,Anglesea vs Modewarre,Barwon Heads Gulls vs Ogcc Blue,Barwon Heads Heads vs Torquay Milliken,Bell Park vs Leaping Joeys,Belmont Lions vs Portarlington Blue,Eagles vs Tigers Black,Giants vs Ammos Green,Grovedale Dale vs Grovedale Smith,Grovedale Delaney vs Drysdale Wilton,Lara Flinders vs St Albans Grinter,Lara Hume vs Swans Blue,Leopold Dowsett vs Ogcc White,Lethbridge vs Bell Post Hill,Little River vs Corio,Newcomb vs Torquay Watson,Ogcc Orange vs Torquay Bruce,Queenscliff vs Leopold Brown,Saints Blue vs Saints Green,Seagulls vs Torquay Stone,Swans Red vs Thomson,Swans White vs Aireys Eels,Tigers Gold vs Ammos Blue,Torquay Browning vs Lara Batman,Torquay Coleman vs Portarlington Red,Torquay Davies vs Drysdale Butcher,Winchelsea vs Inverleigh', 27),
        array('06/05/2017', 'Queenscliff vs Anglesea', '', '', 1, '', '', '', '', '', '', '', '', '', '', 'Inverleigh vs Drysdale 2', 1, 'Anglesea vs Barwon Heads', 1, 'Drysdale Grigg vs Bell Park 1', 1, 'Ammos Green vs Tigers Gold,Anglesea vs Torquay Watson,Barwon Heads Gulls vs Lara Hume,Bell Park vs Ammos Blue,Bell Post Hill vs Swans Red,Belmont Lions vs Aireys Eels,Corio vs Inverleigh,Drysdale Wilton vs Portarlington Red,Flying Joeys vs Giants,Grovedale Dale vs Ogcc Orange,Grovedale Smith vs Torquay Bruce,Lara Batman vs Drysdale Butcher,Lara Flinders vs Torquay Stone,Leaping Joeys vs Eagles,Leopold Dowsett vs Grovedale Delaney,Newcomb vs Lethbridge,Ogcc Blue vs Leopold Brown,Saints Green vs Ammos White,Seagulls vs Little River,St Albans Grinter vs Queenscliff,Swans White vs Winchelsea,Thomson vs Portarlington Blue,Tigers Black vs Saints Blue,Torquay Browning vs Swans Blue,Torquay Coleman vs Modewarre,Torquay Davies vs Barwon Heads Heads,Torquay Milliken vs Ogcc White', 27),
        array('13/05/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', '', '', '', 'Ocean Grove 2 vs Modewarre', 1, '', '', 'Aireys Eels vs Torquay Watson,Ammos Green vs Flying Joeys,Ammos White vs Tigers Black,Barwon Heads Heads vs Grovedale Dale,Corio vs Newcomb,Drysdale Butcher vs Torquay Milliken,Eagles vs Ammos Blue,Giants vs Saints Green,Grovedale Delaney vs Torquay Davies,Inverleigh vs Belmont Lions,Lara Hume vs Ogcc Blue,Leopold Brown vs Drysdale Wilton,Lethbridge vs Thomson,Little River vs Lara Batman,Modewarre vs Bell Post Hill,Ogcc Orange vs Swans Red,Ogcc White vs Barwon Heads Gulls,Portarlington Blue vs Torquay Coleman,Portarlington Red vs Grovedale Smith,Queenscliff vs Leopold Dowsett,Saints Blue vs Leaping Joeys,Swans Blue vs Lara Flinders,Swans White vs Seagulls,Tigers Gold vs Bell Park,Torquay Bruce vs St Albans Grinter,Torquay Stone vs Torquay Browning,Winchelsea vs Anglesea', 27),
        array('20/05/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', '', '', '', 'Drysdale Richardson vs Geelong Amateur 2', 1, 'Drysdale Grigg vs Torquay Bumpstead', 1, 'Ammos Blue vs Saints Blue,Barwon Heads Gulls vs Grovedale Delaney,Bell Park vs Ammos Green,Bell Post Hill vs Corio,Drysdale Butcher vs Torquay Coleman,Inverleigh vs Thomson,Lara Batman vs Aireys Eels,Lara Hume vs Barwon Heads Heads,Leaping Joeys vs Ammos White,Leopold Brown vs Grovedale Smith,Leopold Dowsett vs Torquay Davies,Lethbridge vs Belmont Lions,Little River vs Newcomb,Modewarre vs Swans White,Ogcc White vs Swans Blue,Portarlington Blue vs Winchelsea,Portarlington Red vs Lara Flinders,Queenscliff vs Torquay Browning,Saints Green vs Flying Joeys,Seagulls vs Anglesea,St Albans Grinter vs Ogcc Blue,Swans Red vs Drysdale Wilton,Tigers Black vs Giants,Tigers Gold vs Eagles,Torquay Milliken vs Torquay Bruce,Torquay Stone vs Grovedale Dale,Torquay Watson vs Ogcc Orange', 27),
        array('27/05/2017', 'Queenscliff vs Portarlington', '', '', 2, 'Drysdale vs Spotswood', '', '', 'Geelong West Giants vs Lara', 1, '', '', '', '', '', '', '', 'Drysdale Richardson vs Leopold Butteriss', 1, '', '', 'Aireys Eels vs Swans Red,Ammos Green vs Saints Green,Ammos White vs Ammos Blue,Anglesea vs Portarlington Blue,Barwon Heads Heads vs Leopold Brown,Drysdale Butcher vs Torquay Stone,Drysdale Wilton vs Seagulls,Eagles vs Bell Park,Flying Joeys vs Tigers Black,Giants vs Leaping Joeys,Grovedale Dale vs Leopold Dowsett,Grovedale Delaney vs Torquay Bruce,Grovedale Smith vs Ogcc Orange,Inverleigh vs Bell Post Hill,Lara Batman vs Winchelsea,Lara Flinders vs Queenscliff,Lethbridge vs Corio,Little River vs Belmont Lions,Newcomb vs Swans White,Ogcc White vs Lara Hume,Saints Blue vs Tigers Gold,Swans Blue vs Portarlington Red,Thomson vs Modewarre,Torquay Browning vs Barwon Heads Gulls,Torquay Coleman vs Torquay Milliken,Torquay Davies vs St Albans Grinter,Torquay Watson vs Ogcc Blue', 27),
        array('03/06/2017', '', 'Bannockburn vs Corio', '', 1, '', '', '', 'Bell Park vs South Barwon', 1, '', '', '', '', '', '', '', 'Modewarre vs Grovedale 2', 1, 'Modewarre / Winchelsea vs Geelong West Giants 1', 1, 'Ammos Blue vs Saints Green,Anglesea vs Lara Batman,Barwon Heads Gulls vs Lara Flinders,Barwon Heads Heads vs St Albans Grinter,Bell Park vs Giants,Bell Post Hill vs Lethbridge,Belmont Lions vs Modewarre,Corio vs Thomson,Eagles vs Ammos White,Flying Joeys vs Tigers Gold,Grovedale Delaney vs Swans Blue,Inverleigh vs Newcomb,Lara Hume vs Torquay Milliken,Leaping Joeys vs Tigers Black,Leopold Brown vs Torquay Watson,Leopold Dowsett vs Drysdale Butcher,Ogcc Blue vs Grovedale Smith,Ogcc Orange vs Torquay Davies,Portarlington Blue vs Drysdale Wilton,Portarlington Red vs Torquay Browning,Saints Blue vs Ammos Green,Seagulls vs Aireys Eels,Swans White vs Little River,Torquay Bruce vs Queenscliff,Torquay Coleman vs Grovedale Dale,Torquay Stone vs Ogcc White,Winchelsea vs Swans Red', 27),
        array('10/06/2017', '', '', '', '', '', 'Modewarre vs Queenscliff', 'Bell Post Hill vs Thomson', 'Bell Park vs Grovedale,Newtown & Chilwell vs Lara', 4, '', '', '', '', '', 'North Shore vs Winchelsea', 1, 'Geelong Amateur 1 vs St Albans Osborne,North Shore vs Anglesea,Ocean Grove 2 vs Grovedale 2', 3, 'Geelong Amateur 1 vs Newtown & Chilwell 2,Ogcc 1 vs St Mary\'s 1', 2, 'Aireys Eels vs Winchelsea,Ammos Blue vs Flying Joeys,Ammos Green vs Tigers Black,Ammos White vs Bell Park,Bell Post Hill vs Little River,Belmont Lions vs Thomson,Corio vs Inverleigh,Grovedale Dale vs Drysdale Butcher,Grovedale Delaney vs Torquay Browning,Grovedale Smith vs Torquay Watson,Lara Batman vs Drysdale Wilton,Lara Flinders vs Torquay Bruce,Lara Hume vs Torquay Coleman,Newcomb vs Modewarre,Ogcc Blue vs Torquay Davies,Ogcc Orange vs Barwon Heads Heads,Ogcc White vs Leopold Dowsett,Portarlington Blue vs Seagulls,Portarlington Red vs Queenscliff,Saints Blue vs Eagles,Saints Green vs Leaping Joeys,St Albans Grinter vs Leopold Brown,Swans Blue vs Barwon Heads Gulls,Swans Red vs Anglesea,Swans White vs Lethbridge,Tigers Gold vs Giants,Torquay Milliken vs Torquay Stone', 27),
        array('17/06/2017', '', '', '', '', '', 'Newcomb vs Portarlington', '', '', 2, 'Drysdale vs Spotswood', '', '', '', '', '', '', 'Modewarre vs Ocean Grove 2', 1, 'Geelong Amateur 2 vs Belmont Lions,Geelong Amateur 3 vs Torquay Pyers', 2, 'Ammos Blue vs Giants,Ammos Green vs Eagles,Ammos White vs Tigers Gold,Bell Park vs Saints Blue,Belmont Lions vs Inverleigh,Drysdale Wilton vs Aireys Eels,Grovedale Dale vs Torquay Milliken,Lara Batman vs Corio,Lara Flinders vs Torquay Browning,Leaping Joeys vs Flying Joeys,Leopold Dowsett vs Lara Hume,Little River vs Lethbridge,Modewarre vs Seagulls,Newcomb vs Thomson,Ogcc Blue vs Leopold Brown,Ogcc White vs Drysdale Butcher,St Albans Grinter vs Ogcc Orange,Swans Blue vs Queenscliff,Swans Red vs Portarlington Blue,Swans White vs Bell Post Hill,Tigers Black vs Saints Green,Torquay Bruce vs Barwon Heads Gulls,Torquay Coleman vs Portarlington Red,Torquay Davies vs Grovedale Smith,Torquay Stone vs Grovedale Delaney,Torquay Watson vs Barwon Heads Heads,Winchelsea vs Anglesea', 27),
        array('24/06/2017', '', '', '', 1, 'Drysdale vs Spotswood', 'Torquay vs Ocean Grove', '', 'Colac vs Geelong West Giants', 3, 'Drysdale vs Spotswood', '', '', '', '', '', '', 'Ocean Grove 2 vs Queenscliff', 1, '', '', 'Aireys Eels vs Portarlington Blue,Ammos Green vs Leaping Joeys,Ammos White vs Saints Blue,Barwon Heads Heads vs Ogcc Blue,Bell Post Hill vs Newcomb,Corio vs Belmont Lions,Drysdale Butcher vs Grovedale Delaney,Flying Joeys vs Bell Park,Giants vs Eagles,Grovedale Dale vs Ogcc White,Grovedale Smith vs Leopold Brown,Lara Batman vs Swans White,Lara Flinders vs Torquay Coleman,Little River vs Inverleigh,Modewarre vs St Albans Grinter,Ogcc Orange vs Swans Red,Queenscliff vs Barwon Heads Gulls,Saints Green vs Tigers Gold,Seagulls vs Anglesea,Thomson vs Lethbridge,Tigers Black vs Ammos Blue,Torquay Browning vs Swans Blue,Torquay Bruce vs Portarlington Red,Torquay Milliken vs Leopold Dowsett,Torquay Stone vs Lara Hume,Torquay Watson vs Torquay Davies,Winchelsea vs Drysdale Wilton', 27),
        array('01/07/2017', '', '', '', '', '', '', 'Bell Post Hill vs Corio', 'North Shore vs St Mary\'s', 2, '', '', '', '', '', '', '', 'Lara 2 vs Ocean Grove 3,Queenscliff vs Grovedale 2', 2, 'Torquay Dunstan vs St Mary\'s 2,Torquay Pyers vs Ogcc 3', 2, 'Aireys Eels vs Ogcc Orange,Ammos Blue vs Leaping Joeys,Ammos White vs Ammos Green,Anglesea vs Drysdale Wilton,Barwon Heads Gulls vs Portarlington Red,Barwon Heads Heads vs Grovedale Smith,Bell Park vs Saints Green,Corio vs Little River,Eagles vs Flying Joeys,Grovedale Delaney vs Ogcc White,Lara Batman vs Bell Post Hill,Lara Hume vs Grovedale Dale,Leopold Brown vs Torquay Davies,Leopold Dowsett vs Torquay Stone,Lethbridge vs Newcomb,Ogcc Blue vs Torquay Watson,Portarlington Blue vs Modewarre,Queenscliff vs Torquay Coleman,Saints Blue vs Giants,Seagulls vs Swans Red,St Albans Grinter vs Winchelsea,Swans Blue vs Lara Flinders,Swans White vs Belmont Lions,Thomson vs Inverleigh,Tigers Gold vs Tigers Black,Torquay Browning vs Torquay Bruce,Torquay Milliken vs Drysdale Butcher', 27),
        array('15/07/2017', '', '', 'Leopold vs St Albans', 2, 'North Geelong vs Spotswood', 'Modewarre vs Newcomb', '', '', 2, 'North Geelong vs Spotswood', '', '', '', '', 'North Shore vs Drysdale 2', 1, 'Inverleigh vs Ocean Grove 3', 1, '', '', 'Ammos Green vs Ammos Blue,Bell Park vs Tigers Black,Bell Post Hill vs Thomson,Belmont Lions vs Lethbridge,Drysdale Butcher vs Lara Hume,Drysdale Wilton vs Seagulls,Eagles vs Saints Green,Giants vs Ammos White,Grovedale Delaney vs Leopold Dowsett,Inverleigh vs Lara Batman,Leopold Brown vs Barwon Heads Heads,Modewarre vs Ogcc Orange,Newcomb vs Little River,Ogcc White vs Torquay Milliken,Portarlington Blue vs Winchelsea,Portarlington Red vs Lara Flinders,Queenscliff vs Torquay Browning,Saints Blue vs Flying Joeys,St Albans Grinter vs Anglesea,Swans Red vs Aireys Eels,Swans White vs Corio,Tigers Gold vs Leaping Joeys,Torquay Bruce vs Swans Blue,Torquay Coleman vs Barwon Heads Gulls,Torquay Davies vs Ogcc Blue,Torquay Stone vs Grovedale Dale,Torquay Watson vs Grovedale Smith', 27),
        array('22/07/2017', '', '', '', '', '', '', 'Corio vs Winchelsea', 'St Joseph\'s vs North Shore', 2, '', '', '', '', '', 'Newcomb vs Inverleigh', 1, 'Drysdale Richardson vs St Joseph\'s Jackman', 1, '', '', 'Aireys Eels vs Modewarre,Ammos Blue vs Tigers Gold,Barwon Heads Gulls vs Torquay Browning,Belmont Lions vs Bell Post Hill,Drysdale Butcher vs Torquay Stone,Drysdale Wilton vs Swans Red,Flying Joeys vs Ammos White,Giants vs Ammos Green,Grovedale Smith vs Ogcc Blue,Lara Hume vs Ogcc White,Leaping Joeys vs Bell Park,Leopold Dowsett vs Grovedale Dale,Lethbridge vs Inverleigh,Little River vs Lara Batman,Newcomb vs Corio,Portarlington Blue vs Anglesea,Portarlington Red vs Swans Blue,Queenscliff vs Lara Flinders,Saints Green vs Saints Blue,Seagulls vs St Albans Grinter,Thomson vs Swans White,Tigers Black vs Eagles,Torquay Bruce vs Torquay Coleman,Torquay Davies vs Barwon Heads Heads,Torquay Milliken vs Grovedale Delaney,Torquay Watson vs Leopold Brown,Winchelsea vs Ogcc Orange', 27),
        array('29/07/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', 'Bell Park vs St Joseph\'s', 2, 'North Geelong vs Spotswood', '', '', '', '', '', '', '', '', 'Queenscliff vs Newtown & Chilwell 2', 1, 'Ammos Blue vs Bell Park,Anglesea vs Modewarre,Barwon Heads Heads vs Torquay Watson,Corio vs Bell Post Hill,Drysdale Butcher vs Leopold Dowsett,Eagles vs Leaping Joeys,Flying Joeys vs Giants,Grovedale Dale vs Grovedale Delaney,Inverleigh vs Swans White,Lara Batman vs Lethbridge,Lara Flinders vs Barwon Heads Gulls,Leopold Brown vs Ogcc Blue,Little River vs Thomson,Newcomb vs Belmont Lions,Ogcc Orange vs Drysdale Wilton,Queenscliff vs Torquay Bruce,Saints Green vs Ammos White,Seagulls vs Aireys Eels,St Albans Grinter vs Portarlington Blue,Swans Blue vs Torquay Coleman,Swans Red vs Winchelsea,Tigers Black vs Saints Blue,Tigers Gold vs Ammos Green,Torquay Browning vs Portarlington Red,Torquay Davies vs Grovedale Smith,Torquay Milliken vs Lara Hume,Torquay Stone vs Ogcc White', 27),
        array('05/08/2017', '', '', '', 2, 'Drysdale vs Spotswood,North Geelong vs Spotswood', '', '', 'St Mary\'s vs South Barwon', 3, 'Drysdale vs Spotswood,North Geelong vs Spotswood', '', '', '', '', '', '', 'Drysdale Ruggles vs Torquay Papworth', 1, '', '', 'Ammos White vs Tigers Black,Anglesea vs Aireys Eels,Barwon Heads Gulls vs Swans Blue,Bell Park vs Tigers Gold,Bell Post Hill vs Inverleigh,Belmont Lions vs Little River,Eagles vs Ammos Blue,Flying Joeys vs Ammos Green,Grovedale Delaney vs Drysdale Butcher,Grovedale Smith vs Leopold Brown,Lara Flinders vs Torquay Bruce,Lara Hume vs Torquay Stone,Leaping Joeys vs Saints Blue,Leopold Dowsett vs Torquay Milliken,Lethbridge vs Corio,Modewarre vs Drysdale Wilton,Ogcc Blue vs Barwon Heads Heads,Ogcc Orange vs Portarlington Blue,Ogcc White vs Grovedale Dale,Queenscliff vs Portarlington Red,Saints Green vs Giants,Swans Red vs St Albans Grinter,Swans White vs Newcomb,Thomson vs Lara Batman,Torquay Coleman vs Torquay Browning,Torquay Davies vs Torquay Watson,Winchelsea vs Seagulls', 27),
        array('12/08/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 2, 'Drysdale vs Spotswood,North Geelong vs Spotswood', '', '', '', '', 'Belmont Lions vs Drysdale 2,Corio vs Newcomb', 2, 'Geelong Amateur 1 vs Bannockburn 1', 1, 'Geelong Amateur 2 vs Corio', 1, 'Aireys Eels vs Winchelsea,Ammos Green vs Bell Park,Anglesea vs Ogcc Orange,Barwon Heads Gulls vs Queenscliff,Barwon Heads Heads vs Grovedale Smith,Bell Post Hill vs Swans White,Belmont Lions vs Lara Batman,Drysdale Butcher vs Ogcc White,Drysdale Wilton vs St Albans Grinter,Giants vs Ammos Blue,Grovedale Dale vs Torquay Milliken,Inverleigh vs Newcomb,Leaping Joeys vs Flying Joeys,Leopold Brown vs Torquay Davies,Leopold Dowsett vs Lara Hume,Lethbridge vs Little River,Ogcc Blue vs Torquay Watson,Saints Blue vs Ammos White,Seagulls vs Portarlington Blue,Swans Blue vs Torquay Browning,Swans Red vs Modewarre,Thomson vs Corio,Tigers Black vs Saints Green,Tigers Gold vs Eagles,Torquay Bruce vs Portarlington Red,Torquay Coleman vs Lara Flinders,Torquay Stone vs Grovedale Delaney', 27),
        array('19/08/2017', '', '', '', '', '', '', 'Inverleigh vs Corio', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('26/08/2017', '', '', 'Leopold vs North Shore', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')
    );

    const EXPECTED_DATA_REPORT3_TEST2 = array(
        array('08/04/2017', '', '', 'Simpson vs Alvie', 1, '', ''),
        array('06/05/2017', 'Birregurra vs Colac Imperials', 1, 'Lorne vs Irrewarra-beeac', 1, '', ''),
        array('20/05/2017', '', '', 'Simpson vs Birregurra', 1, '', ''),
        array('27/05/2017', 'Lorne vs Apollo Bay', 1, 'Otway Districts vs Alvie', 1, '', ''),
        array('03/06/2017', '', '', 'Otway Districts vs Colac Imperials', 1, '', ''),
        array('10/06/2017', 'Lorne vs Otway Districts', 1, 'South Colac vs Alvie', 1, '', ''),
        array('08/07/2017', 'Simpson vs Otway Districts', 1, '', '', '', ''),
        array('15/07/2017', '', '', 'Otway Districts vs Irrewarra-beeac', 1, '', ''),
        array('22/07/2017', '', '', 'Apollo Bay vs Alvie', 1, '', ''),
        array('29/07/2017', '', '', 'Birregurra vs Simpson,Colac Imperials vs Alvie', 2, '', ''),
        array('12/08/2017', '', '', 'South Colac vs Apollo Bay', 1, '', ''),
        array('19/08/2017', '', '', 'Apollo Bay vs Birregurra', 1, '', '')
    );

    const EXPECTED_DATA_REPORT4_TEST1 = array(
        array('Aireys Inlet', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Ammos', '', '', '', '', '', '', '', '', '', '', 44, '', '', '', '', '', '', '', '', '', 44, '', '', '', '', '', '', '', '', 44, '', '', ''),
        array('Anakie', '', '', '', '', '', 18, '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Anglesea', '', '', '', 16, '', '', 17, '', 15, 18, 16, '', '', '', '', 17, '', '', '', 1, 16, '', '', 1, '', 1, 5, 14, 16, 16, '', '', ''),
        array('Bannockburn', '', '', '', '', '', 15, 16, '', 29, 16, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 29, 16, '', '', '', ''),
        array('Barwon Heads', '', '', '', 16, '', '', 16, '', 15, 32, 32, 12, 15, 15, '', 17, '', '', '', 1, 32, '', 15, 1, '', 1, 2, 16, 32, 32, 13, 13, 15),
        array('Bell Park', '', '', '', '', 18, '', 12, '', 28, 32, 16, '', '', '', '', '', 14, '', '', '', 16, '', '', '', '', '', '', 28, 32, 16, '', '', ''),
        array('Bell Post Hill', '', '', '', '', '', 17, '', '', '', 18, 16, 15, '', '', '', '', '', 2, '', '', 16, '', '', '', '', '', '', '', 16, 16, 13, '', ''),
        array('Belmont Lions', '', '', '', '', '', 17, 12, '', 15, 16, 16, '', '', '', '', '', '', '', 1, '', 16, '', '', '', '', '', 2, 16, 16, 16, '', '', ''),
        array('Colac', '', '', '', '', 17, '', 12, '', 14, 16, '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', '', 9, 16, '', '', '', ''),
        array('Colac Imperials', '', '', '', '', '', '', '', '', '', '', '', 14, 17, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 13, 15, ''),
        array('Corio', '', '', '', '', '', 18, 15, '', '', 16, 16, '', '', '', '', '', '', '', 1, '', 16, '', '', '', '', '', 1, '', 16, 16, '', '', ''),
        array('Drysdale', '', '', 6, 13, '', '', 25, '', 30, 16, 32, '', 14, '', 1, 16, '', '', 3, '', 32, '', '', 1, 6, 1, 5, 29, 16, 32, '', 13, ''),
        array('Eagles', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('East Geelong', '', '', '', '', '', 18, '', '', 15, 17, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 15, 16, '', '', '', ''),
        array('Flying Joeys', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Geelong Amateur', '', '', '', 12, '', '', 12, '', 25, 54, '', 13, 17, 15, '', 18, '', '', '', '', '', '', 15, 1, '', 1, 3, 29, 48, '', 13, 15, 15),
        array('Geelong West', '', '', '', '', 19, 14, 32, '', 28, 34, '', 14, 13, '', '', '', 19, '', '', '', '', '', '', '', '', '', 2, 30, 32, '', 15, 13, ''),
        array('Giants', '', '', '', '', '', '', '', '', '', '', 16, '', '', 15, '', '', '', '', '', '', 16, '', 15, '', '', '', '', '', '', 16, '', '', 15),
        array('Grovedale', '', '', '', '', 16, '', 12, '', 25, 32, 46, 16, 13, 25, '', '', 16, '', '', '', 46, '', 25, '', '', '', '', 28, 32, 46, 14, 13, 25),
        array('Inverleigh', '', '', '', '', '', 14, 12, '', 15, '', 16, '', '', '', '', '', '', 1, 2, 1, 16, '', '', '', '', '', 2, 16, '', 16, '', '', ''),
        array('Lara', '', '', '', '', 13, '', 15, '', 30, 32, 48, 16, 17, 29, '', '', 17, '', '', 1, 48, 1, 29, '', '', '', 2, 31, 32, 48, 14, 14, 29),
        array('Leaping Joeys', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Leopold', '', '', '', '', 18, '', 29, '', 30, 33, 32, '', '', 15, '', '', 10, '', '', '', 32, '', 15, '', '', '', 2, 30, 32, 32, '', '', 15),
        array('Lethbridge', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Little River', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Modewarre', '', '', '', 17, '', '', '', '', 15, 19, 16, 16, '', '', '', 17, '', '', '', 1, 16, '', '', 1, '', 1, '', 16, 16, 16, 15, '', ''),
        array('Modewarre / Grovedale', '', '', '', '', '', '', 14, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', ''),
        array('Newcomb', 1, '', '', 16, '', '', 14, '', '', 16, 16, '', '', 15, '', 18, '', '', 2, '', 16, '', 15, 1, '', 1, 5, '', 16, 16, '', '', 15),
        array('Newtown & Chilwell', '', '', '', '', 18, '', 13, '', 10, 48, '', 14, 15, 14, '', '', 18, '', '', '', '', 1, 14, '', '', '', '', 15, 48, '', 15, 15, 14),
        array('North Geelong', '', '', 6, '', '', 15, 14, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 6, '', 1, '', '', '', '', '', ''),
        array('North Shore', '', '', '', '', 18, '', 14, '', 15, '', '', '', '', '', '', '', 18, '', 1, '', '', '', '', '', '', '', 2, 14, '', '', '', '', ''),
        array('Ocean Grove', '', '', '', 16, '', '', 31, '', 44, '', '', '', 13, 37, '', 17, '', '', '', 3, '', '', 37, 1, '', 1, 6, 45, '', '', '', 13, 37),
        array('Ogcc', '', '', '', '', '', '', '', '', '', 52, 48, '', '', '', '', '', '', '', '', '', 48, '', '', '', '', '', '', '', 48, 48, '', '', ''),
        array('Portarlington', '', '', '', 16, '', '', '', '', '', 19, 32, '', '', '', '', 15, '', '', '', '', 32, '', '', 1, '', 1, '', '', 16, 32, '', '', ''),
        array('Queenscliff', 1, '', '', 15, '', '', 18, '', 15, 16, 16, 13, '', '', '', 17, '', '', '', '', 16, '', '', 1, '', 1, 3, 16, 16, 16, 13, '', ''),
        array('Saints', '', '', '', '', '', '', '', '', '', '', 30, '', '', '', '', '', '', '', '', '', 30, '', '', '', '', '', '', '', '', 30, '', '', ''),
        array('Seagulls', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('South Barwon', '', '', '', '', 16, '', 21, '', 11, 32, '', '', '', '', '', '', 14, '', '', '', '', '', '', '', '', '', 2, 12, 32, '', '', '', ''),
        array('Spotswood', '', '', 12, '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', 12, '', '', '', '', '', '', '', ''),
        array('St Albans', '', '', '', '', 18, '', 15, '', 16, 16, 16, '', 15, '', '', '', 16, '', '', '', 16, '', '', '', '', '', 1, 14, 16, 16, '', 13, ''),
        array('St Joseph\'s', '', '', '', '', 16, '', 29, 1, 14, 50, '', 11, 15, 15, '', '', 16, '', '', '', '', '', 15, '', '', '', 1, 14, 48, '', 13, 15, 15),
        array('St Joseph\'s Hill', '', '', '', '', '', '', '', '', 15, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 15, '', '', '', '', ''),
        array('St Joseph\'s Podbury', '', '', '', '', '', '', '', '', 13, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 13, '', '', '', '', ''),
        array('St Mary\'s', '', '', '', '', 17, '', 21, 1, 20, 52, '', 15, 16, '', '', '', 16, '', '', '', '', '', '', '', '', '', 2, 29, 48, '', 14, 13, ''),
        array('Swans', '', '', '', '', '', '', '', '', '', '', 48, '', '', 15, '', '', '', '', '', '', 48, '', 15, '', '', '', '', '', '', 48, '', '', 15),
        array('Thomson', '', '', '', '', '', 17, '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Tigers', '', '', '', '', '', '', '', '', '', '', '', '', '', 15, '', '', '', '', '', '', '', '', 15, '', '', '', '', '', '', '', '', '', 15),
        array('Tigers Black', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Tigers Gold', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', 16, '', '', ''),
        array('Torquay', '', '', '', 17, '', '', 30, '', '', '', 102, '', 16, 27, '', 18, '', '', '', '', 102, '', 27, 1, '', 1, 2, '', '', 102, '', 15, 27),
        array('Torquay Bumpstead', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', ''),
        array('Torquay Dunstan', '', '', '', '', '', '', '', '', '', 17, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', ''),
        array('Torquay Jones', '', '', '', '', '', '', '', '', 15, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 15, '', '', '', '', ''),
        array('Torquay Nairn', '', '', '', '', '', '', '', '', '', 17, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', ''),
        array('Torquay Papworth', '', '', '', '', '', '', '', '', 13, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 14, '', '', '', '', ''),
        array('Torquay Pyers', '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 16, '', '', '', ''),
        array('Torquay Scott', '', '', '', '', '', '', '', '', '', '', '', 15, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 15, '', ''),
        array('Werribee Centrals', '', 1, '', '', '', 16, 15, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 3, '', '', '', '', '', ''),
        array('Winchelsea', '', 1, '', '', '', 17, 8, '', '', '', 16, '', '', '', '', '', '', '', '', '', 16, '', '', '', '', '', 2, '', '', 16, '', '', '')
    );

    const EXPECTED_DATA_REPORT4_TEST2 = array(
        array('Alvie', 17, 16, 18, 17, '', 18, '', 5, 14, 18),
        array('Apollo Bay', 18, 17, 18, 18, 7, 18, '', 2, 17, 18),
        array('Birregurra', 17, 17, 18, 16, 5, 18, 1, 4, 17, 18),
        array('Colac Imperials', 18, 11, 18, 18, '', 18, '', 1, 15, 18),
        array('Irrewarra-beeac', 17, 16, 18, 17, 6, 18, '', 1, 16, 18),
        array('Lorne', 17, 16, 18, 16, 4, 18, 1, 3, 16, 18),
        array('Otway Districts', 18, 17, 18, 18, 4, 18, '', 3, 14, 18),
        array('Simpson', 18, 16, 18, 18, 7, 18, '', 1, 17, 18),
        array('South Colac', 18, 13, 18, 18, 3, 18, '', 2, 15, 18),
        array('Western Eagles', 18, 7, 18, 18, 4, 18, '', '', 15, 18)
    );


    public function setUp() {
        //TODO: Add class variable initialisation and check here, so we can run this code only once, to save time
        $host = 'http://localhost:4444/wd/hub'; // this is the default. If I get a JSON decoding error, try adding or removing /wd/hub/
        $options = new ChromeOptions();
        //Set chrome as headless
        $options->addArguments(['--headless', 'window-size=1024,768']);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);


        $this->driver = RemoteWebDriver::create($host, $capabilities);
    }

    public function tearDown() {
        //TODO check for initialised variable from before.
        $this->driver->close();
    }

    private function login() {

        $this->driver->get("http://localhost:8888/umpire/");
        $this->driver->findElement(WebDriverBy::id("username"))->sendKeys("bbrumm");
        $this->driver->findElement(WebDriverBy::id("password"))->sendKeys("bbrumm2017");

        $this->clickElement('loginBtn');

    }

    private function getElement($pElementID) {
        return $this->driver->findElement(WebDriverBy::id($pElementID));
    }

    private function getElementByXpath($pXpath) {
        return $this->driver->findElement(WebDriverBy::xpath($pXpath));
    }

    private function getMultipleElementsBYXpath($pXpath) {
        return $this->driver->findElements(WebDriverBy::xpath($pXpath));
    }

    private function clickElement($pElementID) {
        $this->driver->findElement(WebDriverBy::id($pElementID))->click();
    }


    private function getColumnHeaders($pHeaderRowNumber) {
        return $this->getMultipleElementsBYXpath("//table/thead[2]/tr[".$pHeaderRowNumber."]/th");
    }

    private function getTableData() {
        return $this->getMultipleElementsBYXpath("//*[@id='reportTable']/tbody");
    }

    private function convertTableDataElementsToTable($pElements) {
        $outputArray = array();

        $rowsInTable = $pElements[0]->findElements(WebDriverBy::tagName("tr"));
        $rowCount = count($rowsInTable);
        for($rowNumber = 0; $rowNumber < $rowCount; $rowNumber++) {
            $cellsInRow = $rowsInTable[$rowNumber]->findElements(WebDriverBy::tagName("td"));
            $columnCount = count($cellsInRow);

            for($columnNumber = 0; $columnNumber < $columnCount; $columnNumber++) {
                $outputArray[$rowNumber][$columnNumber] = $cellsInRow[$columnNumber]->getText(); //TODO: this is a slow step, or the fact it loops makes it slow
            }
        }
        return $outputArray;
    }

    private function assertUmpireDisciplineIsCorrect($pUmpireDiscipline) {
        $actualUmpireDiscipline = $this->getElement("reportInfoUmpireDiscipline")->getText();
        $this->assertEquals("Umpire Discipline: ". $pUmpireDiscipline, $actualUmpireDiscipline);
    }

    private function assertLeagueIsCorrect($pLeague) {
        $actualLeague = $this->getElement("reportInfoLeague")->getText();
        $this->assertEquals("League: ". $pLeague, $actualLeague);
    }

    private function assertAgeGroupIsCorrect($pAgeGroup) {
        $actualAgeGroup = $this->getElement("reportInfoAgeGroup")->getText();
        $this->assertEquals("Age Group: ". $pAgeGroup, $actualAgeGroup);
    }

    private function assertSearchRowIsShown() {
        $actualSearchRow = $this->getElement("searchForRow")->getText();
        $this->assertEquals("Search for Row", $actualSearchRow);
        $actualSearchTextbox = $this->getElement("search");
        $this->assertTrue($actualSearchTextbox->isDisplayed());
    }

    private function assertColumnHeadersAreCorrect($pExpectedColumnHeadersFirstRow, $pExpectedColumnHeadersSecondRow) {
        $this->assertSingleRowOfHeaderColumns(1, $pExpectedColumnHeadersFirstRow);
        $this->assertSingleRowOfHeaderColumns(2, $pExpectedColumnHeadersSecondRow);
        /*
        $reportTableHeader = $this->getColumnHeaders(1);
        $this->assertEquals($pExpectedColumnHeadersFirstRow[0], $reportTableHeader[0]->getText());
        $this->assertEquals($pExpectedColumnHeadersFirstRow[1], $reportTableHeader[1]->getText());

        //Get second row headers
        $reportTableHeaderElements = $this->getColumnHeaders(2);

        $countHeaderCells = count($reportTableHeaderElements);
        for($i=0; $i<$countHeaderCells; $i++) {
            $this->assertEquals(
                $pExpectedColumnHeadersSecondRow[$i],
                $reportTableHeaderElements[$i]->getText()
            );
        }
        */
    }

    private function assertSingleRowOfHeaderColumns($pHeaderRowNumber, $pHeaderColumnValues) {
        $reportTableHeaderElements = $this->getColumnHeaders($pHeaderRowNumber);

        $countHeaderCells = count($reportTableHeaderElements);
        for($i=0; $i<$countHeaderCells; $i++) {
            $this->assertEquals(
                $pHeaderColumnValues[$i],
                $reportTableHeaderElements[$i]->getText()
            );
        }
    }

    private function assertTableDataIsCorrect($pExpectedTableData) {
        //Test each row is correct
        $actualTableDataElements = $this->getTableData();
        $actualTableData = $this->convertTableDataElementsToTable($actualTableDataElements);

        $this->assertEquals($pExpectedTableData, $actualTableData);
    }

    private function selectReportFromSelectionBox($pReportNumber) {
        $selectElement = $this->driver->findElement(WebDriverBy::id('reportName'));
        $select = new \Facebook\WebDriver\WebDriverSelect($selectElement);
        $select->selectByValue($pReportNumber);
    }

    private function selectReportYear($pYear) {
        $selectElement = $this->driver->findElement(WebDriverBy::name('season'));
        $select = new \Facebook\WebDriver\WebDriverSelect($selectElement);
        $select->selectByValue($pYear);
    }

    public function test_DisplayReport1() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_SENIORS);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD);
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL);
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS);
        $this->assertSearchRowIsShown();

        //Assert table
        $expectedSecondRowHeaders = array(
            '',
            'Anglesea',
            'Barwon Heads',
            'Drysdale',
            'Geelong Amateur',
            'Modewarre',
            'Newcomb Power',
            'Ocean Grove',
            'Portarlington',
            'Queenscliff',
            'Torquay'
        );

        $this->assertColumnHeadersAreCorrect(array("Name\nUmpire_Name_First_Last", $this::LEAGUE_BFL), $expectedSecondRowHeaders);
        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT1_TEST1);
    }

    public function test_DisplayReport2() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_GFL);
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_BOUNDARY);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_SENIORS);
        $this->clickElement($this::AGE_GROUP_RESERVES);

        //Change report
        $this->selectReportFromSelectionBox(2);

        //Change year
        $this->selectReportYear($this::YEAR_2018);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY);
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL);
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES);
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Name", $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, 'Total'),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GFL, '2 Umpires', $this::LEAGUE_BFL, $this::LEAGUE_GFL, '')
        );
        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT2_TEST1); //
    }

    public function test_DisplayReport3_Geelong() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Change report
        $this->selectReportFromSelectionBox(3);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Week (Sat)", "No Seniors Boundary", "No Seniors Goal", "No Reserves Goal", "No Colts Field", "No Under 16 Field", "No Under 14 Field", "No Under 12 Field"),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total", $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total", $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total",
                $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total")
        );

        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT3_TEST1);
    }

    public function test_DisplayReport3_Colac() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_COLAC);

        //Change report
        $this->selectReportFromSelectionBox(3);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Week (Sat)", "No Seniors Boundary", "No Seniors Goal", "No Reserves Goal", "No Colts Field", "No Under 16 Field", "No Under 14 Field", "No Under 12 Field"),
            array('', $this::LEAGUE_CDFNL, "Total", $this::LEAGUE_CDFNL, "Total", $this::LEAGUE_CDFNL, "Total",
                "Total", "Total", "Total", "Total")
        );

        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT3_TEST2);
    }

    public function test_DisplayReport4_Geelong() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Change report
        $this->selectReportFromSelectionBox(4);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Club", $this::UMP_TYPE_BOUNDARY, $this::UMP_TYPE_FIELD, $this::UMP_TYPE_GOAL),
            array('', $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U19, $this::AGE_GROUP_U16, $this::AGE_GROUP_U14,
                $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U15G, $this::AGE_GROUP_U12G,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U16, $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U12G,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U16, $this::AGE_GROUP_U14,
                $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U15G, $this::AGE_GROUP_U12G),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GFL, $this::LEAGUE_GDFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GFL, $this::LEAGUE_GDFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_BFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL)
        );

        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT4_TEST1);
    }

    public function test_DisplayReport4_Colac() {
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_COLAC);

        //Change report
        $this->selectReportFromSelectionBox(4);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Club", $this::UMP_TYPE_BOUNDARY, $this::UMP_TYPE_FIELD, $this::UMP_TYPE_GOAL),
            array('', $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145,
                $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145),
            array('', $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL,
                $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL,
                $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL)
        );

        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT4_TEST2);
    }

}