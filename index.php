<?php

date_default_timezone_set(get_timezone()); // change this to your local country time zone
include 'GIFEncoder.class.php';

if(empty($_GET["dt"])){
    die("datetime must be set... ?dt=2015-03-24/16:23:56  .... YYYY-mm-dd/H:i:s");
}
$time = str_replace("/", " ", $_GET["dt"]);


//if(empty($_GET["f"])){
//    die("format must be set... ?f=a-H-I-S  ");
//}
//$ff = explode("-", $_GET["f"]);
//$f = "";
//for($i=0; $i<count($ff); $i++){
//    $ff[$i] = "%".$ff[$i];
////    $f = $f."%".$frf[$i]."";
//    //%a:%H:%I:%S
//}
//$f = implode(":", $ff);
//
//
//if(empty($_GET["t"])){
//    die("transition must be set... ?t=second  .... second or minute or hour");
//}
//$t = $_GET["t"];

$future_date = new DateTime(date('r',strtotime($time)));
$time_now = time();
$now = new DateTime(date('r', $time_now));

$f = "%a:%H:%I:%S";
$t = "second";

$frames = array();
$delays = array();
//$is_cache = true;
//if(!empty($_GET["cache"])){
//    if($_GET["cache"]==0){
//        $is_cache = false;
//    }
//}
$cache_file = "cache/".preg_replace("/[^a-z,A-Z,0-9,_]/", "_", $time).".gif";
//if(file_exists($cache_file) & $is_cache){
//    header( 'Expires: Sun, 03 Mar 2015 16:25:00 GMT' ); //expire this image instantly
//    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
//    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
//    header( 'Cache-Control: post-check=0, pre-check=0', false );
//    header( 'Pragma: no-cache' );
//    header('Content-type:image/gif');
//    echo file_get_contents($cache_file);
//    die();
//}


$image = imagecreatefrompng('background/1.png'); // change background 
$delay = 100;
$font = array(
    'size'=>65, // font size
    'angle'=>0,
    'x-offset'=>30, // offset on x asis
    'y-offset'=>80, // offset on y asis
    'file'=>'fonts/PT_Sans-Web-Regular.ttf', // change font (example: handsean.ttf)
    'color'=>imagecolorallocate($image, 255, 255, 255),
);

for($i = 0; $i <= 60; $i++){
    $interval = date_diff($future_date, $now);
    if($future_date < $now){

        $image = imagecreatefrompng('background/1.png'); // change background 
        $text = $interval->format('00:00:00:00');
        imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
        ob_start();
        imagegif($image);
        $frames[]=ob_get_contents();
        $delays[]=$delay;
        $loops = 1;
        ob_end_clean();
        break;
    } else {

        $image = imagecreatefrompng('background/1.png'); // change background 
        $text = $interval->format($f);

        if(preg_match('/^[0-9]\:/', $text)){
            $text = '0'.$text;
        }
        imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
        ob_start();
        imagegif($image);
        $frames[]=ob_get_contents();
        $delays[]=$delay;
        $loops = 0;
        ob_end_clean();
    }
    $now->modify('+1 '.$t);
}

$gif = new AnimatedGif($frames,$delays,$loops);
//if($is_cache){
    file_put_contents($cache_file, $gif->getAnimation());
//}
header( 'Expires: '.gmdate('D, d M Y H:i:s T', strtotime($time)) ); //expire this image
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$gif->display();




function get_timezone(){ 
    $geolocation = unserialize( file_get_contents( "http://www.geoplugin.net/php.gp?ip=".getUserIP() ) );
    return ($geolocation['geoplugin_timezone']);
}

//https://stackoverflow.com/a/13646735/4896819
function getUserIP( ) {
    if ( isset( $_SERVER[ "HTTP_CF_CONNECTING_IP" ] ) ) {
        $_SERVER[ 'REMOTE_ADDR' ]    = $_SERVER[ "HTTP_CF_CONNECTING_IP" ];
        $_SERVER[ 'HTTP_CLIENT_IP' ] = $_SERVER[ "HTTP_CF_CONNECTING_IP" ];
    }
    $client  = @$_SERVER[ 'HTTP_CLIENT_IP' ];
    $forward = @$_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
    $remote  = $_SERVER[ 'REMOTE_ADDR' ];
    if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
        $ip = $client;
    } elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}