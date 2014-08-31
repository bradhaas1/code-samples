<?php

  // Search for an Item Internal Testing URL
  //$url = "http://sap-server:4033/object/display/oItems/10619.aspx";
                                  
  // Search for an Item External Production URL
  //$url = "http://exchange.diono.com:4033/object/display/oItems/10619.aspx";
  
  // Get Prices for all Items Marked as Sales Items Testing URL
  //$url = "http://sap-server:4033/query/devSalesItemPrices.aspx";

  // Get Prices for all Items Marked as Sales Items Production URL
  $url = "http://exchange.diono.com:4033/query/devSalesItemPrices.aspx";
  
  $urlData = getUrlData($url);  
  //$xml = simplexml_load_file('sap-output.xml');
  $xml = simplexml_load_string($urlData);
  
  //var_dump($xml);  
  //echo $xml->asXML();
  //$output = $xml->asXML('itemData.xml');

// **************** LOADING ITEM PROPERTIES WITH MULTIPLE NAMESPACES ***********************/
  $xml->registerXPathNamespace('b1Reply','http://www.zeditsolutions.com/2006/b1Reply');
  $xml->registerXPathNamespace('b1Link','http://www.zeditsolutions.com/v1.0/b1Link');

  $elements = $xml->xpath("b1Reply:payload/querydata/data/row");
  //print_r($elements);

  $itemXML = getSapData($elements);  
  $items = json_decode(json_encode((array) ($elements)), 1);
  
  
  //print_r($items);
//*********************** CONNECTING TO VIRTUEMART ******************///

// Connecting, selecting database

$con = mysql_connect("localhost","diono_dev","CH1ftBN94v;q");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
$db_selected = mysql_select_db('diono_dev', $con);
if (!$db_selected) {
    die ('Can\'t use diono_dev : ' . mysql_error());
}



//************************ UPDATING VIRTUEMART *******************///

$message = "<table cellpadding='3px' cellspacing='3px'>";
$sqlQtyUpdateResults = '';


//try{
  foreach($items as $key => $value ){
    $itemcode = $value['ITM1_ItemCode'];
    $itemname = $value['OITM_ItemName'];
    $itemprice = $value['ITM1_Price'];
    $onhand = $value['OITM_OnHand'];
    $pcavailability = $value['OITM_U_PC_Availability'];
    

/*************************************** UPDATE PUBLISH QUANTITY ON HAND ***/

  $sqlQuantityUpdate = "UPDATE jos_vm_product SET product_in_stock = '$onhand' WHERE jos_vm_product.product_sku = '$itemcode'";
  $quantityresult = mysql_query($sqlQuantityUpdate) or die ('Query failed:' . mysqli_error());

/*************************************** UPDATE PUBLISH STATUS ***/

  if( $onhand >= 10){
    
    $sqlSetPublishStatus = "UPDATE jos_vm_product SET product_publish = 'Y'  WHERE jos_vm_product.product_sku = '$itemcode'";            
  }
  else{
    $sqlSetPublishStatus = "UPDATE jos_vm_product SET product_publish = 'N'  WHERE jos_vm_product.product_sku = '$itemcode'";
    //echo $itemname . " " . $itemcode . " not trigerred<br />";
  }
  
    $publishresult = mysql_query($sqlSetPublishStatus) or die('Query failed:' . mysql_error());  
  
/*************************************** UPDATE PRICE ***/

  $sqlPriceUpdate = "UPDATE jos_vm_product_price INNER JOIN jos_vm_product ON jos_vm_product.product_id = jos_vm_product_price.product_id SET product_price = '$itemprice' WHERE jos_vm_product_price.product_id = jos_vm_product.product_id AND jos_vm_product.product_sku = '$itemcode'";
  
  $selectresult = mysql_query($sqlPriceUpdate) or die('Query failed:' . mysql_error());
    
  $message .= "<tr><td>Updating: " . $itemcode . "</td><td>" . $itemname . "</td><td>" .  $itemprice. "</td><td>" . $onhand . "</td><td>" . $pcavailability . "</td></tr><tr><td colspan='6'>" . $sqlSetPublishStatus . "</td></tr>";
   
  $message .= "<tr><td colspan='5'>" . $sqlPriceUpdate . "</td></tr>";
   
  $message .= "<tr><td colspan='5'>" . $sqlSetPublishStatus . "</td></tr>";
  
    $message .= "<tr style='background-color: #a5acb0;'><td colspan='5'>   </td></tr>";
   

 }
 

 
$message .= "</table>";
echo $message;
//notifyEmail($message);

//********************************** FUNCTIONs ********************************** //

function notifyEmail($msg){  
  $headers = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
  $headers .= "From: Sap Updates<sapupdates@dionolcom>\r\n";
  

//bgcolor=\"#DCEEFC\"
  
  $htmlmsg = "<html><body>";
  
  $htmlmsg .= $msg;
  
  $htmlmsg .= "</body></html>";
   //end of message 

  $to = "brad.haas@diono.com";
  $subject = "SAP Virtuemart Update Log";
  $mailSent = mail($to, $subject, $htmlmsg, $headers);
  
  

  
}
// CONNECT TO B1 GET DATA
function getUrlData($sapurl){
  
  $username = 'test';
  $password = 'test';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $sapurl);
  //curl_setopt($ch, CURLOPT_HTTPGET, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/4");    
  $output = curl_exec($ch);
 
  curl_close($ch);    
  return $output;
}

// CONVERT DATA FROM B1 INTO ARRAY
function getSapData($array){ 
  
  foreach($array as $key=>$value){
    //echo ($key . " " . $value);
    foreach($value as $itemname=>$itemvalue){
        array_push($array, $itemvalue);
    }
  }
  return $array;
}
  
// CREATE ARRAY FROM MYSQL RESULT
function db_result_array($result, $key_column = null) {
    for ($array = array(); $row = mysql_fetch_assoc($result); isset($row[$key_column]) ? $array[$row[$key_column]] = $row : $array[] = $row);
    return $array;
}
  
?>