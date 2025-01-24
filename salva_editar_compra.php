<?php
$conectar = mysqli_connect("localhost", "root", "", "erp");

session_start();

    $id = $_POST["id"];
    $data = $_POST["data"];
    $data_vencimento = $_POST["data-vencimento"];
    $valor = $_POST["valor"];
    $item = $_POST["item"];
    $quantidade = $_POST["qtd"];
    $obs = $_POST["obs"];
    $status = $_POST["status"];
    $fornecedores = $_POST["fornecedores"];

$sql = "UPDATE compra
        SET data_com = '$data',
            vencimento_com = '$data_vencimento',
            valor_com = '$valor',
            item_com = '$item',
            qtd_com = '$quantidade',
            observacao_com = '$obs',
            status_com = '$status'
        WHERE cod_com = '$id'";

        $res = $conectar -> query($sql);

        if($res){
           echo "<script>
           alert('Compra atualizada com sucesso!');
                </script>"; 
                echo "<script>
                 location.href=  ('compra.php')
                 </script>";
                
        }else{
            echo "<script>
            alert('Erro ao atualizar a compra!');
                 </script>";
            echo "<script>
                 location.href=  ('editar_compra.php')
                 </script>";
        }
