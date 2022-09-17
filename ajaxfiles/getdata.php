<?php 
include('dataconfig.php');
if($_REQUEST['action'] == 'loadcity') {
	$sql = "SELECT * FROM cities WHERE state_id=".$_REQUEST['stateid'];
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>"; ?>
			<option dataid="<?php echo $row["name"] ?>" value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option> 
		  <?php }
		}
	
}


if($_REQUEST['action'] == 'loadsubprodct') {
	$sql = "SELECT * FROM producttype WHERE proparentid=".$_REQUEST['id']." AND prolevel=2";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>"; ?>
			<option   value="<?php echo $row["id"] ?>"><?php echo $row["producttype"] ?></option> 
		  <?php }
		}
	
}



if($_REQUEST['action'] == 'loadzones') {
	  $sql = "SELECT * FROM regiondata WHERE regionparent=".$_REQUEST['zoned']." AND regioblavel=2";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			 $staetset = explode(',',$row["regionname"]);
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
					foreach($staetset as $staets){
						
			?>
			<label  style="display:block; width:100%; padding-bottom:5px;">
			<input type="checkbox" name="statename[]" id="statename" value="<?php echo $staets; ?>"><?php echo $staets; ?>
			</label>
			 
		  <?php }
		}
	}
	
}

