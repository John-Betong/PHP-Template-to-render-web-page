<?php 
declare(strict_types=1);


//==============================================
Class JB_Class
{

//==============================================
PUBLIC function __construct()
# CANNOT DECLARE RETURN TYPE 
{
  # $result = 'NOT REQUIRED';
  session_start();
    
# DEFAULTS SET ALL URLs to checked  
  $_SESSION['aHttps1'] = isset($_POST['http1'])  ? 'checked' : 'XXX';
  $_SESSION['aHttps2'] = isset($_POST['http2'])  ? 'checked' : 'XXX';
  $_SESSION['aHttps3'] = isset($_POST['http3'])  ? 'checked' : 'XXX';
  $_SESSION['aHttps4'] = isset($_POST['http4'])  ? 'checked' : 'XXX';

# CANNOT DECLARE RETURN TYPE  
  # return $result;
}


//==============================================
PUBLIC function getHttps()
:array
{
  if( empty($_POST) ):
    $aHttps = [
    'http1' => isset($_POST['http1']) || $_SESSION['aHttps1'] ? 'checked' : 'XXX',
    'http2' => isset($_POST['http2']) || $_SESSION['aHttps2'] ? 'checked' : 'XXX',
    'http3' => isset($_POST['http3']) || $_SESSION['aHttps3'] ? 'checked' : 'XXX',
    'http4' => isset($_POST['http4']) || $_SESSION['aHttps4'] ? 'checked' : 'XXX',
    ]; 
  else:
    $aHttps = [
    'http1' => isset($_POST['http1']) ? 'checked' : '',
    'http2' => isset($_POST['http2']) ? 'checked' : '',
    'http3' => isset($_POST['http3']) ? 'checked' : '',
    'http4' => isset($_POST['http4']) ? 'checked' : '',
    ]; 
  endif;  

  $aHttps['tags'] = FALSE;
  foreach($aHttps as $tmp):
    $aHttps['tags'] = $aHttps['tags'] || $tmp;
  endforeach;  

  return $aHttps;
}


//==============================================
PUBLIC function getModes()
:array
{
    $aModes = [
    'clear'   => isset($_POST['clear'])   ?? FALSE,
    'simple'  => isset($_POST['simple'])  ?? FALSE,
    'verbose' => isset($_POST['verbose']) ?? FALSE,
    'sample'  => isset($_POST['sample'])  ?? FALSE,
  ];  

  return $aModes;
}

//=====================================================
PUBLIC function getSites( array $aModes)
:array
{
# MAYBE DEFAULT SITES
  if( $aModes['sample'] ):  
    $aSites = [
      'letsencrypt.org',
      'apple.com',
      'bing.com',
      ];      

  else: 
    $aSites = [];
    if( isset($_POST['URLS']) ):
      $aSites = explode( "\n", $_POST['URLS'] );
    endif;
    foreach($aSites as $id => $tmp):  
      if( empty( trim($tmp) ) || '\n'===$tmp):
        unset( $aSites[$id]);
      endif;  
    endforeach;  
  endif;
  # $sSites = implode("\n", $aSites);

  return $aSites;
}

//==============================================
PUBLIC function renderForm( array $aTitles, string $sSites, array $aHttps)
:bool // NO RETURN
{

# ACTIVE BUTTON
  $tmp = 'bgs fsl fwb';
  $bgr1 = isset($_POST['clear'] )   ? $tmp : '';
  $bgr2 = isset($_POST['simple'] )  ? $tmp : '';
  $bgr3 = isset($_POST['verbose'] ) ? $tmp : '';
  $bgr4 = isset($_POST['sample'] )  ? $tmp : '';
 
  $styleSheet = $this->getStyleSheet();


$tmp   = <<< ______TMP
  <!DOCTYPE HTML>
  <html lang = 'en-GB'>
  <head>
  <title> $aTitles[0] | $aTitles[1] </title>
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <meta name="description" content="Template page" />
  <style> $styleSheet  </style>  
  </head>
  <body> 
  <h1 class="fwb tac"> <a href="?"> $aTitles[0] </a> </h1>
  <h5 class="flr ooo"> $aTitles[1] </h5>

  <hr class="clb">
  <form action="?" method="post">
    <div class="w88 mga mn5 bgs">
        <h2 class="bge">
          Please enter URL names - ONLY one per line 
        </h2>

        <textarea name="URLS" class="w99 hgt p42 lh2 fwb">$sSites </textarea>

        <div class="tac p42 fwb fsl">
          <label> https://www. </label>
            <input type="checkbox" name="http4" {$aHttps['http4']} >
            \n
          <label> https:// </label>
            <input type="checkbox" name="http3" {$aHttps['http3']} />
            \n
          <label> http://www. </label>
            <input type="checkbox" name="http2" {$aHttps['http2']} />
            \n
          <label> http:// </label>
            <input class="fwb" type="checkbox" name="http1" {$aHttps['http1']} />
            \n
        </div>  

        <div class="tac p42 fwb">
          <input class="$bgr4 flr" type="submit" name="sample"  value="samples"  />
          <input class="$bgr1 fll" type="submit" name="clear"   value="clear"   />
          <input class="$bgr2" type="submit" name="simple"  value="simple"  />
          <input class="$bgr3" type="submit" name="verbose" value="verbose" />
        </div>  
      </div>  
  </form>
______TMP;
echo $tmp;

  return FALSE; // $tmp;  
}//endfunc


//=====================================================
PUBLIC function renderCurl( array $aModes, array $aSites, array $aHttps)
:string
{
  $result = '<h2 class="tac">' .__METHOD__ .'</h2>';

  echo $result;
  
  return $result;
}

//=====================================================
PUBLIC function renderLetsEncrypt()
:bool
{
    $tmp = <<< ____TMP
      <p> <br> </p>
      <h1 class="w88 dib mga bd1 bgs tac">
        <a 
          class="tal tdn"
          href="https://LetsEncrypt.org"> 
          <button>
            <img 
              src="/assets/imgs/lets-497x357.png"
              width="497" height="357"
            alt="LetsEncrypt PNG"
            > 
          </button>
        </a> 
      </h1>
____TMP;
    echo $tmp;  

  return FALSE; // NO RETURN    
}

//=====================================================
PUBLIC function renderVerbose( array $aModes, array $aSites, array $aHttps)
:string
{
  $result = '<h2 class="tac">' .__METHOD__ .'</h2>';

  echo $result;

  return $result;
}


//=====================================================
PUBLIC function renderFooter()
:bool # NO RETURN
{

# FOOTER 
  $tmp = <<< ______TMP
    <p> <br> </p>
    <div class="POS w99 bga bd1 tac">
      Wonderful place for a footer 
    </div>
  
  </body>
  </html>  
______TMP;
  echo $tmp;

  return FALSE; // NO RETURN
}


//=================================================================
PRIVATE function getStyleSheet()
:string
{
  $result = <<< ____TMP
    body {font: 16px/1.55 BlinkMacSystemFont, "Segoe UI", 
          Roboto, Helvetica, Arial, sans-serif;}
    body {border:0; margin:0; padding:0; background-color: #f0f0f0; color:#333;}
    .POS {position: fixed; left: 0; bottom: 0;}
    .bd1 {border: solid 1px #ddd;}
    .bdb {border-bottom: solid 1px #999;}
    .bga {background-color: #aaa;}
    .bgc {background-color: #fcc;}
    .bgg {background-color: #afa;}
    .bgo {background-color: #ffc826;} /* #ffa500 orange #ffbf00 amber */
    .bgr {background-color: #f00; color: #000;}
    .bgs {background-color: snow; color: #000;}
    .bgy {background-color: #ff0;}
    .clb {clear: both;}
    .fgg {color: #0f0;} .fgr {color: red;} .fgs {color: #fff;} 
    .fll {float: left;} .flr {float: right;} 
    .fss {font-size: small;} .fsl {font-size: x-large;}
    .hhh {display: none;}
    .lh2 {line-height: 2em;}
    .mga {margin: 0 auto;}
    .ooo {margin: 0; padding:0;}
    .hgt {height: 8.88em;}
    .p42 {padding: 0.42em;}
    .tac {text-align: center;} 
    .tar {text-align: right;}
    .tdn {text-decoration: none;}
    .w88 {width: 88%; max-width: 888px;}
    .w99 {width: 99.99%;}
____TMP;

  return $result;    
}


}///endclass 

//==========================================================
function fred($val, $title='YES we have no $title', $vd=NULL)
:bool
{
  $result = 'NOT REQUIRED';

# SET $var  
  if($vd):
    $var = var_dump($val, TRUE); 
  else:
    $var = print_r($val, TRUE); 
  endif;  

  $gType = gettype($var);
  $tmp = <<< ______TMP
    <dl class="w88 mga bd1 bgs p42 fss">
      <dt> $title ==> gettype( $gType ) </dt>
        <dd>
          <pre class="bd1 bgs p42 fss"> $var </pre>
        </dd>
     </dl>
______TMP;
  echo $tmp;    

  return FALSE; // $result;
}
