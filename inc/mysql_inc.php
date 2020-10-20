<?php
//连接数据库
function connect(
    $host = DB_HOST,
    $user = DB_USER,
    $password = DB_PASSWORD,
    $database = DB_DATABASE,
    $port = DB_PORT,
    $socket = NULL
){
    $link = @mysqli_connect( //@符号可以屏蔽错误
        $host,
        $user,
        $password,
        $database,
        $port,
        $socket
    );
    if(mysqli_connect_errno()){
        exit(mysqli_connect_error());
    };
    mysqli_set_charset($link,'utf8md4');
    return $link;
}

//执行一条SQL语句，返回结果集对象
function execute($link,$query){
    $result = mysqli_query($link,$query);
    if(mysqli_errno($link)){
        exit(mysqli_error($link));
    }
    return $result;
}

//执行一条SQL语句，只返回布尔值
function execute_bool($link,$query){
    $result = mysqli_real_query($link,$query);
    if(mysqli_errno($link)){
        exit(mysqli_error($link));
    }
    return $result;
}

//一次性执行多条SQL语句
// $link:连接
// $arr_sqls:数组形式的多条sql语句
// $error:传入一个变量，存储错误信息
//不加&传值时，值会复制一份，不改变原来的值，加了&之后会直接改变原来的值
// $arr_sqls=array(
//     'select * from bbs_father_module',
//     'select * from bbs_father_module',
//     'select * from bbs_father_module',
//     'select * from bbs_father_module'
// );

// var_dump(execute_multi($link,$arr_sqls,$error));
// echo '<br />';
// echo $error;
function execute_multi($link,$arr_sqls,&$error){
    $sqls = implode(';',$arr_sqls).';';
    if(mysqli_multi_query($link,$sqls)){
        $data = array();
        $i = 0; //计数
        do{
            if($result=mysqli_store_result($link)){
                $data[$i] = mysqli_fetch_all($result);
                mysqli_free_result(($result));
            }else{
                $data[$i]=null;
            }
            $i++;
            if(!mysqli_more_results(($link))) break;
        }while(mysqli_next_result($link));
        if($i==count($arr_sqls)){
            return $data;
        }else{
            $error="sql语句执行失败:<br /> 数组下标为{$i}的语句:{$arr_sqls[$i]}执行错误<br /> 错误原因：".mysqli_error($link);
            return false;
        }
    }else{
        $error = '执行失败！请检查首条语句是否正确！<br />可能的错误原因:'.mysqli_error($link);
        return false;
    }
}

//获取记录数
function num($link,$sql_count){
    $result = mysqli_query($link,$sql_count);
    $count = mysqli_fetch_row($result);
    return $count[0];
}

//数据入库之前进行转义，确保数据顺利入库
function escape($link,$data){
    if (is_string($data)){//是否是字符串
        mysqli_real_escape_string($link,$data);
    }
    if (is_array($data)){//是否是数组
        foreach($data as $key=>$val){
            $data[$key]=mysqli_real_escape_string($link,$val);
        }
    }
    return $data;
}

//关闭与数据库的连接
function close($link){
    mysqli_close($link);
}
//在php里，如果函数的参数传的是对象，并不复制，传对象本身
?>