// JS para mostrar a prévia da imagem e permitir remover
document.getElementById('imagem-opcional').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('img-preview');
    const removeButton = document.getElementById('remove-image');

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

document.getElementById('remove-image').addEventListener('click', function() {
    document.getElementById('imagem-opcional').value = '';  // Limpa o input
    document.getElementById('img-preview').style.display = 'none';  // Esconde a imagem
    document.getElementById('remove-image').style.display = 'none';  // Esconde o botão de remover
});
