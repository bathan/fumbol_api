<?php

namespace fumbol\common\data\entities;

use fumbol\common\data\DBConnectionFactory;

class UserAuth {

    private $user_id;
    private $user_name;
    private $password;
    private $salt;
    private $last_successful_login;
    private $created_date;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getLastSuccessfulLogin()
    {
        return $this->last_successful_login;
    }

    /**
     * @param mixed $last_successful_login
     */
    public function setLastSuccessfulLogin($last_successful_login)
    {
        $this->last_successful_login = $last_successful_login;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * @param mixed $created_date
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
    }


    public function persist() {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();


            if(intval($this->user_id) > 0 )  {

                //-- Update
                $query = " UPDATE user_auth set ";
                $query.= " password='".$db->escape($this->getPassword())."', ";
                $query.= " salt='".$db->escape($this->getSalt())."',";
                $query.= " last_successful_login='".$db->escape($this->getLastSuccessfulLogin())."' ";
                $query.= " where user_id='".$db->escape($this->user_id)."' ";

                $db->execute($query);
            }else{

                $this->created_date = date("Y-m-d H:i:s");

                $query = "INSERT INTO user_auth (user_name, password, salt, created_date) VALUES ";
                $query.= "('";
                $query.= $db->escape($this->getUserName());
                $query.= "','".$db->escape($this->getPassword());
                $query.= "','".$db->escape($this->getSalt());
                $query.= "','".$this->created_date;
                $query.= "')";


                $new_id = $db->execute($query,$devnull,true);

                $this->user_id = $new_id;
            }
        }catch(\Exception $e) {
            throw $e;
        }


    }

    public static function getByUserName($user_name) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from user_auth where user_name='".$user_name."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    private static function createFromDb($resource) {


        if(!is_null($resource)) {

            $user = new UserAuth();
            $user->setUserId($resource["user_id"]);
            $user->setUserName($resource["user_name"]);
            $user->setSalt($resource["salt"]);
            $user->setPassword($resource["password"]);
            $user->setLastSuccessfulLogin($resource["last_successful_login"]);

            return $user;
        }

        return null;
    }

    public function toArray() {
        return [
            "user_id"=>$this->user_id,
            "user_name"=>$this->user_name,
            "last_login"=>$this->last_successful_login,
        ];
    }

}