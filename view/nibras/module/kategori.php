<div class="menu-kategori">
<div class="title-module">
   <h3><span class="glyphicon glyphicon-bookmark"></span> Kategori</h3>
</div>
<div class="clearfix"></div>
<div class="list-group menu-kategori">
  <?php foreach($kategori as $kat) {?>
  <a href="<?php echo URL_PROGRAM.$kat['kategori_alias']; ?>" class="list-group-item"><b><?php echo strtoupper($kat['kategori_nama']); ?></b></a>
  <?php if ($kat['children']) { ?>
  <?php foreach ($kat['children'] as $child) { ?>
  <a href="<?php echo URL_PROGRAM.$child['kategori_alias']; ?>" class="list-group-item"><?php echo $child['kategori_nama']; ?></a>
  <?php } ?>
 
  <?php } ?>
  
  <?php } ?>
</div>
</div>