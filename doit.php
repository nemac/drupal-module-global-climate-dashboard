<?php

$rows = db_select('field_data_gcd_data_id', 't')
    ->fields('t', array('entity_id'))
    ->condition('gcd_data_id_value', 'asdf')
    ->condition('entity_id', 1, $operator="!=")
    ->execute()
    ->fetchAll();
if (!empty($rows)) {
  print "found something\n";
}



# entity_id != ...
# gcd_data_id_value = ...
# +--------------------+------------------+------+-----+---------+-------+
# | Field              | Type             | Null | Key | Default | Extra |
# +--------------------+------------------+------+-----+---------+-------+
# | entity_type        | varchar(128)     | NO   | PRI |         |       |
# | bundle             | varchar(128)     | NO   | MUL |         |       |
# | deleted            | tinyint(4)       | NO   | PRI | 0       |       |
# | entity_id          | int(10) unsigned | NO   | PRI | NULL    |       |
# | revision_id        | int(10) unsigned | YES  | MUL | NULL    |       |
# | language           | varchar(32)      | NO   | PRI |         |       |
# | delta              | int(10) unsigned | NO   | PRI | NULL    |       |
# | gcd_data_id_value  | varchar(255)     | YES  |     | NULL    |       |
# | gcd_data_id_format | varchar(255)     | YES  | MUL | NULL    |       |
# +--------------------+------------------+------+-----+---------+-------+
# 9 rows in set (0.00 sec)
# 
# describe field_data_gcd_data_id ;
