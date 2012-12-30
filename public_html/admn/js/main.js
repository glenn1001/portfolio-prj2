function deleteSlideshow() {
    var form = document.getElementById('slideshow');
    form.action = '/admn/slideshow/delete/';
    form.submit();
}

function updateSlideshowPos() {
    var form = document.getElementById('slideshow');
    form.action = '/admn/slideshow/update/';
    form.submit();
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

function selectAll() {
    var checkboxes = document.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = true;
        }
    }
}

function deSelectAll() {
    var checkboxes = document.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
        }
    }
}