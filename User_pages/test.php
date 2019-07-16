<?php
// var_dump(openssl_get_cert_locations()); 
// echo "openssl.cafile: ", ini_get('openssl.cafile'), "\n";
// echo "curl.cainfo: ", ini_get('curl.cainfo'), "\n";
// die;
class SimpleClass {
	public $a = array();
}
$b = new SimpleClass();
array_push($b->a, 1,2,3);
for($i=0; $i < count($b->a); $i++)
	echo $b->a[$i].'<br>';
?>
