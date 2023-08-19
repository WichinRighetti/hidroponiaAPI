<?php

use LDAP\Result;

require_once('mysqlconnection.php');
require_once('exceptions/RecordNotFoundException.php');
require_once('records.php');

class Site
{
    private $id;
    private $name;
    private $location;
    private $status;
    private $owner;

    public function getId()
    {
        return $this->id;
    }
    public function setId($value)
    {
        $this->id = $value;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($value)
    {
        $this->name = $value;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation($value)
    {
        $this->location = $value;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($value)
    {
        $this->status = $value;
    }
    public function getOwner()
    {
        return $this->owner;
    }
    public function setOwner($value)
    {
        $this->owner = $value;
    }


    public function __construct()
    {
        if(func_num_args()==0){
            $this->id =0;
            $this->name = "";
            $this->location = "";
            $this->status = true;
            $this->owner = "";
        }
        if(func_num_args()==1){
             //get id 
             $id = func_get_arg(0);
             //get connection 
             $conn = MysqlConnection::getConnection();
             //query 
             $query = "SELECT Id, Name, Location, Status, Owner FROM sites WHERE id = ?";
             //command
             $command = $conn->prepare($query);
             //bind params
             $command->bind_param('i', $id);
             $command->execute();
             //bind result
             $command->bind_result($id, $name, $location, $status, $owner);
             //owner was found 
             if ($command->fetch()) {
                $this->id = $id;
                $this->name = $name;
                $this->location = $location;
                $this->status = $status;
                $this->owner = $owner;
            } else {
                // throw exception 
                throw new RecordNotFoundException($id);
            }
            mysqli_stmt_close($command);
            $conn->close();
        }
        if (func_num_args()==5){
            $arguments = func_get_args();
            $this->id = $arguments[0];
            $this->name = $arguments[1];
            $this->location = $arguments[2];
            $this->status = $arguments[3];
            $this->owner = $arguments[4];
        }
    }

    public static function getAll(){
        $list = array();
        $conn = MysqlConnection::getConnection();
        $query = "SELECT Id, Name, Location, Status, Owner FROM sites";
        $command = $conn->prepare($query);
        $command->execute();
        $command->bind_result($id, $name, $location, $status, $owner);
        while($command->fetch()){
            array_push($list, new Site($id, $name, $location, $status, $owner));
        }
        mysqli_stmt_close($command);
        $conn->close();
        return $list;
    }

    public static function getAllByJson(){
        $list = array();
        foreach(self::getAll() as $item){
            array_push($list, json_decode($item->toJson()));
        }

        return json_encode($list);
    }
    

    public function toJson()
    {
        return json_encode(
            array(
                'Id' => $this->id,
                'Name' => $this->name,
                'Location' => $this->location,
                'Status' => $this->status,
                'Owner' => $this->owner
            )
        );
    }

    function add(){
        //get connection
        $conn = MysqlConnection::getConnection();
        //query
        $query = 'INSERT INTO sites (Name, Location, Status, Owner) values (?, ?, ?, ?)';
        //command
        $command=$conn->prepare($query);
        //bind params
        $command->bind_param('ssis', $this->name, $this->location, $this->status, $this->owner);
        //execute
        $result = $command->execute();
        //close command
        mysqli_stmt_close($command);
        //Close connection
        $conn->close();
        //return result
        return $result;
    }

    public function getRecords(){

        return lista;
    }

    public function toJsonFull(){
        //list
        $recordsList = array();

        foreach(self::getRecords() as $item){
            array_push($recordsList, json_decode($item->toJsontoSite()));
        }

        return json_encode(array(
                'Id' => $this->id,
                'Name' => $this->name,
                'Location' => $this->location,
                'Status' => $this->status,
                'Owner' => $this->owner,
                'Records' => $recordsList
        ));
    }
}