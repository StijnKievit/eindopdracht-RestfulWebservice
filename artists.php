<?php
/**
 * Created by PhpStorm.
 * User: Stijn
 * Date: 12-1-2015
 * Time: 15:27
 */

start();


function start()
{

    $supportedTypes = array('application/json', 'application/xml');
    $HTTPHeaders = getallheaders();
    $allowed = false;
    $Type = [];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {


       $acceptHeader = $_SERVER['HTTP_ACCEPT'];

        if((($pos = strpos($acceptHeader, $supportedTypes[0])) !==false)){

            $allowed = true;
            array_push($Type, $supportedTypes[0]);
            //echo 'its allowed to use '.$supportedTypes[0];
        }
        elseif((($pos = strpos($acceptHeader, $supportedTypes[1])) !==false)){

            $allowed = true;
            array_push($Type, $supportedTypes[1]);
            //echo 'its allowed to use '.$supportedTypes[1];


        }
        else{
            $allowed = false;
            http_response_code(415);
            exit;

        }

        if ($allowed){

            if (isset($HTTPHeaders['Content-Type'])) {

                if ($HTTPHeaders['Content-Type'] == 'application/json' || $HTTPHeaders['Content-Type'] == 'application/xml') {

                    http_response_code(200);
                    $content_type = $HTTPHeaders['Content-Type'];
                    header('Content-Type:'.$content_type);
                }
                else {

                    http_response_code(415);
                    exit;


                }
            }
            else {

                if(count($Type > 1)){

                    $content_type = 'application/json';

                }
                elseif(count($Type) ===  1){

                    $content_type = $Type[0];
                }
                else{


                }

                header('Content-Type:'.$content_type);

            }

        }

       // echo $content_type;


        //$content_type = "application_json";

        if (isset($_GET['start']) && isset($_GET['limit'])) {
            $start = $_GET['start'];
            $limit = $_GET['limit'];
        } elseif (isset($_GET['start']) && !isset($_GET['limit'])) {
            $start = $_GET['start'];
            $limit = 6;
        } elseif (!isset($_GET['start']) && isset($_GET['limit'])) {
            $start = 1;
            $limit = $_GET['limit'];
        } else {
            $start = 1;
            $limit = 6;
        }


        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'popular') {

                if (isset($UriInput[1])) {


                    echo $UriInput[1];

                } else {
                    getData(true, null, $start, $limit, $content_type);
                }
            } elseif (is_numeric($UriInput[0])) {


                getData(null, $UriInput[0], $start, $limit, $content_type);
            } else {

                echo "error 404";
                http_response_code(404);
            }

        } else {


            getData(false, null, $start, $limit, $content_type);
        }


    }

    if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {


        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'popular') {

                header("Allow: GET, OPTIONS");

            } elseif (is_numeric($UriInput[0])) {

                header('Allow: GET, POST, DELETE, OPTIONS');
            } else {

                header('Allow: GET');
            }
        }
        else {

            header("Allow: GET, POST, OPTIONS");

        }


    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'popular') {

                http_response_code(405);

            } elseif (is_numeric($UriInput[0])) {

                http_response_code(405);

            } else {
                echo 'you came in the wrong place';


            }
        } else {


            if(isset($_POST['name']) && isset($_POST['url']) && isset($_POST['main_genre'])){



                echo $name = $_POST['name'];
                echo $url = $_POST['url'];
                echo $main_genre = $_POST['main_genre'];

                addData($name, $url, $main_genre);



            }
            else{

                if (file_get_contents('php://input') != null){

                    $json = file_get_contents('php://input');
                    $newObject = json_decode($json);

                    if($newObject != null){

                        http_response_code(400);


                        if (property_exists($newObject, 'url') && property_exists($newObject, 'name') && property_exists($newObject, 'main_genre')){

                            //echo $newObject->url;
                            //echo $newObject->name;
                            //echo $newObject->main_genre;

                            $name = $newObject->name;
                            $url = $newObject->url;
                            $main_genre = $newObject->main_genre;

                            addData($name, $url, $main_genre);



                        }
                        else{

                            http_response_code(400);
                        }
                    }
                    else{

                        http_response_code(400);
                    }



                }
                else
                {
                    http_response_code(400);
                }
            }
        }


    }

    if ($_SERVER['REQUEST_METHOD'] == "DELETE"){



        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'popular') {

                http_response_code(405);

            } elseif (is_numeric($UriInput[0])) {

                deleteItem($UriInput[0]);

            } else {

                http_response_code(400);


            }
        }

        else{

            http_response_code(405);

            }
        }

    if ($_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'OPTIONS' && $_SERVER['REQUEST_METHOD'] != 'DELETE'){
        http_response_code(405);
        exit;
    }
}
function getData($popular, $id, $start, $limit, $content_type){


    $collection = true;

    $artists = array(

    );


    /*$servername = "sql.cmi.hro.nl";
    $database = "0875013";
    $username = "0875013";
    $password = "7c4e3ec2";*/


    $servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($popular)){

        //echo 'popular is not null';
        //echo '<br>';
        if ($popular){
            //echo 'is popular';
            $sql = "SELECT * FROM artists ORDER BY total_stars DESC ";

        }
        else{
            //echo 'is not popular';
            $sql = "SELECT * FROM artists";
        }

    }
    else{
        if($id != null){

            $sql = "SELECT * FROM artists WHERE id = $id";
        }
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()){

            if ($id == null) {

                $collection = true;

                $artist = array(
                    'id' => $row['id'],
                    'name' => $row['name'],


                    'links' => [
                        ['rel' => 'self',
                            'href' => "http://localhost/user/restfulwebservices/htacces_test/artists/" . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . ""],
                        ['rel' => 'collection',
                            'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/']
                    ]
                );
            }
            else{

                $collection = false;

                $stars =  ($row['total_votes'] > 0) ? round($row['total_stars']/$row['total_votes'], 1): 0;

                $artist = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'url' => $row['url'],
                    'rating' => array(
                            'total_stars' => $row['total_stars'],
                            'total_votes' => $row['total_votes'],
                            'stars' => $stars
                    ),
                    'links' => [
                        ['rel' => 'self',
                            'href' => "http://localhost/user/restfulwebservices/htacces_test/artists/" . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . ""],
                        ['rel' => 'collection',
                            'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/']
                    ]

                );


            }


            array_push($artists, $artist);
        }
    }
    else{
        http_response_code(404);
        exit;

    }



    $conn->close();

    if($collection == false) {

        if ($content_type == "application/json"){

            header('Content-Type:'.$content_type);
            echo json_encode($artist, JSON_FORCE_OBJECT);

        }
        elseif($content_type == "application/xml") {
            convertToXML($collection, $artist);

        }
        else{

            http_response_code(400);
            exit;
        }

    }
    else{

        addPagination($artists, $start, $limit, $content_type, $popular);
    }


}

function addPagination($array, $start, $limit, $content_type, $popular){


    $contentType = $content_type;


    $currentPage = $start;

    $totalItems = count($array);


    $totalPages = ceil(($totalItems/$limit));
   // echo "total items ".$totalItems;
    //echo "total pages ".$totalPages;
    //echo "items per page ".$totalItems/$totalPages;

    $itemsPerPage = round(($totalItems/$totalPages),0, PHP_ROUND_HALF_UP);


    $firstItemId = (($start -1)*$itemsPerPage);


    $limitedarray = array_slice($array, $firstItemId, $limit, true);


    $currentItems = count($limitedarray);




    if($currentPage >= $totalPages){

        $nextPage = $totalPages;
    }
    else{
        $nextPage = $currentPage+1;
    }




    if($popular){

        $artistList = array('items' => $limitedarray,
            'links' => array(
                array(
                    'rel' => 'self',
                    'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular'
                ),
                array(
                    'rel' => 'popular',
                    'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular'
                ),
                array(
                'rel' => 'basic',
                'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/'
                )
            ));

        $pagination = array('pagination' => array(
            'currentPage' => $currentPage,
            'currentItem' => $currentItems,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'links' =>  array(
                array(
                    'rel'   => 'first',
                    'page'  => 1,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular?limit='.$limit.'&start=1'
                ),
                array(
                    'rel'   => 'last',
                    'page'  => $totalPages,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular?limit='.$limit.'&start='.$totalPages.''
                ),
                array(
                    'rel'   => 'previous',
                    'page'  => (($currentPage > 1) ? ($currentPage-1)  :1),
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular?limit='.$limit.'&start='.(($currentPage > 1) ? ($currentPage-1)  :1).''
                ),
                array(
                    'rel'   => 'next',
                    'page'  => $currentPage+1,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular?limit='.$limit.'&start='.$nextPage.''
                )
            )
        ));
    }
    else{

        $artistList = array('items' => $limitedarray,
            'links' => array(
                array(
                    'rel' => 'self',
                    'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/'
                ),
                array(
                    'rel' => 'popular',
                    'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/popular'
                ),
                array(
                'rel' => 'basic',
                'href' => 'http://localhost/user/restfulwebservices/htacces_test/artists/'
                )
            ));
        $pagination = array('pagination' => array(
            'currentPage' => $currentPage,
            'currentItem' => $currentItems,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'links' =>  array(
                array(
                    'rel'   => 'first',
                    'page'  => 1,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/?limit='.$limit.'&start=1'
                ),
                array(
                    'rel'   => 'last',
                    'page'  => $totalPages,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/?limit='.$limit.'&start='.$totalPages.''
                ),
                array(
                    'rel'   => 'previous',
                    'page'  => (($currentPage > 1) ? ($currentPage-1)  :1),
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/?limit='.$limit.'&start='.(($currentPage > 1) ? ($currentPage-1)  :1).''
                ),
                array(
                    'rel'   => 'next',
                    'page'  => $currentPage+1,
                    'href'  => 'http://localhost/user/restfulwebservices/htacces_test/artists/?limit='.$limit.'&start='.$nextPage.''
                )
            )
        ));
    }



    $finalArray = array_merge($artistList, $pagination);


    if ($contentType == "application/json"){

        header('Content-Type:'.$content_type);
        echo json_encode($finalArray, JSON_FORCE_OBJECT);


    }
    elseif($contentType == "application/xml")
    {
        header('Content-Type:'.$content_type);
        convertToXML(true, $finalArray);



    }
    else{

        http_response_code(400);
        exit;
    }


}


function addData($name, $url, $main_genre){

    $servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";
/*
    $servername = "sql.cmi.hro.nl";
    $database = "0875013";
    $username = "0875013";
    $password = "7c4e3ec2";*/

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO artists (name, url, main_genre)
            VALUES ('$name', '$url', '$main_genre')";


    $query = $conn->query($sql);


    if($query) // will return true if succefull else it will return false
    {
        http_response_code(201);
        $sql = "SELECT * FROM artists WHERE name = '$name'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {

                $stars =  ($row['total_votes'] > 0) ? round($row['total_stars']/$row['total_votes'], 1): 0;


                header("Content-Type: application/json");
                echo json_encode(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'url' => $row['url'],
                    'rating' => array(
                        'total_stars' => $row['total_stars'],
                        'total_votes' => $row['total_votes'],
                        'stars' => $stars
                    ),
                    'links' => array(
                        array(
                            'rel' => 'self',
                            'href' => "http://localhost/user/restfulwebservices/htacces_test/artists/" . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . ""
                        ),
                        array(
                            'rel' => 'collection',
                            'href' => "http://localhost/user/restfulwebservices/htacces_test/artists/"
                        )

                    )

                ));

            }

        }






// code here
    }
    else{
        http_response_code(400);

        echo'something went wrong';
        echo $conn->error;
    }

    $conn->close();


}

function convertToXML($collection, $array)
{



    if($collection) {

        $numberOfItems = count($array['items']);

        $xml = new DOMDocument('1.0', "UTF-8");

        $items = $xml->createElement("items");
        $items = $xml->appendChild($items);

        $Pagelinks = $xml->createElement("links");
        $Pagelinks = $xml->appendChild($Pagelinks);

        $pagination = $xml->createElement("pagination");
        $pagination = $xml->appendChild($pagination);


        for ($i = 0; $i < $numberOfItems; $i++) {



            $artist = $xml->createElement("artist");
            $artist = $items->appendChild($artist);

            //add items to artists

            $id = $xml->createElement("id", $array['items'][$i]['id']);
            $artist->appendChild($id);

            $name = $xml->createElement("name", $array['items'][$i]['name']);
            $artist->appendChild($name);

            $links = $xml->createElement("links");
            $artist->appendChild($links);

            $first = $xml->createElement('link');
            $first = $links->appendChild($first);

            $rel = $xml->createElement("rel", $array['items'][$i]['links'][0]['rel']);
            $first->appendChild($rel);

            $href = $xml->createElement("href", $array['items'][$i]['links'][0]['href']);
            $first->appendChild($href);

            $second = $xml->createElement('link');
            $second = $links->appendChild($second);

            $rel = $xml->createElement("rel", $array['items'][$i]['links'][1]['rel']);
            $second->appendChild($rel);

            $href = $xml->createElement("href", $array['items'][$i]['links'][1]['href']);
            $second->appendChild($href);

        }


        for ($k = 0; $k < count($array['links']); $k++){

            $link = $xml->createElement('link');
            $link = $Pagelinks->appendChild($link);

            $rel = $xml->createElement('rel', $array['links'][$k]['rel']);
            $link->appendChild($rel);
            $href = $xml->createElement('href', $array['links'][$k]['href']);
            $link->appendChild($href);

        }

        $currentPage = $xml->createElement("currentPage", $array['pagination']['currentPage']);
        $pagination->appendChild($currentPage);

        $currentItem = $xml->createElement("currentItem", $array['pagination']['currentItem']);
        $pagination->appendChild($currentItem);

        $totalPages = $xml->createElement("totalPages", $array['pagination']['totalPages']);
        $pagination->appendChild($totalPages);

        $totalItems = $xml->createElement("totalItems", $array['pagination']['totalItems']);
        $pagination->appendChild($totalItems);

        $paginationLinks = $xml->createElement("links");
        $paginationLinks = $pagination->appendChild($paginationLinks);


        for ($p = 0; $p < count($array['pagination']['links']); $p++){




            $link = $xml->createElement('link');
            $link = $paginationLinks->appendChild($link);

            $rel = $xml->createElement('rel', $array['pagination']['links'][$p]['rel']);
            $link->appendChild($rel);
            $page = $xml->createElement('page', $array['pagination']['links'][$p]['page']);
            $link->appendChild($page);
            $href = $xml->createElement("href",htmlspecialchars($array['pagination']['links'][$p]['href']));
            $link->appendChild($href);






        }

        print $xml->saveXML();
    }
    else{

        //echo $collection;
        //print_r($array);
        //echo $array['links'][0]['rel'];

        $xml = new DOMDocument('1.0', "UTF-8");



        $artist = $xml->createElement("artist");
        $artist = $xml->appendChild($artist);

        //add items to artists

        $id = $xml->createElement("id", $array['id']);
        $id = $artist->appendChild($id);

        $name = $xml->createElement("name", $array['name']);
        $name = $artist->appendChild($name);

        $main_genre = $xml->createElement("main_genre", $array['main_genre']);
        $main_genre = $artist->appendChild($main_genre);

        $url = $xml->createElement("url", $array['url']);
        $url = $artist->appendChild($url);

        $rating = $xml->createElement("rating");
        $rating = $artist->appendChild($rating);

        $total_stars = $xml->createElement("total_stars", $array['rating']['total_stars']);
        $total_stars = $rating->appendChild($total_stars);

        $total_votes = $xml->createElement("total_votes", $array['rating']['total_votes']);
        $total_votes = $rating->appendChild($total_votes);

        $stars = $xml->createElement("stars", $array['rating']['stars']);
        $stars = $rating->appendChild($stars);

        $links = $xml->createElement("links");
        $links = $artist->appendChild($links);

        $first = $xml->createElement('link');
        $first = $links->appendChild($first);

        $rel = $xml->createElement("rel", $array['links'][0]['rel']);
        $rel = $first->appendChild($rel);

        $href = $xml->createElement("href", $array['links'][0]['href']);
        $href = $first->appendChild($href);

        $second = $xml->createElement('link');
        $second = $links->appendChild($second);

        $rel = $xml->createElement("rel", $array['links'][1]['rel']);
        $rel = $second->appendChild($rel);

        $href = $xml->createElement("href", $array['links'][1]['href']);
        $href = $second->appendChild($href);




        header("Content-type: application/xml");
        print $xml->saveXML();

    }

}


function deleteItem($id){

    $servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";

   /* $servername = "sql.cmi.hro.nl";
    $database = "0875013";
    $username = "0875013";
    $password = "7c4e3ec2";*/

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM artists
            WHERE id='$id'";


    $query = $conn->query($sql);

    if($query){
        http_response_code(200);

    }
    else{

        http_response_code(400);
    }

    $conn->close();

}

?>
