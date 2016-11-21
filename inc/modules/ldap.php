<?php
	$ldap = json_decode(file_get_contents("ldap.json"));
	
	function PrepLDAPTest ($hostname = NULL, $credentials = NULL, $port = NULL, $connType = NULL, $query = NULL) {
		if (!isset($credentials)) { $credentials = $ldap["ldap"]["credentials"];}
		if (!isset($credentials)) { $port = $ldap["ldap"]["defLDAPport"];}
		if (!isset($credentials)) { $query = $ldap["ldap"]["$query"];}
		return TSTldap($hostname,$credentials,$port,$connType,$query);
	}
	
	function TSTldap ($hostname, $credentials, $port, $connType, $query) {
		switch 	($connType) {
			case "ldap":
				if ($port == "true") { $port = $ldap["ldap"]["defLDAPBinding"];}; 
				break;
			case "ldaps":
				if ($port == "true") { $port = $ldap["ldap"]["defLDAPsBinding"];};
				break;
			case "gc":
				if ($port == "true") { $port = $ldap["ldap"]["defGCBinding"];};
				break;
		}
		$ds=ldap_connect($hostname, $port);
		if ($ds) {
			$r=ldap_bind($ds, explode(":", $credentials)[0], explode(":", $credentials)[1]);     // this is an "anonymous" bind, typically
			// read-only access
			// echo "Bind result is " . $r . "<br />";
			// Search surname entry
			$sr=ldap_search($ds, $ldap["ldap"]["basedn"], $query);
			//echo "Search result is " . $sr . "<br />";
			if (ldap_count_entries($ds, $sr) > 0) { return 0;}
			echo "Number of entries returned is " . ldap_count_entries($ds, $sr) . "<br />";
			echo "Closing connection";
			ldap_close($ds);
		} else {
			return "1";
		}	
	}
?>