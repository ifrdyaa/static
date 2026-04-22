<?php

class Pengunjung {
    public static $jumlah = 0;
    public function __construct() {
        self::$jumlah++;
    }
    //Method reset
    public static function reset() {
        self::$jumlah = 0;}
}


$p1 = new Pengunjung();
$p2 = new Pengunjung();
$p3 = new Pengunjung();
$p4 = new Pengunjung();
$p5 = new Pengunjung();

//Menampilkan hasil sebelum di-reset
echo "Jumlah Pengunjung (Sebelum Reset): " . Pengunjung::$jumlah . "<br>";

//Menjalankan method reset
Pengunjung::reset();

//Menampilkan hasil sesudah di-reset
echo "Jumlah Pengunjung (Sesudah Reset): " . Pengunjung::$jumlah;

?>
