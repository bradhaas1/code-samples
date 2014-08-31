<?php

$xml='<?xml version="1.0" encoding="UTF-8"?><b1Reply:response requestType="schema" xmlns:b1Reply="http://www.zeditsolutions.com/2006/b1Reply"><b1Reply:uri>/uow/insert/oCustomerEquipmentCards.aspx</b1Reply:uri><b1Reply:status>ERROR</b1Reply:status><b1Reply:payload schemaDigest=""><message>-5002 - Unique serial number must not be blank </message><exceptionType>System.Exception</exceptionType><stackTrace> at B1ServerEnabler.Processor.UOWRequest.ProcessRequest() at B1ServerEnabler.B1EnablerModule.application_BeginRequest(Object sender, EventArgs e)</stackTrace></b1Reply:payload><b1Reply:error><message>-5002 - Unique serial number must not be blank </message><exceptionType>System.Exception</exceptionType><stackTrace> at B1ServerEnabler.Processor.UOWRequest.ProcessRequest() at B1ServerEnabler.B1EnablerModule.application_BeginRequest(Object sender, EventArgs e)</stackTrace></b1Reply:error></b1Reply:response>';

$dom = new DOMDocument();
$dom->loadXML($xml);

$root = $dom->documentElement;
$firstsibling = $root->firstChild;
$secondsibling = $firstsibling->nextSibling;
$thirdsibling = $secondsibling->nextSibling;
$payloadmessage = $thirdsibling->firstChild;
$error =  $payloadmessage->nodeValue;

print $error;

echo "<hr />";

//if ($root->hasChildNodes()){
//	$children = $root->childNodes;
//	foreach($children as $node){
//	if ($node->hasChildNodes()){
//				$childrens = $node->childNodes;
				
//				$message = $childrens->getElementsByTagName('message');
//				print $message->nodeName->item(0);
//				print "<br />";			
				
//				foreach($childrens as $nodes){
//					print $nodes->nodeName . " Type: " . $nodes->nodeType . " Value: " . $nodes->nodeValue . "<br />";
//					//if($nodes->nodeName = 'message'){
//					//	$message = $nodes->nodeValue;
//					//	print $message;
//					//}
//				}
	
//	}

//	//print $node->nodeName."\n";
//	}
//}

//echo "<hr />";

//$payload = $root->getElementsByTagNameNS('b1Reply:payload', 'message');

//print $payload->nodeName;





//$xpath = new DOMXPath($dom); 
//$rootNamespace = $dom->lookupNamespaceUri($dom->namespaceURI); 
//$xpath->registerNamespace('x', $rootNamespace); 

//vardump($xpath);

//$elementList = $xpath->query('//x:payload');

//$node = $dom->getElementsByTagName('message');
//vardump($node);


//print_r($elementList);
//vardump($elementList);



  //$xml->registerXPathNamespace('b1Reply','http://www.zeditsolutions.com/2006/b1Reply');
  //$xml->registerXPathNamespace('b1Link','http://www.zeditsolutions.com/v1.0/b1Link');

		//$elements = $xml->xpath("b1Reply:payload/querydata/data/row");
  //
  //$elements = $xml->xpath("b1Reply:payload/message");

//$xml = new DomDocument();
//$xml->load('data.xml');


//var_dump($xml);  
//echo $xml->asXML();
//$output = $xml->asXML('itemData.xml');

// **************** LOADING ITEM PROPERTIES WITH MULTIPLE NAMESPACES ***********************/


//echo "<hr />";

//$sxml = simplexml_load_string($xml);


//$sxml->registerXPathNamespace('b1Reply','http://www.zeditsolutions.com/2006/b1Reply');
//$elements = $sxml->xpath("b1Reply:payload");

//$items = json_decode(json_encode((array) ($elements)), 1);


////print_r($elements);

//echo "<hr />";

////$message = $elements[0];
////echo $message;

//vardump($elements);


//foreach($elements->payload as $users){
//	echo $users['message'];
//}



//foreach ($elements as $key => $value) {

//	if(is_array($key)){
//		echo "Yes";
//	}	
//		//foreach($key as $messages => $values){
//		//echo "Array!";
//		//	echo $values;
//		//}
//		else {echo "No!";}

	
//}






?>