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
function look_for_tie($link)
{
	$sql='select * from student s,student ss
			where s.id<>ss.id and 
			s.catagory=ss.catagory and 
			s.all_india=ss.all_india and
			s.tie_breaker=ss.tie_breaker';
			
	$result=mysql_query($sql,$link);echo mysql_error();
	if(mysql_num_rows($result)>0)
	{
		echo 'there is tie<br>';
		while($array=mysql_fetch_assoc($result)){echo '<pre>'; print_r($array);echo '</pre>';};
		return false;
	}
	return true;
}

function prepare_merit_list($link)
{
	$sql='select * from student order by origin,all_india, tie_breaker';
	$result=mysql_query($sql,$link);
	$array=array();
	while($x=mysql_fetch_assoc($result)){$array[]=$x;}
	return $array;
}

function print_merit_list($array)
{
	echo '<table border=1><th colspan=10 >Merit list</th>';
	foreach($array as $value)
	{
		echo '<tr>';
		foreach($value as $v)
		{
			echo '<td>'.$v.'</td>';
		}	
		echo '</tr>';
	}
	echo '</table>';
}

function prepare_choice_list($link,$student_id)
{
	$sql='select * from choice where student_id=\''.$student_id.'\' order by choice';
	$result=mysql_query($sql,$link);
	$array=array();
	while($x=mysql_fetch_assoc($result)){$array[]=$x;}
	return $array;
}





function find_vacancy($link,$subloc_id,$catagory)
{
	$sql='select * from seat where 
				subloc_id=\''.$subloc_id.'\' and 
			catagory=\''.$catagory.'\' and 
				number>0';
				
	$result=mysql_query($sql,$link);echo mysql_error();
	return mysql_fetch_assoc($result);
}
function vacant_seat($link,$subloc_id,$catagory,$student_id)
{
	$sql='update student set alloc_subloc_id=null where id=\''.$student_id.'\'';	
	
	echo $sql.'<br>';		
	$result=mysql_query($sql,$link);echo mysql_error();	
	
	$sql='update seat set number=number+1 where 
				subloc_id=\''.$subloc_id.'\'	and 
				catagory=\''.$catagory.'\'';
	
	echo $sql.'<br>';			
	$result=mysql_query($sql,$link);echo mysql_error();	
		
}

function fill_seat($link,$subloc_id,$catagory,$student_id)
{
	$sql='update student set alloc_subloc_id=\''.$subloc_id.'\' where id=\''.$student_id.'\'';	
	
	echo $sql.'<br>';		
	$result=mysql_query($sql,$link);echo mysql_error();	
		
	$sql='update seat set number=number-1 where 
				subloc_id=\''.$subloc_id.'\'	and 
				catagory=\''.$catagory.'\'';
	
	echo $sql.'<br>';			
	$result=mysql_query($sql,$link);echo mysql_error();	
}	

/////////////Start////////////////
$link=mysql_connect('127.0.0.1','root','nishiiilu');
mysql_select_db('npg',$link);

if(look_for_tie($link)===false){exit(0);}

$merit_list=prepare_merit_list($link);
print_merit_list($merit_list);

foreach($merit_list as $value)
{
	$choice_list=prepare_choice_list($link,$value['id']);
	//print_choice_list($choice_list);
	foreach($choice_list as $choice_element)
	{
		//echo 'Vacant seats(OPEN):'.$choice_element['subloc_id'].'<br>';		
		$vacant_choice=find_vacancy($link,$choice_element['subloc_id'],'Open');
		//print_array($vacant_choice);
		if($vacant_choice['number']>0)
		{
			if($value['alloc_subloc_id']<1)
			{

			}
			else
			{
				vacant_seat($link,$vacant_choice['subloc_id'],'Open',$value['id']);
			}
			fill_seat($link,$vacant_choice['subloc_id'],'Open',$value['id']);
			break;			
		}
		
		//echo 'Vacant seats(Catagory):'.$choice_element['subloc_id'].'<br>';		
		$vacant_choice=find_vacancy($link,$choice_element['subloc_id'],$value['catagory']);
		//print_array($vacant_choice);
		if($vacant_choice['number']>0)
		{
			if($value['alloc_subloc_id']<1)
			{

			}
			else
			{
				vacant_seat($link,$vacant_choice['subloc_id'],$value['catagory'],$value['id']);
			}
			fill_seat($link,$vacant_choice['subloc_id'],$value['catagory'],$value['id']);
			break;			
		}
	}
}

$merit_list=prepare_merit_list($link);
print_merit_list($merit_list);
 
?>
