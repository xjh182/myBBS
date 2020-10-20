<?php
header("Content-type:text/html;charset=utf-8");

/*
分页函数
参数说明：
page($count,$page_size,$num_btn=10,$page='page')
返回值：arr('limit','html');
$count：总记录数
$page_size：每页显示的记录数
$num_btn：要展示的页码按钮数目
$page：分页的get参数
*/
function page($count,$page_size,$num_btn=10,$page='page')
{
    //如果没有帖子,返回空数组
    if($count==0){
        $data=array(
            'limit' => '',
            'html' => '',
        );
        return $data;
    }

    if(!isset($_GET[$page]) || !is_numeric($_GET[$page]) || $_GET[$page]<1){
        $_GET[$page]=1;
    }
    //总页数
    $page_num_all = ceil(($count/$page_size));
    if($_GET[$page]>$page_num_all){
        $_GET[$page]=$page_num_all;
    }
    $start=($_GET[$page]-1)*$page_size;
    $limit="limit {$start},{$page_size}";

    //url动态生成
    $current_url=$_SERVER['REQUEST_URI'];//获取当前url
    $arr_current=parse_url($current_url);//拆分url为数组
    $current_path=$arr_current['path'];//获取无参数的url路径
    $url="";
    if(isset($arr_current['query'])){//如果有其他参数
        parse_str($arr_current['query'],$arr_query);//分开获取参数，成数组
        unset($arr_query[$page]);//删除page参数
        if(empty($arr_query)){//如果删了page参数就没有其他参数的话，加上去
            $url="{$current_path}?{$page}=";
        }else{//如果除了page参数还有其他参数的话
            $other=http_build_query($arr_query);//连接其他参数
            $url="{$current_path}?{$other}&{$page}=";//连接上page参数
        }
    }else{
        $url="{$current_path}?{$page}=";
    }

    //生成html代码
    $html = [];
    if($num_btn>=$page_num_all){
        //把所有的页码按钮全部显示
        //$page_num_all既限制循环次数，也是页码号，$i记录页码号
        for($i=1;$i<=$page_num_all;$i++){
            if($_GET[$page] == $i){
                $html[$i]="<span>{$i}</span> ";
            }else{
                $html[$i]="<a href='{$url}{$i}'>{$i}</a> ";
            }
        }
    }else{
        //若页码无法全部显示
        $num_left=floor(($num_btn-1)/2);
        $start=$_GET[$page]-$num_left;
        $end=$start+$num_btn-1;
        if($start<1){
            $start=1;
        }
        if($end>$page_num_all){
            $start=$page_num_all-($num_btn-1);
        }
        for($i=0;$i<$num_btn;$i++){
            if($_GET[$page]==$start){
                $html[$start]="<span>{$start}</span> ";
            }else{
                $html[$start]="<a href='{$url}{$start}'>{$start}</a> ";
            }
            $start++;
        }

        //按钮数量大于等于3时的省略号效果
        if(count($html)>=3){
            reset($html);//指向首个单元
            $key_first=key($html);//保存首个单元的键
            end($html);//指向末尾单元
            $key_end=key($html);//保存末尾单元的键
            if($key_first!=1){//如果首个单元不是指向第一页
                array_shift($html);//将首个单元移出数组
                array_unshift($html,"<a href='{$url}1'>1...</a>");//插入带省略号的第一页
            }
            if($key_end!=$page_num_all){//如果末尾单元不是指向最后一页
                array_pop($html);//将最后一个单元移出数组
                array_push($html,"<a href={$url}{$page_num_all}>...{$page_num_all}</a>");//插入带省略号的最后一页
            }
        }
    }
    if($_GET[$page]!=1){//如果本页不是第一页
        $prev=$_GET[$page]-1;//获取上一页
        array_unshift($html,"<a href='{$url}{$prev}'>« 上一页</a>");//将上一页插入数组开头
    }
    if($_GET[$page]!=$page_num_all){//如果本页不是最后一页
        $next=$_GET[$page]+1;//获取下一页
        array_push($html,"<a href='{$url}{$next}'>下一页 »</a>");//将下一页插入数组末尾
    }
    $html=implode(' ',$html);//将数组转化为字符串，用空格连接

    $data=array(
        'limit' => $limit,
        'html' => $html,
    );
    return $data;
}
//echo page(100,10,9)['html'];
?>