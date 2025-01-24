<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Relatórios</title>
    <link rel="stylesheet" type="text/css" href="css/relatorios_css.css">
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="pesquisa">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var searchBar = document.getElementById('search');

                searchBar.addEventListener('input', function() {
                    var inputValue = searchBar.value.toLowerCase();
                    var inventoryBoxes = document.querySelectorAll('.report');

                    inventoryBoxes.forEach(function(box) {
                        var productName = box.querySelector('.meta').textContent.toLowerCase();

                        if (productName.includes(inputValue)) {
                            box.style.display = 'flex';
                        } else {
                            box.style.display = 'none';
                        }
                    });
                });
            });
        </script>
        <div class="inputs">
            <input type="text" placeholder="Pesquise o funcionário" id="search" name="pesquisa">
            <header id="filtros">
                <p>Data</p>
                <section>
                    <input type="date" id="date" name="pesquisa">
                </section>
            </header>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('date');
            var reports = document.querySelectorAll('.report');

            dateInput.addEventListener('change', function() {
                var selectedDate = dateInput.value; // Formato yyyy-mm-dd (do input)
                if (!selectedDate) {
                    // Se nenhuma data for selecionada, exibe todos os relatórios
                    reports.forEach(function(report) {
                        report.style.display = 'flex';
                    });
                    return;
                }

                // Formatar a data selecionada para dd/mm/yyyy
                var selectedDateFormatted = selectedDate.split('-').reverse().join('/');

                reports.forEach(function(report) {
                    var reportDate = report.getAttribute('data-product-type'); // Data do relatório (dd/mm/yyyy)

                    // Exibe ou oculta com base na data completa
                    if (selectedDateFormatted === reportDate) {
                        report.style.display = 'flex';
                    } else {
                        report.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <div class="container">
        <h1>Relatório</h1>
        <?php
        $conectar = mysqli_connect("localhost", "root", "", "erp");

        $sql_consulta = "SELECT *,
                        TIMESTAMPDIFF(MINUTE, relatorio.data_rel, NOW()) AS minute,
                        TIMESTAMPDIFF(HOUR, relatorio.data_rel, NOW()) AS hours,
                        TIMESTAMPDIFF(DAY, relatorio.data_rel, NOW()) AS days,
                        TIMESTAMPDIFF(MONTH, relatorio.data_rel, NOW()) AS month
                         FROM relatorio";
        $resultado = mysqli_query($conectar, $sql_consulta);

        $relatorios_por_nivel = [
            5 => [],
            4 => [],
            3 => [],
            2 => [],
            1 => []
        ];

        // Processa os relatórios e agrupa por nível
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $nivel_rel = (int)$linha['nivel_rel'];
            $relatorios_por_nivel[$nivel_rel][] = $linha;
        }

        // Exibe os relatórios organizados por nível
        foreach ($relatorios_por_nivel as $nivel => $relatorios) {
            if (count($relatorios) > 0) {
                foreach ($relatorios as $rel) {
                    // Processamento de tempo
                    $minuto_rel = $rel['minute'];
                    $hora_rel = $rel['hours'];
                    $dia_rel = $rel['days'];
                    $mes_rel = $rel['month'];

                    if ($minuto_rel < 60) {
                        $tempo_rel = $minuto_rel;
                        $tempo = " minutos";
                    } elseif ($hora_rel < 24) {
                        $tempo_rel = $hora_rel;
                        $tempo = " horas";
                    } elseif ($dia_rel < 30) {
                        $tempo_rel = $dia_rel;
                        $tempo = " dias";
                    } else {
                        $tempo_rel = $mes_rel;
                        $tempo = " meses";
                    }

                    // Dados do funcionário
                    $funcionario_cod_fun = $rel["funcionario_cod_fun"];
                    $sql_funcionario = "SELECT nome_fun, funcao_fun FROM funcionario WHERE cod_fun = '$funcionario_cod_fun'";
                    $resultado_funcionario = mysqli_query($conectar, $sql_funcionario);
                    $linha_funcionario = mysqli_fetch_assoc($resultado_funcionario);
                    $funcionario_nome_fun = $linha_funcionario["nome_fun"];
                    $funcao_fun = $linha_funcionario["funcao_fun"];

                    // Exibição do relatório
                    echo '<div class="report" data-product-type="' . date('d/m/Y', strtotime($rel['data_rel'])) . '">
                        <h2>' . $rel['titulo_rel'] . '</h2>
                        <p>' . $rel['tipo_rel'] . '</p>
                        <p class="meta">Feito por: ' . $funcionario_nome_fun . ' há <span class="date">' . $tempo_rel . $tempo . ' </span></p>
                        <p class="importance" style="color: ' . getColorByLevel($nivel) . '">Nível de Importância: ' . $nivel . '</p>
                        <p class="content" style="font-weight: 600;" readonly>' . $rel['conteudo_rel'] . '</p>
                        <button class="read-more-btn" data-content="' . htmlspecialchars($rel['conteudo_rel'], ENT_QUOTES, 'UTF-8') . '">Leia mais</button>';

                    if ($_SESSION["nome_fun"] == $funcionario_nome_fun || $funcao_fun == 'Administrador') {
                        echo "<a  class='btn btn-sm btn-primary' href='editar_relatorio.php?id=" . $rel['cod_rel'] . "' title='Editar'>Editar Relatório</a>";
                    }

                    echo "</div>";
                }
            }
        }

        // Função para determinar cor por nível
        function getColorByLevel($level)
        {
            switch ($level) {
                case 1:
                    return 'green';
                case 2:
                    return '#FDDA0D';
                case 3:
                    return 'orange';
                case 4:
                    return 'orangered';
                case 5:
                    return 'red';
                default:
                    return 'black';
            }
        }
        ?>
    </div>
    <p></p>
    <!-- Modal -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <textarea id="modalTextarea" readonly></textarea>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById("contentModal");
            var modalTextarea = document.getElementById("modalTextarea");
            var closeBtn = document.querySelector(".close");
            var readMoreBtns = document.querySelectorAll(".read-more-btn");

            readMoreBtns.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    var content = this.getAttribute("data-content");
                    modalTextarea.value = content;
                    modal.style.display = "block";
                });
            });

            closeBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>