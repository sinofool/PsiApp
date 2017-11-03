<?php
$app_name = $_REQUEST["app_name"];
$ipa_name = $_REQUEST["ipa_name"];
include "Psi.php";
$PSI = new \ca\gearzero\psiapp\Psi();

$plist = $PSI->parse_ipa($app_name, $ipa_name);
header("Content-Type: application/xml");
?><?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
    <dict>
        <key>items</key>
        <array>
            <dict>
                <key>assets</key>
                <array>
                    <dict>
                        <key>kind</key>
                        <string>software-package</string>
                        <key>url</key>
                        <string><?php echo $plist[0] ?></string>
                    </dict>
<?php if (sizeof($plist) > 4) { ?>
                    <dict>
                        <key>kind</key>
                        <string>display-image</string>
                        <key>url</key>
                        <string><?php echo $plist[4] ?></string>
                    </dict>
                    <dict>
                        <key>kind</key>
                        <string>full-size-image</string>
                        <key>url</key>
                        <string><?php echo $plist[5] ?></string>
                    </dict>
<?php } ?>
                </array>
                <key>metadata</key>
                <dict>
                    <key>bundle-identifier</key>
                    <string><?php echo $plist[1] ?></string>
                    <key>bundle-version</key>
                    <string><?php echo $plist[2] ?></string>
                    <key>kind</key>
                    <string>software</string>
                    <key>title</key>
                    <string><?php echo $plist[3] ?></string>
                </dict>
            </dict>
        </array>
    </dict>
</plist>
