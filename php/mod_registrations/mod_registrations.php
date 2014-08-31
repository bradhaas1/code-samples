<?php
//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');

// RECAPTCHA file and vars

require_once(dirname(__FILE__).DS.'includes/recaptchalib.php');
$publickey = '6LfmIscSAAAAAKvtzgwrpR7qxvfmPwE1OXsR_SZd';
$privatekey = '6LfmIscSAAAAANGUQtkpm41jtEVy26POjq3tcYKk';

$document = &JFactory::getDocument();
$document->addScript('modules'.DS.'mod_registrations'.DS.'js/diono.js');

$items = array("", "RadianGTX", "RadianRXT", "RadianR120", "RadianR100", "RadianXT", "RadianXTSL", "Radian80", "Radian80SL", "Radian65", "Radian65SL", "Monterey");
$referrers = array("", "Word of Mouth", "Retailer/Distributor", "Direct Mail", "Print Advertising", "Press Release", "Facebook", "Twitter", "Blog", "Other");
$vendors = array("", "Online Store", "Major Retail Store", "Independent Store", "Other");

// Build arrays for dropdowns
$items = array("", "RadianGTX", "RadianRXT", "RadianR120", "RadianR100", "RadianXT", "RadianXTSL", "Radian80", "Radian80SL", "Radian65", "Radian65SL", "Monterey");
$referrers = array("", "Word of Mouth", "Retailer/Distributor", "Direct Mail", "Print Advertising", "Press Release", "Facebook", "Twitter", "Blog", "Other");

// Define SAP url to submit xml data to
$url = "http://exchange.diono.com:4033/CPI_Test_US/udo/Registrations.aspx";

// Init xml string 
$xml = "<UDO><Registrations>";

	// List expected fields
	$expected = array('prodName', 'modelNum', 'serialNum', 'dateofMan', 'firstName', 'lastName', 'address1', 'address2', 'city', 'state', 'postalCode', 'phone', 'email', 'purDate', 'mailList', 'referrer', 'vendor', 'assisted', 'comments');
	
	// List required fields
	$required = array('prodName', 'modelNum', 'serialNum', 'dateofMan', 'firstName', 'lastName', 'address1', 'city', 'state', 'postalCode', 'phone', 'email', 'purDate');
	
if($_POST){

	foreach($_POST as $key => $value){
		
		// assign to temporary variable and strip whitespace if not an array
		$temp = is_array($value) ? $value : trim($value);
		
		//if empty and required, add to missing array
		if(empty($temp) && in_array($key, $required)){
			$missing[] = $key;			
		}
		elseif (in_array($key, $expected)){
			//otherwise assign to a variable of the same name as key
			${$key} = $temp;		
		}		
	}
	
	$resp = recaptcha_check_answer ($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
	 
	if (!$resp->is_valid) {
				// What happens when the CAPTCHA was entered incorrectly
				$errors['recaptcha'] = true;
				//die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")");
	}
	
	
	if(!$missing && !$errors){
	$modelNum = "<U_modelNum>" .  ($_POST["modelNum"]) . "</U_modelNum>";
	$serialNum = "<U_serialNum>" . ($_POST["serialNum"]) . "</U_serialNum>";
	$prodName = "<U_productName>" . ($_POST["prodName"]) . "</U_productName>";
	$address1 = "<U_address1>" . ($_POST["address1"]) . "</U_address1>";
	$address2 = "<U_address2>" . ($_POST["address2"]) . "</U_address2>";
	$postalCode = "<U_postalCode>" . ($_POST["postalCode"]) . "</U_postalCode>";
	$city = "<U_city>" . ($_POST["city"]) . "</U_city>";
	$state = "<U_state>" . ($_POST["state"]) . "</U_state>";
	$firstName = "<U_firstName>" .  ($_POST["firstName"]) . "</U_firstName>";
	$lastName = "<U_lastName>" . ($_POST["lastName"]) . "</U_lastName>";
	$phone = "<U_phone>" .  ($_POST["phone"]) . "</U_phone>";
	$email = "<U_email>" . ($_POST["email"]) . "</U_email>";	
	$dateofMan = "<U_dateofMan>" . ($_POST["dateofMan"]) . "</U_dateofMan>";	
	$purDate = "<U_purDate>" .  ($_POST["purDate"]) . "</U_purDate>";
	if(!($_POST['mailList']))	{
		$mailList = "<U_mailList>No</U_mailList>";
	} else{		$mailList = "<U_mailList>Yes</U_mailList>";	}	
	$referrer = "<U_referrer>" . ($_POST['referrer']) . "</U_referrer>";
	$vendor = "<U_vendor>" . ($_POST['vendor']) . "</U_vendor>";
	$assisted = "<U_assisted>" . ($_POST['assisted']) . "</U_assisted>";
	$comments = "<U_comments>" . ($_POST['comments']) . "</U_comments>";
	
	$xml .= $modelNum . $serialNum . $prodName . $dateofMan . $purDate . $firstName . $lastName . $address1 . $address2  . $city . $state . $postalCode . $phone . $email . $mailList . $referrer . $vendor . $assisted . $comments;
	
	$xml .= "</Registrations></UDO>";
	
	//echo $xml;	
	
			$output = ModRegistrationsHelper::insertXmlData($url, $xml);
			
			if (strpos($output,'<exceptionType>'))
			{
				$adminNotify =  ModRegistrationsHelper::sendMail($xml, $output);

				//PREFILLING FORM FIELDS:
				//You can prefill any form field by passing in URL encoded values via GET. For example, in your intranet you may add a link
				//to HelpSpot that looks like this:
				//http://www.example.com/support/index.php?pg=request&fullname=Bob+Smith&sUserId=453232&sEmail=bsmith%40example.com&additional=SAP+ID%3A844883
				
				$hsFirstName = ($_POST["firstName"]);
				$hsLastName	= ($_POST["lastName"]);
				$hsfullname = $hsFirstName.'+'.$hsLastName;
				$hsEmail = ($_POST["email"]);
				$hsPhone = ($_POST["phone"]);
				$hsComments = "There was a problem with your registration, please submit this form to open up a support ticket and a customer service representative will contact you within 24 business hours.";

				$mainframe->redirect('http://server.diono.com/helpspot/index.php?pg=request&fullname=' . $hsfullname . '&sEmail=' . $hsEmail . '&sPhone=' . $hsPhone . '&simple=' . $hsComments);				
			}			
			
			elseif(strpos($output, 'success')){
			$response ="";
			$response = "Success! Thank you for registering";	
			}
	}	
}
?>
	
<?php
	if($missing || $errors){ ?>		
			<p class="error">Please fix the items indictated</p>			
		<?php }
		print_r($errors);		
		?>
		
<?php
	if($response) {
		echo "Message goes here";	
		}
?>
		
		
<div id="RegistrationFormContainer">
		<form id = "RegistrationForm" action="#" method="POST" >
			<div>
				<label for = "prodName" type="text" class="formlabel">Product Name</label>
				<select name = "prodName" id="prodName" class="formentry required">
				<?php
					for($i=0; $i < count($items);$i++){
						echo "<option>" . $items[$i] . "</option>";
					}				
				?>
				</select>				
				<?php
					if($missing && in_array('prodName', $missing)){ ?>
						<span class="error">Please enter your product's name.</span>
				<?php } ?>
												
			</div>
			
			<div>
				<label for = "modelNum" type="text" class="formlabel">Model Number</label>
				<input name = "modelNum" id="modelNum" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($modelNum, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />				   
				<?php
					if($missing && in_array('modelNum', $missing)){ ?>
						<span class="error">Please enter your product's model number.</span>
				<?php } ?>
			</div>
			
			<div>
				<label for = "serialNum" type="text" class="formlabel">Serial Number</label>
				<input name = "serialNum" id="serialNum" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($serialNum, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('serialNum', $missing)){ ?>
						<span class="error">Please enter serial number.</span>
				<?php } ?>
			</div>
			
			<div>
				<label for = "dateofMan" type="text" class="formlabel">Date of Manufacturer</label>
				<input name = "dateofMan" id="dateofMan" type="text" class="formentry date greyed" value="MM/YY"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($dateofMan, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('dateofMan', $missing)){ ?>
						<span class="error">Please enter your product's date of manufacturer in MM/YY format.</span>
				<?php } ?>
			</div>
			<div>
				<label for = "firstName" type="text" class="formlabel">First Name</label>
				<input name = "firstName" id="firstName" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($firstName, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('firstName', $missing)){ ?>
						<span class="error">Please enter your first name.</span>
				<?php } ?>	
			</div>	
			<div>
				<label for = "lastName" type="text" class="formlabel">Last Name</label>
				<input name = "lastName" id="lastName" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($lastName, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('lastName', $missing)){ ?>
						<span class="error">Please enter your last name.</span>
				<?php } ?>	
			</div>
			<div>
				<label for = "address1" type="text" class="formlabel">Address1</label>
				<input name = "address1" id="address2" type="text" class="formentry"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($address1, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('address1', $missing)){ ?>
						<span class="error">Please enter address.</span>
				<?php } ?>	
			</div>
			<div>
				<label for = "address2" type="text" class="formlabel">Address2</label>
				<input name = "address2" id="address2" type="text" class="formentry"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($address2, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
			</div>
			<div>
				<label for = "city" type="text" class="formlabel">City</label>
				<input name = "city" id="city" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($city, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('city', $missing)){ ?>
						<span class="error">Please enter city.</span>
				<?php } ?>	
			</div>
			<div>
				<label for = "state" type="text" class="formlabel">State/Province/Region</label>
				<input name = "state" id="state" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($state, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('state', $missing)){ ?>
						<span class="error">Please enter your state, province or region.</span>
				<?php } ?>	
			</div>
			<div>
				<label for = "postalCode" type="text" class="formlabel">Postal Code</label>
				<input name = "postalCode" id="postalCode" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($postalCode, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('postalCode', $missing)){ ?>
						<span class="error">Please enter your postal code.</span>
				<?php } ?>
				
			</div>
			<div>
				<label for = "phone" type="text" class="formlabel">Phone</label>
				<input name = "phone" id="phone" type="text" class="formentry required"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($phone, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
			</div>			
			<div>
				<label for = "email" type="text" class="formlabel">Email</label>
				<input name = "email" id="email" type="text" class="formentry email"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
			</div>
			
			<div>
				<label for = "purDate" type="text" class="formlabel">Purchase Date</label>
				<input name = "purDate" id="purDate" type="text" class="formentry datefull greyed" value="MM/DD/YYYY"
				<?php if ($missing || $errors){
					echo 'value="' . htmlentities($purDate, ENT_COMPAT, 'UTF-8') . '"';
				 } ?> />
				<?php
					if($missing && in_array('purDate', $missing)){ ?>
						<span class="error">Please enter your purchase date.</span>
				<?php } ?>
			</div>
			<div>
				<label for = "mailList" type="text" class="formlabel">I would like to be on the mailing list.</label>
				<input name = "mailList" id="mailList" type="checkbox" class="formentry checkbox" />
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
				<label for = "assisted" type="text" class="formlabel">Did you receive help from a salesperson?</label>
				<select name = "assisted" id="assisted" class="formentry">
					<option value=""></option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			</div>				

			<div>
				<label for = "comments" type="text" >Do you have any comments or suggestions?</label><br />
				<textarea name="comments" id="comments" class="formentry" rows="6" cols="53"></textarea>						
			</div>
			<?php if (isset($errors['recaptcha'])){ ?>
					<span class="error">The values didn't match, please try again.</span>
			<?php } ?>
			
			<?php
				echo recaptcha_get_html($publickey); 
			?>

			<div>
				<input type="submit" value="Register"  / >
			</div>
			<span id = "info"></span>
	 </form>
	 </div>