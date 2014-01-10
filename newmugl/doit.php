<?php

$doc = new DOMDocument();

$doc->load("graphs/aoi-oak1.xml");
# t/global-climate-dashboard-data/data/

#          <dataValuesRef id="climate_vari_AO.csv"/>
#print $doc->saveXML();

function toXML($domElement) {
    return $domElement->ownerDocument->saveXML($domElement);
}

$dataValuesRefElements = $doc->getElementsByTagName("dataValuesRef");
$dataValuesRefElement = $dataValuesRefElements->item(0);

$id = $dataValuesRefElement->getAttribute("id");

$datafile = "data/" . $id;

$contents = file_get_contents($datafile);

$textValues = $doc->createTextNode($contents);

$parentElement = $dataValuesRefElement->parentNode;

$parentElement->replaceChild($textValues, $dataValuesRefElement);

print( $doc->saveXML() );


#$valuesElement = $valuesElements->item(0);
#$dataElement = $valuesElement->parentNode;
#
#$newvalues = $doc->createElement("values", "1,2,3,4,5,6");
#$p->replaceChild($newvalues, $d);
#
##print( toXML($p) );
