let draggedItem = null;

function drag(event) {
    draggedItem = event.target;
    event.dataTransfer.setData("text/html", event.target.id);
}

function allowDrop(event) {
    event.preventDefault();
} 

function drop(event) {
    event.preventDefault();
    const targetColumn = event.target.closest('.task-item, .column-todo, .column-doing, .column-done');
    
    if (targetColumn && draggedItem) {
        if (targetColumn.classList.contains('column-todo') || 
            targetColumn.classList.contains('column-doing') || 
            targetColumn.classList.contains('column-done')) {
            
            targetColumn.appendChild(draggedItem);
            
            const taskId = draggedItem.id.replace('task-', '');
            const newStatus = targetColumn.getAttribute('data-status');
            
            fetch(`?update_status=${taskId}&new_status=${newStatus}`, { method: 'GET' })
                .then(response => {
                    if (response.ok) {
                        console.log('Status updated');
                    } else {
                        console.log('Failed to update status');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
}