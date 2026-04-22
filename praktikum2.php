<?php
class Matematika {
    public static function kali($a, $b) { return $a * $b; }
    public static function bagi($a, $b) { 
        if ($b == 0) return "Err: /0";
        return $a / $b;
    }
    public static function tambah($a, $b) { return $a + $b; }
    public static function kurang($a, $b) { return $a - $b; }
    public static function luasPersegi($sisi) { return $sisi * $sisi; }
}

$hasil = "0";
if (isset($_POST['hitung'])) {
    $n1 = $_POST['n1'] ? $_POST['n1'] : 0;
    $n2 = $_POST['n2'] ? $_POST['n2'] : 0;
    $op = $_POST['op'];

    if ($op == "+") $hasil = Matematika::tambah($n1, $n2);
    if ($op == "-") $hasil = Matematika::kurang($n1, $n2);
    if ($op == "x") $hasil = Matematika::kali($n1, $n2);
    if ($op == "/") $hasil = Matematika::bagi($n1, $n2);
    if ($op == "L") $hasil = Matematika::luasPersegi($n1);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kalkulator Static OOP</title>
    <style>
        .kalkulator {
            width: 260px;
            background: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.3);
            margin: 50px auto;
            color: white;
            font-family: sans-serif;
        }
        .layar {
            width: 100%; height: 50px;
            background: #a1c45a; color: #222;
            text-align: right; line-height: 50px;
            font-size: 24px; padding: 0 10px;
            box-sizing: border-box; border-radius: 5px;
            margin-bottom: 20px; font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        input, select {
            width: 100%; padding: 10px;
            margin-bottom: 10px; border-radius: 5px;
            border: none; box-sizing: border-box;
            background: #eee;
        }
        input:disabled {
            background: #666;
            color: #999;
            cursor: not-allowed;
        }
        /* Container tombol biar bisa sejajar atau rapi */
        .btn-group {
            display: flex;
            gap: 10px;
        }
        button {
            flex: 2; padding: 15px;
            background: #ff9500; color: white;
            border: none; border-radius: 5px;
            font-size: 18px; cursor: pointer; font-weight: bold;
        }
        /* Style khusus tombol reset */
        .btn-reset {
            flex: 1;
            background: #d63031; /* Warna merah */
            text-decoration: none;
            text-align: center;
            line-height: 48px; /* Menyesuaikan tinggi button */
            font-size: 14px;
        }
        button:hover { background: #e08400; }
        .btn-reset:hover { background: #ff7675; }
        label { font-size: 12px; color: #bbb; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="kalkulator">
    <div class="layar"><?php echo $hasil; ?></div>

    <form method="post" id="calcForm">
        <label>Pilih Operasi:</label>
        <select name="op" id="operasi" onchange="cekOpsi()">
            <option value="+" <?php if(isset($_POST['op']) && $_POST['op'] == '+') echo 'selected'; ?>>Tambah (+)</option>
            <option value="-" <?php if(isset($_POST['op']) && $_POST['op'] == '-') echo 'selected'; ?>>Kurang (-)</option>
            <option value="x" <?php if(isset($_POST['op']) && $_POST['op'] == 'x') echo 'selected'; ?>>Kali (x)</option>
            <option value="/" <?php if(isset($_POST['op']) && $_POST['op'] == '/') echo 'selected'; ?>>Bagi (/)</option>
            <option value="L" <?php if(isset($_POST['op']) && $_POST['op'] == 'L') echo 'selected'; ?>>Luas Persegi (L)</option>
        </select>

        <label>Angka 1 / Sisi:</label>
        <input type="number" name="n1" id="n1" required value="<?php echo $_POST['n1'] ?? ''; ?>">
        
        <label id="labelAngka2">Angka 2:</label>
        <input type="number" name="n2" id="n2" value="<?php echo $_POST['n2'] ?? ''; ?>">

        <div class="btn-group">
            <button type="submit" name="hitung">HITUNG</button>
            <a href="praktikum2.php" class="btn-reset" title="Reset">C</a>
        </div>
    </form>
</div>

<script>
function cekOpsi() {
    var opsi = document.getElementById("operasi").value;
    var input2 = document.getElementById("n2");
    var label2 = document.getElementById("labelAngka2");

    if (opsi == "L") {
        input2.disabled = true;
        input2.value = ""; 
        label2.style.color = "#555"; 
    } else {
        input2.disabled = false;
        label2.style.color = "#bbb"; 
    }
}
window.onload = cekOpsi;
</script>

</body>
</html>