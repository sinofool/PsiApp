/**
 * Created by Bochun on 2016-09-11.
 */

function start_ios(plist_link) {
    window.location.href = plist_link;
}

function start_android(app_name, apk_name) {
    window.location.href = "data/" + app_name + "/Android/" + apk_name;
}

function start_uwp(app_name, appx_name) {
    window.location.href = "data/" + app_name + "/UWP/" + appx_name;
}

function start_macos(app_name, zip_name) {
    window.location.href = "data/" + app_name + "/macOS/" + zip_name;
}

function start_windows(app_name, zip_name) {
    window.location.href = "data/" + app_name + "/Windows/" + zip_name;
}