<?php defined('_JEXEC') or die('Direct access to this location is not allowed.');

class ModPartnerCenterSpashHelper
{

}

function writerows($csvcontents){  
  echo "<td class='sku'>" . $csvcontents[0] . "</td>";
  echo "<td class='name'>" . $csvcontents[1] . "</td>";
  echo "<td class='date'>" . $csvcontents[2] . "</td>";
  echo "<td class='status'>" . $csvcontents[3] . "</td>";  
}
function readcsv($filename, $header=false) {
$handle = fopen($filename, "r");

echo "<table class='stock'>";
    echo "<tr class='caption oos'><td>Out of Stock</td></tr>";
    echo "<tr class='caption dis'><td>Discontinued</td></tr>";
echo "</table>";

echo "<table class='stock'>";
  
//display header row if true
if ($header) {
    $csvcontents = fgetcsv($handle);
    echo "<tr>";
    foreach ($csvcontents as $headercolumn) {
        echo "<th>" . $headercolumn . "</th>";
    }
    echo '</tr>';
}
// displaying contents
while ($csvcontents = fgetcsv($handle)) {

if (($csvcontents[0] == null) && ($csvcontents[1] == null) && ($csvcontents[2] == null) && ($csvcontents[3] == null) ){
    echo null;
}

elseif (strpos($csvcontents[1], "Out Of Stock") !== false) {
    echo null;
}

elseif(strpos($csvcontents[1], "Discontinued") !== false){
    echo null;
}


elseif(strpos($csvcontents[0], "SKU") !== false){
    echo "<tr class='caption header'>";
    $csvcontents[0] = "SKU";
    echo writerows($csvcontents);
}

elseif($csvcontents[1] == null){
    echo "<tr class='caption category'>";
    echo "<td colspan='4'>" . $csvcontents[0] . "</td>";
}

else{
    $oos = "Out of Stock";
    $dis = "Discontinued";

    if(strtolower($csvcontents[3]) == "discontinued"){
        //$csvcontents[3] = $dis;
        $csvcontents[3] = $dis;
        echo "<tr class='dis'>";
    }

    elseif(strpos(strtolower($csvcontents[2]), "out of stock") !== false){
        $csvcontents[2] = $oos;
        echo "<tr class='oos'>";
    }

    echo writerows($csvcontents);
}
  echo "</tr>";
}

echo "</table>";
fclose($handle);
}
?>