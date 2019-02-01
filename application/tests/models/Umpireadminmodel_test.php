<?php

class Umpireadminmodel_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Umpireadminmodel');
        $this->CI->load->model('Umpire');
        $this->obj = $this->CI->Umpireadminmodel;
    }

    public function test_GetAllUmpiresAndValues_ManyRecords() {
        $arrayStore = new Array_store_umpire_admin();
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);

        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 120, 50, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 5, 3, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 120, 0, 12, 0);


        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);
        $expectedCount = count($expectedUmpireArray);
        $actualCount = count($actualUmpireArray);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[1]->getFirstName(), $actualUmpireArray[1]->getFirstName());
        $this->assertEquals($expectedUmpireArray[2]->getLastName(), $actualUmpireArray[2]->getLastName());

    }


    public function test_GetAllUmpiresAndValues_NoRecords() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_umpire_admin_empty();
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);

    }


    public function test_UpdateUmpireValues_Valid() {
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array(
            1=>array(
                'first_name'=>'John',
                'last_name'=>'Smith',
                'geelong_prior'=>130,
                'other_leagues'=>45
            )
        );

        $this->obj->updateUmpireGameValues($arrayStore, $postArray);

        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);

        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 130, 45, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 5, 3, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 12, 0, 0, 0);

        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);

        $this->assertEquals($expectedUmpireArray[0]->getFirstName(), $actualUmpireArray[0]->getFirstName());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedOtherLeagues(), $actualUmpireArray[0]->getGamesPlayedOtherLeagues());

        $this->assertEquals($expectedUmpireArray[1]->getFirstName(), $actualUmpireArray[1]->getFirstName());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedPrior(), $actualUmpireArray[1]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedOtherLeagues(), $actualUmpireArray[1]->getGamesPlayedOtherLeagues());

        $this->assertEquals($expectedUmpireArray[2]->getFirstName(), $actualUmpireArray[2]->getFirstName());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedPrior(), $actualUmpireArray[2]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedOtherLeagues(), $actualUmpireArray[2]->getGamesPlayedOtherLeagues());

    }

    public function test_UpdateUmpireValues_NoValuesInArray() {
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array();
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);
        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 120, 50, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 5, 3, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 12, 0, 0, 0);
        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);
        $this->assertEquals($expectedUmpireArray[0]->getFirstName(), $actualUmpireArray[0]->getFirstName());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedOtherLeagues(), $actualUmpireArray[0]->getGamesPlayedOtherLeagues());
    }

    public function test_UpdateUmpireValues_NoMatchingUmpires() {
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array(
            4=>array(
                'first_name'=>'John',
                'last_name'=>'Smith',
                'geelong_prior'=>129,
                'other_leagues'=>13
            )
        );
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);
        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 120, 50, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 5, 3, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 12, 0, 0, 0);
        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);
        $this->assertEquals($expectedUmpireArray[0]->getFirstName(), $actualUmpireArray[0]->getFirstName());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedOtherLeagues(), $actualUmpireArray[0]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[1]->getFirstName(), $actualUmpireArray[1]->getFirstName());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedPrior(), $actualUmpireArray[1]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedOtherLeagues(), $actualUmpireArray[1]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[2]->getFirstName(), $actualUmpireArray[2]->getFirstName());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedPrior(), $actualUmpireArray[2]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedOtherLeagues(), $actualUmpireArray[2]->getGamesPlayedOtherLeagues());
    }

    public function test_UpdateUmpireValues_ArrayHasText() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array(
            2=>array(
                'first_name'=>'John',
                'last_name'=>'Smith',
                'geelong_prior'=>'Sometext',
                'other_leagues'=>'Moretext'
            )
        );
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);
    }


    public function test_UpdateUmpireValues_MultipleUpdates() {
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array(
            1=>array(
                'first_name'=>'John',
                'last_name'=>'Smith',
                'geelong_prior'=>129,
                'other_leagues'=>13
            ),
            2=>array(
                'first_name'=>'Sue',
                'last_name'=>'Jones',
                'geelong_prior'=>9,
                'other_leagues'=>51
            )
        );
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);
        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 129, 13, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 9, 51, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 12, 0, 0, 0);
        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);
        $this->assertEquals($expectedUmpireArray[0]->getFirstName(), $actualUmpireArray[0]->getFirstName());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedOtherLeagues(), $actualUmpireArray[0]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[1]->getFirstName(), $actualUmpireArray[1]->getFirstName());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedPrior(), $actualUmpireArray[1]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedOtherLeagues(), $actualUmpireArray[1]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[2]->getFirstName(), $actualUmpireArray[2]->getFirstName());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedPrior(), $actualUmpireArray[2]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedOtherLeagues(), $actualUmpireArray[2]->getGamesPlayedOtherLeagues());
    }


    public function test_UpdateUmpireValues_NoChanges() {
        $arrayStore = new Array_store_umpire_admin();
        $postArray = array(
            1=>array(
                'first_name'=>'John',
                'last_name'=>'Smith',
                'geelong_prior'=>120,
                'other_leagues'=>50
            ),
            2=>array(
                'first_name'=>'Sue',
                'last_name'=>'Jones',
                'geelong_prior'=>5,
                'other_leagues'=>3
            )
        );
        $this->obj->updateUmpireGameValues($arrayStore, $postArray);
        $actualUmpireArray = $this->obj->getAllUmpiresAndValues($arrayStore);
        $umpire1 = Umpire::createUmpireAllData(1, 'John', 'Smith', 120, 50, 0, 0);
        $umpire2 = Umpire::createUmpireAllData(2, 'Sue', 'Jones', 5, 3, 0, 0);
        $umpire3 = Umpire::createUmpireAllData(3, 'Mark', 'Brown', 12, 0, 0, 0);
        $expectedUmpireArray = array ($umpire1, $umpire2, $umpire3);
        $this->assertEquals($expectedUmpireArray[0]->getFirstName(), $actualUmpireArray[0]->getFirstName());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedPrior(), $actualUmpireArray[0]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[0]->getGamesPlayedOtherLeagues(), $actualUmpireArray[0]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[1]->getFirstName(), $actualUmpireArray[1]->getFirstName());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedPrior(), $actualUmpireArray[1]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[1]->getGamesPlayedOtherLeagues(), $actualUmpireArray[1]->getGamesPlayedOtherLeagues());
        $this->assertEquals($expectedUmpireArray[2]->getFirstName(), $actualUmpireArray[2]->getFirstName());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedPrior(), $actualUmpireArray[2]->getGamesPlayedPrior());
        $this->assertEquals($expectedUmpireArray[2]->getGamesPlayedOtherLeagues(), $actualUmpireArray[2]->getGamesPlayedOtherLeagues());
    }



}