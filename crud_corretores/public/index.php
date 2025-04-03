<?php
session_start();
require_once '../app/includes/database.php';

// Gera token CSRF se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Corretores</title>
    
    <link rel="stylesheet" href="assets/stylesheet/global.css">
    <link rel="stylesheet" href="assets/stylesheet/alerts.css">
    <link rel="stylesheet" href="assets/stylesheet/table.css">
    <link rel="stylesheet" href="assets/stylesheet/form.css">
</head>
<body>
    <div class="container">
        <!-- alerts -->
        <?php include './alerts.php'; ?>
        <!---->
        
        <h1 id="title">Cadastro de Corretor</h1>
        <section class="form-section">

            <form id="corretorForm" action="../app/middleares/process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <input type="hidden" name="id" id="corretorId">
                
                <div class="form-grid">
                    <div class="input-group">
                        <label for="cpf">CPF</label>
                        <input type="text" 
                            id="cpf" 
                            name="cpf" 
                            placeholder="Apenas digitos numéricos" 
                            minlength="11" 
                            maxlength="11"
                            title="Formato: 00000000000" 
                            required>
                    </div>
        
                    <div class="input-group">
                        <label for="creci">CRECI</label>
                        <input type="text" 
                            id="creci" 
                            name="creci" 
                            placeholder="000000" 
                            minlength="2" 
                            maxlength="20" 
                            required>
                    </div>
        
                    <div class="input-group full-width">
                        <label for="nome">Nome Completo</label>
                        <input type="text" 
                            id="nome" 
                            name="nome" 
                            placeholder="Digite o nome completo" 
                            minlength="2" 
                            maxlength="100" 
                            required>
                    </div>
                </div>
                
                <button type="submit" id="submitBtn" class="primary-btn">Enviar</button>
            </form>
        </section>

        <!-- table -->
        <section class="table-section">
            <?php include './table.php'; ?>
        </section>
    </div>

    <script src="assets/js/form.js"></script>
    <script src="assets/js/workers.js"></script>
</body>
</html>