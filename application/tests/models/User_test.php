<?php
class User_test extends TestCase {
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
$this->expectException(InvalidArgumentException::class);
$inputValue = "";
$this->obj->setActive($inputValue);
}

public function test_ActiveNull() {
$this->expectException(InvalidArgumentException::class);
$inputValue = null;
$this->obj->setActive($inputValue);
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
$this->expectException(InvalidArgumentException::class);
$inputValue = "something";
$this->obj->setActive($inputValue);
}


public function testValidatePassword() {
$expected = true;
$firstPassword = "somepassword";
$secondPassword = "somepassword";
$actual = $this->obj->validatePassword($firstPassword, $secondPassword);
$this->assertEquals($expected, $actual);
}

public function testValidatePasswordNoMatch() {
$expected = false;
$firstPassword = "somepassword";
$secondPassword = "someotherpassword";
$actual = $this->obj->validatePassword($firstPassword, $secondPassword);
$this->assertEquals($expected, $actual);
}

public function testValidatePasswordNoMatchAndShort() {
$expected = false;
$firstPassword = "some";
$secondPassword = "pass";
$actual = $this->obj->validatePassword($firstPassword, $secondPassword);
$this->assertEquals($expected, $actual);
}

public function testValidatePasswordShortMatch() {
$expected = false;
$firstPassword = "some";
$secondPassword = "some";
$actual = $this->obj->validatePassword($firstPassword, $secondPassword);
$this->assertEquals($expected, $actual);
}
  


}
