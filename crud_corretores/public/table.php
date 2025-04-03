<?php
$result = $conn->query("SELECT * FROM corretores ORDER BY id DESC");
?>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CRECI</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['creci'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['cpf'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="actions">

                    <!-- Btn edita -->
                      <button class="edit-btn" 
                            data-id="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>"
                            data-nome="<?= htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            data-creci="<?= htmlspecialchars($row['creci'], ENT_QUOTES, 'UTF-8') ?>"
                            data-cpf="<?= htmlspecialchars($row['cpf'], ENT_QUOTES, 'UTF-8') ?>"
                            title="Editar registro">
                        Editar
                    </button>
                    
                    <!-- Formulário de Exclusão com CSRF Token -->
                    <form method="POST" action="../app/middleares/process.php" class="delete-form">
                        <input type="hidden" name="delete" value="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este corretor?')" title="Excluir registro">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>