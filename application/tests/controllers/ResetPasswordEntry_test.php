<?php

class ResetPasswordEntry_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->helper('string');
        //$this->CI->load->controller('ResetPasswordEntry');
        //$this->obj = new ResetPasswordEntry();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }


    /*
     * Complete this test once the ForgotPassword tests are done
     */
    public function test_LoadPage() {
        //$activationId = random_string('alnum',15);
        $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = 'bbrummtest';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $activationId = $resultArray[0]->activation_id;
        //$this->obj->load($activationId);
        $output = $this->request('POST', ['ResetPasswordEntry', 'load', $activationId]);
        $expected = "<h2>Password Reset</h2>";

        $this->assertContains($expected, $output);

    }

    public function test_SubmitNewPassword_Valid() {
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'abcdefg',
            'confirmPassword'=>'abcdefg'
        );

        $output = $this->request('POST', ['ResetPasswordEntry', 'submitNewPassword'], $postArray);
        $expected = "<h2>Your Password Has Been Reset</h2>";
        $this->assertContains($expected, $output);

        //check password is updated
        $queryString = "SELECT user_password FROM umpire_users WHERE user_name = '". $postArray['username'] ."';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $savedPassword = $resultArray[0]->user_password;
        $expectedPassword = MD5($postArray['password']);
        $this->assertEquals($expectedPassword, $savedPassword);

        //check log password reset
        $queryString = "SELECT user_name, old_password, new_password
            FROM password_reset_log
            ORDER BY reset_datetime DESC
            LIMIT 1;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();

        $this->assertEquals(MD5($postArray['password']), $resultArray[0]->new_password);
        $this->assertEquals($postArray['username'], $resultArray[0]->user_name);

        //Reset password back to original value
        $dbStore = new Database_store_user();
        $userToUpdate = User::createUserFromNameAndPW('bbrummtest', 'Ben', 'Brumm', MD5('test'));
        $dbStore->updatePassword($userToUpdate);

    }

    public function test_SubmitNewPassword_IncorrectPW() {
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'abc',
            'confirmPassword'=>'abc'
        );

        //Get activation ID
        $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = 'bbrummtest';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $postArray['activationID'] = $resultArray[0]->activation_id;

        //Load page
        $output = $this->request('POST', ['ResetPasswordEntry', 'submitNewPassword'], $postArray);
        $expected = "Passwords do not match or are less than 6 characters.";
        $this->assertContains($expected, $output);

        //Check password is not updated
        $queryString = "SELECT user_password FROM umpire_users WHERE user_name = '". $postArray['username'] ."';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $savedPassword = $resultArray[0]->user_password;
        $expectedPassword = MD5($postArray['password']);
        $this->assertNotEquals($expectedPassword, $savedPassword);

        //No need to check log password reset, it's not done for those that fail PW validation
    }

    public function test_SubmitNewPassword_MissingPW() {
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'',
            'confirmPassword'=>''
        );

        //Get activation ID
        $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = 'bbrummtest';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $postArray['activationID'] = $resultArray[0]->activation_id;

        //Load page
        $output = $this->request('POST', ['ResetPasswordEntry', 'submitNewPassword'], $postArray);
        $expected = "Passwords do not match or are less than 6 characters.";
        $this->assertContains($expected, $output);

        //Check password is not updated
        $queryString = "SELECT user_password FROM umpire_users WHERE user_name = '". $postArray['username'] ."';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $savedPassword = $resultArray[0]->user_password;
        $expectedPassword = MD5($postArray['password']);
        $this->assertNotEquals($expectedPassword, $savedPassword);
    }

    public function test_SubmitNewPassword_PWDoesNotMatch() {
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'abcdefg',
            'confirmPassword'=>'abcdefghij'
        );

        //Get activation ID
        $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = 'bbrummtest';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $postArray['activationID'] = $resultArray[0]->activation_id;

        //Load page
        $output = $this->request('POST', ['ResetPasswordEntry', 'submitNewPassword'], $postArray);
        $expected = "Passwords do not match or are less than 6 characters.";
        $this->assertContains($expected, $output);

        //Check password is not updated
        $queryString = "SELECT user_password FROM umpire_users WHERE user_name = '". $postArray['username'] ."';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $savedPassword = $resultArray[0]->user_password;
        $expectedPassword = MD5($postArray['password']);
        $this->assertNotEquals($expectedPassword, $savedPassword);
    }

    public function test_SubmitNewPassword_UsernameDoesNotExist() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Username not found: bbrummtest123');
        $postArray = array(
            'username'=>'bbrummtest123',
            'password'=>'abcdefg',
            'confirmPassword'=>'abcdefg'
        );

        //Get activation ID
        $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = 'bbrummtest';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $postArray['activationID'] = $resultArray[0]->activation_id;

        //Load page
        try {
            $output = $this->request('POST', ['ResetPasswordEntry', 'submitNewPassword'], $postArray);
        } finally {
            $output = ob_get_clean();
        }
    }

}