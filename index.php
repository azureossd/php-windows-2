<?php
/*
 * Azure App Service L200 Lab
 * Open Source Technologies - PHP Windows
 */

$config = simplexml_load_file("./web.config") or die("Error: Cannot open web.config");
$webServer = $config->{'system.webServer'};
$handlers = $webServer->{'handlers'};
$security = $webServer->{'security'};
$staticContent = $webServer->{'staticContent'};

// Statics
$error = "The web.config file does not have the required configuration for this part. </br></br>";
$part1 = "Part 1: Update Allowed VERBS </br>";
$part2 = "Part 2: Updating IIS Max Upload Size </br>";
$part3 = "Part 3: Allow specific MIME Types </br>";

// Update Allowed VERBS
echo $part1;
$hRemove = $handlers->{'remove'};
$hAdd = $handlers->{'add'};

if ( $hRemove->count() > 0 && $hAdd->count() > 0) {
    $verbs = $hAdd['verb'];
    if (strstr ($verbs, 'GET') &&
        strstr ($verbs, 'POST') &&
        strstr ($verbs, 'PUT') &&
        strstr ($verbs, 'DELETE') &&
        strstr ($verbs, 'PATCH')) {
        echo "All verbs are allowed: " . $verbs . "</br></br>";
    } else {
        echo "You are missing a some VERBS. There are 5 total. </br></br>";
    }
} else {
    echo $error;
}

// Updating IIS Max Upload Size
echo $part2;
$requestLimits = $security->{'requestFiltering'}->{'requestLimits'};

if($requestLimits->count() > 0) {
    if(intval($requestLimits['maxAllowedContentLength']) > (32 * 1024 * 1024)) {
        echo "Successfully set maxAllowedContentLength to: " . number_format((intval($requestLimits['maxAllowedContentLength']) / 1024 / 1024), 2, '.', '') . "MB </br></br>";
    } else {
        echo "IIS Max Upload Size needs to be larger.";
    }
} else {
    echo $error;
}

// Allow specific MIME Types
echo $part3;
$scRemove = $staticContent->xpath('remove');
$scMimeMap = $staticContent->xpath('mimeMap');

if(count($scRemove) > 0 && count($scMimeMap) > 0) {
    $ttf = $staticContent->xpath('mimeMap[@fileExtension=".ttf"]');
    $woff = $staticContent->xpath('mimeMap[@fileExtension=".woff"]');
    $woff2 = $staticContent->xpath('mimeMap[@fileExtension=".woff2"]');
    if(count($ttf) > 0 && count($woff) > 0 && count($woff2) > 0) {
        echo "Successfully added MimeTypes for: .tff, .woff, .woff2";
    } else {
        echo "MIME Type(s) missing from web.config.";
    }
} else {
    echo $error;
}

?>