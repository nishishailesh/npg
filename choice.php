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


//choice from a given student
function read_choice($link,$student_id)
{
	$sql='select * from student where id=\''.$student_id.'\'';
	
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
	
	$ra=mysql_fetch_assoc($result);

		
	echo '<form method=post>';
	
	echo '<table border=1>
				<tr><th colspan=2>Student details</th></tr>
				<tr><td>id:</td><td><input type=text readonly name=student_id value=\''.$ra['id'].'\'</td></tr>
				<tr><td>name:</td><td>'.$ra['name'].'</td></tr>
				<tr><td>catagory:</td><td>'.$ra['catagory'].'</td></tr>
				<tr><td>origin:</td><td>'.$ra['origin'].'</td></tr>
				<tr><td>AIPGME score:</td><td>'.$ra['all_india'].'</td></tr>
	</table>';
		
	$sql='select distinct id,subject,location from subloc,seat where subloc.id=seat.subloc_id';
	$result=mysql_query($sql,$link);
	echo '<table border=1><tr><th colspan=5>Available Seats for selection</th></tr>';
	while($x=mysql_fetch_assoc($result))
	{
		echo '<tr>
					<td><button name=subloc_id value=\''.$x['id'].'\'>'.$x['id'].'</button></td>
					<td>'.$x['subject'].'</td>
					<td>'.$x['location'].'</td>
			</tr>';
	}

	echo '<input type=hidden name=action value=save_choice>';
	echo '</form>';
	echo '</table>';
}

function find_max_choice_id($link,$student_id)
{
	$sql='select max(choice) from choice where student_id=\''.$student_id.'\'';
	
	$result=mysql_query($sql,$link);
	if(!$result)
	{
		echo mysql_error().'<br>';
		return false;
	}
	else
	{
		//echo 'success<br>';
	}	
	
	$ar=mysql_fetch_assoc($result);
	return $ar['max(choice)'];
}

function save_choice($link)
{
	$subloc_id=$_POST['subloc_id'];
	$student_id=$_POST['student_id'];
	
	$max=find_max_choice_id($link,$student_id);
	if($max!==FALSE){$next=$max+1;}
	else{$next=1;}
	
	$sql='insert into choice values(\''.$student_id.'\',\''.$subloc_id.'\',\''.$next.'\')';
	$result=mysql_query($sql,$link);
	if(!$result)
	{
		echo mysql_error().' So no changes are made.<br>';
	}
	else
	{
		//echo 'success<br>';
	}
}


function delete_choice($link)
{
	$ex=explode('^',$_POST['delete']);
	$student_id=$ex[0];
	$subloc_id=$ex[1];
	$sql='delete from choice where student_id=\''.$student_id.'\' and subloc_id=\''.$subloc_id.'\'';
	//echo $sql;
	$result=mysql_query($sql,$link);
	echo mysql_error();
}

function print_choice($link,$student_id)
{
	$sql='select student_id,subloc_id,subject,location,choice from choice,subloc 
			where choice.subloc_id=subloc.id
			and student_id=\''.$student_id.'\'
			order by choice';
	$result=mysql_query($sql,$link);
	echo '<form method=post><table border=1><th colspan=10 >Choice supplied</th>';
	echo '<tr><th>del</th><th>student_id</th><th>subloc_id</th><th>Choice</th><th>subject</th><th>location</th><th>choice_id</th></tr>';
	$seq=1;
	while($x=mysql_fetch_assoc($result))
	{
		echo '<tr>';
		echo '<td><button type=submit name=delete value=\''.$x['student_id'].'^'.$x['subloc_id'].'\'>X</button></td>';
		echo '<td>'.$x['student_id'].'</td><td>'.$x['subloc_id'].
						'</td><td style="background-color:lightblue;">'.$seq.'</td>
						<td  style="background-color:lightblue;">'.$x['subject'].'</td>
						<td  style="background-color:lightblue;">'.$x['location'].'</td>
						<td>'.$x['choice'].'</td>';
		echo '</tr>';
		$seq=$seq+1;
	}
	echo '</table></form>';
}

function print_seat($link)
{
	$sql='select subloc_id,subject,location, catagory,number,max_number from seat,subloc where
				subloc_id=id order by subject,location,catagory';
	$result=mysql_query($sql,$link);
	echo '<form method=post><table border=1><th colspan=10 >Seats with reservations</th>';
	echo '<tr><th>subject</th><th>location</th><th>catagory</th><th>available</th><th>max</th></tr>';
	while($x=mysql_fetch_assoc($result))
	{
		echo '<tr>';
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

$link=mysql_connect('127.0.0.1','root','pppp');
mysql_select_db('npg',$link);

$student_id=1;

if(isset($_POST['delete']))
{
	delete_choice($link);
}
if(isset($_POST['action']))
{
	if($_POST['action']=='save_choice')
	{
		save_choice($link);
	}
	
}

echo '<table border=1>';
echo '<tr>';
echo '<td valign=top>';
		read_choice($link,$student_id);
echo '</td><td valign=top>';
		print_choice($link,$student_id);
echo '</td></tr>';
echo '<tr>';
echo '<td colspan=2>';
print_seat($link);
echo '</td></tr>';
//print_array($_POST);
?>
