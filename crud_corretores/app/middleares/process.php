<?php
session_start();
require_once '../includes/database.php';

// Função para validar CPF (apenas dígitos)
function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }
    
    return true;
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $cpf = $conn->real_escape_string($_POST['cpf']);
    $creci = $conn->real_escape_string($_POST['creci']);
    $nome = $conn->real_escape_string($_POST['nome']);

    

    $errors = [];
    
    if (!validarCPF($cpfNumeros)) {
        $errors[] = "CPF inválido. Deve conter exatamente 11 dígitos numéricos.";
    }
    
    //  2 caracteres, máximo 20 - creci
    if (strlen($creci) < 2 || strlen($creci) > 20) {
        $errors[] = "CRECI deve ter entre 2 e 20 caracteres.";
    }
    
    // mínimo 2 caracteres, máximo 100 - nome
    if (strlen($nome) < 2 || strlen($nome) > 100) {
        $errors[] = "Nome deve ter entre 2 e 100 caracteres.";
    }
    
 
    if (empty($errors)) {
        try {
            if (empty($id)) {
                // Verifica se CPF já existe (
                $check = $conn->prepare("SELECT id FROM corretores WHERE cpf = ?");
                $check->bind_param("s", $cpfNumeros);
                $check->execute();
                $check->store_result();
                
                if ($check->num_rows > 0) {
                    $_SESSION['message'] = "Erro: Este CPF já está cadastrado.";
                    $_SESSION['message_type'] = 'error';
                } else {
                    // INSErt
                    $stmt = $conn->prepare("INSERT INTO corretores (cpf, creci, nome) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $cpfNumeros, $creci, $nome);
                    
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Corretor cadastrado com sucesso!";
                        $_SESSION['message_type'] = 'success';
                    }
                }
            } else {
                // Verifica se CPF já existe 
                $check = $conn->prepare("SELECT id FROM corretores WHERE cpf = ? AND id != ?");
                $check->bind_param("si", $cpfNumeros, $id);
                $check->execute();
                $check->store_result();
                
                if ($check->num_rows > 0) {
                    $_SESSION['message'] = "Erro: Este CPF já está cadastrado em outro corretor.";
                    $_SESSION['message_type'] = 'error';
                } else {
                
                    $stmt = $conn->prepare("UPDATE corretores SET cpf=?, creci=?, nome=? WHERE id=?");
                    $stmt->bind_param("sssi", $cpfNumeros, $creci, $nome, $id);
                    
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Corretor atualizado com sucesso!";
                        $_SESSION['message_type'] = 'success';
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Erro no banco de dados: " . $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = 'error';
        $_SESSION['old_input'] = $_POST; 
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM corretores WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Corretor excluído com sucesso!";
            $_SESSION['message_type'] = 'success';
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Erro ao excluir: " . $e->getMessage();
        $_SESSION['message_type'] = 'error';
    }
}

$redirect_url = '../../public/index.php';
header("Location: " . $redirect_url);
exit();