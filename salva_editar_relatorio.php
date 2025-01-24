<?php
include_once "funcao.php";
$conectar = mysqli_connect("localhost", "root", "", "erp");

$id = $_POST["id"];
$titulo_rel = clear ($_POST["titulo"]);
$tipo_rel = clear ($_POST["tipo"]);
$nivel_rel = clear ($_POST["nivel"]);
$conteudo_rel = clear ($_POST["conteudo"]);

$sql_relatorios = "UPDATE relatorio 
  SET tipo_rel='$tipo_rel',
  nivel_rel='$nivel_rel',
  titulo_rel='$titulo_rel',
  conteudo_rel='$conteudo_rel' 
  WHERE cod_rel = '$id'";

$res = $conectar->query($sql_relatorios);

if ($res) {
    echo "<script>
           alert('Relatorio atualizado com sucesso!');
                </script>";
    echo "<script>
                 location.href=  ('relatorios.php')
                 </script>";
} else {
    echo "<script>
            alert('Erro ao atualizar funcionario!');
                 </script>";
    echo "<script>
                 location.href=  ('editar_relatorios.php')
                 </script>";
}