<?php

$xml = new SimpleXMLElement('<xml version="1.0" encoding="UTF-8" standalone="true"/>');

// for ($i = 1; $i <= 8; ++$i) {
//     $track = $xml->addChild('track');
//     $track->addChild('path', "song$i.mp3");
//     $track->addChild('title', "Track $i - Track Title");
// }

// Header('Content-type: text/xml');
// print($xml->asXML());

$fp = fopen('article.xml', 'w+');
$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n";                
$xml .= '<article>'."\n";
$xml .= '<title></title>'."\n";
$xml .= '<description></description>'."\n";
$xml .= '<img></img>'."\n";
$xml .= '</article>'."\n";

fwrite($fp, $xml);
fclose($fp);    


?>