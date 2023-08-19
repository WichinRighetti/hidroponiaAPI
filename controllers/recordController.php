<?php
//allow access from outside the server

use LDAP\Result;

header('Access-Control-Allow-Origin: *');
//allow methods
header('Access-Control-Allow-Origin: GET, POST');

require_once($_SERVER['DOCUMENT_ROOT'] . '/hidroponiaAPI/models/records.php');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        try {
            $r = new Record($_GET['id']);
            echo json_encode(
                array(
                    json_decode($r->toJson())
                )
            );
        } catch (RecordNotFoundException $ex) {
            echo json_encode(
                array(
                    $ex->get_message()
                )
            );
        }
    } else {
        echo json_encode(
                json_decode(Record::getAllbyJson())
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['Ph']) && isset($_POST['Humidity']) && isset($_POST['H2o']) && isset($_POST['Light']) && isset($_POST['Temperature'])) {

        $r = new Record();

        $r->setPh($_POST['Ph']);
        $r->setHumidity($_POST['Humidity']);
        $r->setH2o($_POST['H2o']);
        $r->setLight($_POST['Light']);
        $r->setTemp($_POST['Temperature']);
        if ($r->add()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Record added Succesfully'
            ));
        } else {
            echo json_encode(array(
                'status' => 3,
                'message' => 'Couldnt add'
            ));
        }
    }
    else {
        echo json_encode(array(
            'status' => 999,
            'message' => 'Missing values'
        ));
    }
    
} 