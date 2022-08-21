<?php
/**
* Function list:
* - getIpAddr()
* - get_web_page()
* - getFailedAttempts()
* - getFailedAttemptsByUser()
* - getFailedAttemptsInfoByUser()
* - deleteFailedAttempts()
* - checkIP()
* - compareMilliseconds()
* - valid_email()
* - encrypt()
* - decrypt()
* - randomstr()
* - getRandomBytes()
* - generatePassword()
* - imageUrl()
* - random_str()
* - getGeo()
* - state_abreviation_for()
*/

/**
 * Gets a users IP Address.
 *
 * @return	string:null	$ipAddr	The visitor's IP address.
 */

function getIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
}

/**
 * cURL the contents of a webpage.
 *
 * @param	string		$url 			The desired URL.
 *
 * @return	string:null	$total_count	Total number of attempts.
 */

function get_web_page($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false,        // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_MAXREDIRS => 10,        // stop after 10 redirects
        CURLOPT_ENCODING => "",         // handle compressed
        CURLOPT_USERAGENT => "test",    // name of client
        CURLOPT_AUTOREFERER => true,    // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 500,  // time-out on connect
        CURLOPT_TIMEOUT => 500,         // time-out on response
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

/**
 * Checks how many failed attempts any user on a specific network has.
 *
 * @param	MySQLi Object	$link						An object representing the connection to the MySQL server. 	 
 * @param	string			$ip_address 				A user's IP address.
 *
 * @return	string			$total_count : null 		Total number of attempts.
 */

function getFailedAttempts($link, $ip_address)
{
    $sql = "SELECT COUNT(*) AS total_count from failed_login_attempts where ip = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_ip);
        // Set parameters
        $param_ip = $ip_address;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $check_login_row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    return !empty($check_login_row['total_count']) ? $check_login_row['total_count'] : null;
}

/**
 * Checks how many failed login attempts any user on a specific network has.
 *
 * @param	MySQLi Object	$link						Object representing the connection to the MySQL server. 	 
 * @param	string			$ip_address 				A user's IP address.
 *
 * @return	string			$total_count : null 		Total number of attempts.
 */

function getFailedAttemptsByUser($link, $ip_address, $username)
{
    $sql = "SELECT COUNT(*) AS total_count from failed_login_attempts where ip= ? or username = ? ";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_ip, $param_username);
        // Set parameters
        $param_ip = $ip_address;
        $param_username = $username;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $check_login_row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    return !empty($check_login_row['total_count']) ? $check_login_row['total_count'] : null;
}

/**
 * Checks how many failed login attempts a specific user on a specific network has.
 *
 * @param	MySQLi Object	$link						Object representing the connection to the MySQL server. 	 
 * @param	string			$ip_address 				A user's IP address.
 * @param	string			$username 					A user's username.
 *
 * @return	string			$check_email_sent : null 	A hash that confirms if the user was notified of the activity.
 */

function getFailedAttemptsInfoByUser($link, $ip_address, $username)
{
    $sql = "SELECT * from failed_login_attempts where ip= ? or username = ? ";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_ip, $param_username);
        // Set parameters
        $param_ip = $ip_address;
        $param_username = $username;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $check_email_sent = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    return !empty($check_email_sent) ? $check_email_sent : null;
}

/**
 * Deletes all of the failed login attempts a specific user on a specific network has.
 *
 * @param	MySQLi Object	$link		Object representing the connection to the MySQL server. 	 
 * @param	string			$ip_address A user's IP address.
 * @param	string			$username 	A user's username.
 */

function deleteFailedAttempts($link, $ip_address, $username)
{
    $sql = "DELETE from failed_login_attempts where ip = ? AND username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_ip, $param_username);
        // Set parameters
        $param_ip = $ip_address;
        $param_username = $username;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

/**
 * Checks any user's IP Address for abuse/fraud/VPN/Proxy/TOR node usage.
 *
 * @return bool true:false 	Safe:Unsafe visitor IP Address.
 */

function checkIP()
{
    //check for proxies
    $key = $_ENV["ip_quality_api_key"];
    $ip = getIpAddr();
    if ($ip != $_ENV["myIP"] && $ip != $_ENV["myPhoneIP"])
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])&&!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$user_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		}
		else
		{
			$user_language = 'en-US';
		}
        $strictness = 0;
        $allow_public_access_points = 'true';
        $lighter_penalties = 'true';
        $parameters = array(
            'user_agent' => $user_agent,
            'user_language' => $user_language,
            'strictness' => $strictness,
            'allow_public_access_points' => $allow_public_access_points,
            'lighter_penalties' => $lighter_penalties
        );
        $formatted_parameters = http_build_query($parameters);
        $url = sprintf('https://www.ipqualityscore.com/api/json/ip/%s/%s?%s', $key, $ip, $formatted_parameters);
        $timeout = 5;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $json = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($json, true);
        if (!empty($result['proxy']) &&$result['proxy'] === true && $result['is_crawler'] === false)
        {
            return true;
        }
    }
    return false;
}

/**
 * Compare two DateTime strings and output the difference of their UNIX timestamps in milliseconds.
 *
 * @param	string	$date1			A string in some DateTime format. 
 * @param	string	$date2 			A string in some DateTime format.
 * @param	int		$compare_amount Amount to compare the timestamps by.
 *
 * @return 	bool	true:false 		If the difference is less than the desired $compare_amount.
 */

function compareMilliseconds($date1, $date2, $compare_amount)
{
    if (strtotime($date1) == strtotime($date2))
    {
        list($throw, $milliseond1) = explode('.', $date1);
        list($throw, $milliseond2) = explode('.', $date2);
        return (($milliseond2 - $milliseond1) < $compare_amount);
    }
}

/**
 * Check if an e-mail address is in a valid format.
 *
 * @param	string	$email		The desired e-mail address.
 *
 * @return 	bool	true:false 	If the email/domain is in valid format.
 */

function valid_email($email)
{
    if (is_array($email) || is_numeric($email) || is_bool($email) || is_float($email) || is_file($email) || is_dir($email) || is_int($email)) return false;
    else
    {
        $email = trim(strtolower($email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) return $email;
        else
        {
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
            return (preg_match($pattern, $email) === 1) ? $email : false;
        }
    }
}

/**
 * Encrypt a string with HMAC/SHA256.
 *
 * @param	string	$data		The raw input string.
 *
 * @return 	string	$ciphertext Encrypted result.
 */

function encrypt($data)
{
    $key = key;
    $plaintext = $data;
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
    return $ciphertext;
}

/**
 * Decrypt a HMAC/SHA256-encrypted string encryped with the above method.
 *
 * @param	string	$data				The encryped string.
 *
 * @return 	string	$original_plaintext Decrypted result.
 */

function decrypt($data)
{
    $key = key;
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac))
    {
        return $original_plaintext;
    }
}

/**
 * Generates a random string with OpenSSL.
 *
 * @param	int		$length	The desired length.
 * @param	string	$chars	The desired set of characters to utilize.
 *
 * @return 	string	$retstr The random resultant string.
 */

function randomstr($length, $chars)
{
    $retstr = '';
    $data = openssl_random_pseudo_bytes($length);
    $num_chars = strlen($chars);
    for ($i = 0;$i < $length;$i++)
    {
        $retstr .= substr($chars, ord(substr($data, $i, 1)) % $num_chars, 1);
    }
    return $retstr;
}

/**
 * Generates random bytes with OpenSSL.
 *
 * @param	int		$nbBytes:32	The desired length.
 * 
 * @return 	string	$bytes 		The random resultant string.
 *
 * @throws new OpenSSL Exception
 */

function getRandomBytes($nbBytes = 32)
{
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong)
    {
        return $bytes;
    }
    else
    {
        throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
}

/**
 * Generates a random formatted string with the above OpenSSL function.
 *
 * @param 	int		$length			The desired length.
 *
 * @return 	string	getRandomBytes() The random resultant string.
 */

function generatePassword($length)
{
    return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length + 1))) , 0, $length);
}

/**
 * Generates a URL for some static file on a server (Useful for building reusable URLs).
 *
 * @return 	string 	The resultant URL.
 */

function imageUrl()
{
    return "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "../") + 1) . "donttrip/icons/dont_Trip.png";
}

/**
 * Generates a random OTP.
 *
 * @param 	int		$length:64																			The desired length.
 * @param 	string	$keyspace:"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&()"	The desired set of characters to utilize.
 *
 * @return 	string	$pieces 																			The random resultant string.
 *
 * @throws new int RangeException
 */

function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&()'): string
{
    if ($length < 1)
    {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0;$i < $length;++$i)
    {
        $pieces[] = $keyspace[random_int(0, $max) ];
    }
    return implode('', $pieces);
}

/**
 * Generates a rough geolocation based upon an IP Address.
 *
 * @param 	int		$ip_address		The desired IP Address.
 *
 * @return 	string 	$city.$state	The city/state geolocation of the IP Address.
 */

function getGeo($ip_address)
{
    ini_set('allow_url_fopen', 'On');
    $details = json_decode(file_get_contents("http://ip-api.com/json/{$ip_address}"));
    $city = $details->city;
    $stateFull = $details->regionName;
    return $city . ", " . $stateFull;
}

/**
 * Generates a full state name for a state code.
 *
 * @param 	int		$state						The desired state code.
 *
 * @return 	string 	$states[$state]?$state:null	The full state name.
 */

function state_abreviation_for($state)
{
    // from https://gist.github.com/maxrice/2776900 and http://www.comeexplorecanada.com/abbreviations.php
    static $states = ['ALABAMA' => 'AL', 'ALASKA' => 'AK', 'ARIZONA' => 'AZ', 'ARKANSAS' => 'AR', 'CALIFORNIA' => 'CA', 'COLORADO' => 'CO', 'CONNECTICUT' => 'CT', 'DELAWARE' => 'DE', 'FLORIDA' => 'FL', 'GEORGIA' => 'GA', 'HAWAII' => 'HI', 'IDAHO' => 'ID', 'ILLINOIS' => 'IL', 'INDIANA' => 'IN', 'IOWA' => 'IA', 'KANSAS' => 'KS', 'KENTUCKY' => 'KY', 'LOUISIANA' => 'LA', 'MAINE' => 'ME', 'MARYLAND' => 'MD', 'MASSACHUSETTS' => 'MA', 'MICHIGAN' => 'MI', 'MINNESOTA' => 'MN', 'MISSISSIPPI' => 'MS', 'MISSOURI' => 'MO', 'MONTANA' => 'MT', 'NEBRASKA' => 'NE', 'NEVADA' => 'NV', 'NEW HAMPSHIRE' => 'NH', 'NEW JERSEY' => 'NJ', 'NEW MEXICO' => 'NM', 'NEW YORK' => 'NY', 'NORTH CAROLINA' => 'NC', 'NORTH DAKOTA' => 'ND', 'OHIO' => 'OH', 'OKLAHOMA' => 'OK', 'OREGON' => 'OR', 'PENNSYLVANIA' => 'PA', 'RHODE ISLAND' => 'RI', 'SOUTH CAROLINA' => 'SC', 'SOUTH DAKOTA' => 'SD', 'TENNESSEE' => 'TN', 'TEXAS' => 'TX', 'UTAH' => 'UT', 'VERMONT' => 'VT', 'VIRGINIA' => 'VA', 'WASHINGTON' => 'WA', 'WEST VIRGINIA' => 'WV', 'WISCONSIN' => 'WI', 'WYOMING' => 'WY', 'ALBERTA' => 'AB', 'BRITISH COLUMBIA' => 'BC', 'MANITOBA' => 'MB', 'NEW BRUNSWICK' => 'NB', 'NEWFOUNDLAND AND LABRADOR' => 'NL', 'NOVA SCOTIA' => 'NS', 'NORTWEST TERRITORIES' => 'NT', 'NUNAVUT' => 'NU', 'ONTARIO' => 'ON', 'PRINCE EDWARD ISLAND' => 'PE', 'QUEBEC' => 'QC', 'SASKATCHEWAN' => 'SK', 'YUKON' => 'YT', 'PUERTO RICO' => 'PR', 'VIRGIN ISLANDS' => 'VI', 'WASHINGTON DC' => 'DC'];
    // first check if input is two letters, and if so make sure that it matches one of the abbreviations, then return that
    if (strlen($state) == 2)
    {
        if (in_array(strtoupper($state) , $states))
        {
            return strtoupper($state);
        }
        else
        {
            return null;
        }
    }
    // check for the full state name in the array
    if (array_key_exists(strtoupper($state) , $states))
    {
        return $states[strtoupper($state) ];
    }
    else
    {
        return null;
    }
}
?>