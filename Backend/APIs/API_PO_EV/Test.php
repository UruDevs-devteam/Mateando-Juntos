<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php';
$hor = date('Y-m-d H:i:s');
DeleteEvent($BaseURL , 1);
function GetPosts($BaseURL)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BaseURL . '/Posts');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    var_dump($data);
}
function GetPost($BaseURL, $Id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BaseURL . '/Post/' . $Id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    var_dump($data);
}
function AddPost($BaseURL, $Titulo, $Descripcion, $Fecha)
{
    $ch = curl_init($BaseURL . '/Post');
    $data = array("Title" => $Titulo, "Caption" => $Descripcion, "Date_crea" => $Fecha);// pasa los parametros a un array
    $jsondata = json_encode($data);                                                        //codifica el array en json
    curl_setopt($ch, CURLOPT_POST, 1);                                                     // especifica una solicitud de tipo POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);                                       // manda el array con la info
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));         // Indica que es tipo json
    $result = curl_exec($ch);                                                              // manda la solicitud y devuelve el resultado (del curl, no de la incerción)
    echo json_encode($result);
    curl_close($ch);
}
function DeletePost($BaseURL, $Id)
{
    $ch = curl_init($BaseURL . '/Post');
    $data = array("Id" => $Id);
    $jsondata = json_encode($data);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    echo json_encode($result);
    curl_close($ch);
}

function GetEvents($BaseURL)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BaseURL . '/Events');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    var_dump($data);
}

function GetEvent($BaseURL, $Id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BaseURL . '/Event/' . $Id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    var_dump($data);
}
function AddEvent($BaseURL, $ID_post, $Loc_x, $Loc_y, $Fecha, $Start, $Fin)
{
    $ch = curl_init($BaseURL . '/Event');
    $data = array(
        "ID_post" => $ID_post,
        "Loc_x" => $Loc_x,
        "Loc_y" => $Loc_y,
        "Fecha" => $Fecha,
        "Start" => $Start,
        "Fin" => $Fin

    );
    $jsondata = json_encode($data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    echo json_encode($result);
    curl_close($ch);
}

function DeleteEvent($BaseURL,$Id){
    $ch = curl_init($BaseURL . '/Event');
    $data = array("Id" => $Id);
    $jsondata = json_encode($data);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    echo json_encode($result);
    curl_close($ch);
}
function GetMulti ($BaseURL,$Id){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BaseURL . '/Multi/' . $Id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    var_dump($data);
}
function AddMulti ($BaseURL, $num, $Id, $src){
    $ch = curl_init($BaseURL . '/Multi');
    $data = array( "num" => $num, "Id" => $Id,  "src" => $src );
    $jsondata = json_encode($data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    echo json_encode($result);
    curl_close($ch);
}

GetMulti($BaseURL,2);