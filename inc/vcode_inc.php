<?php
function vcode($width=120,$height=40,$fontSize=25,$countElement=4,$countPixel=100,$countLine=4){
    header('Content-type:image/jpeg');//设置网页,显示图片模式
    $element=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0');

    $text='';
    for($i=0;$i<$countElement;$i++){
        $text.=$element[rand(0,count($element)-1)];
    }
    $img=imagecreatetruecolor($width,$height);//建立图片，默认背景黑色
    $colorBg=imagecolorallocate($img,rand(200,255),rand(200,255),rand(200,255));//设置随机的图片背景颜色
    $colorBorder=imagecolorallocate($img,rand(200,255),rand(200,255),rand(200,255));//矩形边框颜色
    $colorString=imagecolorallocate($img,rand(10,100),rand(10,100),rand(10,100));
    imagefill($img,0,0,$colorBg);//填充背景颜色
    imagerectangle($img,0,0,$width-1,$height-1,$colorBorder);//绘制矩形边框
    for($i=0;$i<$countPixel;$i++){
        imagesetpixel($img,rand(0,$width-1),rand(0,$height-1),imagecolorallocate($img,rand(100,200),rand(100,200),rand(100,200)));//在随机位置生成100个小点
    }
    for($i=0;$i<$countLine;$i++){
    imageline($img,rand(0,$width/2),rand(0,$height),rand($width/2,$width),rand(0,$height),imagecolorallocate($img,rand(100,200),rand(100,200),rand(100,200)));//画一条线段
    }
    putenv('GDFONTPATH=' . realpath('.'));
    $font='font/yori';
    imagettftext($img,$fontSize,rand(-10,10),rand(0,$width/3),rand($height-$height/3,$height),$colorString,$font,$text);
    imagejpeg($img);//显示图片
    imagedestroy($img);//释放内存
    return $text;
}

?>