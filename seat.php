<?php




/*

catagory---|seat
catagory---|student
	
subloc-----|seat
		
student----|choice
seat-------|choice
	



For each student in merit list
	for each choice of the student
			if number of open vacancy for choice is>0
				if student is already given any seat
					vacant it
					give new seat
					mark given seat occupied
					exit loop to get new student
				else
					give new seat
					mark given seat occupied
					exit loop to get new student
			else
				if student is of any catagory and number of vacancy for the catagory-choice is>0
					if student is already given any seat
						vacant it
						give new seat
						mark given seat occupied
						exit loop to get new student
					else
						give new seat
						mark given seat occupied
						exit loop to get new student

*/


function print_array($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function mk_select_from_sql($link, $sql, $name, $display,$real, $disabled, $default)
{
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
	
		echo '<select  '.$disabled.' name='.$name.'>';
		while($result_array=mysql_fetch_assoc($result))
		{
		if($result_array[$real]==$default)
		{
			echo '<option selected  value=\''.$result_array[$real].'\'> '.$result_array[$display].' </option>';
		}
		else
			{
				echo '<option  value=\''.$result_array[$real].'\'> '.$result_array[$display].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array_return_value($name, $select_array,$disabled,$default)
{
		echo '<select  '.$disabled.' name='.$name.'>';
		foreach($select_array as $key=>$value)
		{
		if($value==$default)
		{
			echo '<option selected value=\''.$value.'\' > '.$value.' </option>';
		}
		else
			{
				echo '<option  value=\''.$value.'\' > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function read_seat($link)
{
	echo '<form method=post>';
	$sql='select id, concat_ws("-",id,subject,location) display from subloc';
	$display='display';
	$real='id';	 
	mk_select_from_sql($link,$sql,'id',$display,$real,'','');	
	mk_select_from_sql($link,'select catagory from catagory','catagory','catagory','catagory','','');
	$num=array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
	mk_select_from_array_return_value('max_number',$num,'','');
	echo '<input type=submit name=save_seat value=\'add seat\'>';
	echo '</form>';
}

function save_seat($link)
{
	$subloc_id=$_POST['id'];
	$catagory=$_POST['catagory'];
	$max_number=$_POST['max_number'];
	$number=$max_number;
	
	if($number>0)
	{
		$sql='insert into seat values(\''.$subloc_id.'\',\''.$catagory.'\',\''.$number.'\',\''.$max_number.'\')';
		$result=mysql_query($sql,$link);
		if(!$result)
		{
			echo mysql_error().'<br>';
		}
		else
		{
			echo 'success<br>';
		}
	}
	else
	{
		echo 'seats can not be zero<br>';
	}
}

function print_seat($link)
{
	
	$sql='select subloc_id,subject,location, catagory,number,max_number from seat,subloc where
				subloc_id=id order by subject,location,catagory';
	$result=mysql_query($sql,$link);
	echo '<form method=post><table border=1><th colspan=10 >Seats</th>';
	echo '<tr><th>del</th><th>subject</th><th>location</th><th>catagory</th><th>available</th><th>max</th></tr>';
	while($x=mysql_fetch_assoc($result))
	{
		echo '<tr>';
		echo '<td><button type=submit name=delete value=\''.$x['subloc_id'].'^'.$x['catagory'].'\'>X</button></td>';
		foreach($x as $k=>$v)
		{
			if($k!='subloc_id')
			{
				echo '<td>'.$v.'</td>';
			}
		}	
		echo '</tr>';
	}
	echo '</table></form>';
}

function delete_seat($link)
{
	$ex=explode('^',$_POST['delete']);
	$subloc_id=$ex[0];
	$catagory=$ex[1];
	$sql='delete from seat where subloc_id=\''.$subloc_id.'\' and catagory=\''.$catagory.'\'';
	$result=mysql_query($sql,$link);
}


$link=mysql_connect('127.0.0.1','root','pppp');
mysql_select_db('npg',$link);
read_seat($link);
if(isset($_POST['save_seat']))
{
	save_seat($link);
}

if(isset($_POST['delete']))
{
	delete_seat($link);
}

print_seat($link);
//print_array($_POST);
?>
