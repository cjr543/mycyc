<div class="address">	
	<?php 
		$cookie=array(
			'name' => 'xiaoqu',
			'value' => $pageName,
			'expire' => '3600',
			'prefix' => 'mycyc_'
		);
		$this->input->set_cookie($cookie);
		echo $college;
	?>
	<a href=<?=constant("mycycbase")."/deletecookie"?>>更换地址</a>
	<form action=<?=constant("mycycbase").'/restaurant/'.$pageName.'/'?> method="get" id="filter">
		<input type="checkbox" name="opening" id="opening" <?php if($opening)echo "checked" ?>/>
		<label for="opening">营业中</label>
		<select name="taste" id="taste_choice">
			<option value="0">全部口味</option>
			<?php $count = 0; ?>
			<?php foreach($store_type as $item):?>
			<option value="<?=++$count?>" <?php if($taste==$count)echo "selected";?>><?=$item['storeTypeName']?></option>
			<?php endforeach;?>
		</select>
	</form>
</div>

<div class="restaurant">
	<?php require("restaurant_body.php");?>
</div>
<!--
<script src="<?=constant("mycycbase")?>/js/jquery-1.10.2.min.js"></script>
<script>
	var university_id=<?=$university_id?>;
	function _get_store_info() {
		var checked=0;
		if($('#opening').attr('checked')) {
			checked = 1;
		};
		var taste = $('#taste_choice').find("option:selected").attr('value');
		$.ajax({
			type:'GET',
			dataType: 'html',
			url: '<?=constant("mycycbase")?>'+'/restaurant/ajax_get_store_info/'+university_id+'/'+checked+'/'+taste, 
			success: function(data) {
				$('.restaurant').html(data);
			},
			error:function(data) {
				alert('网络出错');
			}
		});
	}

	$("#taste_choice").change(function(){
		if($('#taste_choice').find("option:selected").attr('value')!=0)
			_get_store_info();
	});  
	$("#opening").click(function() {
		if($('#opening').attr('checked'))
			$('#opening').removeAttr('checked');
		else
			$('#opening').attr('checked', 'true');
		_get_store_info();
	});
</script>-->
<script src="<?=constant("mycycbase")?>/js/jquery-1.10.2.min.js"></script>
<script>
	$("#taste_choice").change(function(){
		$("#filter").submit();
	});  
	$("#opening").click(function() {
		if($('#opening').attr('checked'))
			$('#opening').removeAttr('checked');
		else
			$('#opening').attr('checked', true);
		$("#filter").submit();
	});
</script>