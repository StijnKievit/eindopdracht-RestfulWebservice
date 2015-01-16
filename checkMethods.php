<?php
/**
 * Created by PhpStorm.
 * User: Stijn
 * Date: 15-12-2014
 * Time: 13:36
 */

//DUMMY DATA
//============================================================================

$artists = array(

    "popular" => array(
        array(
            "name" => 'Ariana Grande',
            "id" => 1
        )
    ),
    "all" => array(
                 array(
                     "name" => 'Ariana Grande',
                     "id" => 1
                 ),
                 array(
                     "name" => 'Yellowclaw',
                     "id" => 2
                 ),
                 array(
                     "name" => '40 seconds to mars',
                     "id" => 3
                 ),
                 array(
                     "name" => 'Afrojack',
                     "id" => 4
                 )

    )

);





//===========================================================================
$name = "";
$method = "";
$message = "";


$catogories = array('artists', 'albums', 'tracks', 'track', 'artist');



//get
if ($_SERVER['REQUEST_METHOD'] == 'GET'){



    if (isset($_GET["name"] )) {

        $name = $_GET["name"];


        if (in_array($name, $catogories)) {


            if(isset($_GET["info"])){


               getData($name, $_GET["info"]);

            }
            else{

                echo 'got here without info';


            }
        }
        else{

            echo "404 not found";
        }




    }
    else{

        $message = "Something went wrong!";
    }

}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    echo "method is post";
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

    echo "method is delete";

}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $putData = file_get_contents("php://input");

        echo "method is put";
        print_r($putData);
	// doe iets met $putData

	$jsonData = json_decode($putData, true);
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){

    echo "method is options";
}



function getData($cat, $info){

    $options = array('popular', 'all');

    $artists = array(

        "popular" => array(
            array(
                "name" => 'Ariana Grande',
                "id" => 1,
                "link" => "http://localhost/user/restfulwebservices/htacces_test/artist/000001"
            )
        ),
        "all" => array(
            array(
                "name" => 'Ariana Grande',
                "id" => 1,
                "link" => "http://localhost/user/restfulwebservices/htacces_test/artist/000001"
            ),
            array(
                "name" => 'Yellowclaw',
                "id" => 2,
                "link" => "http://localhost/user/restfulwebservices/htacces_test/artist/000002"
            ),
            array(
                "name" => '40 seconds to mars',
                "id" => 3,
                "link" => "http://localhost/user/restfulwebservices/htacces_test/artist/000003"
            ),
            array(
                "name" => 'Afrojack',
                "id" => 4,
                "link" => "http://localhost/user/restfulwebservices/htacces_test/artist/000004"
            )

        )

    );

    if($cat == 'artists'){


        if($info != null){

            $option = explode('/', $info);

            if(in_array($option[0], $options))
            {


                if($option[0] == "popular"){


                    echo json_encode($artists['popular']);
                }
                else{
                    echo json_encode($artists['all']);
                }

            }
            else
            {

                echo json_encode($artists['all']);
            }

        }
        else{

            echo json_encode($artists['all']);
        }


    }
    if($cat == 'artist'){


        if($info != null){

            $option = explode('/', $info);


                if (is_numeric($option[0])){

                    $id = ltrim($option[0], '0');


                    echo json_encode($artists['all'][$id - 1]);

                }

            }

    }
    if($cat == 'album'){}
    if($cat == 'tracks'){}
    if($cat == 'track'){}





}

    ?>