<?php

class UI_test_expected_results extends CI_Model {

    public function __construct() {

    }

    //public const EXPECTED_DATA_REPORT6_COLHEADERS =
    //    array("Umpire Name\nUmpire_Name_First_Last", 'Abbott, Trevor', 'Abrehart, Jack', 'Abrehart, Thomas', 'Allcorn, Mason', 'Anderson, Nicholas', 'Armstrong, Bree', 'Armstrong, Dean', 'Armstrong, Halle', 'Armstrong, Wayne', 'Baker-brooks, Jake', 'Barrand, Michael', 'Bell, Chris', 'Beveridge, Brendan', 'Bisinella, Tiana', 'Boardman, Matthew', 'Boom, Marcus', 'Boyd, Christopher', 'Brayshaw, James', 'Brew, Alana', 'Brew, Kaylie', 'Brown, Joshua', 'Buckley, Matthew', 'Burns, Hayden', 'Burns, Noah', 'Bury, Nathan', 'Callander, Timothy', 'Cannard, Patrick', 'Carruthers, Chris', 'Chaston, David', 'Chaston, Samuel', 'Clissold, Kelvin', 'Crocker, Oscar', 'Crucitti, Jess', 'D\'Alessandro, Mark', 'De Been, Rebecca', 'Dean, Darryl', 'Dickson, Eric', 'Dodds, Harry', 'Dorling, Daniel', 'Dougherty, Liam', 'Edwards, Callum', 'Elliott, Peter', 'Facey, Joshua', 'Formosa, Jason', 'Gahan, Darren', 'Geall, Cooper', 'Gee, Isaac', 'Graham, Michael', 'Grills, Ethan', 'Grills, Jonathan', 'Grist, Lachlan', 'Guarnaccia, Steve', 'Guy, Andrew', 'Guy, Jaymee', 'Haines, Lachlan', 'Hamill-beach, Rhys', 'Hamilton, Jake', 'Hastie, Jack', 'Hay, Raymond', 'Helwig, Kieren', 'Hill, Noah', 'Hodgart, Benjamin', 'Hodge, Callum', 'Hollis, Paul', 'House, David', 'Irving, Levi', 'James, Joshua', 'Jones, Adam', 'Jones, Christopher', 'Jones, Paul', 'Keating, Steve', 'Kerr, Craig', 'Kerr, William', 'Knight, Linda', 'Knight, Rob', 'Kramme, David', 'Kramme, Jayde', 'Laffy, Finn', 'Lawrence, Roy', 'Lobbe, Dean', 'Lunt, Jordan', 'Lynch, Ashliegh', 'Lyon, James', 'Maiden, James', 'Martin, Harvey', 'Mazaraki, Oscar', 'Mccosh, Jason', 'Mcdonald, Stephen', 'Mcdowell, Paul', 'Mcelhinney, Paul', 'Mcglade, Aaron', 'Mckenzie, Molly', 'Mckenzie, Rodney', 'Mcmaster, Damian', 'Mcmillan, Craig', 'Menzies, Hugh', 'Milligan, James', 'Mirabile, Salvatore', 'Morris, Justin', 'Moyle, Aiden', 'Nelson, Regan', 'Neville, Rick', 'Nisbet, William', 'Noble, Reece', 'Nolan, Mark', 'Nuessler, Peter', 'O\'Dwyer, Bernard', 'O\'Neill, Jack', 'Oggero, William', 'Oldfield, Craig', 'Ozols, Peter', 'Palmer, Anthony', 'Palmer, Jacob', 'Parrello, Dean', 'Peck, Jonathan', 'Peeler, Benjamin', 'Place, Cameron', 'Pratt, Matthew', 'Previti, Frank', 'Rae, Gabriel', 'Rakas-hoare, Brandon', 'Ramsay, Monique', 'Rankin, Bradley', 'Reid, Davin', 'Richardson, James', 'Riches, Aaron', 'Richmond-craig, Brandon', 'Robbins, Dale', 'Roberts, Trae', 'Robertson, Joshua', 'Robinson, Daniel', 'Robinson, Graeme', 'Ross, Will', 'Ross-watson, Nicholas', 'Scheitner, Amy', 'Scott, Leopold', 'Scott, Lionel', 'Shannon, Lykeira', 'Smith, Alastair', 'Smith, Bradley', 'Sykstus, Kyle', 'Tate, Benjamin', 'Tatnell, John', 'Tattersall, Austin', 'Timilsina, Saroj', 'Tingiri, Benjamin', 'Tingiri, Timothy', 'Tullis, Kelsey', 'Verdichizzi, Jess', 'Visintin, Jack', 'Visintin, Max', 'Waight, Jarrod', 'Wallis, Tomek', 'Watson, Nick', 'Webber, Brayden', 'Weber, Joel', 'Weber, Travis', 'Wekwerth, Karen', 'West, Peter', 'Whelan, Gerard', 'Wilkes, Lachlan', 'Williams, Daniel', 'Williams, Josh', 'Wood, Shalia', 'Wood, Taleitha', 'Young, Oscar');

    public const EXPECTED_DATA_REPORT6_COLHEADERS =
        array("Umpire Name\nUmpire_Name_First_Last", 'Abbott, Trevor', 'Beveridge, Brendan', 'Boom, Marcus', 'Bury, Nathan', 'Callander, Timothy', 'Carruthers, Chris', 'Crucitti, Jess', 'Dorling, Daniel', 'Elliott, Peter', 'Helwig, Kieren', 'Hollis, Paul', 'Jones, Adam', 'Jones, Christopher', 'Mcdowell, Paul', 'Mcglade, Aaron', 'Mcmaster, Damian', 'Palmer, Anthony', 'Peeler, Benjamin', 'Place, Cameron', 'Previti, Frank', 'Robertson, Joshua', 'Ross, Will', 'Tatnell, John', 'Tattersall, Austin', 'Verdichizzi, Jess', 'Watson, Nick');


    public const EXPECTED_DATA_REPORT6_TEST1 = array(
        array('Abbott, Trevor', '', 1, '', '', '', '', '', '', '', '', 1, 1, 1, '', '', 2, '', 1, '', '', '', '', '', 1, '', ''),
        array('Beveridge, Brendan', 1, '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Boom, Marcus', '', '', '', '', 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Bury, Nathan', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', ''),
        array('Callander, Timothy', '', '', 1, '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Carruthers, Chris', '', '', 1, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', 1),
        array('Crucitti, Jess', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', ''),
        array('Dorling, Daniel', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', ''),
        array('Elliott, Peter', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', ''),
        array('Helwig, Kieren', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', ''),
        array('Hollis, Paul', 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Jones, Adam', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', ''),
        array('Jones, Christopher', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
        array('Mcdowell, Paul', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', 1, '', '', '', '', '', '', '', ''),
        array('Mcglade, Aaron', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', 1, '', '', '', '', '', '', '', ''),
        array('Mcmaster, Damian', 2, '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', 1, '', '', '', '', 1, '', 1, ''),
        array('Palmer, Anthony', '', '', '', 1, '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Peeler, Benjamin', 1, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, '', '', '', '', '', '', '', '', '', ''),
        array('Place, Cameron', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, ''),
        array('Previti, Frank', '', '', '', '', '', '', 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Robertson, Joshua', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
        array('Ross, Will', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
        array('Tatnell, John', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', 1, ''),
        array('Tattersall, Austin', 1, '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', 1, '', '', '', '', '', 1, ''),
        array('Verdichizzi, Jess', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', 1, '', '', '', 1, 1, '', ''),
        array('Watson, Nick', '', '', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '', '', '', '', '')
    );
}