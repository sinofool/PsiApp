<?php
/**
 * Created by PhpStorm.
 * User: Bochun
 * Date: 2016-09-11
 * Time: 3:41 AM
 */

namespace ca\gearzero\psiapp;

use CFPropertyList\CFPropertyList;

class Psi
{
    const IOS_NAME = "iOS";
    const ANDROID_NAME = "Android";
    const UWP_NAME = "UWP";
    const MACOS_NAME = "macOS";
    const WINDOWS_NAME = "Windows";

    function get_apps()
    {
        $dataDir = $this->_data_dir();
        if (!is_dir($dataDir)) return array();
        $ret = array();
        foreach (scandir($dataDir) as $subdir) {
            if ($subdir[0] == '.') continue;
            $ret[] = $subdir;
        }
        return $ret;
    }

    function _data_dir()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . "data";
    }

    function _app_dir($app_name)
    {
        return $this->_data_dir() . DIRECTORY_SEPARATOR . $app_name;
    }

    function _platform_dir($app_name, $platform)
    {
        return $this->_data_dir() . DIRECTORY_SEPARATOR . $app_name . DIRECTORY_SEPARATOR . $platform;
    }

    function get_app_info($app_name)
    {
        $infoFile = $this->_app_dir($app_name) . DIRECTORY_SEPARATOR . ".psi_info";
        if (!is_file($infoFile)) return $app_name . ' Builds';
        return file_get_contents($infoFile);
    }

    function _is_platform_enabled($app_name, $platform)
    {
        return is_dir($this->_platform_dir($app_name, $platform));
    }

    function get_app_button($app_name, $platform)
    {
        if ($this->_is_platform_enabled($app_name, $platform)) {
            switch ($platform) {
                case Psi::IOS_NAME:
                    return '<button type="button" class="btn btn-primary" ' . $this->_ios_platform_link($app_name) . ">iOS</button>";
                case Psi::ANDROID_NAME:
                    return '<button type="button" class="btn btn-success" '. $this->_android_platform_link($app_name) .'>Android</button>';
                case Psi::UWP_NAME:
                    return '<button type="button" class="btn btn-info" ' . $this->_uwp_platform_link($app_name) . '>UWP</button>';
                case Psi::MACOS_NAME:
                    return '<button type="button" class="btn btn-warning" ' . $this->_macos_platform_link($app_name) . '>macOS</button>';
                case Psi::WINDOWS_NAME:
                    return '<button type="button" class="btn btn-danger" ' . $this->_windows_platform_link($app_name) . '>Windows</button>';
            }
        } else {
            return '';
        }
    }

    function _android_platform_link($app_name)
    {
        $platform_dir = $this->_platform_dir($app_name, PSi::ANDROID_NAME);
        $latest_file = $this->_find_latest_file_with_ext($platform_dir, ".apk");
        if ($latest_file == null) return "disabled";
        return "onclick=\"start_android('" . $app_name . "', '" . $latest_file . "');\"";
    }

    function _ios_platform_link($app_name)
    {
        $platform_dir = $this->_platform_dir($app_name, PSi::IOS_NAME);
        $latest_file = $this->_find_latest_file_with_ext($platform_dir, ".ipa");
        if ($latest_file == null) return "disabled";

        $plist_link = $this->base_https_link() . "iosplist.php?app_name=" . $app_name . "&ipa_name=" . $latest_file;
        $plist_link = "itms-services://?action=download-manifest&url=" . urlencode($plist_link);
        return "onclick=\"start_ios('" . $plist_link . "');\"";
    }

    function _uwp_platform_link($app_name)
    {
        $platform_dir = $this->_platform_dir($app_name, PSi::UWP_NAME);
        $latest_file = $this->_find_latest_file_with_ext($platform_dir, ".appx");
        if ($latest_file == null) return "disabled";
        return "onclick=\"start_uwp('" . $app_name . "', '" . $latest_file . "');\"";
    }

    function _macos_platform_link($app_name)
    {
        $platform_dir = $this->_platform_dir($app_name, PSi::MACOS_NAME);
        $latest_file = $this->_find_latest_file_with_ext($platform_dir, ".app.zip");
        if ($latest_file == null) return "disabled";
        return "onclick=\"start_macos('" . $app_name . "', '" . $latest_file . "');\"";
    }

    function _windows_platform_link($app_name)
    {
        $platform_dir = $this->_platform_dir($app_name, PSi::WINDOWS_NAME);
        $latest_file = $this->_find_latest_file_with_ext($platform_dir, ".application");
        if ($latest_file == null) return "disabled";
        return "onclick=\"start_windows('" . $app_name . "', '" . $latest_file . "');\"";
    }

    function base_https_link()
    {
        // TODO This does not fit all server environments
        $base_link = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        $query_pos = strpos($base_link, '?');
        if ($query_pos !== FALSE)
        {
            $base_link = substr($base_link, 0, $query_pos);
        }
        if (substr($base_link, -9) == "index.php")
        {
            $base_link = substr($base_link, 0, strlen($base_link) - 9);
        }
        if (substr($base_link, -12) == "iosplist.php")
        {
            $base_link = substr($base_link, 0, strlen($base_link) - 12);
        }
        if (substr($base_link, -1) != '/') $base_link += '/';
        return $base_link;
    }

    function parse_ipa($app_name, $ipa_name)
    {
        $ret = array();
        $ret[0] = $this->base_https_link() . "data/" . $app_name . '/' . $this::IOS_NAME . '/' . $ipa_name;

        $platform_dir = $this->_platform_dir($app_name, PSi::IOS_NAME);
        $ipa = $platform_dir . DIRECTORY_SEPARATOR . $ipa_name;
        if (!is_file($ipa)) return null;
        $ipa_cache_dir = $platform_dir . DIRECTORY_SEPARATOR . '.' . $ipa_name . '.psi_info';
        @mkdir($ipa_cache_dir);
        $cache_enabled = is_dir($ipa_cache_dir);
        if ($cache_enabled && filemtime($ipa_cache_dir) > filemtime($ipa))
        {
            $cache_integrity = true;

            $meta_file = $ipa_cache_dir . DIRECTORY_SEPARATOR . 'metadata';
            $meta = fopen($meta_file, "r");
            $i = 0;
            if ($meta) {
                while (($line = fgets($meta)) !== false) {
                    $ret[$i++] = $line;
                }
                fclose($meta);
            } else {
                $cache_integrity = false;
            }

            if (is_file($ipa_cache_dir . DIRECTORY_SEPARATOR . '57.png') &&
                is_file($ipa_cache_dir . DIRECTORY_SEPARATOR . '512.jpg')) {
                $psi_info_url = $this->base_https_link() . "data/" . $app_name . '/' . $this::IOS_NAME . '/.' . $ipa_name . '.psi_info';
                $ret[4] = $psi_info_url . DIRECTORY_SEPARATOR . '57.png';
                $ret[5] = $psi_info_url . DIRECTORY_SEPARATOR . '512.jpg';
            }
            if ($cache_integrity) return $ret;
        }

        // Generate Cache
        $zip = zip_open($ipa);
        if (!$zip) return ret;

        while ($zip_entry = zip_read($zip))
        {
            $fileinfo = pathinfo(zip_entry_name($zip_entry));
            if ($fileinfo['basename'] == "Info.plist" && preg_match('/^Payload\/.*\.app$/', $fileinfo['dirname']))
            {
                $plist_content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                require_once("lib/CFPropertyList/CFPropertyList.php");
                $plist = new CFPropertyList();
                $plist->parseBinary($plist_content);
                $plist_array = $plist->toArray();
                $ret[1] = $plist_array['CFBundleIdentifier'];
                $ret[2] = $plist_array['CFBundleVersion'];
                $ret[3] = $plist_array['CFBundleDisplayName'];
                $meta = fopen($ipa_cache_dir . DIRECTORY_SEPARATOR . 'metadata', 'w');
                fwrite($meta, $ret[1] . "\n");
                fwrite($meta, $ret[2] . "\n");
                fwrite($meta, $ret[3] . "\n");
                fclose($meta);
            }
            else if ($cache_enabled && $fileinfo['basename'] == "iTunesArtwork")
            {
                $artwork_content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                $artwork = imagecreatefromstring($artwork_content);
                if ($artwork) {
                    imagejpeg($artwork, $ipa_cache_dir . DIRECTORY_SEPARATOR . '512.jpg');
                    $icon = imagecreatetruecolor(57, 57);
                    imagecopyresampled($icon, $artwork, 0, 0, 0, 0, 57, 57, 512, 512);
                    imagepng($icon, $ipa_cache_dir . DIRECTORY_SEPARATOR . '57.png');
                }
                $psi_info_url = $this->base_https_link() . "data/" . $app_name . '/' . $this::IOS_NAME . '/.' . $ipa_name . '.psi_info';
                $ret[4] = $psi_info_url . DIRECTORY_SEPARATOR . '57.png';
                $ret[5] = $psi_info_url . DIRECTORY_SEPARATOR . '512.jpg';
            }
        }
        zip_close($zip);
        return $ret;
    }

    function _find_latest_file_with_ext($dir, $ext) {
        $latest_time = 0;
        $latest_file = null;
        foreach (scandir($dir) as $item) {
            if ($item[0] == '.') continue;
            if (!is_file($dir . DIRECTORY_SEPARATOR . $item)) continue;
            if (strtolower(substr($item, -strlen($ext))) == $ext) {
                $file_time = filemtime($dir . DIRECTORY_SEPARATOR . $item);
                if ($file_time > $latest_time) $latest_file = $item;
            }
        }
        return $latest_file;
    }
}
