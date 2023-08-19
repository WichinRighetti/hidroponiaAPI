<?php

require_once('mysqlconnection.php');
require_once('exceptions/lightNotFoundException.php');
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
    public function getname()
    {
        return $this->name;
    }
    public function setname($value)
    {
        $this->name = $value;
    }
    public function getlocation()
    {
        return $this->location;
    }
    public function setlocation($value)
    {
        $this->location = $value;
    }
    public function getstatus()
    {
        return $this->status;
    }
    public function setstatus($value)
    {
        $this->status = $value;
    }
    public function getowner()
    {
        return $this->owner;
    }
    public function setowner($value)
    {
        $this->owner = $value;
    }


    public function __construct()
    {
        if(func_num_args()==0){
            $this->id =0;
            $this->name = "";
            $this->location = "";
            $this->status = 0;
            $this->owner = new Record();
        }
        if(func_num_args()==1){
             //get id 
             $id = func_get_arg(0);
             //get connection 
             $conn = MysqlConnection::getConnection();
             //query 

            //falta hacer el quety 


             $query = "SELECT * FROM WHERE id = ?";
             //command
             $command = $conn->prepare($query);
             //bind params
             $command->bind_param('i', $id);
             $command->execute();
             //bind result

            //falta traer los binds de owner 

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
                throw new ownerNotFoundException($id);
            }
            mysqli_stmt_close($command);
            $conn->close();
        }
    }

}