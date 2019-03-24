<?php
include("../connect.php");


$sql="SELECT * FROM questions;";
$result=mysqli_query($conn,$sql);
$rc=mysqli_num_rows($result);
$questions=array();
$ans=array();
$q=0;
$a=0;
if($rc>0)
{
	while($row=mysqli_fetch_assoc($result))
	{
		$questions[$q]=$row['question'];
		$q++;
		$ans[$a]=$row['a'];
		$a++;
		$ans[$a]=$row['b'];
		$a++;
		$ans[$a]=$row['c'];
		$a++;
		$ans[$a]=$row['d'];
		$a++;
	}
}

$i = 0;
$j = 0;
$flag=0;
$flag1 = 0;
while($i < count($questions)) {
	echo '<div class= "dark_div">
		<h1>'.$questions[$i].'</h1>';
		if($flag==1){
			$jk = $j % 4;
			echo  '<div><div><input type="radio" value='.$jk.'name="'.$j.'"><p>'.$ans[$j].'</p></div>';
			$j++;
		}
		$flag=1;	
		while ($j == 0 || $j % 4 != 0){
			$jk = $j % 4;
			if($flag1 == 1)
				echo '<div><input type="radio" value='.$jk.'name="'.$j.'"><p>'.$ans[$j].'</p></div>';
			else
				echo '<div><div><input type="radio" value='.$jk.'name="'.$j.'"><p>'.$ans[$j].'</p></div></div>';
			$j++;
		}
		echo '</div></div>';
		$flag1 = 1;
		$i++;

	}
?>
