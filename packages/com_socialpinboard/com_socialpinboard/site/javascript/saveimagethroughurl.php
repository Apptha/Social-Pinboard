<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$url = $_REQUEST['url'];
if($url)
{
/**
 * Initialize the cURL session
 */
 $ch = curl_init();
$res  = '';
 /**
 * Set the URL of the page or file to download.
 */
 curl_setopt($ch, CURLOPT_URL, $url);

 /**
 * Ask cURL to return the contents in a variable instead of simply echoing them to  the browser.
 */
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

 /**
 * Execute the cURL session
 */
 $contents = curl_exec ($ch);

 /**
 * Close cURL session
 */
 curl_close ($ch);

$doc = new DOMDocument();
@$doc->loadHTML($contents);

$tags = $doc->getElementsByTagName('img');
$anchor_tags = $doc->getElementsByTagName('a');
$image_id=0;
//foreach ($anchor_tags as $anchor_tag)
//{
//    $image=$anchor_tag->getAttribute('href');
//    $name=pathinfo($image);
//    $name_extenstion=strtolower($name['extension']);
//    $extension_image=array('jpg','png','jpeg','gif');
//    if(strstr( $name_extenstion,'jpg') || strstr( $name_extenstion,'png')|| strstr( $name_extenstion,'gif') || strstr( $name_extenstion,'jpeg') )
//    {
//   $res.= '<img id="imgsrc' . $image_id . '" src="' . $image . '" />,';
//   $image_id++ ;
//}  
//}

$cnt = 0;


foreach ($tags as $tag) {
    
    $image_new =$tag->getAttribute( 'src' );
    
    $name=pathinfo($image_new);
    if (isset($name['extension']))
    $name_extenstion=strtolower($name['extension']);
   // echo $name_extenstion;exit;
        if(strstr($name_extenstion,'jpg') || strstr($name_extenstion,'gif') || strstr($name_extenstion,'jpeg'))
    {
        $res.= '<img id="mobimgsrc' . $cnt . '" src="' . $image_new . '" />,';
        $cnt++;
    }
//        if(strstr($name_extenstion,'jpg') || strstr($name_extenstion,'gif') || strstr($name_extenstion,'jpeg'))
//    {
    if(($tag->getAttribute('width')>50) || ($tag->getAttribute('height')>50) || $tag->getAttribute( 'style' ))          
    {   
        $getimage = true;
        if($tag->getAttribute( 'style' ))
        { 
           $style = $tag->getAttribute( 'style' );
            $width = 'width:'; $height = 'height:';
            if(strstr( $style, $width))
            {
                $getimage = true;
            }
            elseif(strstr( $style, $height))
            {
                $getimage = true;
            }
            else
            {
                $getimage = false;
            }
        }
        if($getimage)
        {
    $image = $tag->getAttribute('src');
   
            $images = explode("/", $image);
            $count = count($images);
            $count--;
            $str2 = explode(".", $images[$count]);
           $len2 = count($str2);
           $ext = $str2[($len2 - 1)];
           $ext=strtolower($ext);
        if (strstr($image, 'http') || strstr($image, 'https')) { 
            if (($ext == 'png') || ($ext == 'gif') || ($ext == 'jpeg') || ($ext == 'jpg') ) {
                $res.= '<img id="mobimgsrc' . $cnt . '" src="' . $image . '" />,';
    $cnt++;
    }
        }
 else {
 $domain = explode('/',  str_replace('http://', '', $url));
$image= 'http://'.$domain[0].'/'.$image;    
 
         if (($ext == 'png') || ($ext == 'gif') || ($ext == 'jpeg') || ($ext == 'jpg')) {
                $res.= '<img id="mobimgsrc' . $cnt . '" src="' . $image . '" />,';
    $cnt++;
    }
         }
}
    }
}
//}

}
else
{
    $tempValue = rand();
    $uploaddir = "images/socialpinboard/temp/"; 
     $userImageDetails=pathinfo($_FILES['uploadfile']['name']);
    
$file = $uploaddir . $tempValue.'.'.$userImageDetails['extension'];
 
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
  $res =  $tempValue.'.'.$userImageDetails['extension']; 
} 
}
echo $res;
?>

