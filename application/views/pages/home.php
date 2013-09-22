<?php
	if($cookie=$this->input->cookie('mycyc_xiaoqu'))
		header('Location: restaurant/'.$cookie);
?>
<h3>请选择校区</h3>
<div class="choose">
<a href="restaurant/ndbny" class="btn btn-default home-btn">宁波大学<br />(本部·南门·甬江)</a>
<a href="restaurant/ndxxq" class="btn btn-default home-btn">宁波大学<br />(西校区)</a>
<a href="restaurant/ndbq" class="btn btn-default home-btn">宁大北区<br />(纺院·科院)</a>
</div>
