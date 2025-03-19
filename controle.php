<?php

require_once 'http://novaagro.totalh.net/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cadastrar cliente
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, email, endereco) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $telefone, $email, $endereco);
    $stmt->execute();

    $cliente_id = $stmt->insert_id;

    // Inserir pedidos
    if (!empty($_POST['servico'])) {
        foreach ($_POST['servico'] as $key => $servico) {
            $obs = $_POST['observacoes'][$key];
            $stmt2 = $conn->prepare("INSERT INTO pedidos (cliente_id, servico, observacoes) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $cliente_id, $servico, $obs);
            $stmt2->execute();
        }
    }

    echo "<p>Cliente e pedidos cadastrados com sucesso!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro PetShop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, textarea { margin-bottom: 10px; display: block; width: 300px; }
        .pedido { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        button { margin-top: 10px; }
    </style>
</head>
<body>
<h2>Cadastro de Cliente e Pedidos</h2>
<form method="POST">
    <h3>Dados do Cliente</h3>
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="text" name="telefone" placeholder="Telefone">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="endereco" placeholder="Endereço">

    <h3>Pedidos (Banho e Tosa)</h3>
    <div id="pedidos">
        <div class="pedido">
            <input type="text" name="servico[]" placeholder="Serviço (Banho, Tosa...)" required>
            <textarea name="observacoes[]" placeholder="Observações"></textarea>
        </div>
    </div>
    <button type="button" onclick="addPedido()">Adicionar Pedido</button>

    <br>
    <button type="submit">Cadastrar Cliente</button>
</form>

<script>
function addPedido() {
    let container = document.getElementById('pedidos');
    let div = document.createElement('div');
    div.className = 'pedido';
    div.innerHTML = `
        <input type="text" name="servico[]" placeholder="Serviço (Banho, Tosa...)" required>
        <textarea name="observacoes[]" placeholder="Observações"></textarea>
    `;
    container.appendChild(div);
}
</script>
</body>
</html>
