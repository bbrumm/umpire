<?php

class User_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('User');
        $this->obj = $this->CI->User;
    }

    public function test_ID() {
        $expected = 6;
        $this->obj->setID($expected);
        $this->assertEquals($expected, $this->obj->getID());
    }

    public function test_IDNull() {
        $expected = null;
        $this->obj->setID($expected);
        $this->assertEquals($expected, $this->obj->getID());
    }

    public function test_IDEmptyString() {
        $expected = "";
        $this->obj->setID($expected);
        $this->assertEquals($expected, $this->obj->getID());
    }

    public function test_Username() {
        $expected = "something";
        $this->obj->setUsername($expected);
        $this->assertEquals($expected, $this->obj->getUsername());
    }

    public function test_Username255() {
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstu";
        $this->obj->setUsername($expected);
        $this->assertEquals($expected, $this->obj->getUsername());
    }

    public function test_Username256() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuv";
        $this->obj->setUsername($expected);
    }

    public function test_Username300() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmn";
        $this->obj->setUsername($expected);
    }

    public function test_UsernameNull() {
        $expected = null;
        $this->obj->setUsername($expected);
        $this->assertEquals($expected, $this->obj->getUsername());
    }

    public function test_UsernameEmptyString() {
        $expected = "";
        $this->obj->setUsername($expected);
        $this->assertEquals($expected, $this->obj->getUsername());
    }

    public function test_Password() {
        $expected = "another";
        $this->obj->setPassword($expected);
        $this->assertEquals($expected, $this->obj->getPassword());
    }

    public function test_PasswordNull() {
        $expected = null;
        $this->obj->setPassword($expected);
        $this->assertEquals($expected, $this->obj->getPassword());
    }

    public function test_PasswordEmpty() {
        $expected = "";
        $this->obj->setPassword($expected);
        $this->assertEquals($expected, $this->obj->getPassword());
    }

    public function test_FirstName() {
        $expected = "John";
        $this->obj->setFirstName($expected);
        $this->assertEquals($expected, $this->obj->getFirstName());
    }

    public function test_FirstNameNull() {
        $expected = null;
        $this->obj->setFirstName($expected);
        $this->assertEquals($expected, $this->obj->getFirstName());
    }

    public function test_FirstNameEmpty() {
        $expected = "";
        $this->obj->setFirstName($expected);
        $this->assertEquals($expected, $this->obj->getFirstName());
    }

    public function test_LastName() {
        $expected = "Smith";
        $this->obj->setLastName($expected);
        $this->assertEquals($expected, $this->obj->getLastName());
    }

    public function test_LastNameNull() {
        $expected = null;
        $this->obj->setLastName($expected);
        $this->assertEquals($expected, $this->obj->getLastName());
    }

    public function test_LastNameEmpty() {
        $expected = "";
        $this->obj->setLastName($expected);
        $this->assertEquals($expected, $this->obj->getLastName());
    }

    public function test_RoleName() {
        $expected = "Major";
        $this->obj->setRoleName($expected);
        $this->assertEquals($expected, $this->obj->getRoleName());
    }

    public function test_RoleNameNull() {
        $expected = null;
        $this->obj->setRoleName($expected);
        $this->assertEquals($expected, $this->obj->getRoleName());
    }

    public function test_RoleNameEmpty() {
        $expected = "";
        $this->obj->setRoleName($expected);
        $this->assertEquals($expected, $this->obj->getRoleName());
    }

    public function test_SubRoleName() {
        $expected = "Minor";
        $this->obj->setSubRoleName($expected);
        $this->assertEquals($expected, $this->obj->getSubRoleName());
    }

    public function test_SubRoleNameNull() {
        $expected = null;
        $this->obj->setSubRoleName($expected);
        $this->assertEquals($expected, $this->obj->getSubRoleName());
    }

    public function test_SubRoleNameEmpty() {
        $expected = "";
        $this->obj->setSubRoleName($expected);
        $this->assertEquals($expected, $this->obj->getSubRoleName());
    }


    public function test_PasswordResetURL() {
        $expected = "http://www.umpirereporting.com/reset123";
        $this->obj->setPasswordResetURL($expected);
        $this->assertEquals($expected, $this->obj->getPasswordResetURL());
    }

    public function test_PasswordResetURLNotURL() {
        $expected = "some path name";
        $this->obj->setPasswordResetURL($expected);
        $this->assertEquals($expected, $this->obj->getPasswordResetURL());
    }

    public function test_PasswordResetURLNumber() {
        $expected = 5;
        $this->obj->setPasswordResetURL($expected);
        $this->assertEquals($expected, $this->obj->getPasswordResetURL());
    }

    public function test_PasswordResetURLNull() {
        $expected = null;
        $this->obj->setPasswordResetURL($expected);
        $this->assertEquals($expected, $this->obj->getPasswordResetURL());
    }

    public function test_PasswordResetURLEmpty() {
        $expected = "";
        $this->obj->setPasswordResetURL($expected);
        $this->assertEquals($expected, $this->obj->getPasswordResetURL());
    }

    public function test_EmailAddress() {
        $expected = "something";
        $this->obj->setEmailAddress($expected);
        $this->assertEquals($expected, $this->obj->getEmailAddress());
    }

    public function test_EmailAddress255() {
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstu";
        $this->obj->setEmailAddress($expected);
        $this->assertEquals($expected, $this->obj->getEmailAddress());
    }

    public function test_EmailAddress256() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuv";
        $this->obj->setEmailAddress($expected);
    }

    public function test_EmailAddress300() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmn";
        $this->obj->setEmailAddress($expected);
    }

    public function test_EmailAddressNull() {
        $expected = null;
        $this->obj->setEmailAddress($expected);
        $this->assertEquals($expected, $this->obj->getEmailAddress());
    }

    public function test_EmailAddressEmptyString() {
        $expected = "";
        $this->obj->setEmailAddress($expected);
        $this->assertEquals($expected, $this->obj->getEmailAddress());
    }

    public function test_ActivationID() {
        $expected = "abcdef";
        $this->obj->setActivationID($expected);
        $this->assertEquals($expected, $this->obj->getActivationID());
    }

    public function test_ActivationID200() {
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqr";
        $this->obj->setActivationID($expected);
        $this->assertEquals($expected, $this->obj->getActivationID());
    }

    public function test_ActivationID201() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrq";
        $this->obj->setActivationID($expected);
    }

    public function test_ActivationID300() {
        $this->expectException(InvalidArgumentException::class);
        $expected = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmn";
        $this->obj->setActivationID($expected);
    }

    public function test_ActivationIDNull() {
        $expected = null;
        $this->obj->setActivationID($expected);
        $this->assertEquals($expected, $this->obj->getActivationID());
    }

    public function test_ActivationIDEmptyString() {
        $expected = "";
        $this->obj->setActivationID($expected);
        $this->assertEquals($expected, $this->obj->getActivationID());
    }

    public function test_Active0() {
        $expected = false;
        $inputValue = 0;
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_Active1() {
        $expected = true;
        $inputValue = 1;
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_ActiveEmpty() {
        $expected = false;
        $inputValue = "";
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_ActiveNull() {
        $expected = false;
        $inputValue = null;
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_Active0String() {
        $expected = false;
        $inputValue = "0";
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_Active1String() {
        $expected = true;
        $inputValue = "1";
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }

    public function test_Active2() {
        $this->expectException(InvalidArgumentException::class);
        $inputValue = 2;
        $this->obj->setActive($inputValue);
    }

    public function test_ActiveTextValue() {
        $expected = false;
        $inputValue = "something";
        $this->obj->setActive($inputValue);
        $this->assertEquals($expected, $this->obj->isActive());
    }
    
    public function test_CreateUserFromNameAndRole() {
        $id = 4;
        $username = "johnabc";
        $firstname = "John";
        $lastname = "Smith";
        $rolename = "super";
        $active = 1;
        $emailAddress = "test@johnsmith";

        $user = User::createUserFromNameAndRole($id, $username, $firstname, $lastname, $rolename, $active, $emailAddress);

        $this->assertInstanceOf('User', $user);
        $this->assertEquals($id, $user->getID());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($firstname, $user->getFirstName());
        $this->assertEquals($lastname, $user->getLastName());
        $this->assertEquals($rolename, $user->getRoleName());
        $this->assertEquals(($active == 1), $user->isActive());
        $this->assertEquals($emailAddress, $user->getEmailAddress());
    }
    
    public function test_CreateUserFromNameAndPW() {
        $username = "test1";
        $firstname = "john";
        $lastname = "smith";
        $password = "mypass";

        $user = User::createUserFromNameAndPW($username, $firstname, $lastname, $password);

        $this->assertInstanceOf('User', $user);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($firstname , $user->getFirstname());
        $this->assertEquals($lastname , $user->getLastname());
        $this->assertEquals($password , $user->getPassword());

    }

    public function test_CreateUserFromNameAndPW_MissingUsername() {
        $username = null;
        $firstname = "john";
        $lastname = "smith";
        $password = "mypass";

        $user = User::createUserFromNameAndPW($username, $firstname, $lastname, $password);

        $this->assertInstanceOf('User', $user);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($firstname , $user->getFirstname());
        $this->assertEquals($lastname , $user->getLastname());
        $this->assertEquals($password , $user->getPassword());

    }

    public function test_CreateUserFromNameAndPW_MissingFirstname() {
        $username = "test3";
        $firstname = null;
        $lastname = "smith";
        $password = "mypass";

        $user = User::createUserFromNameAndPW($username, $firstname, $lastname, $password);

        $this->assertInstanceOf('User', $user);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($firstname , $user->getFirstname());
        $this->assertEquals($lastname , $user->getLastname());
        $this->assertEquals($password , $user->getPassword());

    }

    public function test_SetPermissionArray() {
        $testArray = array('one', 'two', 'three');

        $username = "test1";
        $firstname = "john";
        $lastname = "smith";
        $password = "mypass";

        $user = User::createUserFromNameAndPW($username, $firstname, $lastname, $password);
        $user->setPermissionArray($testArray);

        $this->assertEquals($testArray, $user->getPermissionArray());
    }


    public function test_SetPermissionArray_Empty() {
        $testArray = [];

        $username = "test1";
        $firstname = "john";
        $lastname = "smith";
        $password = "mypass";

        $user = User::createUserFromNameAndPW($username, $firstname, $lastname, $password);
        $user->setPermissionArray($testArray);

        $this->assertEquals($testArray, $user->getPermissionArray());
    }

}
