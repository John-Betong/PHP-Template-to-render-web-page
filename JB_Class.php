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
PUBLIC function renderCurl( array $aSites, array $aHttps)
:string
{
  # $result = '';
  $msg = 'Please add at least one site to start testing'; // RETURN $result 

  if( empty($aSites) ):
    echo '<h1 class="fgr tac">' . $msg .'</h1>';
  endif;  

# WHATEVER
  $tStart = microtime(true);
    foreach($aSites as $id => $url):
      echo '<p> &nbsp; </p>';
      echo '<div class="w88 mga bgs">';
        $url = $this->fnRemovePrefix($url);
        $url = trim($url);
        if( empty( $url ) ):
          # echo '<br>PREVENT BLANK LINES';
        else:  
          if( $aHttps['http4'] ):
              $aHeader = $this->fnCurlHeaders('https://www.' .$url);
            echo "\n";
          endif;  
          if( $aHttps['http3'] ):
            $aHeader = $this->fnCurlHeaders('https://' .$url);
            echo "\n";
          endif;  
          if( $aHttps['http2'] ):
            $aHeader = $this->fnCurlHeaders('http://www.' .$url);
            echo "\n";
          endif;  
          if( $aHttps['http1'] ):
            $aHeader = $this->fnCurlHeaders('http://' .$url);
            echo "\n";
          endif;  
          $elapsed = number_format( (float) microtime(true) - $tStart, 3);
          $msg2 = ''
               . '<i class="ooo flr">'
               .   $elapsed .'secs'
               . '</i>';   
          $msg2 .= '<h4 class="tac"> ¯\_(ツ)_/¯ </h4>';
          echo $msg2;         
        endif;  
      echo '</div>';  
    endforeach;  

  return $msg;
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
  # $result = '';
  $msg = 'NOT USED'; // RETURN $result 

  # $result = '';
  $msg = 'Please add at least one site to start testing'; // RETURN $result 

  if( empty($aSites) ):
    echo '<h1 class="fgr tac">' . $msg .'</h1>';
  endif;  

  $tStart = microtime(true);

# GET_HEADERS(...);
    foreach($aSites as $id => $url):
        $url = $this->fnRemovePrefix($url);
        $url = trim($url);
        if( empty( $url ) ):
          # echo '<br>PREVENT BLANK LINES';
        else:  
          if( $aHttps['http4'] ):
            $aHeader = $this->fnGetHeaders('https://www.' .$url, $aModes);
            echo "\n";
          endif;  
          if( $aHttps['http3'] ):
            $aHeader = $this->fnGetHeaders('https://' .$url, $aModes);
            echo "\n";
          endif;  
          if( $aHttps['http2'] ):
            $aHeader = $this->fnGetHeaders('http://www.' .$url, $aModes);
            echo "\n";
          endif;  
          if( $aHttps['http1'] ):
            $aHeader = $this->fnGetHeaders('http://' .$url, $aModes);
            echo "\n";
          endif;  
          $elapsed = number_format( (float) microtime(true) - $tStart, 3);
          $msg .= ''
               . '<i class="ooo flr">'
               .   $elapsed .'secs'
               . '</i>';   
          $msg .= '<h4 class="tac"> ¯\_(ツ)_/¯ </h4>';         
        endif;  
    endforeach;  

  return $msg;
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


//=======================================        
//=======================================        
PRIVATE function fnRemovePrefix( string $url)
:string
{
  $url = trim($url);
  $url = strtolower($url);

  /*
    BEWARE: CHECK FOR LONGEST STRING FIRST
  */  

  if( 'http://www.' === substr($url, 0, 11) ):
    $result = substr($url, 11);

  elseif( 'http://' === substr($url, 0, 7) ):
    $result = substr($url, 7);

  elseif( 'https://www.' === substr($url, 0, 12) ):
    $result = substr($url, 12);

  elseif( 'https://' === substr($url, 0, 8) ):
    $result = substr($url, 8);

  elseif( '//www.' === substr($url, 0, 6) ):
    $result = substr($url, 6);

  elseif( 'www.' === substr($url, 0, 4) ):
    $result = substr($url, 4);

  elseif( '//' === substr($url, 0, 2) ):
    $result = substr($url, 2);

  else:
    $result = $url;  
  endif;    

  return $result;
}

//=======================================        
//=======================================        
PRIVATE function fnCurlHeaders(string $url)
:bool // NOT REQUIRED
{
  if( ! $url 
      || ! is_string($url) 
      || ! preg_match
           (
            '/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)
         ):
    echo $this->fnWhoops('Whoops - Bad URL ==> ' . $url, $url);

    return FALSE; // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
  endif;

# CURL HEADER ONLY WITH ELAPSED
  /* \n SEPERATED VARIBLE
      HTTP/1.1 200 OK
      ETag: "52ee03de84b1b44964304ce117395227:1533631913"
      Last-Modified: Tue, 07 Aug 2018 08:51:53 GMT
      Accept-Ranges: bytes
      Content-Length: 52140
      Content-Type: text/html
      Cache-Control: private, max-age=300
      Expires: Sat, 25 Aug 2018 04:00:08 GMT
      Date: Sat, 25 Aug 2018 03:55:08 GMT
      Connection: keep-alive
      Set-Cookie: eaesssn=5778e7313d1f00001cd3805b78020000d42c0000; path=/; domain=.ikea.com
      Set-Cookie: MyLocation=TH; expires=Sat, 25-Aug-2018 04:00:08 GMT
      X-Content-Type-Options: nosniff
      X-UA-Compatible: IE=edge
      Server: IITP Server
    */
  $tStart = microtime(true);
  $opts = [
    CURLOPT_URL             => $url,
    CURLOPT_HEADER          => TRUE,
    CURLOPT_RETURNTRANSFER  => TRUE,
    CURLOPT_FOLLOWLOCATION  => FALSE,
  # CURLOPT_FRESH_CONNECT   => TRUE,
  # CURLOPT_NOBODY          => TRUE,
  # CURLOPT_FORBID_REUSE    => TRUE,
  # CURLOPT_SSL_VERIFYHOST  => FALSE,
  # CURLOPT_SSL_VERIFYPEER  => FALSE,
  # CURLOPT_FAILONERROR     => TRUE,
  # CURLOPT_SSL_VERIFYHOST  => FALSE,
  # CURLOPT_SSL_VERIFYPEER  => FALSE,
    CURLOPT_TIMEOUT         => 5,
  ];
  $ch = curl_init($url);
    curl_reset($ch);
    curl_setopt_array($ch, $opts);
    curl_reset($ch);
    curl_setopt_array($ch, $opts);
  $sOK  = curl_exec($ch); 

# MAYBE FAILED
  if( is_bool($sOK) || empty($sOK) ):
    echo $this->fnWhoops( curl_error($ch), $url );

    return FALSE; // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
  endif;
  $elapsed = number_format( (float) microtime(true) - $tStart, 3);

# ESSENTIAL TO CALL BEFORE CLOSING
  # fred(curl_getinfo($ch));
  curl_close($ch);

# SET COLORS
  $aOK = explode("\n", $sOK);
  $clr = $this->_getColor($aOK);

# MAYBE REDIRECT
  $ok3 = [];
  foreach($aOK as $id => $data):
    if( strpos($data, ':') ):
      $ok3[strstr($data, ':', true)] = strstr($data, ':', false);
    endif;  
  endforeach;  

# WHATEVER
  $fav = '<img class="fgr" src="' .$url .'/favicon.ico" width="40" height="40" alt="  favicon.ico MISSING???">';          
  $hasRedirect = isset($ok3['Location']) || isset($ok3['location'])  
            ? ''
            . '<dd> &nbsp; </dd>'
            . '<dt> <b class="tal"> Redirect_URL </b> </dt>'
            . '<dd>' .substr($ok3['Location'],1) .'</dd>'
            : '';
  $maybeLF  = strpos($aOK[0], '200') 
            ? '<dd> &nbsp; </dd>'
            : '';

  $tmp = <<< ____TMP
    <p> &nbsp; <br></p>  
    <b class="fsl">  
      <a href="$url"> 
        $url 
        $fav
      </a>
    </b>  
    <dl class="w88 mga bd1 $clr">
      <dt class="flr ooo tar"> $elapsed secs </dt>    
      <dt> $aOK[0] </dt>
      $maybeLF
      <dd>
        $hasRedirect
      </dd>
    </dl>
____TMP;
  echo $tmp;

  return FALSE; // NO RETURN $result;
}


//=======================================        
//=======================================        
PRIVATE function _getColor( array $aOK)
:string
{
  $aHdr = $aOK[0];
  if($aHdr && strpos($aHdr, '2') ):
    $clr = 'bgg'; // NO PROBLEM
  elseif($aHdr && strpos($aHdr, '3') ):  
    $clr = 'bgo';
  elseif($aHdr && strpos($aHdr, '4') ):  
    $clr = 'bgr';
  else:  
    $clr = 'bgr';
  endif;

  return $clr;
}


//=======================================        
//=======================================        
PRIVATE function fnGetHeaders($url, $mode)
:string
{
  $result = '<h2 class="tac"> Problem with site? </h2>';

  $mStart = microtime(true);
# https://www.sitepoint.com/community#report-ad    
  $context =  stream_context_create(['http' => ['timeout' => 5]]);
  $aHdr    = @get_headers($url, 0, $context);    
  #$aHdr = @get_headers($url);// ?? 

  $elapse = number_format( (float) microtime(true) - $mStart, 3);

# ERRORS ???
    if( is_bool($aHdr) ):
      # echo $this->fnWhoops('Invalid URL ???', $url);
      # die;
      $aHdr[0] = '<b class="fsl">Strange problem with URL ??? </b>';
    endif; 

# RENDER
  if($aHdr && strpos($aHdr[0], '2') ):
    $clr = 'bgg'; // NO PROBLEM
  elseif($aHdr && strpos($aHdr[0], '3') ):  
    $clr = 'bgo';
  elseif($aHdr && strpos($aHdr[0], '4') ):  
    $clr = 'bgr';
  else:  
    $clr = 'bgr';
  endif;
# $filesize = '9999999999999999';  
# $fSize    = '888888888888888';

  echo '<br>',
  $result   = $this->GetTable($url, $aHdr, $clr );   
    
  return $result;
}


//======================================================
//======================================================
PRIVATE function GetTable( string $url, array $aTable, string $clr ) 
: string
{
  #  $result = [];
  $qqq = $aTable[0];
# fred($aTable, '$aTable');
  $aTable = array_slice($aTable, 1, 999);

  $result = '<dl class="w88 mga bd1 ' .$clr .'">'
          . '<dt class="fsl"><a href="' .$url .'">' .$url .'</a></dt>'
          . '<dd>' .$qqq .'</dt>';

  foreach( $aTable as $tmp ):
    if( strpos($tmp, ':') ):
      $xx = strstr($tmp, ':', true);
      $yy = substr(strstr($tmp, ':', false), 1);
    else:
      $xx = '<b>Location Response</b>';
      $yy = $tmp;
    endif;  
      $result .= '<dt>' .$xx .'</dt>'
              .  '<dd>' .$yy .'</dt>';
  endforeach;
  $result .= '</dl>';

  return $result;    
}//


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
