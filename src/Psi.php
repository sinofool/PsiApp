<?php
/**
 * Created by PhpStorm.
 * User: Bochun
 * Date: 2016-09-11
 * Time: 3:41 AM
 */

namespace ca\gearzero\psiapp;

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
        if (! is_dir($dataDir)) return array();
        $ret = array();
        foreach (scandir($dataDir) as $subdir)
        {
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
    function get_app_info($app_name)
    {
        $infoFile = $this->_app_dir($app_name) . DIRECTORY_SEPARATOR . ".psi_info";
        if (!is_file($infoFile)) return $app_name . ' Builds';
        return file_get_contents($infoFile);
    }

    function _get_app_enabled($app_name, $type)
    {
        return is_dir($this->_app_dir($app_name) . DIRECTORY_SEPARATOR . $type);
    }

    function get_app_link($app_name, $type)
    {
        if ($this->_get_app_enabled($app_name, $type)) {
            switch($type)
            {
                case Psi::IOS_NAME:
                    return "onclick=\"start_ios('".$app_name."');\"";
                case Psi::ANDROID_NAME:
                    return "onclick=\"start_android('".$app_name."');\"";
                case Psi::UWP_NAME:
                    return "onclick=\"start_uwp('".$app_name."');\"";
                case Psi::MACOS_NAME:
                    return "onclick=\"start_macos('".$app_name."');\"";
                case Psi::WINDOWS_NAME:
                    return "onclick=\"start_windows('".$app_name."');\"";
            }
        } else {
            return 'disabled';
        }
    }
}