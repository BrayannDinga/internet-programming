function addTask() {
    var taskInput = document.getElementById("task-input");
    var taskText = taskInput.value.trim();

    if (taskText === "") {
        alert("Task cannot be empty!");
        return;
    }

    var taskList = document.getElementById("task-list");

    var li = document.createElement("li");
    li.innerHTML = `
        ${taskText}
        <button class="delete-button" onclick="deleteTask(this)">Delete</button>
    `;

    taskList.appendChild(li);

    taskInput.value = "";
}

function deleteTask(button) {
    var taskList = document.getElementById("task-list");
    taskList.removeChild(button.parentElement);
}