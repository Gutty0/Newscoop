<?php  
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/common.php');
load_common_include_files("article_images");
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Article.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Image.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Issue.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Section.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Language.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Publication.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Input.php');
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/camp_html.php");

list($access, $User) = check_basic_access($_REQUEST);
if (!$access) {
	header("Location: /$ADMIN/logout.php");
	exit;
}

$Pub = Input::Get('Pub', 'int', 0);
$Issue = Input::Get('Issue', 'int', 0);
$Section = Input::Get('Section', 'int', 0);
$sLanguage = Input::Get('sLanguage','int', 0);
$Article = Input::Get('Article', 'int', 0);
$ImageId = Input::Get('ImageId', 'int', 0);
$Language = Input::Get('Language', 'int', 0);

if (!Input::IsValid()) {
	camp_html_display_error(getGS('Invalid input: $1', Input::GetErrorString()));
	exit;		
}

$articleObj =& new Article($Pub, $Issue, $Section, $sLanguage, $Article);

// This file can only be accessed if the user has the right to change articles
// or the user created this article and it hasnt been published yet.
$access = false;
if ($articleObj->userCanModify($User) || $User->hasPermission('DeleteImage')) {
	$access = true;
}
if (!$access) {
	camp_html_display_error(getGS("You do not have the right to change this article.  You may only edit your own articles and once submitted an article can only changed by authorized users."));	
	exit;		
}

$imageObj =& new Image($ImageId);
$imageObj->delete();
$logtext = getGS('Image $1 deleted', $imageObj->getImageId()); 
Log::Message($logtext, $User->getUserName(), 42);

// Go back to article image list.
header('Location: '.camp_html_article_url($articleObj, $Language, 'images/'));

?>