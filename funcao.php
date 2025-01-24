<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");
global $conexao;

function clear ($input){
    
    global $conexao;
    // Limpa SQL Injection
  $var = mysqli_real_escape_string($conexao, $input);
  // Limpa XSS
  $var = htmlspecialchars($var);
  return $var;
}