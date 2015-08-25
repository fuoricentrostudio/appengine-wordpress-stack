<?php

namespace FuoricentroStudio\WP\Helpers\Watermark;

function apply($postdata){
        
    // Load the stamp and the photo to apply the watermark to
    switch ( $postdata['type'] ) {
            case 'image/jpeg':
                    $image = imagecreatefromjpeg($postdata['file']);
                    break;
            case 'image/png':
                    $image = imagecreatefrompng($postdata['file']);
                    break;
            case 'image/gif':
                    $image = imagecreatefromgif($postdata['file']);
                    break;
            default:
                    return $postdata;
    }       
    
    $text = html_entity_decode('&copy;').date('Y').' - www.piananotizie.it';
    $font_file = dirname(__FILE__).'/OpenSans-Light.ttf';
    $font_size = 12;
    
    $bbox = imagettfbbox($font_size, 0, $font_file, $text );
    
    $wtm_x = abs($bbox[4] - $bbox[0]);
    $wtm_y = abs($bbox[5] - $bbox[1]);
               
    // First we create our stamp image manually from GD
    $stamp = imagecreatetruecolor($wtm_x, $wtm_y);
    imagecolortransparent($stamp, imagecolorallocate($stamp, 0, 0, 0));
        
    imagettftext($stamp, $font_size, 0, 0, $wtm_y, 0xFFFFFF, $font_file, $text);
    
    imagesavealpha($stamp, true);
    
    $src_x = imagesx($image);
    $src_y = imagesy($image);

    //imagecopymerge($image, $stamp, $src_x - $wtm_x - $marge_right, $src_y - $wtm_y - $marge_bottom, 0, 0, $wtm_x, $wtm_y, 30);
    imagecopymerge($image, $stamp, round($src_x/2)-round($wtm_x/2) ,round($src_y/2) -round($wtm_y/2) , 0, 0, $wtm_x, $wtm_y, 60);
    //imagecopymerge($image, $stamp, $marge_right, $marge_bottom, 0, 0, $wtm_x, $wtm_y, 30);

    switch ( $postdata['type'] ) {
            case 'image/jpeg':
                    $result = imagejpeg( $image, $postdata['file'], apply_filters( 'jpeg_quality', 90, 'edit_image' ) );
            case 'image/png':
                    $result = imagepng( $image, $postdata['file'] );
            case 'image/gif':
                    $result = imagegif( $image, $postdata['file'] );
    }    
        
    imagedestroy($image);    
    imagedestroy($stamp);    
    
    return $postdata;
}

add_filter( 'wp_handle_upload', __NAMESPACE__.'\\apply', 9);
