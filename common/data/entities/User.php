<?php

namespace fumbol\common\data\entities;

use fumbol\common\data\DBConnectionFactory;

class User {

    private $row_id;
    private $user_id;
    private $first_name;
    private $last_name;
    private $nickname;
    private $email;
    private $created_date;

    /**
     * @return mixed
     */
    public function getRowId()
    {
        return $this->row_id;
    }

    /**
     * @param mixed $row_id
     */
    public function setRowId($row_id)
    {
        $this->row_id = $row_id;
    }


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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

            if(intval($this->row_id) > 0 )  {

                //-- Update
                $query = " UPDATE users set ";
                $query.= " first_name='".$db->escape($this->getFirstName())."', ";
                $query.= " last_name='".$db->escape($this->getLastName())."',";
                $query.= " nickname='".$db->escape($this->getNickname())."',";
                $query.= " email='".$db->escape($this->getEmail())."',";
                $query.= " where row_id='".$db->escape($this->row_id)."' ";

                $db->execute($query);
            }else{

                $this->created_date = date("Y-m-d H:i:s");

                $query = "INSERT INTO users (user_id,first_name, last_name, nickname, email, created_date) VALUES ";
                $query.= "('";
                $query.= $db->escape($this->getUserId());
                $query.= "','".$db->escape($this->getFirstName());
                $query.= "','".$db->escape($this->getLastName());
                $query.= "','".$db->escape($this->getNickname());
                $query.= "','".$db->escape($this->getEmail());
                $query.= "','".$this->created_date;
                $query.= "')";


                $new_id = $db->execute($query,$devnull,true);

                $this->row_id = $new_id;
            }
        }catch(\Exception $e) {
            throw $e;
        }


    }

    public static function getByUserId($user_id) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from users where user_id='".$db->escape($user_id)."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getByEmail($email) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from users where email='".$db->escape($email)."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getByListOfIds($list_of_ids) {

        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from users where user_id in (".implode(',',$list_of_ids).")";

            $result = $db->executeAndFetch($query);

            $list_of_users = [];
            foreach($result as $r) {
                $list_of_users[$r['user_id']] = self::createFromDb($r);
            }

            return $list_of_users;

        }catch(\Exception $e) {
            throw $e;
        }
    }

    private static function createFromDb($resource) {


        if(!is_null($resource)) {

            $user = new User();
            $user->setRowId($resource["row_id"]);
            $user->setUserId($resource["user_id"]);
            $user->setFirstName($resource["first_name"]);
            $user->setLastName($resource["last_name"]);
            $user->setNickname($resource["nickname"]);
            $user->setEmail($resource["email"]);
            $user->setCreatedDate($resource["created_date"]);


            return $user;
        }

        return null;
    }

    public function toArray() {
        return [
            "user_id"=>$this->user_id,
            "first_name"=>$this->first_name,
            "last_name"=>$this->last_name,
            "nickname"=>$this->nickname,
            "email"=>$this->email,
            "created_date"=>$this->created_date,
            "row_id"=>$this->row_id,
        ];
    }

}