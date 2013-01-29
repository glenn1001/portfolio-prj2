function deleteSlideshow() {
    var form = document.getElementById('slideshow');
    form.action = '/webmaster-panel/slideshow/delete/';
    form.submit();
}

function updateSlideshowPos() {
    var form = document.getElementById('slideshow');
    form.action = '/webmaster-panel/slideshow/update/';
    form.submit();
}

function deleteSocial() {
    var form = document.getElementById('social');
    form.action = '/webmaster-panel/social/delete/';
    form.submit();
}

function updateSocialPos() {
    var form = document.getElementById('social');
    form.action = '/webmaster-panel/social/update/';
    form.submit();
}

function deleteProjects() {
    var form = document.getElementById('projects');
    form.action = '/webmaster-panel/project/delete/';
    form.submit();
}

function updateProjectPos() {
    var form = document.getElementById('projects');
    form.action = '/webmaster-panel/project/update/';
    form.submit();
}

function deletePages() {
    var form = document.getElementById('pages');
    form.action = '/webmaster-panel/page/delete/';
    form.submit();
}

function updatePagePos() {
    var form = document.getElementById('pages');
    form.action = '/webmaster-panel/page/update/';
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