<?php

  /*
   * Quick summary of the PHP DOM api as used in this file:
   *
   * The DOM api represents an XML document through instances of various
   * classes.  The full API is documented at
   * 
   *     http://us1.php.net/manual/en/intro.dom.php,
   *  
   * but the gist of it is that an XML document consists of a tree of
   * the following:
   *  
   *     DOMNode objects: the base class for all nodes in the tree
   *  
   *     DOMElement: subclass of DOMNode for storing XML element (tag)
   *         nodes
   *  
   *     DOMDocument object: subclass of DOMNode corresponding to the
   *         entire document.  Note that the DOMDocuemnt object is not
   *         the actual root node of the element tree; that is available
   *         in the $doc->documentElement property, which is a reference
   *         to a DOMElement object.
   *  
   * (There are other DOMNode subclasses for other things, such as
   * DOMCharacterData for text, and DOMComment for XML comments.)
   *  
   * DOMNodes contain a reference to the DOMDocument they belong to.
   * If you want to insert nodes from one document into another, as
   * happens below in the code that "expands" tabRef, graphRef, and
   * dataValuesRef nodes, you have to use the $doc->importNode
   * method to create copies of the nodes that have the correct
   * DOMDocument reference.  (Otherwise you get a cryptic "Wrong Document"
   * error.)
   */

  /*
   * _gcd_to_xml() is just for debuging; it takes an abitrary DOMElement and converts
   * it to an XML string.  (Note that the saveXML() method only exists on
   * the DOMDocuemnt class, not DOMNode or DOMElement; to convert a subtree
   * of an entire document, pass the DOMElement at its root to this function.
   */
function _gcd_to_xml($domElement) {
  return $domElement->ownerDocument->saveXML($domElement);
}

/*
 * Take a MUGL Id string $id, and return a string containing the gcd_mugl field
 * value of the gcd_mugl node...
 */
function _gcd_get_mugl($id) {
  $query = db_select('field_data_gcd_mugl',  'v');
  $query->join('field_data_gcd_mugl_id', 'i', 'i.entity_id=v.entity_id');
  $query->fields('v', array('gcd_mugl_value'));
  $query->condition('i.gcd_mugl_id_value', $id);
  return $query->execute()->fetchField();
}

function _gcd_get_data($id) {
  $query = db_select('field_data_gcd_data',  'd');
  $query->join('field_data_gcd_data_id', 'i', 'i.entity_id=d.entity_id');
  $query->fields('d', array('gcd_data_value'));
  $query->condition('i.gcd_data_id_value', $id);
  $data = $query->execute()->fetchField();
  // Remove control-M characters at EOL; somehow they creep in, either
  // because Drupal or MySQL is inserting them, perhaps due to some
  // settings on the gcd_data field or table column that I didn't know
  // to unset.  In any case, the following str_replace gets rid of them.
  return str_ireplace("\x0D", "", $data);
}

function _gcd_get_mugl_dom($id) {
  $mugl = _gcd_get_mugl($id);
  $doc = new DOMDocument();
  $doc->resolveExternals = true;
  $doc->substituteEntities = false;
  $doc->loadXML($mugl);
  return $doc;
}

function _gcd_get_mugl_dom_expanded($id) {
  $doc = _gcd_get_mugl_dom($id);

  $refElements = array();
  foreach (dnl2array($doc->getElementsByTagName("tabRef")) as $e) {
    $refElements[] = array(
      'type' => 'tab',
      'element' => $e
    );
  }
  foreach (dnl2array($doc->getElementsByTagName("graphRef")) as $e) {
    $refElements[] = array(
      'type' => 'graph',
      'element' => $e
    );
  }
  foreach (dnl2array($doc->getElementsByTagName("dataValuesRef")) as $e) {
    $refElements[] = array(
      'type' => 'dataValues',
      'element' => $e
    );
  }

  for ($i=0; $i<count($refElements); ++$i) {
    $e = $refElements[$i]['element'];
    $type = $refElements[$i]['type'];
    $id = $e->getAttribute("id");
    if ($type == "dataValues") {
      $data = _gcd_get_data($id);
      $childRoot = $doc->createTextNode($data);
    } else {
      $childDoc = _gcd_get_mugl_dom_expanded($id);
      $childRoot = $childDoc->documentElement;
    }
    $parentElement = $e->parentNode;
    $parentElement->replaceChild($doc->importNode($childRoot, true), $e);
  }

  return $doc;
}

$d = _gcd_get_mugl_dom_expanded('tab1');
print $d->saveXML();


#print_r( _gcd_get_mugl_dom_expanded('tab1')->saveXML() );

### $dataValuesRefElement = $dataValuesRefElements->item(0);
### 
### $id = $dataValuesRefElement->getAttribute("id");
### 
### $datafile = "data/" . $id;
### 
### $contents = file_get_contents($datafile);
### 
### $textValues = $doc->createTextNode($contents);
### 
### $parentElement = $dataValuesRefElement->parentNode;
### 
### $parentElement->replaceChild($textValues, $dataValuesRefElement);
### 
### 
### 
### 
### 
###   return _gcd_expand($mugl);
### }
### 
### function _gcd_expand($mugl) {
###   return $mugl;
### }


### $query = db_select('node', 'n');
### $table_alias = $query->join('users', 'u', 'n.uid = u.uid AND u.uid = :uid', array(':uid' => 5));
### 
### 
### 
### function _gcd_get_mugl($id) {
### }
### 
### function _gcd_get_mugl_expanded($id) {
### }
### 
### function _expand_mugl($id) {
###   set_error_handler('_gcd_handlexmlerror');
###   $answer = _deref($xml);
###   restore_error_handler();
###   return $answer;
### }
### 
### function deref(
### 
### 
### 
###   try {
###     $doc = new DOMDocument();
###     $doc->loadXML($xml);
###   } catch (DOMException $e) {
###     $err = (
###       "The MUGL XML is not well-formed:\n" .
###       preg_replace('/in Entity, .*$/', '', 
###                    preg_replace('/^DOMDocument::loadXML\(\):\s+/', '',
###                                 $e->getMessage()))
###     );
###   }
### 
### 
### 
### $doc = new DOMDocument();
### 
### $doc->load("graphs/aoi-oak1.xml");
### # t/global-climate-dashboard-data/data/
### 
### #          <dataValuesRef id="climate_vari_AO.csv"/>
### #print $doc->saveXML();
### 
### function toXML($domElement) {
###     return $domElement->ownerDocument->saveXML($domElement);
### }
### 
### $dataValuesRefElements = $doc->getElementsByTagName("dataValuesRef");
### $dataValuesRefElement = $dataValuesRefElements->item(0);
### 
### $id = $dataValuesRefElement->getAttribute("id");
### 
### $datafile = "data/" . $id;
### 
### $contents = file_get_contents($datafile);
### 
### $textValues = $doc->createTextNode($contents);
### 
### $parentElement = $dataValuesRefElement->parentNode;
### 
### $parentElement->replaceChild($textValues, $dataValuesRefElement);
### 
### print( $doc->saveXML() );
### 
### 
### #$valuesElement = $valuesElements->item(0);
### #$dataElement = $valuesElement->parentNode;
### #
### #$newvalues = $doc->createElement("values", "1,2,3,4,5,6");
### #$p->replaceChild($newvalues, $d);
### #
### ##print( toXML($p) );
