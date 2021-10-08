<div style="position:fixed;top:80px; left:10px">
<form action="res.php" method="post">
<!--
	<input type="radio" name="class_or_type" value="class" autocomplete="off" onchange="if(this.checked) { this.form.select_res_type.disabled=true;this.form.select_res_class.disabled=false;}" checked> From class:</input>

	</br>

	<select name="select_res_class" autocomplete="off">
		 <option value="Inorganic">ALL</option>
		 <option value="Inorganic">Inorganic</option>
		 <option value="Organic">Organic</option>
		 <option value="Mineral">Mineral</option>
		 <option value="Ore">Metal</option>
	</select>

	</br>
	<input type="radio" name="class_or_type" value="type" autocomplete="off" onchange="if(this.checked) {this.form.select_res_class.disabled=true; this.form.select_res_type.disabled=false;}"> Of type:</input>

	</br>
	<select name="select_res_type" disabled="true" autocomplete="off">
		 <option value="Inorganic">Duralloy Steel</option>
		 <option value="Organic">Ditanium Steel</option>
		 <option value="Mineral">Polysteel Copper</option>
		 <option value="Ore">Titanium alluminum</option>
	</select>
//-->
	<label for="">Comma separated 
	</br> or leave empty
	</br>(ex.:must,intrusive):</label>
	</br>
	
	<input type="text" name="search" value="<?php echo $search;?>">
	
	</br>
	</br>
	<!--
	<label for="select_res_planet">Only on planet:</label>
	</br>
	<select name="select_res_planet" autocomplete="off">
		 <option value="All">ALL</option>
		 <option value="Inorganic">Corellia</option>
		 <option value="Organic">Naboo</option>
		 <option value="Mineral">Talus</option>
		 <option value="Ore">Lok</option>
	</select>
	</br>
	</br>
	</br>
	//-->
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
