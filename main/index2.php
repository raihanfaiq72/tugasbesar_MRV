
<?php
class HillCipher_Enkripsi
{
    function __construct()
    {
        // kosongan
    }
    public static function HillCipher_Enkripsi()
    {
        $local_this = new HillCipher_Enkripsi();
        return $local_this;
    }
    public static $abjad = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"
    );
    public static $angka = array(
        0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25
    );
    public static $modulo = 26;
    public static $teks2karakter;
    public static $hasilKonversi;
    public static $hasilHitungKunci;
    public static $totalHasilEnkrip = "";

    // proses perhitungan
    public function hitungEnkripsi($text, &$kunci)
    {
        echo "Hitungan Deskripsi" . "\n";
        echo "Plaintext : " . $text,"\n";
        // String hasilSpasi = hilangkanSpasi(text);
        $this->hitungJumlahHuruf($text);
        HillCipher_Enkripsi::pisahkanTeks($text);
        HillCipher_Enkripsi::AbjadKeAngka(HillCipher_Enkripsi::$teks2karakter);
        HillCipher_Enkripsi::perhitunganKunci(HillCipher_Enkripsi::$hasilKonversi, $kunci);
        HillCipher_Enkripsi::AngkaKeAbjad(HillCipher_Enkripsi::$hasilHitungKunci);
        return HillCipher_Enkripsi::$totalHasilEnkrip;
    }

    static function hilangkanSpasi($text)
    {
        $hasil = $text.replaceAll("\\s+","");
        return $hasil;
    }

    public function hitungJumlahHuruf($text)
    {
        $jumlahHuruf = strlen($text);
        echo "Jumlah huruf : " . strval($jumlahHuruf),"\n";
        return $jumlahHuruf;
    }

    static function pisahkanTeks($text)
    {
        echo "========== MEMBAGI TIAP 2 HURUF  ===========","\n";
        $teksnya = $text;
        if (strlen($teksnya) % 2 == 0)
        {
            $teksnya = $text;
        }
        else 
        {
            $teksnya = $text . ".";
        }
        assert(strlen($teksnya) % 2 == 0);
        HillCipher_Enkripsi::$teks2karakter = array_fill(0,(int)(strlen($teksnya) / 2),NULL);
        for ($index = 0; $index < count(HillCipher_Enkripsi::$teks2karakter); $index++)
        {
            HillCipher_Enkripsi::$teks2karakter[$index] = substr($teksnya,$index * 2,$index * 2 + 2 - $index * 2);
            echo HillCipher_Enkripsi::$teks2karakter[$index],"\n";
        }
        return $teksnya;
    }

    static function AbjadKeAngka(&$text)
    {
        HillCipher_Enkripsi::$hasilKonversi = array_fill(0,count($text),array_fill(0,2,NULL));
        echo "========== TRANSFORMASI HURUF KE ANGKA  ===========","\n";
        for ($i = 0; $i < count($text); $i++)
        {
            $char1 = substr($text[$i],0,1 - 0);
            $char2 = substr($text[$i],1);
            for ($j = 0; $j < count(HillCipher_Enkripsi::$abjad); $j++)
            {
                if ((strcmp($char1,HillCipher_Enkripsi::$abjad[$j])==0))
                {
                    $char1 = strval(HillCipher_Enkripsi::$angka[$j]);
                }
                if ((strcmp($char2,HillCipher_Enkripsi::$abjad[$j])==0))
                {
                    $char2 = strval(HillCipher_Enkripsi::$angka[$j]);
                }
            }
            if (HillCipher_Enkripsi::$hasilKonversi[$i][0] == NULL)
            {
                HillCipher_Enkripsi::$hasilKonversi[$i][0] = $char1;
                if (HillCipher_Enkripsi::$hasilKonversi[$i][1] == NULL)
                {
                    HillCipher_Enkripsi::$hasilKonversi[$i][1] = $char2;
                }
            }
        }
        for ($n = 0; $n < count(HillCipher_Enkripsi::$hasilKonversi); $n++)
        {
            for ($p = 0; $p < count(HillCipher_Enkripsi::$hasilKonversi[0]); $p++)
            {
                echo HillCipher_Enkripsi::$hasilKonversi[$n][$p] . " ";
            }
            echo "","\n";
        }
        return HillCipher_Enkripsi::$hasilKonversi;
    }

    static function perhitunganKunci(&$angka, &$kunci)
    {
        $kunciK0B0 = $kunci[0][0];
        $kunciK0B1 = $kunci[0][1];
        $kunciK1B0 = $kunci[1][0];
        $kunciK1B1 = $kunci[1][1];
        HillCipher_Enkripsi::$hasilHitungKunci = array_fill(0,count($angka),array_fill(0,2,NULL));
        // int hasil = (kunci[0][0]*plain[0]) + (kunci[0][1]*plain[1]) ;
        // int hasil1 = (kunci[1][0]*plain[0]) + (kunci[1][1]*plain[1]) ;
        echo "========== HASIL PERKALIAN KUNCI ===========","\n";
        for ($n = 0; $n < count($angka); $n++)
        {
            $konvert = intval($angka[$n][0]);
            $konvert1 = intval($angka[$n][1]);
            $hasil = ($kunciK0B0 * $konvert) + ($kunciK0B1 * $konvert1);
            $hasil1 = ($kunciK1B0 * $konvert) + ($kunciK1B1 * $konvert1);
            echo strval($hasil) . " " . strval($hasil1),"\n";
            $hasil = $hasil % HillCipher_Enkripsi::$modulo;
            $hasil1 = $hasil1 % HillCipher_Enkripsi::$modulo;
            //   System.out.println(hasil + " " + hasil1);
            if (HillCipher_Enkripsi::$hasilHitungKunci[$n][0] == NULL)
            {
                HillCipher_Enkripsi::$hasilHitungKunci[$n][0] = strval($hasil);
                if (HillCipher_Enkripsi::$hasilHitungKunci[$n][1] == NULL)
                {
                    HillCipher_Enkripsi::$hasilHitungKunci[$n][1] = strval($hasil1);
                }
            }
        }
        echo "========== HASIL MODULO 26 ===========","\n";
        for ($i = 0; $i < count(HillCipher_Enkripsi::$hasilHitungKunci); $i++)
        {
            for ($j = 0; $j < count(HillCipher_Enkripsi::$hasilHitungKunci[0]); $j++)
            {
                echo HillCipher_Enkripsi::$hasilHitungKunci[$i][$j] . " ";
            }
            echo "","\n";
        }
        return HillCipher_Enkripsi::$hasilHitungKunci;
    }

    static function AngkaKeAbjad(&$hasilHitungKunci)
    {
        $hasilEnkripsi = "";
        echo "========== HASIL ENKRIPSI ===========","\n";
        HillCipher_Enkripsi::$totalHasilEnkrip = "";
        for ($i = 0; $i < count($hasilHitungKunci); $i++)
        {
            for ($j = 0; $j < count($hasilHitungKunci[0]); $j++)
            {
                // System.out.print(hasilHitungKunci[i][j]+" ");
                for ($k = 0; $k < count(HillCipher_Enkripsi::$angka); $k++)
                {
                    if ((strcmp($hasilHitungKunci[$i][$j],strval(HillCipher_Enkripsi::$angka[$k]))==0))
                    {
                        $hasilEnkripsi = HillCipher_Enkripsi::$abjad[$k];
                        HillCipher_Enkripsi::$totalHasilEnkrip = HillCipher_Enkripsi::$totalHasilEnkrip . $hasilEnkripsi;
                    }
                }
            }
        }
        echo HillCipher_Enkripsi::$totalHasilEnkrip,"\n";
        return HillCipher_Enkripsi::$totalHasilEnkrip;
    }


    public static function main(&$args)
    {   
        $text = "ASSALAAM";
        $kunci = array(
            array(
                5, 6
            ), array(
                2, 3
            )
        );
        HillCipher_Enkripsi::HillCipher_Enkripsi()->hitungEnkripsi($text, $kunci);
    }
}
HillCipher_Enkripsi::main($argv);

?>