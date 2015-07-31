<?php

use google\appengine\api\cloud_storage\CloudStorageException;
use google\appengine\api\cloud_storage\CloudStorageTools;

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$request = $_SERVER['REQUEST_URI'];
$matches = array();

    if(preg_match('~^/wp-content/uploads/.*/((.*)-(\d+)x(\d+)\.(.*))$~', $request, $matches)){
        $filename = '/'.$matches[2].'.'.$matches[5];
        $sizes = array($matches[3], $matches[4]);
    }elseif(preg_match('~^/wp-content/uploads/.*/(.*\.(.*))$~', $request, $matches)){
        $filename = '/'.$matches[1];
    }elseif(preg_match('~^/wp-content/uploads/(.*)$~', $request, $matches)){
        $filename = '/'.$matches[1];
    }else{
        $filename = $request;
    }
    
$memcache = new Memcache;
$key = 'static_301_'.md5($filename);
$url = $memcache->get($key);

if ($url === false) {    

    $bucket_file = 'gs://'.CloudStorageTools::getDefaultGoogleStorageBucketName().$filename;

    if(preg_match('~\.(ico|jpg|jpeg|png|gif|svg)$~', $filename, $matches)){
        $url = CloudStorageTools::getImageServingUrl($bucket_file);
    } else {
        $url = CloudStorageTools::getPublicUrl($bucket_file, $https);
    }
    
    $memcache->set($key, $url);
}
  
if(isset($sizes)){
    $url .= '=w'.$sizes[0].'-h'.$sizes[1];
}

header("Location: ".$url, true, 301);
exit;

