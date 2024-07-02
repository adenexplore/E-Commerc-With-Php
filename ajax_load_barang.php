<?php
	session_start();
	require 'connect.php';
	require 'functions.php';
	
	$page = $_POST["page"];
	$id_merk = $_POST["id_merk"];
	$id_kategori = $_POST["id_kategori"];
	//$keyword = $_POST["keyword"];
	
	$jumlah_data_per_page = 9;
	
	$query = "select * from barang where id_merk='$id_merk' and id_kategori='$id_kategori'";
	$resultCount = mysql_query($query);
	$jumlah_data = mysql_num_rows($resultCount);
	
	$jumlah_page = ceil($jumlah_data / $jumlah_data_per_page);
	if($jumlah_page <= 0)
	{
		$jumlah_page = 1;
	}
	if($page > $jumlah_page - 1)
	{
		$page = $jumlah_page - 1;
	}
	if($page < 0)
	{
		$page = 0;
	}
	$start_limit = $page * $jumlah_data_per_page;
	
	$query = "select barang.*,kategori1.nama as nama_kategori,merk1.nama as nama_merk from barang 
				left join kategori1 on barang.id_kategori=kategori1.id_kategori
				left join merk1 on barang.id_merk=merk1.id_merk
				where barang.id_merk='$id_merk' and  barang.id_kategori='$id_kategori' and barang.jumlah_stock >= '1'
				order by id_barang desc limit $start_limit,$jumlah_data_per_page";
	//echo $query;
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
		$gambar = "images/no-picture.jpg";	//default no picture
		$id_barang = $row["id_barang"];
		$nama_barang = $row["nama"];
		$volume_barang = $row["volume"];
		$harga = $row["harga"];
		
		$idjenis = "select * from jenis where id_jenis=$row[id_jenis] limit 1";
		$resultjenis = mysql_query($idjenis);
		if($rowjenis = mysql_fetch_array($resultjenis))
		{
			$jenis = $rowjenis["jenis"];
		}
		
		$query = "select * from gambar_barang where id_barang='$id_barang' limit 1";
		$resultGambar = mysql_query($query);
		if($rowGambar = mysql_fetch_array($resultGambar))
		{
			$gambar = $rowGambar["image"];
		}
		
?>
<style>
body{
  font-family: "Arial";
  font-size: 20px;
}
.sise
{
	font-family: "Arial";
  font-size: 20px;
}
</style>
	<div class="col-sm-4">
		<div class="product-image-wrapper">
			<div class="single-products">
					<div class="productinfo text-center">
						<img src="admin1/<?php echo $gambar; ?>" alt="<?php echo $nama_barang; ?>" />
						<h2>Rp <?php echo number_format($harga,0,",","."); ?></h2>
						<p><b><?php echo $nama_barang; echo " "; echo $jenis ;echo "-";echo $volume_barang; ?></b></p>
						<a href="session_produk-details.php?id=<?php echo $id_barang; ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>product-details</a>
					</div>
			</div>
		</div>
	</div>
<?php
	}
	?><div style="clear: both"></div><?php
	
	//nav
	?>
	<nav>
	<center>
	  <ul class="pagination">
		<?php
			if($page > 0)
			{
		?>
		<li>
		  <a style="cursor:pointer;" onclick="gantiPage('<?php echo ($page-1); ?>')" aria-label="Previous">
			<span aria-hidden="true">&laquo;</span>
		  </a>
		</li>
		<?php
			}
			else
			{
		?>
		<li class="disabled">
		  <a href="" aria-label="Previous">
			<span aria-hidden="true">&laquo;</span>
		  </a>
		</li>
		<?php
			}
		?>
		<?php
			for($i=0; $i<$jumlah_page; $i++)
			{
			?>
			<li <?php if($page == $i) { echo "class=\"active\""; } ?> ><a style="cursor:pointer;" onclick="gantiPage('<?php echo $i; ?>')"><?php echo ($i+1); ?></a></li>
			<?php
			}
		?>
		
		<?php
			if($page < $jumlah_page - 1)
			{
		?>
		<li>
		  <a style="cursor:pointer;" onclick="gantiPage('<?php echo ($page+1); ?>')" aria-label="Next">
			<span aria-hidden="true">&raquo;</span>
		  </a>
		</li>
		<?php
			}
			else
			{
		?>
		<li class="disabled">
		  <a href="" aria-label="Next">
			<span aria-hidden="true">&raquo;</span>
		  </a>
		</li>
		<?php
			}
		?>
	  </ul>
	 </center>
	</nav>
	<?php
	
	
?>