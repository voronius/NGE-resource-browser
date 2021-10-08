<?php
/////Made by : bintodec/voronius/alexuspanait@yahoo.com (one and only)
/////You're free to change and redistribute as long as you keep the above line
/////I'm not reposnsible for any harm it might cause (like ddos  ...)
/////Purpose of this is to give you a base for your own resource browsing tool
//
//
//
/////////////////////////////////////// CONFIG PART /////////////////////////////////
//user
$user="swg";
//pass
$pass="swg";
//db_url
$db_url="localhost/swg";
//max rows returned, use it to limit traffic (?)
$limit=10;

////////////////////////////////////// END CONFIG PART ///////////////////////////////


require_once("planets_def.php");
require_once("attribute_def.php");
require_once("resource_class_tree.php");


$conn = oci_connect($user, $pass, $db_url);
if (!$conn) 
{
	$e = oci_error();
	echo "connect failed ".print_r($e);
}


$search="";
$search_type_query="";
$sort_attribute='res_quality';
$only_current="";
$current=0;

$offset=0;
$prev_offset=0;
$next_offset=0;


if(!empty($_POST))
{
	if(isSet($_POST["order_res_attribute"]))
	{
		$sort_attribute=$_POST["order_res_attribute"];
	}

	if(isSet($_POST["search"]))
	{
		$search=trim($_POST["search"]);

	}
	if(strlen($search)>0)
	{
		$search_arr=explode(",",$_POST["search"]);
		$count=0;
		foreach ($search_arr as $item_type)
		{
			$item_type=str_replace("_","\\_",addslashes(trim($item_type)));
			$search_type_query.=" and  ( RESOURCE_CLASS like '%".$item_type."%' )";


				
			++$count;

			//limit search on 3 keywords
			if($count>2)
			{
				break;
			}
			
		}
	}


	//if no specific resources searched enable only_current
	else
	{
		$only_current="and depleted_timestamp >= (select last_save_time from clock)";
		$current=1;

	}

	if(isSet($_POST["current"]))
	{
		$only_current="and depleted_timestamp >= (select last_save_time from clock)";
		$current=1;

	}
	
	if(isSet($_POST["prev"]))
	{
		$offset=intval($_POST["offset"]);
		$offset-=$limit;
	}
	else if(isSet($_POST["next"]))
	{
		$offset=intval($_POST["offset"]);
		$offset+=$limit;
	
	}
}



$query_order="select  RESOURCE_ID, RESOURCE_NAME, RESOURCE_CLASS, ATTRIBUTES, FRACTAL_SEEDS , (DEPLETED_TIMESTAMP - (SELECT last_save_time FROM clock)) as DEPLETED_TIMESTAMP, substr(ATTRIBUTES, TO_NUMBER(instr(ATTRIBUTES,'".$sort_attribute." ') ) +".(strlen($sort_attribute)+1).") as first_part from resource_types where ATTRIBUTES like '%".$sort_attribute." %' ".$search_type_query." ".$only_current." order by TO_NUMBER(substr(first_part,0,TO_NUMBER(instr(first_part,':'))-1)) desc";
//." OFFSET ".$offset." ROWS FETCH NEXT ".$limit." ROWS ONLY";





$statement=oci_parse($conn,$query_order);

if(!$statement)
{
	$e = oci_error();
	echo "ERROR: Invalid statement </br>".$e['message'];

}
else
{
	
	echo "<table cellpadding=\"20px\"><tr><td valign=\"top\">Chose your options: ";
	include("form.php");
	echo "</td><td>";
	echo "<table border=\"1\" cellpadding=\"5px\" style=\"border-collapse: collapse; \">";
	echo "<tr>";
	echo "<th style=\"width:150px;background-color: #bde9ba;position: sticky;top: 0;\">Resource</th>";
	echo "<th style=\"width:835px;background-color: #bde9ba;position: sticky;top: 0;\">Attributes</th>";
	echo "<th style=\"width:150px;background-color: #bde9ba;position: sticky;top: 0;\">Planets</th>";
	echo "<th style=\"width:100px;background-color: #bde9ba;position: sticky;top: 0;\">Avail. until<br>(estimated)</th>";



	echo "</tr>\n";

	oci_execute($statement); 
	//$nrows = oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
	$nrows = oci_fetch_all($statement, $res, $offset, $limit, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
	

	foreach ( $res as $row)
	{

		//fix to ignore smelted/mixed whose name will mess up the table
		if(strpos($row['RESOURCE_NAME'],"@resource")===0)
		{
			continue;
		}


		echo "\n <tr>";



		$resource_class=$resource_classes[strval($row['RESOURCE_CLASS'])];
		
		echo "\n\t<td style=\"text-wrap:normal;word-wrap:break-word;\"><b>".$row['RESOURCE_NAME']."</b></br>(".$resource_class["names"][0].")</td>";


		//interpret encoded attribute data
		echo "\n\t";


		$resource_stat_display=array
			(
				"res_decay_resist"=>array("DR"),
			        "res_flavor"=>array("FL"),
			        "res_potential_energy"=>array("PE"),
			        "res_quality"=>array("OQ"),
			        "res_malleability"=>array("MA"),
				"res_shock_resistance"=>array("SR"),
			        "res_toughness"=>array("UT"),
				"res_cold_resist"=>array("CR"),
			        "res_conductivity"=>array("CD"),
				"res_heat_resist"=>array("HR"),
			        "entangle_resistance"=>array("ER")
			);

		echo "<td style=\"width:835px\"><table cellspacing=0 cellpadding=0 style=\"table-layout: fixed; width: 825px\">";

		$attr_header="<tr>";
		$attr_values="<tr>";
		$attr_percent="<tr>";
		
		if($row['ATTRIBUTES']!=="")
		{
			$attr_set=explode(":",$row['ATTRIBUTES']);
			foreach($attr_set as $tmp_data)
			{
				if($tmp_data!=="")
				{

					$tmp_arr=explode(" ",$tmp_data);
					if($tmp_arr[0]!=="")
					{
						$attr_internal=$tmp_arr[0];
						$attr_name=$attributes[$attr_internal];
						$attr_val=intval($tmp_arr[1]);	
						$attr_max=intval($resource_class["Attributes"][strval($attr_internal)."_MAX"]);
						$attr_min=intval($resource_class["Attributes"][strval($attr_internal)."_MIN"]);
						$range=$attr_max-$attr_min;
						if($range<=0) $range=$attr_max;
						$percent=intval(     floatval(($attr_val-$attr_min)/($range))*1000 )/10;

						$resource_stat_display[$attr_internal][1]=$attr_val;
						$resource_stat_display[$attr_internal][2]=$percent;

	

					}
				}
			}
		}

		foreach($resource_stat_display as $attr_data)
		{
			$attr_header.="<td width=\"75px\" align=\"right\" >".$attr_data[0]."</td>";
			$attr_values.="<td align=\"right\" width=\"75px\"  >";
			$attr_values.=(isset($attr_data[1]))?str_replace('~', '&nbsp;',str_pad($attr_data[1],4,'~',STR_PAD_LEFT)):" ";
			$attr_values.="</td>";
			if(isset($attr_data[2]))
			{
				$attr_percent.="<td align=\"right\" width=\"75px\" style=\"color:";
				$attr_percent.=(($attr_data[2]<=49)?"red":(($attr_data[2]<=85)?"blue":"green"));
				$attr_percent.=";\">(".$attr_data[2]."%)</td>";
			}
			else
			{
				$attr_percent.="<td></td>";
			}

		}


		$attr_header.="</tr>";
		$attr_values.="</tr>";
		$attr_percent.="</tr>";
		


		//end attr parsing
		echo $attr_header.$attr_values.$attr_percent."</table></td>";



		$planet_str="";

		//get planets from fractal data
		if (strlen($row['FRACTAL_SEEDS'])==0)
		{
			$planet_str="NONE";

		}
		else
		{
			$spawn=explode(':',$row['FRACTAL_SEEDS']);
			foreach ($spawn as $planet_data)
			{
				if( isset($planet_data) && $planet_data!=="")
				{
					$pdata=explode(' ',$planet_data);

					if(count($pdata)!=2)
					{
						echo "Unable to parse planet data for input : \"".$planet_data."\", row was: \"".$row['FRACTAL_SEEDS']."\"</br>";

					}
					else
					{
						$planet_id=$pdata[0];

						//ignore multiple Kashyyyk entries, this should be ok, right ? 
						if( intval($planet_id)>10000031 && intval($planet_id)<=10000037)
						{
							continue;
						}					
						if(strlen($planet_str)>0)
						{
							$planet_str.=", ";
						}

						if(isset($planet_id) && $planet_id!=="")
						{	
							if(!isset($planets[$planet_id]))
							{
								echo "Unable to find planet name for id : \"".$planet_id."\"</br>";
								echo "<pre>";
								print_r($pdata);
								echo "</pre>";

							}
							else
							{
								$planet_str.=$planets["".$planet_id];
							}
						}
					}


				}
			}

		}

		echo "\n\t<td width=\"150px\">".$planet_str."</td>";



		//interpret depleted - aproximate based on the server clock and depleted timestamp
		//assuming server clock last_save_time is set very often and that the server won't shutdown
		$depleted=time()+$row['DEPLETED_TIMESTAMP'];

		$depleted_date=date('Y-M-d,  H:i:s',$depleted);
		echo "\n\t<td width=\"150px\">".$depleted_date."</td>";








		echo "\n </tr>";
	}


	echo "</table></td></tr><table>";
	echo "</br></br><center>Made by: <b>bintodec/voronius/alexuspanait@yahoo.com</b> (one and only)</center></br>";
}

oci_free_statement($statement);
oci_close($conn);

?>
