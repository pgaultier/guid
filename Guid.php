<?php
/**
 * Guid.php
 *
 * PHP version 5.3+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   1.0.0
 * @link      http://www.sweelix.net
 * @category  guid
 * @package   sweelix.guid
 */

namespace sweelix\guid;

/**
 * Simple guid generator.
 * A lot of code as been borrowed around the web.
 *
 * <code>
 *  // generate a v4 guid
 * 	$guid = Guid::v4();
 * </code>
 *
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   1.0.0
 * @link      http://www.sweelix.net
 * @category  guid
 * @package   sweelix.guid
 */
class Guid {
	/**
	 * Generate V3 guid
	 *
	 * @param string $namespace guid format : xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
	 * @param string $name      name to encode in guid format
	 *
	 * @return boolean|string
	 * @since  1.0.0
	 */
	public static function v3($namespace, $name) {
		$result = false;
		if(static::isValid($namespace) === true) {
			// Get hexadecimal components of namespace
			$nhex = str_replace(array('-','{','}'), '', $namespace);

			// Binary Value
			$nstr = '';

			// Convert Namespace UUID to bits
			for($i = 0; $i < strlen($nhex); $i+=2) {
				$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
			}

			// Calculate hash value
			$hash = md5($nstr . $name);
			$result = sprintf('%08s-%04s-%04x-%04x-%12s',

					// 32 bits for "time_low"
					substr($hash, 0, 8),

					// 16 bits for "time_mid"
					substr($hash, 8, 4),

					// 16 bits for "time_hi_and_version",
					// four most significant bits holds version number 3
					(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

					// 16 bits, 8 bits for "clk_seq_hi_res",
					// 8 bits for "clk_seq_low",
					// two most significant bits holds zero and one for variant DCE1.1
					(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

					// 48 bits for "node"
					substr($hash, 20, 12)
			);

		}
		return $result;

	}
	/**
	 * Generate V4 guid
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function v4() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

				// 32 bits for "time_low"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),

				// 16 bits for "time_mid"
				mt_rand(0, 0xffff),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand(0, 0x0fff) | 0x4000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand(0, 0x3fff) | 0x8000,

				// 48 bits for "node"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
	/**
	 * Generate V5 guid
	 *
	 * @param string $namespace guid format : xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
	 * @param string $name      name to encode in guid format
	 *
	 * @return boolean|string
	 * @since  1.0.0
	 */
	public static function v5($namespace, $name) {
		if(!static::isValid($namespace)) return false;
		$result = false;
		if(static::isValid($namespace) === true) {
			// Get hexadecimal components of namespace
			$nhex = str_replace(array('-','{','}'), '', $namespace);

			// Binary Value
			$nstr = '';

			// Convert Namespace UUID to bits
			for($i = 0; $i < strlen($nhex); $i+=2) {
				$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
			}

			// Calculate hash value
			$hash = sha1($nstr . $name);
			$result = sprintf('%08s-%04s-%04x-%04x-%12s',

					// 32 bits for "time_low"
					substr($hash, 0, 8),

					// 16 bits for "time_mid"
					substr($hash, 8, 4),

					// 16 bits for "time_hi_and_version",
					// four most significant bits holds version number 5
					(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

					// 16 bits, 8 bits for "clk_seq_hi_res",
					// 8 bits for "clk_seq_low",
					// two most significant bits holds zero and one for variant DCE1.1
					(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

					// 48 bits for "node"
					substr($hash, 20, 12)
			);
		}
		return $result;
	}

	/**
	 * Check if selected guid is valid
	 *
	 * @param ustring $uuid guid to check
	 *
	 * @return boolean
	 * @since  1.0.0
	 */
	public static function isValid($uuid) {
		return (preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
				'[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1);
	}
}
