<?php 
# FAIL FAST FOR THIS FILE ONLY
  declare(strict_types=1);
 
# DYNAMIC TOGGLE TEST
  defined('LOCALHOST') 
  || 
  define('LOCALHOST', 'localhost'===$_SERVER['SERVER_NAME']);

# MAYBE OVERRIDE PHP.ini
  if(LOCALHOST):
    ini_set('html_errors', '1');
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(-1);
  endif;  

# LOAD CLASS
  require 'JB_Class.php';
  $jb = new JB_Class;

  $aTitles = [
    'John_Betong\'s Htaccess Redirection Tester',
    'Version: 005',
  ];

# GET VARIABLES
  $aHttps = $jb->getHttps();
  $aModes = $jb->getModes();
  $aSites = $jb->getSites($aModes);

  $sSites = implode("\n", $aSites);


# RENDER STUFF
  if(isset($_POST['clear'])):
    $jb->renderForm( $aTitles, $sSites="", $aHttps);
    $jb->renderLetsEncrypt();   

  elseif( isset($_POST['simple']) ):  
    $jb->renderForm( $aTitles, $sSites, $aHttps);
    $jb->renderCurl($aSites, $aHttps);

  elseif(isset($_POST['verbose']) ):
    $jb->renderForm( $aTitles, $sSites, $aHttps);
    $jb->renderVerbose( $aModes, $aSites, $aHttps);

  elseif(isset($_POST['sample'])):
    $jb->renderForm( $aTitles, $sSites, $aHttps);

  else:  
    $jb->renderForm( $aTitles, $sSites="", $aHttps);
    $jb->renderLetsEncrypt();   

  endif; 

  $jb->renderFooter(); // close body/html
