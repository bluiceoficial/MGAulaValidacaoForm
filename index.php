<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = filter_input(INPUT_POST, 'nome');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $mensagem = filter_input(INPUT_POST, 'mensagem');

    $erros = [];

    if ($nome == '') {
        $erros['nome'] = 'O nome é obrigatório!';
    }

    if ($email == '') {
        $erros['email'] = 'O email é obrigatório!';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros['email']   = 'O formato do email é inválido!';
        }
    }

    if ($mensagem == '') {
        $erros['mensagem'] = 'O mensagem é obrigatório!';
    }

    if (!empty($erros)) {
        echo json_encode(['erros' => $erros]);
        exit;
    }

    echo json_encode(['sucesso' => 'Formulário enviado com sucesso!']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagem de Erro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            background: #0f172a;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #111827;
            padding: 30px;
            border-radius: 17px;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }

        .erro {
            color: #ef4444;
            font-size: 14px;
            margin-top: 4px;
        }

        .sucesso {
            margin-top: 15px;
            color: #22c55e;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Contato</h1>

        <input id="nome" type="text" placeholder="Nome">
        <div class="erro" id="erro-nome"></div>

        <input id="email" type="email" placeholder="E-mail">
        <div class="erro" id="erro-email"></div>

        <textarea id="mensagem" placeholder="Mensagem"></textarea>
        <div class="erro" id="erro-mensagem"></div>

        <button onclick="enviar()">Enviar</button>

        <div id="sucesso" class="sucesso"></div>
    </div>

    <script>
        function limparErros() {
            document.querySelectorAll('.erro').forEach(e => e.innerText = '');
            document.getElementById('sucesso').innerText = '';
        }

        function enviar() {
            limparErros();

            const nome = document.getElementById('nome').value;
            const email = document.getElementById('email').value;
            const mensagem = document.getElementById('mensagem').value;

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'nome=' + encodeURIComponent(nome) +
                        '&email=' + encodeURIComponent(email) +
                        '&mensagem=' + encodeURIComponent(mensagem)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.erros) {
                        if (data.erros.nome) {
                            document.getElementById('erro-nome').innerText = data.erros.nome;
                        }

                        if (data.erros.email) {
                            document.getElementById('erro-email').innerText = data.erros.email;
                        }

                        if (data.erros.mensagem) {
                            document.getElementById('erro-mensagem').innerText = data.erros.mensagem;
                        }

                        return
                    }

                    // Sucesso
                    document.getElementById('sucesso').innerText = data.sucesso;

                    // Limpar Campos
                    document.getElementById('nome').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('mensagem').value = '';
                });
        }
    </script>
</body>

</html>