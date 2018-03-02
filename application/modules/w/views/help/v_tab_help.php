<ul id="mytab" class="tab-style-1 clearfix">
	<?php 
	if (count($sql) > 0){
		foreach ($sql as $row){		
		$name = strtolower(str_ireplace(" ", "-", $row->name_helper));
		echo '<li><a href="'.base_url(FOMODULE.'/'.$name.'-3.'.$row->id_helper.'.html').'">'.$row->name_helper.'</a></li>';
		}
	}?>
	
	
</ul>