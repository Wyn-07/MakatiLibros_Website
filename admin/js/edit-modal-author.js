
function openEditModal(category) {
    document.getElementById('editModal').classList.add('show');
    document.getElementById('category').value = category;
}


function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');

}

function saveChanges() {
    closeEditModal();
}