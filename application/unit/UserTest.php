<?php
require_once 'application/models/User.php';

class UserTest extends PHPUnit_Framework_TestCase
{

    private $user;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated UserTest::tearDown()
        $this->user = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests User->__construct()
     */
    public function test__construct()
    {
        // TODO Auto-generated UserTest->test__construct()
        $this->markTestIncomplete("__construct test not implemented");
        
        $this->user->__construct(/* parameters */);
    }

    
    /**
     * Tests User->setId()
     */
    public function testSetId()
    {
        $inputID = 1;
        $this->user->setId($inputID);
        $actualResult = $this->user->getID();
    
        $this->assertEquals($actualResult, $inputID);
    }
    
    
    /**
     * Tests User->getId()
     */
    public function testGetId()
    {
        // TODO Auto-generated UserTest->testGetId()
        $this->markTestIncomplete("getId test not implemented");
        
        $this->user->getId(/* parameters */);
    }

    /**
     * Tests User->getUsername()
     */
    public function testGetUsername()
    {
        // TODO Auto-generated UserTest->testGetUsername()
        $this->markTestIncomplete("getUsername test not implemented");
        
        $this->user->getUsername(/* parameters */);
    }

    /**
     * Tests User->getPassword()
     */
    public function testGetPassword()
    {
        // TODO Auto-generated UserTest->testGetPassword()
        $this->markTestIncomplete("getPassword test not implemented");
        
        $this->user->getPassword(/* parameters */);
    }

    /**
     * Tests User->getFirstName()
     */
    public function testGetFirstName()
    {
        // TODO Auto-generated UserTest->testGetFirstName()
        $this->markTestIncomplete("getFirstName test not implemented");
        
        $this->user->getFirstName(/* parameters */);
    }

    /**
     * Tests User->getLastName()
     */
    public function testGetLastName()
    {
        // TODO Auto-generated UserTest->testGetLastName()
        $this->markTestIncomplete("getLastName test not implemented");
        
        $this->user->getLastName(/* parameters */);
    }

    /**
     * Tests User->getRoleName()
     */
    public function testGetRoleName()
    {
        // TODO Auto-generated UserTest->testGetRoleName()
        $this->markTestIncomplete("getRoleName test not implemented");
        
        $this->user->getRoleName(/* parameters */);
    }

    /**
     * Tests User->getSubRoleName()
     */
    public function testGetSubRoleName()
    {
        // TODO Auto-generated UserTest->testGetSubRoleName()
        $this->markTestIncomplete("getSubRoleName test not implemented");
        
        $this->user->getSubRoleName(/* parameters */);
    }

    /**
     * Tests User->getPermissionArray()
     */
    public function testGetPermissionArray()
    {
        // TODO Auto-generated UserTest->testGetPermissionArray()
        $this->markTestIncomplete("getPermissionArray test not implemented");
        
        $this->user->getPermissionArray(/* parameters */);
    }

  

    /**
     * Tests User->setUsername()
     */
    public function testSetUsername()
    {
        // TODO Auto-generated UserTest->testSetUsername()
        $this->markTestIncomplete("setUsername test not implemented");
        
        $this->user->setUsername(/* parameters */);
    }

    /**
     * Tests User->setPassword()
     */
    public function testSetPassword()
    {
        // TODO Auto-generated UserTest->testSetPassword()
        $this->markTestIncomplete("setPassword test not implemented");
        
        $this->user->setPassword(/* parameters */);
    }

    /**
     * Tests User->setFirstName()
     */
    public function testSetFirstName()
    {
        // TODO Auto-generated UserTest->testSetFirstName()
        $this->markTestIncomplete("setFirstName test not implemented");
        
        $this->user->setFirstName(/* parameters */);
    }

    /**
     * Tests User->setLastName()
     */
    public function testSetLastName()
    {
        // TODO Auto-generated UserTest->testSetLastName()
        $this->markTestIncomplete("setLastName test not implemented");
        
        $this->user->setLastName(/* parameters */);
    }

    /**
     * Tests User->setRoleName()
     */
    public function testSetRoleName()
    {
        // TODO Auto-generated UserTest->testSetRoleName()
        $this->markTestIncomplete("setRoleName test not implemented");
        
        $this->user->setRoleName(/* parameters */);
    }

    /**
     * Tests User->setSubRoleName()
     */
    public function testSetSubRoleName()
    {
        // TODO Auto-generated UserTest->testSetSubRoleName()
        $this->markTestIncomplete("setSubRoleName test not implemented");
        
        $this->user->setSubRoleName(/* parameters */);
    }

    /**
     * Tests User->setPermissionArray()
     */
    public function testSetPermissionArray()
    {
        // TODO Auto-generated UserTest->testSetPermissionArray()
        $this->markTestIncomplete("setPermissionArray test not implemented");
        
        $this->user->setPermissionArray(/* parameters */);
    }

    /**
     * Tests User->login()
     */
    public function testLogin()
    {
        // TODO Auto-generated UserTest->testLogin()
        $this->markTestIncomplete("login test not implemented");
        
        $this->user->login(/* parameters */);
    }

    /**
     * Tests User->getUserFromUsername()
     */
    public function testGetUserFromUsername()
    {
        // TODO Auto-generated UserTest->testGetUserFromUsername()
        $this->markTestIncomplete("getUserFromUsername test not implemented");
        
        $this->user->getUserFromUsername(/* parameters */);
    }

    /**
     * Tests User->setPermissionArrayForUser()
     */
    public function testSetPermissionArrayForUser()
    {
        // TODO Auto-generated UserTest->testSetPermissionArrayForUser()
        $this->markTestIncomplete("setPermissionArrayForUser test not implemented");
        
        $this->user->setPermissionArrayForUser(/* parameters */);
    }

    /**
     * Tests User->userHasImportFilePermission()
     */
    public function testUserHasImportFilePermission()
    {
        // TODO Auto-generated UserTest->testUserHasImportFilePermission()
        $this->markTestIncomplete("userHasImportFilePermission test not implemented");
        
        $this->user->userHasImportFilePermission(/* parameters */);
    }

    /**
     * Tests User->userCanSeeUserAdminPage()
     */
    public function testUserCanSeeUserAdminPage()
    {
        // TODO Auto-generated UserTest->testUserCanSeeUserAdminPage()
        $this->markTestIncomplete("userCanSeeUserAdminPage test not implemented");
        
        $this->user->userCanSeeUserAdminPage(/* parameters */);
    }

    /**
     * Tests User->userCanSeeDataTestPage()
     */
    public function testUserCanSeeDataTestPage()
    {
        // TODO Auto-generated UserTest->testUserCanSeeDataTestPage()
        $this->markTestIncomplete("userCanSeeDataTestPage test not implemented");
        
        $this->user->userCanSeeDataTestPage(/* parameters */);
    }

    /**
     * Tests User->userCanCreatePDF()
     */
    public function testUserCanCreatePDF()
    {
        // TODO Auto-generated UserTest->testUserCanCreatePDF()
        $this->markTestIncomplete("userCanCreatePDF test not implemented");
        
        $this->user->userCanCreatePDF(/* parameters */);
    }

    /**
     * Tests User->userHasSpecificPermission()
     */
    public function testUserHasSpecificPermission()
    {
        // TODO Auto-generated UserTest->testUserHasSpecificPermission()
        $this->markTestIncomplete("userHasSpecificPermission test not implemented");
        
        $this->user->userHasSpecificPermission(/* parameters */);
    }
}

