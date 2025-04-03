<?php
session_start();
require_once '../includes/database.php';

// Função para validar CPF (com dígitos verificadores)
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    return true;
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $cpfNumeros = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $creci = trim($_POST['creci']);
    $nome = trim($_POST['nome']);

    // Validações
    $errors = [];
    
    if (!validarCPF($cpfNumeros)) {
        $errors[] = "CPF inválido. Deve conter 11 dígitos válidos.";
    }
    
    if (strlen($creci) < 2 || strlen($creci) > 20 || !preg_match('/^[a-zA-Z0-9]+$/', $creci)) {
        $errors[] = "CRECI deve ter entre 2 e 20 caracteres alfanuméricos.";
    }
    
    if (strlen($nome) < 2 || strlen($nome) > 100) {
        $errors[] = "Nome deve ter entre 2 e 100 caracteres.";
    }
    
    if (empty($errors)) {
        try {
            if (empty($id)) {
                // Verifica se CPF já existe
                $check = $conn->prepare("SELECT id FROM corretores WHERE cpf = ?");
                $check->bind_param("s", $cpfNumeros);
                $check->execute();
                $check->store_result();
                
                if ($check->num_rows > 0) {
                    $_SESSION['message'] = "Erro: Este CPF já está cadastrado.";
                    $_SESSION['message_type'] = 'error';
                } else {
                    // INSERT
                    $stmt = $conn->prepare("INSERT INTO corretores (cpf, creci, nome) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $cpfNumeros, $creci, $nome);
                    
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Corretor cadastrado com sucesso!";
                        $_SESSION['message_type'] = 'success';
                    }
                }
            } else {
                // Verifica se CPF já existe em outro registro
                $check = $conn->prepare("SELECT id FROM corretores WHERE cpf = ? AND id != ?");
                $check->bind_param("si", $cpfNumeros, $id);
                $check->execute();
                $check->store_result();
                
                if ($check->num_rows > 0) {
                    $_SESSION['message'] = "Erro: Este CPF já está cadastrado em outro corretor.";
                    $_SESSION['message_type'] = 'error';
                } else {
                    // UPDATE
                    $stmt = $conn->prepare("UPDATE corretores SET cpf=?, creci=?, nome=? WHERE id=?");
                    $stmt->bind_param("sssi", $cpfNumeros, $creci, $nome, $id);
                    
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Corretor atualizado com sucesso!";
                        $_SESSION['message_type'] = 'success';
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['message'] = "Erro no processamento dos dados.";
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = 'error';
        $_SESSION['old_input'] = $_POST;
    }
}

// Processar exclusão via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete'];
    
    try {
        // Verifica se o registro existe antes de deletar
        $check = $conn->prepare("SELECT id FROM corretores WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM corretores WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Corretor excluído com sucesso!";
                $_SESSION['message_type'] = 'success';
            }
        } else {
            $_SESSION['message'] = "Erro: Corretor não encontrado.";
            $_SESSION['message_type'] = 'error';
        }
    } catch (Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        $_SESSION['message'] = "Erro ao excluir corretor.";
        $_SESSION['message_type'] = 'error';
    }
}

$redirect_url = '../../public/index.php';
header("Location: " . $redirect_url);
exit();