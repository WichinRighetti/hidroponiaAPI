<?php

require_once('mysqlconnection.php');
require_once('sites.php');
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
    private $Site;

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
    public function getPump()
    {
        return $this->pump;
    }
    public function setPump($value)
    {
        $this->pump = $value;
    }
    public function getDateTime()
    {
        return $this->dateTime;
    }
    public function setDateTime($value)
    {
        $this->dateTime = $value;
    }
    public function getSite()
    {
        return $this->Site;
    }
    public function setSite($value)
    {
        $this->Site = $value;
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
            $this->Site = new Site();
        }
        //constructor  
        if (func_num_args() == 1) {
            //get id 
            $id = func_get_arg(0);
            //get connection 
            $conn = MysqlConnection::getConnection();
            //query 
            $query = "SELECT r.Id, r.Ph, r.Humidity, r.H2o, r.Light, r.Temperature, r.pump, r.dateTime, r.siteId, s.name, s.location, s.Status, s.owner 
            FROM sensorData r 
            Left Join Sites s on r.siteId = s.id
            WHERE r.Id = ?";
            //command
            $command = $conn->prepare($query);
            //bind params
            $command->bind_param('i', $id);
            $command->execute();
            //bind result
            $command->bind_result($id, $ph, $humidity, $h2o, $light, $temperature, $pump, $dateTime, $siteId, $name, $location, $siteStatus, $owner);
            //record was found 
            if ($command->fetch()) {
                $site = new Site($siteId, $name, $location, $siteStatus, $owner);

                $this->id = $id;
                $this->ph = $ph;
                $this->humidity = $humidity;
                $this->h2o = $h2o;
                $this->light = $light;
                $this->temperature = $temperature;
                $this->pump = $pump;
                $this->dateTime = $dateTime;
                $this->Site = $site;
            } else {
                // throw exception 
                throw new RecordNotFoundException($id);
            }
            mysqli_stmt_close($command);
            $conn->close();
        }
        //constructor with data from args
        if (func_num_args() == 9) {
            $arguments = func_get_args();
            //pass arguments to attributes 
            $this->id = $arguments[0];
            $this->ph = $arguments[1];
            $this->humidity = $arguments[2];
            $this->h2o = $arguments[3];
            $this->light = $arguments[4];
            $this->temperature = $arguments[5];
            $this->pump = $arguments[6];
            $this->dateTime = $arguments[7];
            $this->Site = $arguments[8];
        }
    }

    public static function getAll(){
        $list = array();
        $conn = MysqlConnection::getConnection();
        $query = "Select r.Id, r.Ph, r.Humidity, r.H2o, r.Light, r.Temperature, r.pump, r.dateTime, r.siteId, s.name, s.location, s.Status, s.owner
            From sensorData r 
            Left JOIN Sites s on s.id = r.siteId ";
        $command = $conn->prepare($query);
        $command->execute();
        $command->bind_result($id, $ph, $humidity, $h2o, $light, $temperature, $pump, $dateTime, $siteId, $name, $location, $siteStatus, $owner);
        while($command->fetch()){
            $site = new Site($siteId, $name, $location, $siteStatus, $owner);
            array_push($list, new Record($id, $ph, $humidity, $h2o, $light, $temperature, $pump, $dateTime, $site));
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
                'pump' => $this->pump,
                'datetime' => $this->dateTime,
                'site' => json_decode($this->Site->toJson())
            )
        );
    }

    public function toJsontoSite()
    {
        return json_encode(
            array(
                'id' => $this->id,
                'ph' => $this->ph,
                'humidity' => $this->humidity,
                'h2o' => $this->h2o,
                'light' => $this->light,
                'temperature' => $this->temperature,
                'pump' => $this->pump,
                'datetime' => $this->dateTime,
            )
        );
    }

        function add(){
        //get connection
        $conn = MysqlConnection::getConnection();
        //query
        $query = 'INSERT INTO sensorData (Ph, Humidity, H2o, Light, Temperature, pump, siteId) values (?, ?, ?, ?, ?, ?, ?)';
        //command
        $command=$conn->prepare($query);
        //bind params
        $command->bind_param('dddidii', $this->ph, $this->humidity, $this->h2o, $this->light, $this->temperature, $this->pump, $this->Site->getId());
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
