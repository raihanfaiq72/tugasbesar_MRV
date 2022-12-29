<?php

class HillCipherExample
{
    function __construct(){
    }
    public static function HillCipherExample()
    {
        $local_this = new HillCipherExample();
        return $local_this;
    }
    // method to accept key matrix
    private static function getKeyMatrix()
    {
        $sc = "Inputs";
        echo "Enter key matrix:","\n";
        $key = readline();
        // int len = key.length();  
        $sq = sqrt(strlen($key));
        if ($sq != (int)$sq)
        {
            echo "Cannot Form a square matrix","\n";
        }
        $len = (int)$sq;
        $keyMatrix = array_fill(0,$len,array_fill(0,$len,0));
        $k = 0;
        for ($i = 0; $i < $len; $i++)
        {
            for ($j = 0; $j < $len; $j++)
            {
                $keyMatrix[$i][$j] = ((int)$key[$k]) - 97;
                $k++;
            }
        }
        return $keyMatrix;
    }
    // Below method checks whether the key matrix is valid (det=0)
    private static function isValidMatrix(&$keyMatrix)
    {
        $det = $keyMatrix[0][0] * $keyMatrix[1][1] - $keyMatrix[0][1] * $keyMatrix[1][0];
        // If det=0, throw exception and terminate
        if ($det == 0)
        {
            throw new Exception("Det equals to zero, invalid key matrix!");
        }
    }
    // This method checks if the reverse key matrix is valid (matrix mod26 = (1,0,0,1)
    private static function isValidReverseMatrix(&$keyMatrix, &$reverseMatrix)
    {
        $product = array_fill(0,2,array_fill(0,2,0));
        // Find the product matrix of key matrix times reverse key matrix  
        $product[0][0] = ($keyMatrix[0][0] * $reverseMatrix[0][0] + $keyMatrix[0][1] * $reverseMatrix[1][0]) % 26;
        $product[0][1] = ($keyMatrix[0][0] * $reverseMatrix[0][1] + $keyMatrix[0][1] * $reverseMatrix[1][1]) % 26;
        $product[1][0] = ($keyMatrix[1][0] * $reverseMatrix[0][0] + $keyMatrix[1][1] * $reverseMatrix[1][0]) % 26;
        $product[1][1] = ($keyMatrix[1][0] * $reverseMatrix[0][1] + $keyMatrix[1][1] * $reverseMatrix[1][1]) % 26;
        // Check if a=1 and b=0 and c=0 and d=1
        // If not, throw exception and terminate
        if ($product[0][0] != 1 || $product[0][1] != 0 || $product[1][0] != 0 || $product[1][1] != 1)
        {
            throw new Exception("Invalid reverse matrix found!");
        }
    }
    // This method calculates the reverse key matrix
    private static function reverseMatrix(&$keyMatrix)
    {
        $detmod26 = ($keyMatrix[0][0] * $keyMatrix[1][1] - $keyMatrix[0][1] * $keyMatrix[1][0]) % 26;
        // Calc det  
        $factor;
        $reverseMatrix = array_fill(0,2,array_fill(0,2,0));
        // Find the factor for which is true that
        // factor*det = 1 mod 26
        for ($factor = 1; $factor < 26; $factor++)
        {
            if (($detmod26 * $factor) % 26 == 1)
            {
                break;
            }
        }
        // Calculate the reverse key matrix elements using the factor found  
        $reverseMatrix[0][0] = $keyMatrix[1][1] * $factor % 26;
        $reverseMatrix[0][1] = (26 - $keyMatrix[0][1]) * $factor % 26;
        $reverseMatrix[1][0] = (26 - $keyMatrix[1][0]) * $factor % 26;
        $reverseMatrix[1][1] = $keyMatrix[0][0] * $factor % 26;
        return $reverseMatrix;
    }
    // This method echoes the result of encrypt/decrypt
    private static function echoResult($label, $adder, $phrase)
    {
        $i;
        echo $label;
        // Loop for each pair
        for ($i = 0; $i < count($phrase);
        $i += 2)
        {
            print_r(Character.toChars($phrase[$i] + (64 + $adder)));
            print_r(Character.toChars($phrase[$i + 1] + (64 + $adder)));
            if ($i + 2 < count($phrase))
            {
                echo "-";
            }
        }
        print("\n");
    }
    // This method makes the actual encryption
    public static function encrypt($phrase, $alphaZero)
    {
        $i;
        $adder = $alphaZero ? 1 : 0;
        // For calclulations depending on the alphabet  
        $keyMatrix;
        $phraseToNum = array();
        $phraseEncoded = array();
        // Delete all non-english characters, and convert phrase to upper case  
        $phrase = strtoupper($phrase.replaceAll("[^a-zA-Z]",""));
        // If phrase length is not an even number, add "Q" to make it even
        if (strlen($phrase) % 2 == 1)
        {
            $phrase += "Q";
        }
        // Get the 2x2 key matrix from sc  
        $keyMatrix = HillCipherExample::getKeyMatrix();
        // Check if the matrix is valid (det != 0)  
        HillCipherExample::isValidMatrix($keyMatrix);
        // Convert characters to numbers according to their
        // place in ASCII table minus 64 positions (A=65 in ASCII table)
        // If we use A=0 alphabet, subtract one more (adder)
        for ($i = 0; $i < strlen($phrase); $i++)
        {
            array_push($phraseToNum,ord($phrase[$i]) - (64 + $adder));
        }
        // Find the product per pair of the phrase with the key matrix modulo 26
        // If we use A=1 alphabet and result is 0, replace it with 26 (Z)
        for ($i = 0; $i < count($phraseToNum);
        $i += 2)
        {
            $x = ($keyMatrix[0][0] * $phraseToNum[$i] + $keyMatrix[0][1] * $phraseToNum[$i + 1]) % 26;
            $y = ($keyMatrix[1][0] * $phraseToNum[$i] + $keyMatrix[1][1] * $phraseToNum[$i + 1]) % 26;
            array_push($phraseEncoded,$alphaZero ? $x : ($x == 0 ? 26 : $x));
            array_push($phraseEncoded,$alphaZero ? $y : ($y == 0 ? 26 : $y));
        }
        // Print the result  
        HillCipherExample::echoResult("Encoded phrase: ", $adder, $phraseEncoded);
    }
    // This method makes the actual decryption
    public static function decrypt($phrase, $alphaZero)
    {
        $i;
        $adder = $alphaZero ? 1 : 0;
        $keyMatrix;
        $revKeyMatrix;
        $phraseToNum = array();
        $phraseDecoded = array();
        // Delete all non-english characters, and convert phrase to upper case  
        $phrase = strtoupper($phrase.replaceAll("[^a-zA-Z]",""));
        // Get the 2x2 key matrix from sc  
        $keyMatrix = HillCipherExample::getKeyMatrix();
        // Check if the matrix is valid (det != 0)  
        HillCipherExample::isValidMatrix($keyMatrix);
        // Convert numbers to characters according to their
        // place in ASCII table minus 64 positions (A=65 in ASCII table)
        // If we use A=0 alphabet, subtract one more (adder)
        for ($i = 0; $i < strlen($phrase); $i++)
        {
            array_push($phraseToNum,ord($phrase[$i]) - (64 + $adder));
        }
        // Find the reverse key matrix  
        $revKeyMatrix = HillCipherExample::reverseMatrix($keyMatrix);
        // Check if the reverse key matrix is valid (product = 1,0,0,1)  
        HillCipherExample::isValidReverseMatrix($keyMatrix, $revKeyMatrix);
        // Find the product per pair of the phrase with the reverse key matrix modulo 26
        for ($i = 0; $i < count($phraseToNum);
        $i += 2)
        {
            array_push($phraseDecoded,($revKeyMatrix[0][0] * $phraseToNum[$i] + $revKeyMatrix[0][1] * $phraseToNum[$i + 1]) % 26);
            array_push($phraseDecoded,($revKeyMatrix[1][0] * $phraseToNum[$i] + $revKeyMatrix[1][1] * $phraseToNum[$i + 1]) % 26);
        }
        // Print the result  
        HillCipherExample::echoResult("Decoded phrase: ", $adder, $phraseDecoded);
    }
    // main method
    public static function main(&$args)
    {
        $opt;
        $phrase;
        $p;
        $sc = "Inputs";
        echo "Hill Cipher Implementation (2x2)","\n";
        echo "-------------------------","\n";
        echo "1. Encrypt text (A=0,B=1,...Z=25)","\n";
        echo "2. Decrypt text (A=0,B=1,...Z=25)","\n";
        echo "3. Encrypt text (A=1,B=2,...Z=26)","\n";
        echo "4. Decrypt text (A=1,B=2,...Z=26)","\n";
        print("\n");
        echo "Type any other character to exit","\n";
        print("\n");
        echo "Select your choice: ";
        $opt = readline();
        switch ($opt) {
            case "1":
                echo "Enter phrase to encrypt: ";
                $phrase = readline();
                HillCipherExample::encrypt($phrase, true);
                break;
            case "2":
                echo "Enter phrase to decrypt: ";
                $phrase = readline();
                HillCipherExample::decrypt($phrase, true);
                break;
            case "3":
                echo "Enter phrase to encrypt: ";
                $phrase = readline();
                HillCipherExample::encrypt($phrase, false);
                break;
            case "4":
                echo "Enter phrase to decrypt: ";
                $phrase = readline();
                HillCipherExample::decrypt($phrase, false);
                break;
        }
    }
}
HillCipherExample::main($argv);
?>