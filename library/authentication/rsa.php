<?php
class rsa_key_manager
{
    static $config = array(
        "digest_alg" => "sha1",
        "private_key_bits" => 512,
        "private_key_type" => OPENSSL_KEYTYPE_RSA
    );

    protected $pubKey;
    protected $privKey;
    
    public function __construct()
    {
        
    }
    public function debug_keys()
    {
        $this->pubKey="-----BEGIN PUBLIC KEY-----\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDXXkzCbTjNSS12DhU+MPSEhsV2\n5NJmvsEGUFtQRgjywye3r3wV4jbNxyOz9vySz3QRo7Lsd2ZBEX8riBiCt79LbtIT\nT6MF9BIkRXTysqt6XrEts5z7geQMybjOOueFcog3jlmoR4bxMdboJTkMP/AnQpol\n1i8/2Iw+x8rKK/IChwIDAQAB\n-----END PUBLIC KEY-----\n";
        $this->privKey="-----BEGIN PRIVATE KEY-----\nMIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBANdeTMJtOM1JLXYO\nFT4w9ISGxXbk0ma+wQZQW1BGCPLDJ7evfBXiNs3HI7P2/JLPdBGjsux3ZkERfyuI\nGIK3v0tu0hNPowX0EiRFdPKyq3pesS2znPuB5AzJuM4654VyiDeOWahHhvEx1ugl\nOQw/8CdCmiXWLz/YjD7Hysor8gKHAgMBAAECgYAmQueKJxNPTS/ZaFkXymS2YqcU\nH0TN4heywnXmhfqy/j7BIFkAHkc0Oau7HibzAg4R0C5KWk/9QVmBZ1VLa899CMgp\n7EOzNkE/eBUr1aDp2z79Epm4ugMYbr04RI+RvZoEBWBzI4DrNC1bj0EoWlTh0kiR\nFY27YPzYWKPuRf21+QJBAPBNYy8FcoAxnGh6L1odDCb9khTvBrrreDKyTuV11QID\nUfmHSchy5b0AUN9xoYLVo6hWuBuys07m4C8Xc5NMtBsCQQDlb/A2IKeduyh5baCc\nMgGvGM8lP07dnzxQXYYLdbpwqAqC+sVPj+gTk0sHUZ3kiz2MAcm4qmTxxg66NA4E\nc1oFAkEApqsWItPlYbKHKBOu6hKBjj2LZ4eNpHGTMZ5oiFAcyEOjRK2n6CaA34Dr\nlr7KZeNlmmljUpq0MQKC9UaPu9eUhwJAeXBGNGAUV+g4BA2CdSoCuirneU+I7sTZ\np6/YwzgM9pP6/Fi/Ft2UeMf9bmJEsDMC4JgRrSyDQXUTVns28CQeAQJAHjUD/3+y\nFN+jL3fmgf7vQzrabUom6TKFAk+0PSp+pnkSLbOZBdKt6bY9jaJB6Y26IjWzUsar\nW7VEKtY4ly1fkw==\n-----END PRIVATE KEY-----\n";
        
    }
    public function initialize()
    {
        $pair=openssl_pkey_new();
        $keyDetails=openssl_pkey_get_details($pair);
        $this->pubKey=$keyDetails['key'];
        openssl_pkey_export($pair, $this->privKey);
        sqlQuery("INSERT into rsa_pairs (public,private,created) values (?,?,NOW())",array($this->get_pubKeyJS(),$this->privKey));
    }
    
    public function load_from_db($pub)
    {
        $res=sqlQuery("SELECT private FROM rsa_pairs where public=?",array($pub));
        $this->privKey=$res['private'];
        sqlQuery("DELETE FROM rsa_pairs where public=?",array($pub));
        
    }
    public function get_pubKey()
    {
        return $this->pubKey;
    }
    
    public function decrypt($msg)
    {
        $decrypted='';
        $encrypted='';
        $status=openssl_private_decrypt(base64_decode($msg),$decrypted,$this->privKey);
        error_log($decrypted);
        return $decrypted;
    }
    public function get_pubKeyJS()
    {
        return preg_replace("/\\n/","",$this->get_pubKey());
    }
}
?>
