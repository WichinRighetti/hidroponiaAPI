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
            $r = new Site($_GET['id']);
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
                json_decode(Site::getAllbyJson())
        );
    }
}
