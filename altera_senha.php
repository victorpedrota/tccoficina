<?php
require ('connect.php');
$senha = $_POST['nova'];
$cpf = $_POST['cpf'];

$sql_servico ="SELECT * FROM `cliente` WHERE `cpf` = $cpf";

$resultado_cliente2 = mysqli_query($conn,$sql_servico);
$vetor_veiculo = mysqli_fetch_array($resultado_cliente2);
$cod_login = $vetor_veiculo['cod_login'];

if (mysqli_num_rows($resultado_cliente2) == 0) {
	$sql_servico ="SELECT * FROM `mecanico` WHERE `cpf` = $cpf";
	$resultado_cliente1 = mysqli_query($conn,$sql_servico);
	$vetor_veiculo = mysqli_fetch_array($resultado_cliente1);
	$cod_login = $vetor_veiculo['cod_login'];
	if (mysqli_num_rows($resultado_cliente1) == 0) {
		$sql_servico ="SELECT * FROM `oficina` WHERE `cnpj` = $cpf";
		$resultado_cliente1 = mysqli_query($conn,$sql_servico);
		$vetor_veiculo = mysqli_fetch_array($resultado_cliente1);
		$cod_login = $vetor_veiculo['cod_login'];
	}
}
$senha_c = base64_encode($senha);


$senha_c = base64_encode($senha);
$sql_servico ="UPDATE `login` SET `senha`= '$senha_c' WHERE `cod_login` = $cod_login";
$resultado_cliente2 = mysqli_query($conn,$sql_servico);

?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="scss/main.css">
    

    <title>sucesso</title>
  </head>
  <body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">concluído</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Senha alterado com sucesso
      </div>
      <div class="modal-footer">
        
        <a  href="login.php" class="btn btn-primary">Voltar</a>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $('#exampleModalLong').modal('show')
    $('#exampleModalLong').on('hidden.bs.modal', function (e) {
 window.location.href = 'login.php';
})

  })
</script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
  </body>
</html>