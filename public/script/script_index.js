function changeTab(tab)
{
    if(tab === 'shared lists')
    {
        $("#SharedLists").css("display", "block");
        $("#MyLists").css("display", "none");
    }
    else
    {
        $("#MyLists").css("display", "block");
        $("#SharedLists").css("display", "none");
    }
    TabToggleClasses();
}

function TabToggleClasses()
{
    $(".tab-button").toggleClass("back-color-0 border-color-0 color-4 border-color-4 color-4 back-color-1");
    DarkMode();
}

function DeleteList(id)
{
    $.post("delete_todo.php", { id: id},
        function(data, status) {
            if(status === 'success')
                $('#List-' + id).css("display", "none");
        });
}

function RefuseNotif(todo_id)
{
    $.post("refuse_todo_access.php", {todo_id: todo_id},
        function(data, status) {
            if(status === 'success') {
                $('#Notif-' + todo_id).remove();
                CheckHideNotif();
            }
        });
}

function AcceptNotif(todo_id)
{
    $.post("accept_todo_access.php", {todo_id: todo_id},
        function(data, status) {
            if(status === 'success'){
                $('#Notif-' + todo_id).remove();
                CheckHideNotif();
            }
        });
}

function CheckHideNotif() {
    if(document.getElementById("NotifContainer").childNodes.length === 2){
        $('#Notif').toggle();
        $('#NotifContainer').remove();
    }
}