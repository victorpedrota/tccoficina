<?php
    //Mantendo a sessão/cria uma sessao
session_start();

if(!isset($_SESSION["system_control"]))
{

  require('erro.php');    

}
else{
        //Sessao já criada  
        //Recuperando as variaveis da sessão
  $system_control = $_SESSION["system_control"];   
  $cod_login = $_SESSION['cod_login'];
  $privilegio = $_SESSION["privilegio"];
  $nome = $_SESSION["nome"];
  $cod_oficina = $_SESSION["cod_oficina"];

  if($system_control == 1 && $privilegio == 2){
    require('connect.php'); 
    
    ?>

    <!DOCTYPE html>
    <html>

    <head>
      <title>Oficina Pro</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
      <!-- Bootstrap CSS CDN -->
      <link rel="stylesheet" href="scss/main.css">
      <link rel="stylesheet" href="css/chat.css">
      <!-- Our Custom CSS -->
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <style type="text/css">

      div.show-image {
        position: relative;
        float:left;
        margin:5px;
      }
      div.show-image:hover img{
        opacity:0.5;
      }
      div.show-image:hover .delete {
        display: block;
      }
      div.show-image .delete {
        position:absolute;
        display:none;
      }
      
      div.show-image .delete {
        top:0;
        left:84%;
      }
      .cortar{
        object-fit: cover; object-position: center;
      }
    </style>


  </head>

  <body style="overflow-x: hidden;">
    <script type="text/javascript" >

      function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('uf').value=("");
            
          }

          function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
            
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
          }
        }
        
        function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";
                

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
              }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
          }
        };

      </script>

      <?php
      require("navbar_oficina.html");
      ?>
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="">
                  <span data-feather="home"></span>
                  Página inical <span class="sr-only">(current)</span>
                </a>

              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="btn_servicos">Serviços</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="btn_mecanicos">Mecânicos</a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link" id="imagens" href="#" data-toggle="modal" data-target="#ima">Galeria de imagens
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="btn_estatistica">Estatísticas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="btn_mecanico">Cadastrar Mecânico</a>
              </li>




            </ul>
          </div>
        </nav>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-top: 30px;">
         <div style="display: none;" id="estatistica">
          <center>Sem dados suficientes para Estatísticas no momento</center>
          <canvas id="myChart" ></canvas>

        </div>
        <div style="display: block;" id="servicos">
          <center><h3>Gerenciar Serviços</h3></center>
          <?php
          $sql_servico ="SELECT * FROM `servico` WHERE `cod_mecanico` = 0 && `status` != 0";
          $resultado_cliente2 = mysqli_query($conn,$sql_servico);
          $num2 = mysqli_num_rows($resultado_cliente2);

          if ($num2!=0) {
            while ($vetor_veiculo = mysqli_fetch_array($resultado_cliente2)) {
              echo " <div class='alert alert-danger' role='alert'>
              Parece que tem um serviço parado, escolha um mecânico para ele.<button value='".$vetor_veiculo["cod_servico"]."' class='btn btn-link cod_serv'  data-target='#lista_mec'  data-toggle='modal'>Escolher mecânico</button> <button class='aviso btn btn-link' style='float: right;margin-top: -7px;'><i class='fas fa-times'></i></button>
              </div>";
            }

          }

          ?>

          <br>


          <center><h5>Lista de Serviços</h5></center>
          <ul style="text-align: left;" class="list-group">
            <?php

            $cliente2 ="SELECT * FROM `servico` WHERE `status` = 0 && `status` <=3 && `cod_oficina` = $cod_oficina";
            $resultado_cliente2 = mysqli_query($conn,$cliente2);
            $num2 = mysqli_num_rows($resultado_cliente2);

            if ($num2 != 0) {
              while ($vetor_veiculo = mysqli_fetch_array($resultado_cliente2)) {
                echo "<li class='list-group-item itens'>Protocolo:".$vetor_veiculo["protocolo"]."<a class='teste' style='float: right;' href='excluir_servico.php?codigo=".$vetor_veiculo['cod_servico']."'><i class='fas fa-times'></i></a><br>
                Serviço desejado:".$vetor_veiculo['servico_desejado']."<br>Tipo serviço:".$vetor_veiculo['tipo_servico']."
                </li>";
              }
            }
            else{
              echo "<li class='list-group-item itens'><p>Não há serviços em espera</p></li>";
            }




            ?>
            <br>
          </ul>
          <center><h5>Lista de Serviços em andamento</h5></center>
          <div style="height: 300px;overflow-y: scroll;">
            <ul style="text-align: left;" class="list-group">
              <?php

              $cliente2 ="SELECT * FROM `servico` WHERE `cod_oficina` <= 3 && `cod_oficina` = $cod_oficina";
              $resultado_cliente2 = mysqli_query($conn,$cliente2);
              $num2 = mysqli_num_rows($resultado_cliente2);

              if ($num2 != 0) {
                while ($vetor_veiculo = mysqli_fetch_array($resultado_cliente2)) {
                  echo "<li class='list-group-item itens'>Protocolo:".$vetor_veiculo["protocolo"]."<a class='teste' style='float: right;' href='excluir_servico.php?codigo=".$vetor_veiculo['cod_servico']."'><i class='fas fa-times'></i></a><br>
                  Serviço desejado:".$vetor_veiculo['servico_desejado']."<br>Tipo serviço:".$vetor_veiculo['tipo_servico']."<br><br>
                  <button class='btn btn-primary cod_serv' data-toggle='modal' data-target='#lista_mec' value='".$vetor_veiculo['cod_servico']."'>Alterar mecânico responsável</button>
                  </li>";
                }
              }
              else{
                echo "<li class='list-group-item itens'><p>Não há serviços em andamento</p></li>";
              }




              ?>
            </ul>
          </div>
        </div>    
        <div style="margin-left: 0px;display: none" id="mecanicos"><center><h5>Lista de Mecanicos cadastrados</h5></center>
          <ul style="text-align: left;" class="list-group">
            <?php

            $cliente1 ="SELECT * FROM `mecanico` WHERE `cod_oficina` = $cod_oficina";
            $resultado_cliente1 = mysqli_query($conn,$cliente1);
            $num1 = mysqli_num_rows($resultado_cliente1);

            if ($num1 != 0) {
              while ($vetor_veiculo = mysqli_fetch_array($resultado_cliente1)) {
                echo "<li class='list-group-item itens'>".$vetor_veiculo["nome"]."<a class='teste' style='float: right;' href='excluir_mecanico.php?codigo=".$vetor_veiculo['cod_mecanico']."'><i class='fas fa-times'></i></a></li>";
              }
            }
            else{
              echo "<li class='list-group-item itens'><p>Não há Mecânicos cadastrados</p></li>";
            }




            ?>
          </ul>
        </div></div>
      </div>
      <script src="js/validar_form2.js"></script>
      <script src="js/validar_form.js"></script>
      <script type="text/javascript" src="js/jquery.mask.min.js"></script>
      <div class="" id="form_mecanico" style="display: none">
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-top: -30px;">
          <form action="cadastrar_mecanico.php" id="form" method="POST">
           <h3 >Formulário Cadastrar Mecânico</h3>


           <div class="row">
            <div class="col "><div id="texto"></div>
            <label for="ex3">Login:</label>

            <input class="form-control"  name="login" id="login" minlength="6" type="text" required>
          </div>
          <div class="col">
            <label for="ex3">Senha:</label>

            <input class="form-control"  name="senha" id="senha"  minlength="6" type="password" required>
          </div>
          <div class="col">
            <label for="ex3">Confirmar senha:</label>

            <input class="form-control"  name="c_senha"  id="c_senha" minlength="6" type="password" required>
          </div>

        </div>
        <div class="row">
          <div class="col ">
            <label for="ex3">Primeiro nome:</label>

            <input class="form-control letters" id="nome" name="nome" type="text" required>
          </div>
          <div class="col">
            <label for="ex3">Sobrenome:</label>
            <input class="form-control letters"   name="sobrenome" id="sobrenome"   type="text" required></td>
          </div>
        </div>
        <div class="row">
          <div class="col ">
           <label for="ex3">Data de nascimento:</label>
           <input class="form-control data" id="data" type="date" required>
         </div>
         <div class="col">
          <label for="ex3">Telefone:</label>
          <input class="form-control telefone" type="text" name="telefone" id="telefone"  required>
        </div>
        <div class="col">
          <label for="ex3">Celular:</label>
          <input class="form-control telefone" type="text" name="celular" id="celular" required>
        </div>

      </div>
      <div class="row">
        <div class="col ">
         <label for="ex3">RG:</label>
         <input class="form-control" type="text" name="rg" id="rg" required>
       </div>
       <div class="col">
        <label for="ex3">CPF:</label><span id="resposta"></span>  
        <input class="form-control" type="text" name="cpf"  id="cpf" required >
      </div>
      <div class="col">
        <label for="ex3">CEP:</label>
        <input class="form-control cep" id="cep"  onblur="pesquisacep(this.value);"  name="cep" type="text" required>
      </div>
      <div class="col">
        <label for="ex3">Estado:</label>
        <input class="form-control" id="uf" name="uf" required readonly="readonly"> 

      </div>

    </div>
    <div class="row">

      <div class="col ">
        <label for="ex3">Cidade:</label>
        <input id="cidade" name="cidade" class="form-control letters" required readonly="readonly">

      </div>
      <div class="col">
        <label for="ex3">Bairro:</label>
        <input class="form-control" id="bairro"   name="bairro" type="text" required readonly="readonly">
      </div>


    </div>
    <div class="row">
      <div class="col">
        <label for="ex3">Rua:</label>
        <input class="form-control"  id="rua" name="rua" type="text" required readonly="readonly">
      </div>
      <div class="col ">
        <label for="ex3">Numero:</label>
        <input class="form-control"  id="numero"  name="numero" type="text" required>
      </div>
      <div class="col">
        <label for="ex3">Complemento:</label>
        <input class="form-control"  name="complemento" id="complemento" type="text">
      </div>


    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#cep').mask('00.000-000');
        $('#numero').mask('#0');
        $('.telefone').mask('(00) 0000-00009');
        $('.telefone').blur(function(event) {
                if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
                  $('.telefone').mask('(00) 00000-0009');
                } else {
                  $('.telefone').mask('(00) 0000-00009');
                }
              });
        $('.letters').bind('keyup blur',function(){ 
          var node = $(this);
          node.val(node.val().replace(/[^a-z]/g,'') ); }
          );
      })
      $('#cpf').mask('000.000.000-00', {reverse: true});
      $('#rg').mask('00.000.000-A', {
        reverse: true,
        A: {pattern: /[A-Z]+$/}
      });
    </script>
    <br>
    <div><center><button class="btn btn-primary" id="send" type="button"> Enviar</button></center></div>
  </form>
</div>

</div>
<div class="modal fade" id="ima" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Galeria de imagens</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class='card-group'>
          <?php 


          $sql_servico = "SELECT * FROM `galeria` WHERE  `cod_oficina` = $cod_oficina";
          $query_servico = mysqli_query($conn, $sql_servico);
          $numero_servico = mysqli_num_rows($query_servico); 
          if ($numero_servico !=0) {
            while ($vetor_servico = mysqli_fetch_array($query_servico)) {

              echo "
              <div class='show-image' >
              <img  src='".$vetor_servico['imagem']."' class='cortar' style='height:100px;width:100px;'  >

              <i class='fas fa-times delete'></i>
              </div>";





            }


          }



          else{

            echo "<li class='list-group-item itens'>Não há imagens cadastradas</li>";

          }              


          ?>

          <button class="btn btn-secondary" style="height: 40px; margin-top: 40px;margin-left: 35px;" data-toggle="modal" data-target="#envia_img" data-dismiss="modal"><i class="fas fa-plus"></i></button></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="envia_img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Escolher imagens</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form method="post" enctype="multipart/form-data" action="foto_mecanico.php">

            <input type="file" class="form-control" name="arquivo">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" name="opcao" value="0" class="btn btn-primary">Enviar</button></form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="lista_mec" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Escolher Mecânico</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" >
           <div class="" style="max-height: 400px;overflow-y: scroll;">
            <?php
            $cliente1 ="SELECT * FROM `mecanico` WHERE `cod_oficina` = $cod_oficina";
            $resultado_cliente1 = mysqli_query($conn,$cliente1);
            $num1 = mysqli_num_rows($resultado_cliente1);

            if ($num1 != 0) {
              while ($vetor_veiculo = mysqli_fetch_array($resultado_cliente1)) {

                echo "<li class='list-group-item itens'>".$vetor_veiculo["nome"]."<button class='gay btn btn-secondary' style='float: right;' value='".$vetor_veiculo['cod_mecanico']."'><i class='fas fa-share-square'></i></button><br></li>";
              }
            }
            else{
              echo "<li class='list-group-item itens'><p>Não há Mecânicos cadastrados</p></li>";
            }
            ?>    
            <input type="hidden" id="cod_servico" name="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

          </div>
        </div>
      </div>
    </div>
    
    <script>

      
      var ctx = document.getElementById('myChart').getContext('2d');

      var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: ["January", "February", "March", "April", "May", "June", "July"],
      datasets: [{
        label: "My First dataset",
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: [0, 10, 5, 2, 20, 30, 45],
      }]
    },

    // Configuration options go here
        options: {}
  });
</script>
<script>
  $(document).ready(function () {
    $("#btn_mecanico").click(function(){
      if ($("#form_mecanico").css("display") == "none") {
        $("#mecanicos").css("display", "none");
        $("#servicos").css("display", "none");
        $("#form_mecanico").css("display", "block");
        $("#estatistica").css("display", "none");
      }
      
    })
    $("#btn_servicos").click(function(){
      if ($("#servicos").css("display") == "none") {
        $("#form_mecanico").css("display", "none");
        $("#mecanicos").css("display", "none");
        $("#servicos").css("display", "block");
        $("#estatistica").css("display", "none");
      }

    })
    $("#btn_mecanicos").click(function(){
      if ($("#mecanicos").css("display") == "none") {
        $("#form_mecanico").css("display", "none");
        $("#servicos").css("display", "none");
        $("#mecanicos").css("display", "block");
        $("#estatistica").css("display", "none");
      }

    })
    $("#btn_estatistica").click(function(){
      if ($("#estatistica").css("display") == "none") {
        $("#form_mecanico").css("display", "none");
        $("#servicos").css("display", "none");
        $("#mecanicos").css("display", "none");
        $("#estatistica").css("display", "block");
      }

    })
  });
  $(".aviso").click(function(){
    $(this).parent().remove()
  })
  $("#meuForm").validate({
    rules: {
      cpf: {cpf: true, required: true}
    },
    messages: {
     cpf: { cpf: 'CPF inválido'}
   }
   ,submitHandler:function(form) {
     alert('ok');
   }
 });
  
  

  $("#send").click(function(){

    var form
    form = $("#form")
    form.valid();
    if (form.valid() == true) {
      if ($("#senha").val()==$("#c_senha").val()) {
        $.post("cadastrar_mecanico.php", {
          nome: $("#nome").val(),
          sobrenome: $("#sobrenome").val(),
          celular: $("#celular").val(),
          telefone: $("#telefone").val(),
          rg: $("#rg").val(),
          cpf: $("#cpf").val(),
          cidade: $("#cidade").val(),
          rua: $("#rua").val(),
          login: $("#login").val(),
          bairro: $("#bairro").val(),
          numero: $("#numero").val(),
          complemento: $("#complemento").val(),
          senha: $("#senha").val(),
          cep: $("#cep").val(),
          c_senha: $("#c_senha").val(),
          data: $("#data").val(),
          uf: $("#uf").val()

        })
        .done(function(data) {

          if ( data == 2) {
           $('#erro').modal('show')
         }
         else if( data == 1) {
          $("#login").css("background-color","#ff7b7b")
          $("#texto").text("Nick ja existe")
        }
        else if (data == 0 ) {
         $('#exampleModal').modal('show')


       }
       else if(data == 3){
        $("#texto").text("Login ou cnpj já cadastrados")
      }
    });
      }
      else{
        $("#senha").css("background-color","#ff7b7b")
        $("#c_senha").css("background-color","#ff7b7b")
      }
    }
    $(".form-control").click(function(){
      $(this).css("background-color","#fff")
    })



  })
  
  $("#porra").click(function(){
    location.reload();
  })
  $('#exampleModal').on('hidden.bs.modal', function (e) {
   window.location.href = 'perfil_oficina.php';
 })
  $(".cod_serv").click(function(){
    alert($(this).val())
    $("#cod_servico").attr("value",$(this).val())

  })
  $(".gay").click(function(){

    var cod_servico = $("#cod_servico").val()
    $(location).attr('href','alterar_mecanico_servico.php?codigo='+$(this).val()+'&cod_servico='+cod_servico)

  })
  function CPF(){"user_strict";function r(r){for(var t=null,n=0;9>n;++n)t+=r.toString().charAt(n)*(10-n);var i=t%11;return i=2>i?0:11-i}function t(r){for(var t=null,n=0;10>n;++n)t+=r.toString().charAt(n)*(11-n);var i=t%11;return i=2>i?0:11-i}var n=" Inválido",i=" Válido";this.gera=function(){for(var n="",i=0;9>i;++i)n+=Math.floor(9*Math.random())+"";var o=r(n),a=n+"-"+o+t(n+""+o);return a},this.valida=function(o){for(var a=o.replace(/\D/g,""),u=a.substring(0,9),f=a.substring(9,11),v=0;10>v;v++)if(""+u+f==""+v+v+v+v+v+v+v+v+v+v+v)return n;var c=r(u),e=t(u+""+c);return f.toString()===c.toString()+e.toString()?i:n}}



   var CPF = new CPF();
   document.write(CPF.valida("123.456.789-00"));
   
   document.write("<br> Utilizando o proprio gerador da lib<br><br><br>");
   for(var i =0;i<40;i++) {
      var temp_cpf = CPF.gera();
      document.write(temp_cpf+" = "+CPF.valida(temp_cpf)+"<br>");
   }

$("#cpf").keypress(function(){
    $("#resposta").html(CPF.valida($(this).val()));
});

$("#cpf").blur(function(){
     $("#resposta").html(CPF.valida($(this).val()));
});

</script>


</main>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Cadastro feito com sucesso
      </div>
      <div class="modal-footer">
        <a href="perfil_oficina.php" class="btn btn-primary">Fechar</a>
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="erro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        CPF ou RG já cadastrados
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        
      </div>
    </div>
  </div>
</div>



</body>

</html>
<?php
}
else
{
            //Acesso Inválido

            //Finalizando a sessão
  session_destroy();

            //Mensagem para o Usuário

  
  require('erro.php');


}
}
?>