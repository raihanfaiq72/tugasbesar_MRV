<?php

// Php code to implement Hill Cipher
class GFG
{
    function __construct(){
    }
    public static function GFG()
    {
        $local_this = new GFG();
        return $local_this;
    }
    // Following function generates the
    // key matrix for the key string
    static function getKeyMatrix($key, &$keyMatrix)
    {
        $k = 0;
        for ($i = 0; $i < 3; $i++)
        {
            for ($j = 0; $j < 3; $j++)
            {
                $keyMatrix[$i][$j] = ord(($key[$k])) % 65;
                $k++;
            }
        }
    }
    // Following function encrypts the message
    static function encrypt(&$cipherMatrix, &$keyMatrix, &$messageVector)
    {
        $x;
        $i;
        $j;
        for ($i = 0; $i < 3; $i++)
        {
            for ($j = 0; $j < 1; $j++)
            {
                $cipherMatrix[$i][$j] = 0;
                for ($x = 0; $x < 3; $x++)
                {
                    $cipherMatrix[$i][$j] += $keyMatrix[$i][$x] * $messageVector[$x][$j];
                }
                $cipherMatrix[$i][$j] = $cipherMatrix[$i][$j] % 26;
            }
        }
    }
    // Function to implement Hill Cipher
    static function HillCipher($message, $key)
    {
        // Get key matrix from the key string
        $keyMatrix = array_fill(0,3,array_fill(0,3,0));
        GFG::getKeyMatrix($key, $keyMatrix);
        $messageVector = array_fill(0,3,array_fill(0,1,0));
        // Generate vector for the message
        for ($i = 0; $i < 3; $i++)
        {
            $messageVector[$i][0] = ord(($message[$i])) % 65;
        }
        $cipherMatrix = array_fill(0,3,array_fill(0,1,0));
        // Following function generates
        // the encrypted vector
        GFG::encrypt($cipherMatrix, $keyMatrix, $messageVector);
        $CipherText = "";
        // Generate the encrypted text from
        // the encrypted vector
        for ($i = 0; $i < 3; $i++)
        {
            $CipherText += chr(($cipherMatrix[$i][0] + 65));
        }
        // Finally print the ciphertext
        echo " Ciphertext:" . $CipherText;
    }
    // Driver code
    public static function main(&$args)
    {
        // Get the message to be encrypted
        $message = "ACT";
        // Get the key
        $key = "GYBNQKURP";
        GFG::HillCipher($message, $key);
    }
}
GFG::main($argv);
?>