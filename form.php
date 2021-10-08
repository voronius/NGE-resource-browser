<div style="position:fixed;top:80px; left:10px">
<form action="res.php" method="post">
	<label for="">Comma separated 
	</br> or leave empty
	</br>(ex.:must,intrusive):</label>
	</br>
	
	<input type="text" name="search" value="<?php echo $search;?>">
	
	</br>
	</br>
	<label for="order_res_attribute">Order by attribute:</label>
	</br>
	<select name="order_res_attribute" >
		 <option value="res_quality" >OQ</option>
		 <option value="res_decay_resist"<?php if( strcmp($sort_attribute,"res_decay_resist")==0) echo " selected";?> >DR</option>
		 <option value="res_flavor"<?php if( strcmp($sort_attribute,"res_flavor")==0) echo " selected";?> >FL</option>
		 <option value="res_potential_energy"<?php if( strcmp($sort_attribute,"res_potential_energy")==0) echo " selected";?> >PE</option>
		 <option value="res_malleability"<?php if( strcmp($sort_attribute,"res_malleability")==0) echo " selected";?> >MA</option>
		 <option value="res_shock_resistance"<?php if( strcmp($sort_attribute,"res_shock_resistance")==0) echo " selected";?> >SR</option>
		 <option value="res_toughness"<?php if( strcmp($sort_attribute,"res_toughness")==0) echo " selected";?> >UT</option>
		 <option value="res_cold_resist"<?php if( strcmp($sort_attribute,"res_cold_resist")==0) echo " selected";?> >CR</option>
		 <option value="res_conductivity"<?php if( strcmp($sort_attribute,"res_conductivity")==0) echo " selected";?> >CD</option>
		 <option value="res_heat_resist"<?php if( strcmp($sort_attribute,"res_heat_resist")==0) echo " selected";?> >HR</option>
		 <option value="entangle_resistance"<?php if( strcmp($sort_attribute,"entangle_resistance")==0) echo " selected";?> >ER</option>

	</select>

	</br>
	</br>
	<label for="current"> Only current 
	</br>
	<input type="checkbox" name="current" <?php if($current==1) echo " checked"; ?>>
	</br>
	</br>
	</br>
	<input type="submit" value="Go" style="font-weight:bold;"/>
	<input type="hidden" name="offset" value="<?php echo $offset;?>">

	<?php
	echo "<input type=\"submit\" name=\"next\" value=\" next >> \"></br>"; 
	if($offset>0) echo "<input type=\"submit\" name=\"prev\" value=\" <prev \">"; 

	?>






</form>
</div>
