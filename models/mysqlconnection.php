<?php

//class
class MysqlConnection{
    //return a MYSQL connection object
    public static function getConnection(){
        //open config
        $configPath = $_SERVER['DOCUMENT_ROOT'].'/hidroponiaAPI/config/mysqlconnection.json';
        $configData = json_decode(file_get_contents($configPath),true);
        // check params
        if (isset($configData['server'])){
            $server = $configData["server"];
        }else{
            echo 'config error, server not found';
            die;
        }
        if (isset($configData['database'])){
            $database = $configData["database"];
        }else{
            echo 'config error, database not found';
            die;
        }
        if (isset($configData['user'])){
            $user = $configData["user"];
        }else{
            echo 'config error, user incorrect';
            die;
        }
        if (isset($configData['password'])){
            $password = $configData["password"];
        }else{
            echo 'config error, password incorrect';
            die;
        }
        //create connection 
        $conn = mysqli_connect($server,$user,$password,$database);
        //character set 
        $conn->set_charset('utf8mb4');
        //check conn 
        if(!$conn){
            echo 'Could not connect to mySQL';
            die;
        }
        return $conn;
    } 
}
