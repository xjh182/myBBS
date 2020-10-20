<?php
if (mb_strlen($_POST['content'])<3){
    skip('回复内容不得少于3个字符','error',$_SESSION['REQUEST_URI'],);
}

?>