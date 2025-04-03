

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('corretorForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Edit functionality
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const cpf = this.getAttribute('data-cpf');
            const creci = this.getAttribute('data-creci');
            const nome = this.getAttribute('data-nome');
            
            document.getElementById('corretorId').value = id;
            document.getElementById('cpf').value = cpf;
            document.getElementById('creci').value = creci;
            document.getElementById('nome').value = nome;
            let title = document.getElementById("title").textContent = "Editar Corretor"
            submitBtn.textContent = 'Atualizar';
            
        
        });
    });
    
    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {

        btn.addEventListener('click', function() {

            if(confirm('Tem certeza que deseja excluir este corretor?')) {
               
                const id = this.getAttribute('data-id');
                window.location.href = `../app/middleares/process.php?delete=${id}`;
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        

        document.querySelectorAll('[required]').forEach(input => {
            if(!input.value.trim()) {
                input.style.borderColor = 'red';
                isValid = false;
            }
        });
        
        if(!isValid) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios!');
        }
    });
    
    // Reset input
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
        });
    });
});