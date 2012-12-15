function addProject() {
    window.location = '/admn/project/create/';
}

function deleteProjects() {
    var form = document.getElementById('projects');
    form.action = '/admn/project/delete/';
    form.submit();
}

function updateProjectPos() {
    var form = document.getElementById('projects');
    form.action = '/admn/project/update/';
    form.submit();
}

function addPage() {
    window.location = '/admn/page/create/';
}

function deletePages() {
    var form = document.getElementById('pages');
    form.action = '/admn/page/delete/';
    form.submit();
}

function updatePagePos() {
    var form = document.getElementById('pages');
    form.action = '/admn/page/update/';
    form.submit();
}

function selectPages() {
    var checkboxes = document.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = true;
        }
    }
}

function deSelectPages() {
    var checkboxes = document.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
        }
    }
}