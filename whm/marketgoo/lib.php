<?php

/*
 *                         _        _
 *                        | |      | |
 *    _ __ ___   __ _ _ __| | _____| |_ __ _  ___   ___
 *   | '_ ` _ \ / _` | '__| |/ / _ \ __/ _` |/ _ \ / _ \
 *   | | | | | | (_| | |  |   <  __/ || (_| | (_) | (_) |
 *   |_| |_| |_|\__,_|_|  |_|\_\___|\__\__, |\___/ \___/
 *                                      __/ |
 *   MarketGoo Plug-in for cPanel      |___/
 *
 */

define("MKTGOO_PARTNERID_FILE", ".marketgoo_partner_id");

//-----------------------------------------------------------------------------
function generate_partnerid()
{
    $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $base  = strlen($index);

    mt_srand(microtime(true) * 100000 + memory_get_usage(true));
	$in = mt_rand().microtime(true);

	$out = "";
    for ($t = floor(log($in, $base)); $t >= 0; $t--) {
        $bcp = pow($base, $t);
        $a   = floor($in / $bcp) % $base;
        $out = $out.substr($index, $a, 1);
        $in  = $in - ($a * $bcp);
    }
    return substr($out, 0, 10);
}

//-----------------------------------------------------------------------------
function get_partnerid()
{
	$usr = posix_getpwnam($_SERVER["REMOTE_USER"]);
	if (!file_exists($usr["dir"]."/".MKTGOO_PARTNERID_FILE)) {
		
		echo "Generating new ID\n";
		print_r($usr);
		
		$new_partner_id = generate_partnerid();
		file_put_contents($usr["dir"]."/".MKTGOO_PARTNERID_FILE, $new_partner_id);
		return $new_partner_id;
	} else {

		echo "Existing ID\n";
		print_r($usr);

		return file_get_contents($usr["dir"]."/".MKTGOO_PARTNERID_FILE);
	}
}

//-----------------------------------------------------------------------------
function get_host_partnerid()
{
	$usr = posix_getpwuid(0);
	if (!file_exists($usr["dir"]."/".MKTGOO_PARTNERID_FILE)) {

		echo "Generating new ID (HOST)\n";
		print_r($usr);

		$new_partner_id = generate_partnerid();
		file_put_contents($usr["dir"]."/".MKTGOO_PARTNERID_FILE, $new_partner_id);
		return $new_partner_id;
	} else {
		echo "Existing ID (HOST)\n";
		print_r($usr);

		return file_get_contents($usr["dir"]."/".MKTGOO_PARTNERID_FILE);
	}
}


?>