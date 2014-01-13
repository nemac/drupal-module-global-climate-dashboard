<?php


#$x = _gcd_get_mugl('html5-graph-pnap');
#print $x;

$d = _gcd_get_mugl_dom_expanded('html5-graph-pnap');
print $d->saveXML();
