function CheckOffAllTasks(list_id)
{
    $.ajax({
        type: "GET",
        url: "get_in_progress_tasks.php?list_id="+list_id,
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            response.forEach(function(item, index){
                ChangeTaskState(item, list_id);
            })
        }
    });
}

function DeleteAllDoneTasks(list_id)
{
    $.ajax({
        type: "GET",
        url: "get_done_tasks.php?list_id="+list_id,
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            response.forEach(function(item, index){
                DeleteTask(item, list_id);
            })
        }
    });
}

function DeleteList(id)
{
    $.post("delete_todo.php", { id: id},
        function(data, status) {
            if(status === 'success')
                $('list-' + id).css("display", "none");
        });
}

function DeleteTask(task_id, list_id) {
    $.post("delete_task.php", { task_id: task_id, list_id: list_id},
        function(data, status) {
            if(status === 'success')
                $('.task-' + task_id).remove();
            else
                alert("error when deleting task");
        });
}

function ChangeTaskState(task_id, list_id)
{
    $.post("change_task_state.php", { task_id: task_id, list_id: list_id},
        function(data, status) {
            if(status === 'success')
                $('.task-' + task_id).toggle();
            else
                alert("error when modifying task state");
        });
}