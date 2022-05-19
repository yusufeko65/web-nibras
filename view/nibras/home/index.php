<?php
$dtCart         = new controller_Cart();
$dtProduk         = new controller_Produk();
$dtTestim         = new controller_Testimonial();
$dtPaging         = new Paging();

include path_to_includes . 'bootcart.php';

$dataview = $dtProduk->tampildata('', '', $config_produkhome);

$totaldata  = $dataview['total'];
$baris       = $dataview['baris'];
$page       = $dataview['page'];
$jmlpage  = $dataview['jmlpage'];
$ambildata = $dataview['rows'];
$cari       = isset($_GET['q']) ? urlencode($_GET['q']) : '';

$datatestim  = $dtTestim->tampildata();
$ambiltestim = $datatestim['rows'];
$linkpage = '';

$linkcari         = '';
$linksort         = '?';
$sort            = isset($_GET['sort']) ? $_GET['sort'] : '';
$fwarna            = isset($_GET['fwarna']) ? urlencode($_GET['fwarna']) : '';
$fukuran        = isset($_GET['fukuran']) ? urlencode($_GET['fukuran']) : '';

if ($cari != '') $link[] = 'q=' . trim(stripslashes(strip_tags($cari)));
$atribut = [];
$datawarna = false;
$dataukuran = false;

if ($fwarna != '') {
    $link[] = 'fwarna=' . trim(strip_tags($fwarna));
    $datawarna = $dtAtribut->getWarnaByAlias($fwarna);

    $atribut['namawarna']    = 'Warna : ' . $datawarna['warna'];
}
if ($fukuran != '') {
    $link[] = 'fukuran=' . trim(strip_tags($fukuran));
    $dataukuran = $dtAtribut->getUkuranByAlias($fukuran);
    $atribut['namaukuran']    = 'Ukuran : ' . $dataukuran['ukuran'];
}

/*		
if(!empty($link)){
   $linkcari = implode("&",$link);
   $linkcari = '?'.$linkcari;
}
*/
/*
if (!empty($link)) {
    $linkcari .=  implode("&", $link) . '&';
    if ($sort != '') {
        $linksort = $linkcari;
        $linkcari .= 'sort=' . trim(strip_tags($sort));
    }
}
echo $linkcari;
*/
if (!empty($link)) {
    $linkcari = implode("&", $link);

    //if (count($link) > 1) {
    //    $linkcari = '&' . $linkcari;
    //} else {
    $linkcari = '?' . $linkcari;
    //}
    $linksort = $linkcari . '&';
    if ($sort != '') {
        $linkcari .= '&sort=' . trim(strip_tags($sort));
    }
} else {
    if ($sort != '') {
        $linkcari .= '?sort=' . trim(strip_tags($sort));
    }
}
$juduldepan = '';

if ($amenu != '') {
    $linkpage = '/';
}

include DIR_THEMES . "header.php";
?>
<main>

    <?php  ?>
    <?php include DIR_THEMES . "/module/bannerslideshow.php"; ?>
    <?php  ?>
    <?php include DIR_THEMES . "/module/bannertop.php"; ?>
    <?php include DIR_THEMES . "/produk/produk.php"; ?>
    <?php include DIR_THEMES . "/module/sale.php"; ?>
</main>

<?php include DIR_THEMES . "script.php"; ?>
<?php include DIR_THEMES . "footer.php"; ?>