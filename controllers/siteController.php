<?php
//allow access from outside the server

use LDAP\Result;

header('Access-Control-Allow-Origin: *');
//allow methods
header('Access-Control-Allow-Origin: GET, POST');

require_once($_SERVER['DOCUMENT_ROOT'] . '/hidroponiaAPI/models/sites.php');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        try {
            $s = new Site($_GET['id']);
            echo json_encode(
                array(
                    json_decode($s->toJsonFull())
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
                json_decode(Site::getAllbyJson())
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['Name'], $_POST['Location'], $_POST['Status'], $_POST['Owner'])) {

        $s = new Site();

        $s->setName($_POST['Name']);
        $s->setLocation($_POST['Location']);
        $s->setStatus($_POST['Status']);
        $s->setOwner($_POST['Owner']);
        if ($s->add()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Site added Succesfully'
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