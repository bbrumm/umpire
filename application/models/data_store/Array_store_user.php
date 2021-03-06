<?php
require_once 'IData_store_user.php';

class Array_store_user extends CI_Model implements IData_store_user
{


    public function getUserFromUsername($pUsername) {
        $existingData = array(
            array("username"=>"abcdef", "first_name"=>"andy", "last_name"=>"jones"),
            array("username"=>"qwe", "first_name"=>"quinten", "last_name"=>"johnson"),
            array("username"=>"asd", "first_name"=>"sam", "last_name"=>"smith"),
            array("username"=>"asd", "first_name"=>"sam2", "last_name"=>"smith2"),
            array("username"=>"john", "first_name"=>"john", "last_name"=>"smith"),
            array("username"=>"paul", "first_name"=>"paul", "last_name"=>"smith"),
            array("username"=>"george", "first_name"=>"george", "last_name"=>"smith")
        );

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUsername) {
                $user = User::createUserFromNameAndRole(1, $existingData[$i]["username"],
                    $existingData[$i]["first_name"], $existingData[$i]["last_name"],
                    null, 1, null);
                return $user;
            }
        }
    }

    public function findPermissionsForUser(User $pUser) {
        $permissionArrayJohn = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"All"),
            array("id"=>2, "permission_id"=>2, "permission_name"=>"VIEW_USER_ADMIN", "selection_name"=>"All"),
            array("id"=>3, "permission_id"=>3, "permission_name"=>"VIEW_DATA_TEST", "selection_name"=>"All"),
            array("id"=>4, "permission_id"=>4, "permission_name"=>"CREATE_PDF", "selection_name"=>"All")

        );

        $permissionArrayPaul = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"All")

        );

        $permissionArrayGeorge = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"Some"),
            array("id"=>2, "permission_id"=>2, "permission_name"=>"VIEW_USER_ADMIN", "selection_name"=>"Some"),
            array("id"=>3, "permission_id"=>3, "permission_name"=>"VIEW_DATA_TEST", "selection_name"=>"Some"),
            array("id"=>4, "permission_id"=>4, "permission_name"=>"CREATE_PDF", "selection_name"=>"Some")

        );

        if($pUser->getUsername() == "john") {
            return $permissionArrayJohn;
        } elseif($pUser->getUsername() == "paul") {
            return $permissionArrayPaul;
        } elseif($pUser->getUsername() == "george") {
            return $permissionArrayGeorge;
        } else {
            return $permissionArrayJohn;
        }

    }

    public function checkUserExistsForReset(User $pUser) {
        $userArray = array(
            "test", "something", "another", "bongo"
        );
        $userFound = false;
        $arrayCount = count($userArray);
        for ($i=0; $i<$arrayCount; $i++) {
            if ($userArray[$i] == $pUser->getUsername()) {
                $userFound = true;
            }
        }
        return $userFound;
    }

    public function logPasswordResetRequest($pRequestData) {
        $existingArray = [];
        $existingArray[] = $pRequestData;
        return true;
    }

    public function storeActivationID($pUser, $pActivationID) {
        $recordFound = false;
        $existingData = array(
            array("username"=>"abcdef", "email_address"=>"test@abc.com")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            //echo "username (". $pUser->getUsername() ."), email (".$pUser->getEmailAddress() .") <BR />";
            if ($existingData[$i]["username"] == $pUser->getUsername() &&
                $existingData[$i]["email_address"] == $pUser->getEmailAddress()) {
                $recordFound = true;
            }
        }
        return $recordFound;

    }


    public function createUserFromActivationID($pActivationID) {
        $existingData = array(
            array("username"=>"abcdef", "activation_id"=>"123456"),
            array("username"=>"qwe", "activation_id"=>"123"),
            array("username"=>"asd", "activation_id"=>"111"),
            array("username"=>"zxc", "activation_id"=>"111")
        );

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["activation_id"] == $pActivationID) {
                $user = User::createUserFromActivationID($existingData[$i]["username"], $pActivationID);
                //$user = new User();
                //$user->setUsername($existingData[$i]["username"]);
                return $user;
            }
        }

    }


    public function getUserNameFromActivationID(User $pUser) {
        $existingData = array(
            array("username"=>"abcdef", "activation_id"=>"123456"),
            array("username"=>"qwe", "activation_id"=>"123"),
            array("username"=>"asd", "activation_id"=>"111"),
            array("username"=>"zxc", "activation_id"=>"111")
        );

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["activation_id"] == $pUser->getActivationID()) {
                return $existingData[$i]["username"];
            }
        }
    }


    public function updatePassword(User $pUser) {
        $updateStatus = false;
        $existingData = array(
            array("username"=>"abcdef", "password"=>"mypass"),
            array("username"=>"john", "password"=>"one"),
            array("username"=>"john", "password"=>"two"),
            array("username"=>"another", "password"=>"three")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUser->getUsername()) {
                if ($pUser->getPassword() != null && $pUser->getPassword() != "") {
                    $existingData[$i]["password"] == $pUser->getPassword();
                    $updateStatus = true;
                }
            }
        }
        return $updateStatus;
    }

    public function findOldUserPassword(User $pUser) {
        return "some_old_password";
    }

    public function logPasswordReset($pData) {
        $existingData = [];
        $existingData[] = $pData;
        return true;

    }

    public function updateEmailAddress(User $pUser) {
        $updateStatus = false;
        $existingData = array(
            array("username"=>"test", "email_address"=>"email1"),
            array("username"=>"john", "email_address"=>"email1one"),
            array("username"=>"john", "email_address"=>"email1two"),
            array("username"=>"another", "email_address"=>"email1three")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUser->getUsername()) {
                $updateStatus = true;
                $existingData[$i]["email_address"] == $pUser->getEmailAddress();
            }
        }
        return $updateStatus;
    }

    /*
    public function findUserFromUsernameAndPassword($username, $password) {


    }
*/

    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword) {
        $testData = array(
            array("id" => 1, "username" => "john", "password" => MD5("mypass")),
            array("id" => 2, "username" => "paul", "password" => MD5(null)),
            array("id" => 3, "username" => "ringo", "password" => MD5("otherthing")),
            array("id" => 4, "username" => "george", "password" => MD5("theword")),
            array("id" => 5, "username" => "george", "password" => MD5("another")),
            array("id" => 6, "username" => "ringo", "password" => MD5("otherthing"))
        );

        foreach ($testData as $key => $subArray) {
            if ($subArray["username"] == $pUsername && $subArray["password"] == MD5($pPassword)) {
                return User::createUserFromNameAndPW($subArray["username"], null, null, $subArray["password"]);
            }
        }
    }

    public function checkUserActive($pUsername) {
        $testData = array(
            array("id" => 1, "username" => "john", "active" => 1),
            array("id" => 2, "username" => "paul", "active" => 0),
            array("id" => 3, "username" => "ringo", "active" => 1),
            array("id" => 4, "username" => "george", "active" => 1),
            array("id" => 5, "username" => "ringo", "active" => 0),
            array("id" => 6, "username" => "george", "active" => 1)
        );
        $rowCount = 0;

        foreach ($testData as $key => $subArray) {
            if ($subArray["username"] == $pUsername && $subArray["active"] == 1) {
                $rowCount++;
            }
        }
        return ($rowCount == 1);

    }




}
