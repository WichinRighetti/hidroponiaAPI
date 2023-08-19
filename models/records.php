<?php

require_once('mysqlconnection.php');
require_once('exceptions/recordNotFoundException.php');

class Record
{
    //attributes
    private $id;
    private $ph;
    private $humidity;
    private $h2o;
    private $light;
    private $temperature;
    private $pump;
    private $dateTime;

    // getter setters
    public function getId()
    {
        return $this->id;
    }
    public function setId($value)
    {
        $this->id = $value;
    }
    public function getPh()
    {
        return $this->ph;
    }
    public function setPh($value)
    {
        $this->ph = $value;
    }
    public function getHumidity()
    {
        return $this->humidity;
    }
    public function setHumidity($value)
    {
        $this->humidity = $value;
    }
    public function getH2o()
    {
        return $this->h2o;
    }
    public function setH2o($value)
    {
        $this->h2o = $value;
    }
    public function getLight()
    {
        return $this->light;
    }
    public function setLight($value)
    {
        $this->light = $value;
    }
    public function getTemp()
    {
        return $this->temperature;
    }
    public function setTemp($value)
    {
        $this->temperature = $value;
    }
    public function getDateTime()
    {
        return $this->dateTime;
    }
    public function setDateTime($value)
    {
        $this->dateTime = $value;
    }

    //constructors 
    public function __construct()
    {
        //empty constrcut 
        if (func_num_args() == 0) {
            $this->id = 0;
            $this->ph = 0.0;
            $this->humidity = 0.0;
            $this->h2o = 0.0;
            $this->light = 0.0;
            $this->temperature = 0.0;
            $this->dateTime = "";
        }
        //constructor  
        if (func_num_args() == 1) {
            //get id 
            $id = func_get_arg(0);
            //get connection 
            $conn = MysqlConnection::getConnection();
            //query 
            $query = "SELECT Id, Ph, Humidity, H2o, Light, Temperature, Datetime  FROM Records WHERE Id = ?";
            //command
            $command = $conn->prepare($query);
            //bind params
            $command->bind_param('i', $id);
            $command->execute();
            //bind result
            $command->bind_result($id, $ph, $humidity, $h2o, $light, $temperature, $dateTime);
            //record was found 
            if ($command->fetch()) {
                $this->id = $id;
                $this->ph = $ph;
                $this->humidity = $humidity;
                $this->h2o = $h2o;
                $this->light = $light;
                $this->temperature = $temperature;
                $this->dateTime = $dateTime;
            } else {
                // throw exception 
                throw new RecordNotFoundException($id);
            }
            mysqli_stmt_close($command);
            $conn->close();
        }
        //constructor with data from args
        if (func_num_args() == 7) {
            $arguments = func_get_args();
            //pass arguments to attributes 
            $this->id = $arguments[0];
            $this->ph = $arguments[1];
            $this->humidity = $arguments[2];
            $this->h2o = $arguments[3];
            $this->light = $arguments[4];
            $this->temperature = $arguments[5];
            $this->dateTime = $arguments[6];
        }
    }

    public static function getAll(){
        $list = array();
        $conn = MysqlConnection::getConnection();
        $query = " Select id, ph, humidity, h2o, light, temperature, datetime from records";
        $command = $conn->prepare($query);
        $command->execute();
        $command->bind_result($id, $ph, $humidity, $h2o, $light, $temperature, $datetime);
        while($command->fetch()){
            array_push($list, new Record($id, $ph, $humidity, $h2o, $light, $temperature, $datetime));
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
                'id' => $this->id,
                'ph' => $this->ph,
                'humidity' => $this->humidity,
                'h2o' => $this->h2o,
                'light' => $this->light,
                'temperature' => $this->temperature,
                'datetime' => $this->dateTime,
            )
        );
    }

        function add(){
        //get connection
        $conn = MysqlConnection::getConnection();
        //query
        $query = 'INSERT INTO records (Ph, Humidity, H2o, Light, Temperature) values (?, ?, ?, ?, ?)';
        //command
        $command=$conn->prepare($query);
        //bind params
        $command->bind_param('dddid', $this->ph, $this->humidity, $this->h2o, $this->light, $this->temperature);
        //execute
        $result = $command->execute();
        //close command
        mysqli_stmt_close($command);
        //Close connection
        $conn->close();
        //return result
        return $result;

    }
    
}
