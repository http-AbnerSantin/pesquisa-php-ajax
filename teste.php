<?php
$data = date('D');

if($data){
    $datas = "2023-03-18";
    $dado = strtotime($datas);
    echo date("D", $dado);
    // echo $data;
}else{
    echo 'nao';
}
?>