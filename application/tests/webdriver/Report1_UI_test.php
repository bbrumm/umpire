<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Report1_UI_test extends TestCase
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

    //Age Group: Seniors, Reserves, Colts, Under 19, Under 18, Under 17.5, Under 17, Under 16, Under 15, Under 14.5, Under 14, Under 13, Under 12, Under 19 Girls, Under 18 Girls, Under 15 Girls, Under 12 Girls, Youth Girls, Junior Girls,'
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
        array('01/04/2017', '', '', '', '', '', '', 'Thomson vs Bannockburn,Anakie vs Werribee Centrals', 'St Albans vs South Barwon', 3, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('08/04/2017', 'Newcomb vs Queenscliff', 'Werribee Centrals vs Winchelsea', '', 2, '', '', '', 'St Joseph\'s vs Bell Park', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('15/04/2017', '', '', '', '', '', 'Portarlington vs Newcomb', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('22/04/2017', '', 'Winchelsea vs Corio', '', 1, '', '', '', 'North Shore vs Colac', 1, '', '', '', '', '', '', '', '', '', 'Ogcc 3 vs St Joseph\'s 3,Ogcc 2 vs Geelong West Giants 2', 2, 'Swans Blue vs Lara Batman,Grovedale Smith vs Barwon Heads Gulls,Modewarre vs Barwon Heads Heads,Torquay Milliken vs Swans White,Flying Joeys vs Saints Blue,Drysdale Butcher vs Grovedale Dale,Bell Post Hill vs Little River,Aireys Eels vs Anglesea,Portarlington Blue vs Swans Red,Torquay Browning vs Lara Flinders,Corio vs Seagulls,Tigers Black vs Bell Park,St Albans Grinter vs Lara Hume,Ammos Blue vs Ammos Green,Leopold Brown vs Torquay Davies,Torquay Bruce vs Leopold Dowsett,Giants vs Ammos White,Torquay Stone vs Torquay Coleman,Portarlington Red vs Newcomb,Thomson vs Belmont Lions,Drysdale Wilton vs Ogcc Orange,Ogcc Blue vs Queenscliff,Saints Green vs Eagles,Leaping Joeys vs Tigers Gold,Inverleigh vs Lethbridge,Ogcc White vs Grovedale Delaney,Torquay Watson vs Winchelsea', 27),
        array('29/04/2017', 'Newcomb vs Geelong Amateur', '', '', 2, 'Drysdale vs Spotswood', '', '', 'Grovedale vs Leopold', 2, 'Drysdale vs Spotswood', '', '', '', '', '', '', '', '', '', '', 'Lara Flinders vs St Albans Grinter,Little River vs Corio,Winchelsea vs Inverleigh,Saints Blue vs Saints Green,Queenscliff vs Leopold Brown,Swans White vs Aireys Eels,Newcomb vs Torquay Watson,Ogcc Orange vs Torquay Bruce,Belmont Lions vs Portarlington Blue,Tigers Gold vs Ammos Blue,Barwon Heads Heads vs Torquay Milliken,Bell Park vs Leaping Joeys,Torquay Davies vs Drysdale Butcher,Lara Hume vs Swans Blue,Torquay Coleman vs Portarlington Red,Giants vs Ammos Green,Leopold Dowsett vs Ogcc White,Grovedale Delaney vs Drysdale Wilton,Seagulls vs Torquay Stone,Torquay Browning vs Lara Batman,Lethbridge vs Bell Post Hill,Eagles vs Tigers Black,Anglesea vs Modewarre,Grovedale Dale vs Grovedale Smith,Swans Red vs Thomson,Barwon Heads Gulls vs Ogcc Blue,Ammos White vs Flying Joeys', 27),
        array('06/05/2017', 'Queenscliff vs Anglesea', '', '', 1, '', '', '', '', '', '', '', '', '', '', 'Inverleigh vs Drysdale 2', 1, 'Anglesea vs Barwon Heads', 1, 'Drysdale Grigg vs Bell Park 1', 1, 'Grovedale Smith vs Torquay Bruce,Leaping Joeys vs Eagles,Torquay Browning vs Swans Blue,St Albans Grinter vs Queenscliff,Ogcc Blue vs Leopold Brown,Leopold Dowsett vs Grovedale Delaney,Anglesea vs Torquay Watson,Grovedale Dale vs Ogcc Orange,Corio vs Inverleigh,Bell Park vs Ammos Blue,Ammos Green vs Tigers Gold,Newcomb vs Lethbridge,Drysdale Wilton vs Portarlington Red,Saints Green vs Ammos White,Swans White vs Winchelsea,Torquay Davies vs Barwon Heads Heads,Thomson vs Portarlington Blue,Bell Post Hill vs Swans Red,Seagulls vs Little River,Lara Batman vs Drysdale Butcher,Barwon Heads Gulls vs Lara Hume,Belmont Lions vs Aireys Eels,Torquay Coleman vs Modewarre,Torquay Milliken vs Ogcc White,Flying Joeys vs Giants,Lara Flinders vs Torquay Stone,Tigers Black vs Saints Blue', 27),
        array('13/05/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', '', '', '', 'Ocean Grove 2 vs Modewarre', 1, '', '', 'Torquay Bruce vs St Albans Grinter,Portarlington Blue vs Torquay Coleman,Modewarre vs Bell Post Hill,Little River vs Lara Batman,Barwon Heads Heads vs Grovedale Dale,Ogcc Orange vs Swans Red,Eagles vs Ammos Blue,Saints Blue vs Leaping Joeys,Tigers Gold vs Bell Park,Torquay Stone vs Torquay Browning,Lara Hume vs Ogcc Blue,Ammos Green vs Flying Joeys,Grovedale Delaney vs Torquay Davies,Swans White vs Seagulls,Aireys Eels vs Torquay Watson,Corio vs Newcomb,Ammos White vs Tigers Black,Inverleigh vs Belmont Lions,Queenscliff vs Leopold Dowsett,Swans Blue vs Lara Flinders,Winchelsea vs Anglesea,Lethbridge vs Thomson,Portarlington Red vs Grovedale Smith,Ogcc White vs Barwon Heads Gulls,Drysdale Butcher vs Torquay Milliken,Giants vs Saints Green,Leopold Brown vs Drysdale Wilton', 27),
        array('20/05/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', '', '', '', 'Drysdale Richardson vs Geelong Amateur 2', 1, 'Drysdale Grigg vs Torquay Bumpstead', 1, 'Queenscliff vs Torquay Browning,Bell Park vs Ammos Green,Portarlington Red vs Lara Flinders,Swans Red vs Drysdale Wilton,Tigers Gold vs Eagles,Torquay Milliken vs Torquay Bruce,Bell Post Hill vs Corio,Leopold Dowsett vs Torquay Davies,Saints Green vs Flying Joeys,Ammos Blue vs Saints Blue,Lara Batman vs Aireys Eels,Torquay Stone vs Grovedale Dale,Modewarre vs Swans White,Seagulls vs Anglesea,Little River vs Newcomb,Inverleigh vs Thomson,Barwon Heads Gulls vs Grovedale Delaney,Tigers Black vs Giants,Ogcc White vs Swans Blue,Lara Hume vs Barwon Heads Heads,St Albans Grinter vs Ogcc Blue,Leaping Joeys vs Ammos White,Drysdale Butcher vs Torquay Coleman,Leopold Brown vs Grovedale Smith,Torquay Watson vs Ogcc Orange,Portarlington Blue vs Winchelsea,Lethbridge vs Belmont Lions', 27),
        array('27/05/2017', 'Queenscliff vs Portarlington', '', '', 2, 'Drysdale vs Spotswood', '', '', 'Geelong West Giants vs Lara', 1, '', '', '', '', '', '', '', 'Drysdale Richardson vs Leopold Butteriss', 1, '', '', 'Ogcc White vs Lara Hume,Inverleigh vs Bell Post Hill,Torquay Coleman vs Torquay Milliken,Lara Flinders vs Queenscliff,Torquay Davies vs St Albans Grinter,Barwon Heads Heads vs Leopold Brown,Lara Batman vs Winchelsea,Flying Joeys vs Tigers Black,Grovedale Dale vs Leopold Dowsett,Torquay Watson vs Ogcc Blue,Saints Blue vs Tigers Gold,Anglesea vs Portarlington Blue,Eagles vs Bell Park,Drysdale Butcher vs Torquay Stone,Thomson vs Modewarre,Lethbridge vs Corio,Ammos Green vs Saints Green,Torquay Browning vs Barwon Heads Gulls,Newcomb vs Swans White,Grovedale Smith vs Ogcc Orange,Drysdale Wilton vs Seagulls,Aireys Eels vs Swans Red,Swans Blue vs Portarlington Red,Little River vs Belmont Lions,Grovedale Delaney vs Torquay Bruce,Giants vs Leaping Joeys,Ammos White vs Ammos Blue', 27),
        array('03/06/2017', '', 'Bannockburn vs Corio', '', 1, '', '', '', 'Bell Park vs South Barwon', 1, '', '', '', '', '', '', '', 'Modewarre vs Grovedale 2', 1, 'Modewarre / Winchelsea vs Geelong West Giants 1', 1, 'Anglesea vs Lara Batman,Torquay Coleman vs Grovedale Dale,Grovedale Delaney vs Swans Blue,Ogcc Blue vs Grovedale Smith,Corio vs Thomson,Portarlington Blue vs Drysdale Wilton,Ammos Blue vs Saints Green,Ogcc Orange vs Torquay Davies,Barwon Heads Heads vs St Albans Grinter,Saints Blue vs Ammos Green,Inverleigh vs Newcomb,Leaping Joeys vs Tigers Black,Eagles vs Ammos White,Portarlington Red vs Torquay Browning,Torquay Stone vs Ogcc White,Barwon Heads Gulls vs Lara Flinders,Seagulls vs Aireys Eels,Flying Joeys vs Tigers Gold,Bell Post Hill vs Lethbridge,Winchelsea vs Swans Red,Belmont Lions vs Modewarre,Leopold Dowsett vs Drysdale Butcher,Torquay Bruce vs Queenscliff,Lara Hume vs Torquay Milliken,Bell Park vs Giants,Leopold Brown vs Torquay Watson,Swans White vs Little River', 27),
        array('10/06/2017', '', '', '', '', '', 'Modewarre vs Queenscliff', 'Bell Post Hill vs Thomson', 'Bell Park vs Grovedale,Newtown & Chilwell vs Lara', 4, '', '', '', '', '', 'North Shore vs Winchelsea', 1, 'Geelong Amateur 1 vs St Albans Osborne,Ocean Grove 2 vs Grovedale 2,North Shore vs Anglesea', 3, 'Ogcc 1 vs St Mary\'s 1,Geelong Amateur 1 vs Newtown & Chilwell 2', 2, 'Lara Batman vs Drysdale Wilton,Ammos Green vs Tigers Black,St Albans Grinter vs Leopold Brown,Ogcc Orange vs Barwon Heads Heads,Ammos White vs Bell Park,Belmont Lions vs Thomson,Corio vs Inverleigh,Swans Red vs Anglesea,Lara Hume vs Torquay Coleman,Ogcc White vs Leopold Dowsett,Swans Blue vs Barwon Heads Gulls,Newcomb vs Modewarre,Grovedale Smith vs Torquay Watson,Tigers Gold vs Giants,Lara Flinders vs Torquay Bruce,Portarlington Blue vs Seagulls,Saints Blue vs Eagles,Aireys Eels vs Winchelsea,Bell Post Hill vs Little River,Grovedale Dale vs Drysdale Butcher,Grovedale Delaney vs Torquay Browning,Portarlington Red vs Queenscliff,Swans White vs Lethbridge,Torquay Milliken vs Torquay Stone,Ammos Blue vs Flying Joeys,Ogcc Blue vs Torquay Davies,Saints Green vs Leaping Joeys', 27),
        array('17/06/2017', '', '', '', '', '', 'Newcomb vs Portarlington', '', '', 2, 'Drysdale vs Spotswood', '', '', '', '', '', '', 'Modewarre vs Ocean Grove 2', 1, 'Geelong Amateur 3 vs Torquay Pyers,Geelong Amateur 2 vs Belmont Lions', 2, 'Modewarre vs Seagulls,Torquay Stone vs Grovedale Delaney,Torquay Coleman vs Portarlington Red,Swans Blue vs Queenscliff,Torquay Watson vs Barwon Heads Heads,Ammos White vs Tigers Gold,Ogcc Blue vs Leopold Brown,Leaping Joeys vs Flying Joeys,Leopold Dowsett vs Lara Hume,Ogcc White vs Drysdale Butcher,Bell Park vs Saints Blue,Grovedale Dale vs Torquay Milliken,Ammos Green vs Eagles,Swans Red vs Portarlington Blue,Torquay Bruce vs Barwon Heads Gulls,St Albans Grinter vs Ogcc Orange,Lara Flinders vs Torquay Browning,Tigers Black vs Saints Green,Ammos Blue vs Giants,Little River vs Lethbridge,Swans White vs Bell Post Hill,Belmont Lions vs Inverleigh,Drysdale Wilton vs Aireys Eels,Newcomb vs Thomson,Winchelsea vs Anglesea,Torquay Davies vs Grovedale Smith,Lara Batman vs Corio', 27),
        array('24/06/2017', '', '', '', 1, 'Drysdale vs Spotswood', 'Torquay vs Ocean Grove', '', 'Colac vs Geelong West Giants', 3, 'Drysdale vs Spotswood', '', '', '', '', '', '', 'Ocean Grove 2 vs Queenscliff', 1, '', '', 'Aireys Eels vs Portarlington Blue,Queenscliff vs Barwon Heads Gulls,Lara Batman vs Swans White,Bell Post Hill vs Newcomb,Thomson vs Lethbridge,Seagulls vs Anglesea,Ammos Green vs Leaping Joeys,Torquay Watson vs Torquay Davies,Torquay Bruce vs Portarlington Red,Giants vs Eagles,Lara Flinders vs Torquay Coleman,Ammos White vs Saints Blue,Modewarre vs St Albans Grinter,Winchelsea vs Drysdale Wilton,Torquay Browning vs Swans Blue,Torquay Stone vs Lara Hume,Barwon Heads Heads vs Ogcc Blue,Drysdale Butcher vs Grovedale Delaney,Corio vs Belmont Lions,Grovedale Smith vs Leopold Brown,Tigers Black vs Ammos Blue,Little River vs Inverleigh,Saints Green vs Tigers Gold,Torquay Milliken vs Leopold Dowsett,Flying Joeys vs Bell Park,Ogcc Orange vs Swans Red,Grovedale Dale vs Ogcc White', 27),
        array('01/07/2017', '', '', '', '', '', '', 'Bell Post Hill vs Corio', 'North Shore vs St Mary\'s', 2, '', '', '', '', '', '', '', 'Queenscliff vs Grovedale 2,Lara 2 vs Ocean Grove 3', 2, 'Torquay Dunstan vs St Mary\'s 2,Torquay Pyers vs Ogcc 3', 2, 'Torquay Browning vs Torquay Bruce,Bell Park vs Saints Green,Torquay Milliken vs Drysdale Butcher,Barwon Heads Heads vs Grovedale Smith,Swans White vs Belmont Lions,Leopold Dowsett vs Torquay Stone,Seagulls vs Swans Red,Grovedale Delaney vs Ogcc White,Lethbridge vs Newcomb,Saints Blue vs Giants,Tigers Gold vs Tigers Black,Lara Batman vs Bell Post Hill,Corio vs Little River,Ogcc Blue vs Torquay Watson,Portarlington Blue vs Modewarre,Eagles vs Flying Joeys,Queenscliff vs Torquay Coleman,Swans Blue vs Lara Flinders,Barwon Heads Gulls vs Portarlington Red,Lara Hume vs Grovedale Dale,Leopold Brown vs Torquay Davies,Thomson vs Inverleigh,Anglesea vs Drysdale Wilton,Aireys Eels vs Ogcc Orange,St Albans Grinter vs Winchelsea,Ammos White vs Ammos Green,Ammos Blue vs Leaping Joeys', 27),
        array('15/07/2017', '', '', 'Leopold vs St Albans', 2, 'North Geelong vs Spotswood', 'Modewarre vs Newcomb', '', '', 2, 'North Geelong vs Spotswood', '', '', '', '', 'North Shore vs Drysdale 2', 1, 'Inverleigh vs Ocean Grove 3', 1, '', '', 'Leopold Brown vs Barwon Heads Heads,Portarlington Red vs Lara Flinders,Ogcc White vs Torquay Milliken,Swans Red vs Aireys Eels,Newcomb vs Little River,Torquay Davies vs Ogcc Blue,Eagles vs Saints Green,Bell Post Hill vs Thomson,St Albans Grinter vs Anglesea,Grovedale Delaney vs Leopold Dowsett,Saints Blue vs Flying Joeys,Tigers Gold vs Leaping Joeys,Swans White vs Corio,Modewarre vs Ogcc Orange,Inverleigh vs Lara Batman,Portarlington Blue vs Winchelsea,Giants vs Ammos White,Torquay Watson vs Grovedale Smith,Bell Park vs Tigers Black,Torquay Coleman vs Barwon Heads Gulls,Drysdale Butcher vs Lara Hume,Queenscliff vs Torquay Browning,Torquay Stone vs Grovedale Dale,Belmont Lions vs Lethbridge,Ammos Green vs Ammos Blue,Drysdale Wilton vs Seagulls,Torquay Bruce vs Swans Blue', 27),
        array('22/07/2017', '', '', '', '', '', '', 'Corio vs Winchelsea', 'St Joseph\'s vs North Shore', 2, '', '', '', '', '', 'Newcomb vs Inverleigh', 1, 'Drysdale Richardson vs St Joseph\'s Jackman', 1, '', '', 'Giants vs Ammos Green,Grovedale Smith vs Ogcc Blue,Barwon Heads Gulls vs Torquay Browning,Belmont Lions vs Bell Post Hill,Seagulls vs St Albans Grinter,Ammos Blue vs Tigers Gold,Leaping Joeys vs Bell Park,Lara Hume vs Ogcc White,Torquay Bruce vs Torquay Coleman,Saints Green vs Saints Blue,Drysdale Wilton vs Swans Red,Newcomb vs Corio,Flying Joeys vs Ammos White,Little River vs Lara Batman,Torquay Milliken vs Grovedale Delaney,Portarlington Blue vs Anglesea,Torquay Watson vs Leopold Brown,Portarlington Red vs Swans Blue,Tigers Black vs Eagles,Aireys Eels vs Modewarre,Drysdale Butcher vs Torquay Stone,Torquay Davies vs Barwon Heads Heads,Winchelsea vs Ogcc Orange,Thomson vs Swans White,Lethbridge vs Inverleigh,Queenscliff vs Lara Flinders,Leopold Dowsett vs Grovedale Dale', 27),
        array('29/07/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', 'Bell Park vs St Joseph\'s', 2, 'North Geelong vs Spotswood', '', '', '', '', '', '', '', '', 'Queenscliff vs Newtown & Chilwell 2', 1, 'Flying Joeys vs Giants,Leopold Brown vs Ogcc Blue,Barwon Heads Heads vs Torquay Watson,Saints Green vs Ammos White,Torquay Browning vs Portarlington Red,Torquay Milliken vs Lara Hume,Corio vs Bell Post Hill,Drysdale Butcher vs Leopold Dowsett,Swans Blue vs Torquay Coleman,St Albans Grinter vs Portarlington Blue,Grovedale Dale vs Grovedale Delaney,Little River vs Thomson,Torquay Stone vs Ogcc White,Seagulls vs Aireys Eels,Swans Red vs Winchelsea,Ammos Blue vs Bell Park,Torquay Davies vs Grovedale Smith,Anglesea vs Modewarre,Queenscliff vs Torquay Bruce,Lara Flinders vs Barwon Heads Gulls,Eagles vs Leaping Joeys,Lara Batman vs Lethbridge,Ogcc Orange vs Drysdale Wilton,Tigers Gold vs Ammos Green,Inverleigh vs Swans White,Tigers Black vs Saints Blue,Newcomb vs Belmont Lions', 27),
        array('05/08/2017', '', '', '', 2, 'Drysdale vs Spotswood,North Geelong vs Spotswood', '', '', 'St Mary\'s vs South Barwon', 3, 'North Geelong vs Spotswood,Drysdale vs Spotswood', '', '', '', '', '', '', 'Drysdale Ruggles vs Torquay Papworth', 1, '', '', 'Saints Green vs Giants,Leaping Joeys vs Saints Blue,Barwon Heads Gulls vs Swans Blue,Ogcc Blue vs Barwon Heads Heads,Queenscliff vs Portarlington Red,Modewarre vs Drysdale Wilton,Winchelsea vs Seagulls,Torquay Coleman vs Torquay Browning,Swans White vs Newcomb,Eagles vs Ammos Blue,Lara Hume vs Torquay Stone,Lethbridge vs Corio,Belmont Lions vs Little River,Bell Park vs Tigers Gold,Ogcc White vs Grovedale Dale,Bell Post Hill vs Inverleigh,Ammos White vs Tigers Black,Flying Joeys vs Ammos Green,Swans Red vs St Albans Grinter,Ogcc Orange vs Portarlington Blue,Grovedale Delaney vs Drysdale Butcher,Torquay Davies vs Torquay Watson,Grovedale Smith vs Leopold Brown,Leopold Dowsett vs Torquay Milliken,Anglesea vs Aireys Eels,Thomson vs Lara Batman,Lara Flinders vs Torquay Bruce', 27),
        array('12/08/2017', '', '', '', 1, 'North Geelong vs Spotswood', '', '', '', 2, 'North Geelong vs Spotswood,Drysdale vs Spotswood', '', '', '', '', 'Belmont Lions vs Drysdale 2,Corio vs Newcomb', 2, 'Geelong Amateur 1 vs Bannockburn 1', 1, 'Geelong Amateur 2 vs Corio', 1, 'Bell Post Hill vs Swans White,Lethbridge vs Little River,Swans Red vs Modewarre,Tigers Gold vs Eagles,Leopold Brown vs Torquay Davies,Tigers Black vs Saints Green,Giants vs Ammos Blue,Torquay Coleman vs Lara Flinders,Grovedale Dale vs Torquay Milliken,Leopold Dowsett vs Lara Hume,Barwon Heads Heads vs Grovedale Smith,Drysdale Wilton vs St Albans Grinter,Ogcc Blue vs Torquay Watson,Anglesea vs Ogcc Orange,Leaping Joeys vs Flying Joeys,Drysdale Butcher vs Ogcc White,Swans Blue vs Torquay Browning,Belmont Lions vs Lara Batman,Ammos Green vs Bell Park,Seagulls vs Portarlington Blue,Saints Blue vs Ammos White,Torquay Stone vs Grovedale Delaney,Inverleigh vs Newcomb,Aireys Eels vs Winchelsea,Barwon Heads Gulls vs Queenscliff,Thomson vs Corio,Torquay Bruce vs Portarlington Red', 27),
        array('19/08/2017', '', '', '', '', '', '', 'Inverleigh vs Corio', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('26/08/2017', '', '', 'Leopold vs North Shore', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')
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

    public function test_DisplayReport3() {
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

}