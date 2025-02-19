document.addEventListener('DOMContentLoaded', function () {
    // Apenas execute o código quando o DOM estiver completamente carregado
    const fileInput = document.getElementById('imagem-opcional');
    const preview = document.getElementById('img-preview');
    const removeButton = document.getElementById('remove-image');

    if (fileInput) {
        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    removeButton.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeButton) {
        removeButton.addEventListener('click', function() {
            fileInput.value = '';  // Limpa o input
            preview.style.display = 'none';  // Esconde a imagem
            removeButton.style.display = 'none';  // Esconde o botão de remover
        });
    }
});
