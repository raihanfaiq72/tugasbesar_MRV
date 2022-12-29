<?php

class Basic
{
    function __construct(){
        $this->allChar = NULL;
    }
    public static function Basic()
    {
        $local_this = new Basic();
        return $local_this;
    }
    public $allChar = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    function indexOfChar($c)
    {
        for ($i = 0; $i < strlen($this->allChar); $i++)
        {
            if ($this->allChar[$i] == $c)
            {
                return $i;
            }
        }
        return -1;
    }
    function charAtIndex($pos)
    {
        return $this->allChar[$pos];
    }
}class Hill
{
    function __construct(){
        $this->b1 = NULL;
        $this->block = 0;
        $this->key = NULL;
    }
    public static function Hill($block)
    {
        $local_this = new Hill();
        $local_this->block = $block;
        return $local_this;
    }
    public $b1 = Basic::Basic();
    public $block = 2;
    public $key = array_fill(0,$this->block,array_fill(0,$this->block,0));
    function keyInsert() 
    {
        $scn = "Inputs";
        echo "Enter key Matrix","\n";
        for ($i = 0; $i < $this->block; $i++)
        {
            for ($j = 0; $j < $this->block; $j++)
            {
                $this->key[$i][$j] = (int)readline();
            }
        }
    }
    function KeyInverseInsert()
    {
        $scn = "Inputs";
        echo "Enter key Inverse Matrix:","\n";
        for ($i = 0; $i < $this->block; $i++)
        {
            for ($j = 0; $j < $this->block; $j++)
            {
                $this->key[$i][$j] = (int)readline();
            }
        }
    }
    function encryptBlock($plain) 
    {
        $plain = strtoupper($plain);
        $a = array_fill(0,$this->block,array_fill(0,1,0));
        $sum = 0;
        $cipherMatrix = array_fill(0,$this->block,array_fill(0,1,0));
        $cipher = "";
        for ($i = 0; $i < $this->block; $i++)
        {
            $a[$i][0] = $this->b1->indexOfChar($plain[$i]);
        }
        for ($i = 0; $i < $this->block; $i++)
        {
            for ($j = 0; $j < 1; $j++)
            {
                for ($k = 0; $k < $this->block; $k++)
                {
                    $sum = $sum + $this->key[$i][$k] * $a[$k][$j];
                }
                $cipherMatrix[$i][$j] = $sum % 26;
                $sum = 0;
            }
        }
        for ($i = 0; $i < $this->block; $i++)
        {
            $cipher += $this->b1->charAtIndex($cipherMatrix[$i][0]);
        }
        return $cipher;
    }
    function encrypt($plainText) 
    {
        $cipherText = "";
        $this->keyInsert();
        $plainText = strtoupper($plainText);
        $len = strlen($plainText);
        // System.out.println(plainText.substring(1,2+1));
        while ($len % $this->block != 0)
        {
            $plainText += "X";
            printf("%d\n",$len);
            $len = strlen($plainText);
        }
        for ($i = 0; $i < $len - 1; $i = $i + $this->block)
        {
            $cipherText += $this->encryptBlock(substr($plainText,$i,$i + $this->block - $i));
            $cipherText += " ";
        }
        return $cipherText;
    }
    function decryptBlock($cipher) 
    {
        $cipher = strtoupper($cipher);
        $a = array_fill(0,$this->block,array_fill(0,1,0));
        $sum = 0;
        $plainMatrix = array_fill(0,$this->block,array_fill(0,1,0));
        $plain = "";
        for ($i = 0; $i < $this->block; $i++)
        {
            $a[$i][0] = $this->b1->indexOfChar($cipher[$i]);
        }
        for ($i = 0; $i < $this->block; $i++)
        {
            for ($j = 0; $j < 1; $j++)
            {
                for ($k = 0; $k < $this->block; $k++)
                {
                    $sum = $sum + $this->key[$i][$k] * $a[$k][$j];
                }
                while ($sum < 0)
                {
                    $sum += 26;
                }
                $plainMatrix[$i][$j] = $sum;
                $sum = 0;
            }
        }
        for ($i = 0; $i < $this->block; $i++)
        {
            $plain += $this->b1->charAtIndex($plainMatrix[$i][0]);
        }
        return $plain;
    }
    function Decrypt($cipherText) 
    {
        $plainText = "";
        $this->KeyInverseInsert();
        $cipherText = $cipherText.replaceAll(" ","");
        $cipherText = strtoupper($cipherText);
        $len = strlen($cipherText);
        for ($i = 0; $i < $len - 1; $i = $i + $this->block)
        {
            $plainText += $this->decryptBlock(substr($cipherText,$i,$i + $this->block - $i));
            $plainText += " ";
        }
        return $plainText;
    }
}class HillCipher
{
    function __construct(){
    }
    public static function HillCipher()
    {
        $local_this = new HillCipher();
        return $local_this;
    }
    public static function main(&$args) 
    {
        $plainText;
        $cipherText;
        $block;
        $scn = "Inputs";
        echo "Enter plain-text:","\n";
        $plainText = readline();
        echo "Enter block size of matrix:","\n";
        $block = (int)readline();
        $hill = Hill::Hill($block);
        $plainText = $plainText.replaceAll(" ","");
        $cipherText = $hill->encrypt($plainText);
        echo "Encrypted Text is:\n" . $cipherText,"\n";
        $decryptedText = $hill->Decrypt($cipherText);
        echo "Decrypted Text is:\n" . $decryptedText,"\n";
    }
}
HillCipher::main($argv);
?>