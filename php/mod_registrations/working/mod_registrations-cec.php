<?php
//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');

$document = &JFactory::getDocument();
//$document->addScript('modules'.DS.'mod_registrations'.DS.'js/diono.js');


$items = array("", "RadianGTX", "RadianRXT", "RadianR120", "RadianR100", "RadianXT", "RadianXTSL", "Radian80", "Radian80SL", "Radian65", "Radian65SL", "Monterey");
$referrers = array("", "Word of Mouth", "Retailer/Distributor", "Direct Mail", "Print Advertising", "Press Release", "Facebook", "Twitter", "Blog", "Other");
$vendors = array("", "Online Store", "Major Retail Store", "Independent Store", "Other");

// Build arrays for dropdowns
$items = array("", "RadianGTX", "RadianRXT", "RadianR120", "RadianR100", "RadianXT", "RadianXTSL", "Radian80", "Radian80SL", "Radian65", "Radian65SL", "Monterey");



$referrers = array("", "Word of Mouth", "Retailer/Distributor", "Direct Mail", "Print Advertising", "Press Release", "Facebook", "Twitter", "Blog", "Other");
$vendors = array("", "Online Store", "Major Retail Store", "Independent Store", "Other");

// Define SAP url to submit xml data to
$url = "http://exchange.diono.com:4033/uow/insert/oCustomerEquipmentCards.aspx";   

// Init xml string 
$xml = "<BOM xmlns:b1Link='http://www.zeditsolutions.com/v1.0/b1Link'><BO><AdmInfo><Object>176</Object><Version>2</Version></AdmInfo><CustomerEquipmentCards><row><CustomerCode>99999</CustomerCode><CustomerName>Consumers</CustomerName><ContactEmployeeCode>0</ContactEmployeeCode>";

if($_POST){
	$modelNum = "<ManufacturerSerialNum>" .  ($_POST["modelNum"]) . "</ManufacturerSerialNum>";
	$serialNum = "<InternalSerialNum>" . ($_POST["serialNum"]) . "</InternalSerialNum>";
	$productCode = "<ItemCode>" . ($_POST["productCode"]) . "</ItemCode>";
	$address = "<Street>" . ($_POST["address"]) . "</Street>";	
	$postalCode = "<ZipCode>" . ($_POST["postalCode"]) . "</ZipCode>";
	$city = "<City>" . ($_POST["city"]) . "</City>";
	$state = "<StateCode>" . ($_POST["state"]) . "</StateCode>";
	$firstName = "<U_cusFirstName>" .  ($_POST["firstName"]) . "</U_cusFirstName>";
	$lastName = "<U_cusLastName>" . ($_POST["lastName"]) . "</U_cusLastName>";
	$phone = "<U_phone>" .  ($_POST["phone"]) . "</U_phone>";
	$email = "<U_email>" . ($_POST["email"]) . "</U_email>";	
	$dateofMan = "<U_dateOfMan>" . ($_POST["dateofMan"]) . "</U_dateOfMan>";	
	$purDate = "<U_purDate>" .  ($_POST["purDate"]) . "</U_purDate>";
	if(!($_POST['mailList']))	{
		$mailList = "<U_list>No</U_list>";
	} else{
		$mailList = "<U_list>" . ($_POST['mailList']) . "</U_list>";
	}	
	$referrer = "<U_referrer>" . ($_POST['referrer']) . "</U_referrer>";
	$vendor = "<U_vendor>" . ($_POST['vendor']) . "</U_vendor>";
	$assisted = "<U_assisted>" . ($_POST['assisted']) . "</U_assisted>";
	$comments = "<InstallLocation>" . ($_POST['comments']) . "</InstallLocation>";
	
	$xml .= $modelNum . $serialNum . $productCode . $address . $postalCode . $city .  "<CountryCode>US</CountryCode>" . $state . $firstName . $lastName . $phone  . $email . $dateofMan .$purDate . $mailList . $referrer . $vendor . $assisted . $comments;

	$xml .= "</row></CustomerEquipmentCards></BO></BOM>";

	$output = ModRegistrationsHelper::insertXmlData($url, $xml);
	
	if (strpos($output,'<exceptionType>'))
	{

		$dom = new DOMDocument();
		$dom->loadXML($output, LIBXML_NOBLANKS);
		
		//print $dom->saveXML();

		$domxpath = new DOMXPath($dom);
		$domxpath->registerNamespace('b1Reply','http://www.zeditsolutions.com/2006/b1Reply');
		$payload = $domxpath->evaluate("b1Reply:message");
		//print $domxpath->saveXML();
		echo $payload;
		
		var_dump($payload->item(0)->nodeName);
		
		
		

		
	}	
	
	
	
	
	}
	
	


?>

<div id="RegistrationFormContainer">
		<form id = "RegistrationForm" action="#" method="POST" >
			<div>
				<label for = "productCode" type="text" class="formlabel">Product Name</label>
				<select name = "productCode" id="productCode" class="formentry required">
					<option value="16915">Radian RXT - Cobalt</option>
				</select>
			</div>
			
			<div>
				<label for = "modelNum" type="text" class="formlabel">Model Number</label>
				<input name = "modelNum" id="modelNum" type="text" class="formentry required" />
			</div>
			
			<div>
				<label for = "serialNum" type="text" class="formlabel">Serial Number</label>
				<input name = "serialNum" id="serialNum" type="text" class="formentry required" />
			</div>
			
			<div>
				<label for = "dateofMan" type="text" class="formlabel">Date of Manufacturer</label>
				<input name = "dateofMan" id="dateofMan" type="text" class="formentry date greyed" value="MM/YYYY" />
			</div>
			<div>
				<label for = "firstName" type="text" class="formlabel">First Name</label>
				<input name = "firstName" id="firstName" type="text" class="formentry required" />
			</div>	
			<div>
				<label for = "lastName" type="text" class="formlabel">Last Name</label>
				<input name = "lastName" id="lastName" type="text" class="formentry required" />
			</div>
			<div>
				<label for = "address" type="text" class="formlabel">Address</label>
				<input name = "address" id="address" type="text" class="formentry required" />
			</div>
			<div>
				<label for = "city" type="text" class="formlabel">City</label>
				<input name = "city" id="city" type="text" class="formentry required" />
			</div>
			<div>
				<label for = "state" type="text" class="formlabel">State/Province/Region</label>
				<input name = "state" id="state" type="text" class="formentry required" />
			</div>
			<div>
				<label for = "postalCode" type="text" class="formlabel">Postal Code</label>
				<input name = "postalCode" id="postalCode" type="text" class="formentry required" />
			</div>
			<div>
				<label for = "phone" type="text" class="formlabel">Phone</label>
				<input name = "phone" id="phone" type="text" class="formentry required" />
			</div>			
			<div>
				<label for = "email" type="text" class="formlabel">Email</label>
				<input name = "email" id="email" type="text" class="formentry email"/>
			</div>
			
			<div>
				<label for = "purDate" type="text" class="formlabel">Purchase Date</label>
				<input name = "purDate" id="purDate" type="text" class="formentry datefull greyed" value="MM/DD/YYYY" />
			</div>
			<div>
				<label for = "mailList" type="text" class="formlabel">I would like to be on the mailing list.</label>
				<input name = "mailList" id="mailList" type="checkbox" class="formentry required checkbox" value="Yes" />
			</div>
			
			<div>
				<label for = "referrer" type="text" class="formlabel">How did you hear about our products?</label>
				<select name = "referrer" id="referrer" class="formentry">
				<?php
				for($i=0; $i < count($referrers);$i++){
					echo "<option>" . $referrers[$i] . "</option>";
				}				
				?>
				</select>
			</div>

			<div>
				<label for = "vendor" type="text" class="formlabel">Where did you purchase our products?</label>
				<select name = "vendor" id="vendor" class="formentry">
					<?php
					for($i=0; $i<count($vendors);$i++){
						echo "<option>" . $vendors[$i] . "</option>";
					}					
					?>
				</select>
			</div>			

			<div>
				<label for = "assisted" type="text" class="formlabel">Did you recive help from a salesperson?</label>
				<select name = "assisted" id="assisted" class="formentry">
					<option value="Yes"></option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			</div>				

			<div>
				<label for = "comments" type="text" >Do you have any comments or suggestions?</label><br />
				<textarea name="comments" id="comments" class="formentry" rows="6" cols="53"></textarea>						
			</div>

			<div>
				<input type="submit" value="Register"  / >
			</div>
		
		
			<span id = "info"></span>

	 </form>
	 </div>