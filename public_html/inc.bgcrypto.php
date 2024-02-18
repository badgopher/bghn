<?php

class BGCrypto{
	const BGIv = 'X}>gWvM>';
	const BGKey = '5nh#qJc%';
	const BGHash = "my dog winks at me sometimes.  i always wink back in case it's some sort of code.";

	public static function decryptString($string){
		$string = base64_decode($string);
		return trim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_256, 
				BGCrypto::stringToHash(BGCrypto::BGKey), 
				$string, 
				MCRYPT_MODE_CBC, 
				BGCrypto::stringToHash(BGCrypto::BGIv)
			) 
		);
	}
	
	public static function decipherString($string){
		return base64_decode($string);
	}

	public static function eString($string){
		$string = mcrypt_encrypt(
			MCRYPT_RIJNDAEL_256, 
			BGCrypto::stringToHash(BGCrypto::BGKey), 
			$string, 
			MCRYPT_MODE_CBC, 
			BGCrypto::stringToHash(BGCrypto::BGIv)
		);
		return base64_encode($string);
	}

	private static function stringToHash($string){
		return md5($string.BGCrypto::BGHash);
	}
}

?>