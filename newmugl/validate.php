<?php


function HandleXmlError($errno, $errstr, $errfile, $errline) {
  if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::load()")>0)) {
      throw new DOMException($errstr);
  } else {
      return false;
  }
}

$xml = "<mugl><foo></mugl>";


set_error_handler('HandleXmlError');

try {
  $doc = new DOMDocument();
  //$doc->load("foo.xml");
  $doc->loadXML($xml);
  print $doc->saveXML();
} catch (DOMException $e) {
  print "caught an error\n";
  print_r($e->getMessage());
}

restore_error_handler();
