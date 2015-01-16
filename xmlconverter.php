<?php
/**
 * Created by PhpStorm.
 * User: Stijn
 * Date: 15-1-2015
 * Time: 19:51
 */

function convertToXML($collection, $array)
{

    if($collection) {


        $xml = new DOMDocument('1.0', "UTF-8");

        $items = $xml->createElement("items");
//$links = $xml->createElement("links");
//$pagination = $xml->createElement("pagination");

        $items = $xml->appendChild($items);
//$links = $xml->appendChild($links);
//$pagination = $xml->appendChild($pagination);


        for ($i = 0; $i < 10; $i++) {


            $artist = $xml->createElement("artist");
            $artist = $items->appendChild($artist);

            //add items to artists

            $id = $xml->createElement("id", $i);
            $id = $artist->appendChild($id);

            $name = $xml->createElement("name", "Yellowclaw");
            $name = $artist->appendChild($name);

            $main_genre = $xml->createElement("main_genre", "Dance");
            $main_genre = $artist->appendChild($main_genre);

            $url = $xml->createElement("url", "google.com");
            $url = $artist->appendChild($url);

            $rating = $xml->createElement("rating");
            $rating = $artist->appendChild($rating);

            $total_stars = $xml->createElement("total_stars", "5");
            $total_stars = $rating->appendChild($total_stars);

            $total_votes = $xml->createElement("total_votes", "1");
            $total_votes = $rating->appendChild($total_votes);

            $stars = $xml->createElement("stars", "5");
            $stars = $rating->appendChild($stars);

            $links = $xml->createElement("links");
            $links = $artist->appendChild($links);

            $first = $xml->createElement('firstlinks');
            $first = $links->appendChild($first);

            $rel = $xml->createElement("rel", 'self');
            $rel = $first->appendChild($rel);

            $href = $xml->createElement("href", 'self.com');
            $href = $first->appendChild($href);

            $second = $xml->createElement('secondlink');
            $second = $links->appendChild($second);

            $rel = $xml->createElement("rel", "collection");
            $rel = $second->appendChild($rel);

            $href = $xml->createElement("href", "collection.com");
            $href = $second->appendChild($href);

        }

        $xml->formatOutput = true;

        $string_value = $xml->saveXML();

        $xml->save('example.xml');
    }
    else{

        echo $collection;
        print_r($array);

        $xml = new DOMDocument('1.0', "UTF-8");



        $artist = $xml->createElement("artist");
        $artist = $xml->appendChild($artist);

        //add items to artists

        $id = $xml->createElement("id", 'currentID');
        $id = $artist->appendChild($id);

        $name = $xml->createElement("name", "Yellowclaw");
        $name = $artist->appendChild($name);

        $main_genre = $xml->createElement("main_genre", "Dance");
        $main_genre = $artist->appendChild($main_genre);

        $url = $xml->createElement("url", "google.com");
        $url = $artist->appendChild($url);

        $rating = $xml->createElement("rating");
        $rating = $artist->appendChild($rating);

        $total_stars = $xml->createElement("total_stars", "5");
        $total_stars = $rating->appendChild($total_stars);

        $total_votes = $xml->createElement("total_votes", "1");
        $total_votes = $rating->appendChild($total_votes);

        $stars = $xml->createElement("stars", "5");
        $stars = $rating->appendChild($stars);

        $links = $xml->createElement("links");
        $links = $artist->appendChild($links);

        $first = $xml->createElement('firstlinks');
        $first = $links->appendChild($first);

        $rel = $xml->createElement("rel", 'self');
        $rel = $first->appendChild($rel);

        $href = $xml->createElement("href", 'self.com');
        $href = $first->appendChild($href);

        $second = $xml->createElement('secondlink');
        $second = $links->appendChild($second);

        $rel = $xml->createElement("rel", "collection");
        $rel = $second->appendChild($rel);

        $href = $xml->createElement("href", "collection.com");
        $href = $second->appendChild($href);


    }

}

?>