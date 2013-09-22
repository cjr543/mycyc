<?php
function echo_state($state) {
	if (!$state)
		echo "休息中";
	else
		echo "来易份";
}
?>

<div id="store-header">
	<?=$store_name?>--食物列表|<a href="<?=constant("mycycbase")?>/store/info/<?=$university_id?>/<?=$store_id?>">店铺信息</a>
</div>

<div id="button-wrapper">
	<button class="btn btn-default btn-sm" id="confirm-button">去提交订单</button>
	<button class="btn btn-default btn-sm" id="reset-button">重置所有</button>
</div>

<div id="food-category-wrapper">
	<ul id="food-category">
		<?php
			foreach($food_type as $val) {?>
				<li><a href="#category-<?=$val['foodTypeId']?>"><?=$val['foodTypeName']?></a></li>
		<?php
			}
		?>
	</ul>
</div>	

<div class="food_info">
	<?php
		foreach($food_type as $val) { 
	?>
	        <div class='section'id='category-<?=$val['foodTypeId']?>'>
				<div class="food_type"><p><?=$val['foodTypeName']?></p><a class="top" href="#header">回到顶部</a></div>
				<div class="my_food">
				<?php
					$my_food = $food_info[$val['foodTypeId']]; 
					if (count($my_food['no_img']) > 0) { 
						echo "<table>";
						foreach ($my_food['no_img'] as $v) { 
				?>
							<tr class='food_no_img' id='food-<?=$v['foodId']?>'>
								<td class='no_f_name'>
									<?=$v['foodName']?><br />
									<?=$v['note']?>
								</td>
								<td class='no_f_price'>￥<?=$v['price']?></td>

								<td class='no_f_btn'>
									<a <?php if (!$state) echo "disabled"?> class='btn btn-success btn-xs food_no_img_line order-one' 
										food-id='<?=$v['foodId']?>'
										food-name='<?=$v['foodName']?>'
										food-price='<?=$v['price']?>'>
										<?php echo_state($state); ?>
									</a>
								</td>
								<td class="no_f_symbol" food-id='<?=$v['foodId']?>'></td>
							</tr>
				<?php
						}
						echo "</table>";
					}
				?>
					</div>
				</div>
	<?php 
		}
	?>
</div>
	
<script src="<?=constant("mycycbase")?>/js/jquery-1.10.2.min.js"></script>

<script>
var order = new Array();

$(".order-one").click(function(){
	foodId=$(this).attr("food-id");
	foodName=$(this).attr("food-name");
	chooser=".no_f_symbol[food-id='"+foodId+"']";
	isChecked=$(chooser).html();
	if(isChecked){
		if((deletePos=order.indexOf(foodName))>=0)
			order.splice(deletePos, 1);
		$(chooser).empty();
	}
	else{
		order.push(foodName);
		$(chooser).append("<span class='glyphicon glyphicon-ok'></span>");
	}
});

$("#reset-button").click(function(){
	$(".no_f_symbol").empty();
});
</script>


