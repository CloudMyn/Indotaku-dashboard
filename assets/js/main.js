

// Facebook Api Init
window.fbAsyncInit = function () {
    FB.init({
        appId: '{your-app-id}',
        cookie: true,
        xfbml: true,
        version: '{api-version}'
    });

    FB.AppEvents.logPageView();

};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// ------------------------------------------- Menu Modal -------------------------------------------
function addMenu() {
    $("#menuModalLabel").html("Add New Menu");
    $("#btn-submit").html("Add");
    $("#name-field").val("");
    $("#menu-form").attr("action", "");
    $("#isActive-field").html(`<option value="1">true</option><option value="0">false</option>`);
}

function updateMenu(id, name, isActive) {
    $("#menuModalLabel").html("Update Menu");
    $("#modal-menu").append(`<input type="hidden" value="${id}" name="id">`);
    $("#btn-submit").html("Update");
    $("#name-field").val(name);
    $("#menu-form").attr("action", `${base_url}menu/updateMenu`);
    if (isActive == 1) {
        $("#isActive-field").html(`<option value="1">true</option><option value="0">false</option>`);
    } else {
        $("#isActive-field").html(`<option value="0">false</option><option value="1">true</option>`);
    }
}