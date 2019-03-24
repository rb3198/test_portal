<html>
<head>
	<link rel="stylesheet" type="text/css" href="writestyle.css">
</head>
<body>

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
while($i < count($questions)) {
	echo '<div class= "dark_div">
		<h1>'.$questions[$i].'</h1>';
		if($flag==1){
			$jk = $j % 4;
			echo  '<input type="radio" value='.$jk.'name="'.$j.'">'.$ans[$j].'<br>';
			$j++;
		}
		$flag=1;	
		while ($j == 0 || $j % 4 != 0){
			$jk = $j % 4;
			echo '<input type="radio" value='.$jk.'name="'.$j.'">'.$ans[$j].'<br>';
			$j++;
		}
	echo '</div>';
		$i++;

	}
?>
</body>
</html>
