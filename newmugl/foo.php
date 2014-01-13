<?php


$msg = "DOMDocument::loadXML(): Opening and ending tag mismatch: bar line 2 and mugl in Entity, line: 3";

print preg_replace('/in Entity, .*$/', '', 
             preg_replace('/^DOMDocument::loadXML\(\):\s+/', '',
                          $msg)) . "\n";

