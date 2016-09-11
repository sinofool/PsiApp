/**
 * Created by Bochun on 2016-09-11.
 */

function start_ios(plist_link) {
    window.location.href = "itms-services://?action=download-manifest&url=" + plist_link;
    // window.location.href = plist_link;
}

function start_android(app_name, apk_name) {
    window.location.href = "data/" + app_name + "/Android/" + apk_name;
}

function start_uwp() {
    
}

function start_macos() {
}

function start_windows() {
    
}