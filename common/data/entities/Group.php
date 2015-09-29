<?php

namespace fumbol\common\data\entities;

use fumbol\common\data\DBConnectionFactory;

class Group {

    private $group_id;
    private $group_name;
    private $group_description;
    private $created_date;


    /**
     * @return mixed
     */
    public function getIdGroup()
    {
        return $this->group_id;
    }

    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @param mixed $group_name
     */
    public function setGroupName($group_name)
    {
        $this->group_name = $group_name;
    }

    /**
     * @return mixed
     */
    public function getGroupDescription()
    {
        return $this->group_description;
    }

    /**
     * @param mixed $group_description
     */
    public function setGroupDescription($group_description)
    {
        $this->group_description = $group_description;
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


            if(intval($this->group_id) > 0 )  {
                //-- Update
                $query = " UPDATE groups set ";
                $query.= " name='".$db->escape($this->getGroupName())."', ";
                $query.= " description='".$db->escape($this->getGroupDescription())."' ";
                $query.= " where group_id='".$db->escape($this->group_id)."' ";

                $db->execute($query);
            }else{

                $this->created_date = date("Y-m-d H:i:s");

                $query = "INSERT INTO groups (name, description, created_date) VALUES ";
                $query.= "('".$db->escape($this->getGroupName())."','".$db->escape($this->getGroupDescription())."','".$this->created_date."')";

                $new_id = $db->execute($query,$devnull,true);

                $this->group_id = $new_id;
            }
        }catch(\Exception $e) {
            throw $e;
        }


    }

    public static function getById($group_id) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from groups where group_id=".$group_id;

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getByName($group_name) {

        //-- Find by Group Name
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from groups where LOWER(name)='".$db->escape(strtolower($group_name))."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    private static function createFromDb($resource) {


        if(!is_null($resource)) {

            $group = new Group();
            $group->setGroupId($resource["group_id"]);
            $group->setGroupName($resource["name"]);
            $group->setGroupDescription($resource["description"]);
            $group->setCreatedDate($resource["created_date"]);

            return $group;
        }

        return null;
    }

    public function toArray() {
        return [
            "group_id"=>$this->group_id,
            "group_name"=>$this->group_name,
            "group_description"=>$this->group_description,
            "created_date"=>$this->created_date
        ];
    }

}