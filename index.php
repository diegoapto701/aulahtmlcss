<?php
// ============================================================
// index.php
// Página de boas-vindas com cadastro de nome e telefone
// ============================================================

// Inclui a configuração do banco de dados
require_once 'database.php';

// Inicializa a variável de feedback
$feedback = null;

// Processa o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome']) && isset($_POST['telefone'])) {
    
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    
    // Validação básica
    if (empty($nome) || empty($telefone)) {
        $feedback = [
            'status' => 'error',
            'message' => '❌ Preencha ambos os campos.'
        ];
    } else {
        // Obtém a instância do banco de dados e insere o contato
        $db = Database::getInstance();
        $success = $db->insertContato($nome, $telefone);
        
        if ($success) {
            $feedback = [
                'status' => 'success',
                'message' => "✅ Cadastro realizado! $nome foi salvo na tabela 'contatos' (Neon)."
            ];
        } else {
            $feedback = [
                'status' => 'error',
                'message' => '❌ Erro ao salvar no banco de dados. Tente novamente.'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boas-vindas · Contatos Neon</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        body {
            background: linear-gradient(145deg, #f0f7fb 0%, #e3eef5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 2.8rem;
            box-shadow: 0 25px 50px -12px rgba(0, 20, 30, 0.3);
            padding: 2.8rem 3rem;
            max-width: 560px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 30px 65px -15px rgba(0, 40, 60, 0.35);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }
        h1 {
            font-size: 2.3rem;
            font-weight: 600;
            color: #0b2b3d;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        h1 small {
            font-size: 1rem;
            font-weight: 400;
            color: #1f6a82;
            background: #d4e7f0;
            padding: 0.1rem 1.2rem;
            border-radius: 40px;
        }
        .badge-neon {
            background: #0b2b3d;
            color: #b6e8ff;
            padding: 0.2rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.4px;
        }
        .subhead {
            color: #1f5a70;
            font-size: 0.95rem;
            border-left: 4px solid #3e8eaa;
            padding-left: 1rem;
            margin-bottom: 2rem;
            background: rgba(62, 142, 170, 0.06);
            border-radius: 0 12px 12px 0;
            line-height: 1.6;
        }
        .form-group {
            margin-bottom: 1.6rem;
        }
        label {
            display: block;
            font-weight: 500;
            color: #1f4a5c;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        input {
            width: 100%;
            padding: 0.9rem 1.4rem;
            border: 1.5px solid #d6e4ec;
            border-radius: 60px;
            font-size: 1rem;
            background: white;
            transition: 0.2s;
            color: #0b2b3d;
            outline: none;
            box-shadow: 0 2px 6px rgba(0,20,30,0.02);
        }
        input:focus {
            border-color: #2f7f9c;
            box-shadow: 0 0 0 4px rgba(47, 127, 156, 0.15);
            background: #ffffff;
        }
        input::placeholder {
            color: #8baec2;
            font-weight: 300;
        }
        .btn {
            background: #1b5b74;
            border: none;
            padding: 0.9rem 1.8rem;
            border-radius: 60px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 10px 20px -8px rgba(18, 75, 95, 0.3);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn:hover {
            background: #124a5f;
            transform: scale(1.01);
            box-shadow: 0 15px 25px -10px #124a5f80;
        }
        .btn:active {
            transform: scale(0.97);
        }
        .feedback {
            margin-top: 1.8rem;
            padding: 0.8rem 1.4rem;
            border-radius: 40px;
            background: #ecf9ff;
            border: 1px solid #bddceb;
            color: #104c63;
            font-weight: 450;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: 0.2s;
        }
        .feedback.success {
            background: #e1f3e8;
            border-color: #8fc9b0;
            color: #0f4d3a;
        }
        .feedback.error {
            background: #ffe8e8;
            border-color: #f5bebe;
            color: #a13d3d;
        }
        .feedback-icon {
            font-size: 1.4rem;
        }
        .footer-note {
            margin-top: 1.8rem;
            font-size: 0.75rem;
            color: #537b8b;
            text-align: center;
            border-top: 1px solid #d4e3ec;
            padding-top: 1.2rem;
            letter-spacing: 0.2px;
        }
        .footer-note span {
            background: #dcecf5;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
        }
        .info-existente {
            background: #e2f0f7;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-size: 0.8rem;
            color: #124a5f;
            border: 1px solid #b6d4e3;
            margin-top: 0.2rem;
            display: inline-block;
        }
        @media (max-width: 480px) {
            .card { padding: 1.8rem; }
            h1 { font-size: 1.8rem; flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>
                👋 Boas-vindas
                <small>Neon</small>
            </h1>
            <span class="badge-neon">⚡ tabela: contatos</span>
        </div>
        <div class="subhead">
            ✦ Cadastre nome e telefone · dados salvos no PostgreSQL (Neon)
            <div class="info-existente">✔ ID 1 já registrado na tabela</div>
        </div>

        <!-- Formulário -->
        <form id="cadastroForm" method="POST" action="">
            <div class="form-group">
                <label for="nome">📛 Nome completo</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: Ana Maria Silva" required autofocus>
            </div>
            <div class="form-group">
                <label for="telefone">📞 Telefone</label>
                <input type="tel" id="telefone" name="telefone" placeholder="(11) 91234-5678" required>
            </div>
            <button type="submit" class="btn" id="btnEnviar">
                <span>✓ Enviar</span>
                <span style="font-size: 1.2rem;">→</span>
            </button>
        </form>

        <!-- Feedback (sucesso/erro) -->
        <div id="feedback" class="feedback" style="<?php echo isset($feedback) ? 'display: flex;' : 'display: none;'; ?>">
            <span class="feedback-icon" id="feedbackIcon">
                <?php echo isset($feedback) && $feedback['status'] === 'success' ? '✅' : '❌'; ?>
            </span>
            <span id="feedbackMessage">
                <?php echo isset($feedback) ? htmlspecialchars($feedback['message']) : 'Processando...'; ?>
            </span>
        </div>

        <div class="footer-note">
            <span>🧠 neon.tech · banco cloud</span> &nbsp;|&nbsp; tabela <strong>contatos</strong>
        </div>
    </div>

    <!-- Script para esconder feedback ao digitar -->
    <script>
        (function() {
            const feedbackDiv = document.getElementById('feedback');
            const nomeInput = document.getElementById('nome');
            const telefoneInput = document.getElementById('telefone');

            // Esconde feedback ao digitar em qualquer campo
            nomeInput.addEventListener('input', function() {
                feedbackDiv.style.display = 'none';
            });
            telefoneInput.addEventListener('input', function() {
                feedbackDiv.style.display = 'none';
            });

            // Se houver feedback de sucesso, limpa os campos
            <?php if (isset($feedback) && $feedback['status'] === 'success'): ?>
                nomeInput.value = '';
                telefoneInput.value = '';
            <?php endif; ?>
        })();
    </script>

</body>
</html>
