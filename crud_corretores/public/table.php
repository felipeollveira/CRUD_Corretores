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
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['creci']) ?></td>
                <td><?= htmlspecialchars($row['cpf']) ?></td>
                <td class="actions">
                    <button class="edit-btn" 
                            data-id="<?= htmlspecialchars($row['id']) ?>"
                            data-nome="<?= htmlspecialchars($row['nome']) ?>"
                            data-creci="<?= htmlspecialchars($row['creci']) ?>"
                            data-cpf="<?= htmlspecialchars($row['cpf']) ?>"
                            title="Editar registro">
                        Editar
                    </button>
                    <button class="delete-btn" 
                            data-id="<?= $row['id'] ?>"
                            title="Excluir registro">
                        Excluir
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>