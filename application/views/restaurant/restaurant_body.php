<?php
	$count = 0;
	// var_dump($i);
	//var_dump($store_info);
	foreach ($store_info as $val) {
		//var_dump($val);
		if($val['state_choise']!="open")
			$state="closed";
		else
			$state="";
?>
		<div class="info <?=$state?>">
			<hr />
			<div class="name"><a href="<?=constant("mycycbase")?>/store/<?=$university_id?>/<?=$val['storeId']?>/"><?=$val['storeName']?></a></div>
			<div class="status"><span><?=$val['state']?></span><span class="deliver-amount">起送价：<?=$val['delivery_cost']?></span></div>
			<div class='special'>特色：<?=$val['each_store_type']?></div>
		</div>
<?php 
		$count++;
	}
?><br/>
<p><?php echo $links;?></p>


	

