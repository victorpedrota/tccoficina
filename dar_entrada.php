<?php
$codigo = $_POST['codigo'];

require("connect.php");

$sql_veiculo_anda ="UPDATE `servico` SET `status`=3,`mostra`=0 WHERE `cod_servico` = $codigo" ;
$veiculo_resultado_anda = mysqli_query($conn,$sql_veiculo_anda);

echo 1;
?>