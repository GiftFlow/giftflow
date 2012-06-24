<?php 
// GZip all of the files
header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 5 * 365 * 24 * 60 * 60 ;
$ExpStr = "Expires: " . 
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);

// UI Core
include('./includes/jquery-ui-core.php');

// UI Widgets
include('./includes/jquery-ui-widgets.php');

// UI Effects
include('./includes/jquery-ui-effects.php');

// UI Position
include('./includes/jquery-ui-position.php');

// UI Autocomplete
include('./includes/jquery-ui-autocomplete.php');

// Form
include('./includes/jquery-form.php');

// In-Field Labels
include('./includes/in-field-labels.php');

// jqModal
include('./includes/jqmodal.php');

// notifyBar
include('./includes/notifybar.php');

// tipTip
include('./includes/tiptip.php');

// SimplePager
include('./includes/pager.php');

// buttonTabs
include('./includes/buttontabs.php');
?>