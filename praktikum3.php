<?php
session_start(); // Wajib untuk fitur keranjang belanja

// ======================================================
// BAGIAN 1: KODE ASLI DARI FOTO (JANGAN DIUBAH)
// ======================================================
class Produk {
    public static $jumlahProduk = 0;

    public function tambahProduk() {
        self::$jumlahProduk++;
    }
}

class Transaksi {
    final public function prosesTransaksi() {
        echo "Transaksi diproses";
    }
}

$p1 = new Produk();
$p1->tambahProduk();
// echo "Total Produk: " . Produk::$jumlahProduk; // Baris ini ada di foto tapi kita biarkan logicnya jalan
// ======================================================


// ======================================================
// BAGIAN 2: PENGEMBANGAN (TUGAS MAHASISWA)
// ======================================================

// Menggunakan Inheritance untuk menambah properti tanpa error deprecated
class Sembako extends Produk {
    public $id, $nama, $harga;

    public function __construct($id, $nama, $harga) {
        $this->id = $id;
        $this->nama = $nama;
        $this->harga = $harga;
        $this->tambahProduk(); // Memanggil method static parent
    }
}

// Inisialisasi Produk Sembako
$katalog = [
    1 => new Sembako(1, "Beras Ramos 1kg", 15000),
    2 => new Sembako(2, "Minyak Goreng 1L", 18500),
    3 => new Sembako(3, "Gula Pasir 1kg", 13000),
    4 => new Sembako(4, "Telur Ayam (Butir)", 2000)
];

// Logic Keranjang Belanja
if (isset($_POST['tambah_item'])) {
    $id = $_POST['item_id'];
    $qty = $_POST['qty'];
    if ($qty > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
}

// Logic Reset
if (isset($_POST['kosongkan'])) {
    unset($_SESSION['cart']);
    header("Location: praktikum3.php");
    exit;
}

$tampilkanStruk = false;
if (isset($_POST['bayar']) && !empty($_SESSION['cart'])) {
    $tampilkanStruk = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Kasir - Praktikum 3</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #2c3e50; color: white; padding: 20px; }
        .wrapper { max-width: 850px; margin: auto; display: flex; gap: 30px; align-items: flex-start; }
        .pos-system { background: #333; padding: 25px; border-radius: 12px; flex: 1; box-shadow: 0 15px 35px rgba(0,0,0,0.4); }
        .lcd-screen { background: #a1c45a; color: #1a1a1a; padding: 15px; border-radius: 6px; text-align: center; font-weight: bold; margin-bottom: 25px; border: 3px solid #88a04d; }
        .menu-item { background: #444; padding: 12px; margin-bottom: 12px; border-radius: 8px; border-left: 6px solid #ff9500; display: flex; justify-content: space-between; align-items: center; }
        .info b { font-size: 16px; display: block; }
        .info span { color: #ff9500; font-size: 14px; }
        .qty-box { width: 45px; padding: 6px; border-radius: 4px; border: none; text-align: center; font-weight: bold; }
        .btn-add { background: #ff9500; border: none; color: white; padding: 7px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-left: 10px; }
        .btn-pay { background: #27ae60; border: none; color: white; width: 100%; padding: 15px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 18px; margin-top: 15px; }
        .btn-pay:hover { background: #2ecc71; }
        
        /* Style Struk */
        .thermal-paper { background: #fff; color: #000; width: 320px; padding: 25px; border-radius: 2px; box-shadow: 0 0 20px rgba(0,0,0,0.5); border-top: 8px solid #ddd; }
        .struk-head { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 15px; margin-bottom: 15px; }
        .struk-body { font-size: 14px; margin-bottom: 15px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .total-bold { border-top: 2px solid #000; margin-top: 10px; padding-top: 10px; font-weight: bold; font-size: 16px; }
        .btn-clear { background: transparent; color: #e74c3c; border: 1px solid #e74c3c; padding: 8px; width: 100%; border-radius: 5px; cursor: pointer; margin-top: 10px; font-size: 12px; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="pos-system">
        <div class="lcd-screen">
            TOTAL PRODUK TERDAFTAR: <?php echo Produk::$jumlahProduk; ?>
        </div>

        <h3 style="margin: 0 0 20px 0;">Menu Sembako</h3>
        <?php foreach ($katalog as $p): ?>
            <div class="menu-item">
                <div class="info">
                    <b><?php echo $p->nama; ?></b>
                    <span>Rp <?php echo number_format($p->harga, 0, ',', '.'); ?></span>
                </div>
                <form method="post">
                    <input type="hidden" name="item_id" value="<?php echo $p->id; ?>">
                    <input type="number" name="qty" value="1" min="1" class="qty-box">
                    <button type="submit" name="tambah_item" class="btn-add">TAMBAH</button>
                </form>
            </div>
        <?php endforeach; ?>

        <form method="post">
            <button type="submit" name="bayar" class="btn-pay">PROSES PEMBAYARAN</button>
            <button type="submit" name="kosongkan" class="btn-clear">KOSONGKAN KERANJANG</button>
        </form>
    </div>

    <div>
        <?php if ($tampilkanStruk): ?>
            <div class="thermal-paper">
                <div class="struk-head">
                    <strong style="font-size: 18px;">WARUNG SEMBAKO OOP</strong><br>
                    <small>Jl. Praktikum PHP No. 3</small><br>
                    <small><?php echo date('d/m/Y H:i:s'); ?></small>
                </div>
                
                <div class="struk-body">
                    <?php 
                    $grandTotal = 0;
                    $transaksiObj = new Transaksi();
                    foreach ($_SESSION['cart'] as $id => $jumlah): 
                        $prod = $katalog[$id];
                        $sub = $prod->harga * $jumlah;
                        $grandTotal += $sub;
                    ?>
                        <div class="row">
                            <span><?php echo $prod->nama; ?> (x<?php echo $jumlah; ?>)</span>
                            <span><?php echo number_format($sub, 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="row total-bold">
                        <span>GRAND TOTAL</span>
                        <span>Rp <?php echo number_format($grandTotal, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div style="text-align: center; font-size: 12px; border-top: 1px dashed #000; padding-top: 10px;">
                    <p>Status: <?php $transaksiObj->prosesTransaksi(); ?></p>
                    <p>-- Terima Kasih --</p>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; color: #7f8c8d; border: 2px dashed #7f8c8d; padding: 40px; border-radius: 12px;">
                Keranjang Kosong /<br>Menunggu Pembayaran
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>