<?php
function upload($prefix,$save_path,$custom_upload_max_filesize,$key,$type=['jpg','jpeg','gif','png','']){
    //初始化要返回的数组
    $return_data=[];
    //获取phpini配置文件的upload_max_filesize
    $phpini = ini_get('upload_max_filesize');

    //获取phpini配置文件的upload_max_filesize的单位
    $phpini_unit=strtoupper(substr($phpini,-1,1));

    //获取phpini配置文件的upload_max_filesize的数字
    $phpini_number=substr($phpini,0,-1);

    //计算出转换成字节应该乘以的倍数
    $phpini_multiple=get_multiple($phpini_unit);

    //转换成字节
    $phpini_bytes=$phpini_number*$phpini_multiple;

    //处理传入的参数的文件大小
    $custom_unit=strtoupper(substr($custom_upload_max_filesize,-1));
    $custom_number=substr($custom_upload_max_filesize,0,-1);
    $custom_multiple=get_multiple($custom_unit);
    $custom_bytes=$custom_number*$custom_multiple;

    //文件大小验证
    if($custom_bytes>$phpini_bytes){
        $return_data['error']='传入的$custom_upload_max_filesize大于'.$phpini;
        $return_data['OK']=false;
        return $return_data;
    }
    $arr_errors=['没有错误','上传文件超过phpini中限定的值','文件大小超过了HTML表单中限定的值','文件只有部分被上传','没有文件被上传','找不到临时文件夹','文件写入失败'];
    if(!isset($_FILES[$key]['error'])){
        $return_data['error']='由于未知原因上传失败，请重试';
        $return_data['OK']=false;
        return $return_data;
    }
    if($_FILES['error']!=0){
        $return_data['error']=$arr_errors[$_FILES['error']];
    }
    if($_FILES[$key]['size']>$custom_bytes){
        $return_data['error']='上传文件大小超过文件限定的'.$custom_upload_max_filesize;
        $return_data['OK']=false;
        return $return_data;
    }

    //验证上传方式
    if(!is_uploaded_file($_FILES[$key]['tmp_name'])){
        $return_data['error']='上传的文件不是通过http post方式上传的';
        $return_data['OK']=false;
        return $return_data;
    }

    //验证文件类型
    $arr_filename=pathinfo($_FILES[$key]['name']);
    if(!$arr_filename['extension']){
        $arr_filename['extension']='';
    }
    if(!in_array($arr_filename['extension'],$type)){
        $return_data['error']='上传文件的类型必须是'.implode(',',$type).'其中的一个';
        $return_data['OK']=false;
        return $return_data;
    }

    //验证、创建文件目录
    if(!file_exists($save_path)){
        if(!mkdir($save_path,0777,true)){
            $return_data['error']='上传文件保存目录失败，请检查权限';
            $return_data['OK']=false;
            return $return_data;
        }
    }
    //文件名生成、赋值
    $new_filename=str_replace('.','',uniqid($prefix.'_',true));
    if($arr_filename['extension']!=''){
        $new_filename.=".{$arr_filename['extension']}";
    }
    $save_path=rtrim($save_path,'/').'/';
    //把临时文件夹里的文件移动到信文件夹
    if(!move_uploaded_file($_FILES[$key]['tmp_name'],$save_path.$new_filename)){
        $return_data['error']='临时文件移动失败，请检查权限';
        $return_data['OK']=false;
        return $return_data;
    }

    $return_data['save_path']=$save_path.$new_filename;
    $return_data['filename']=$new_filename;
    $return_data['return']=true;
    return $return_data;

}

function get_multiple($unit){
    switch ($unit){
        case 'K':
            $multiple=1024;
            break;
        case 'M':
            $multiple=1024*1024;
            break;
        case 'G':
            $multiple=1024*1024*1024;
            break;
        default:
            return false;
    }
    return $multiple;
}
?>
