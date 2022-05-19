<div id="hasil"></div>
<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>


    <table class="table table-bordered table-striped table-hover tabel-grid">
        <thead>
            <tr>
                <th style="min-width:3%" class="tengah">No</th>
                <th width="15%" class="text-center">Kode Produk</th>
                <th>Nama Produk</th>
                <th>Ukuran</th>
                <th>Warna</th>
                <th class="text-right">Sisa Stok</th>
            </tr>
        </thead>
        <tbody id="viewdata">
            <?php $no = 0 ?>
            <?php $grandtot = 0 ?>

            <?php foreach ($dataview['rows'] as $datanya) { ?>
            <?php $grandtot = $grandtot + (int)$datanya["stok"]; ?>
            <tr>
                <td class="tengah"><?php echo $no = $no + 1 ?></td>
                <td class="text-center"><?php echo $datanya["kode_produk"] ?></td>
                <td><?php echo ucwords($datanya["nama_produk"]) ?></td>
                <td><?php echo ucwords($datanya["ukuran"]) ?></td>
                <td><?php echo ucwords($datanya["warna"]) ?></td>
                <td class="text-right"><?php echo $datanya["stok"] ?></td>
            </tr>
            <?php 
        } ?>
        </tbody>

    </table>
    <?php if ($total > 0) { ?>
    <!-- Pagging -->
    <div class="col-md-6">
        <div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
    </div>
    <div class="col-md-6 text-right">
        <ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total, $baris, $page, $jmlpage, $linkpage) ?></ul>
    </div>

    <!-- End Pagging -->
    <?php 
} ?>
</div> 