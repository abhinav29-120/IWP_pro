<?php
$insert = false;
$joker=false;
if(isset($_POST['url'])){

//$url = "https://sso.connect.pingidentity.com/sso/pages/sessionterminationdone#overview";

$url = $_POST['url'];
//scheme_or_protocol
$protocol = (parse_url($url, PHP_URL_SCHEME));
//echo $protocol,'<br>';

// domain
function get_domain($url)
{
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return false;
}
$domain = get_domain($url).'<br>';
//echo $domain;
/*
//false domain_check_1
$parse = parse_url($url);
$domain = $parse['host'];
$domain = str_ireplace('www.', '', $domain);
echo $domain,'<br>';
*/

//Subdomain check_1
/*
$parsedUrl = parse_url($url);
$host = explode('.', $parsedUrl['host']);
$subdomain = $host[0];
echo $subdomain,'<br>';
*/

//for multiple subdomains
$parsedUrl = parse_url($url);
$host = explode('.', $parsedUrl['host']);
$subdomains = array_slice($host, 0, count($host) - 2 );
//print_r($subdomains);
$n = count ($subdomains);
//echo $n,'<br>';
$jvr="";
for($i=0;$i<$n; $i++)
{
    //echo $subdomains[$i],'<br>';
    if($i==0)
    {
        //echo $i;
        $jvr=$jvr.$subdomains[$i];
    }
    else{
        $jvr = $jvr.".".$subdomains[$i];
    }  
}
//echo $jvr."<br>";

//$jk = $subdomains[0].'.'.$subdomains[1];
//echo $jk,'<br>';



//hostname
$hostname = parse_url($url, PHP_URL_HOST);
//echo $hostname.'<br>';

/*
//host check_2
$parse = parse_url($url);
echo $parse['host'];
*/

//ip
$ip = gethostbyname($hostname);
//echo $ip."<br>";

//establishing the connection;
$server = "localhost";
$username = "root";
$password = "";


// Create a database connection
$con = mysqli_connect($server, $username, $password);
if(!$con)
{
    die("connection to this database failed due to".mysqli_connect_error());
}
//echo "connection established to the db <br>";

/*
$sql = "INSERT INTO `proj_0`.`link_inp` (`Protocol`, `subdomain`, `hostn`) VALUES ('$protocol','$jvr','$hostname')";

// Execute the query
if($con->query($sql) == true)
{
// echo "Successfully inserted <br>";
    
    // Flag for successful insertion
//$insert = true;
    }
else{
        echo "ERROR: $sql <br> $con->error";
    }

    // Close the database connection
//$con->close();
*/
/*
$sql2 = "SELECT*FROM `proj_0`.`link_inp`";
// Execute the query

    $result = $con->query($sql2);

    if ($result->num_rows > 0) 
    {
      // output data of each row
      while($row = $result->fetch_assoc()) 
      {
       echo /"<br> snm: ".$row['snm']."  Protocol: ".$row["Protocol"]." subdomain: ".$row["subdomain"]." hostname: ".$row["hostn"]."<br>";
      }
    } 
    else 
    {
        echo "0 results";
    }
    //$con->close();
*/

// query for checking
 $sql3= "SELECT*FROM `proj_0`.`link_inp` WHERE Protocol LIKE'$protocol' AND subdomain LIKE'$jvr' AND hostn  LIKE'$hostname'";
 $result = $con->query($sql3);

 if ($result->num_rows>0)
 {
    $insert = true;
    while($row = $result->fetch_assoc()) 
    {
     //echo "  Protocol: ".$row["Protocol"]." <br> subdomain: ".$row["subdomain"]. "<br> hostname: ".$row["hostn"]."<br>";
     //echo "badhai ho link safe ha";
    }
}
 else 
 {
    $joker=true; 
    //echo "0 results";
    //echo "apka katne vala ha";
 }
 
 $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page</title>
	<link rel="stylesheet" href='Project.css'>
</head>
<body>
	<header>
		<h1>Security Pal</h1>
		<nav>
			<span>
				<a href="Page1.html" class="active">Home</a>
				<a href="Page2.html">About</a>
				<a href="Page3.html">Types</a>
				<a href="Page4.html">Techniques</a>
				<a href="Page5.html">Safety</a>
			</span>
		</nav>
	</header>
	
	<!--<form action="i3.php" method="POST">
		<input type="url" name="url" id="url" placeholder="url" required>
		<button class = "button">submit</button> <input class="btn" type="submit" name="submit" value="Check">
	</form>-->
   <form action="i3.php" method="POST" target = "_self">
        <fieldset class="account-info">
           <label>
               Domain Name
               <input class="pg1" type="url" name="url" id="url" placeholder="Please enter the url" required>
           </label>
        </fieldset>
        <fieldset class="account-action">
            <input class="btn" type="submit" name="submit" value="Check">
        </fieldset>
   </form> 
   <div class="result">
        <?php

        if($insert == true)
            {
                echo " <div class='righ'>The link seems to be safe, you can freely visit the website. <br> Thanks for visiting G.P.Y.A </div>";
            }
            elseif($joker==true)
            {
                echo"<div class='righ'>The link does not seem to be legitimate,<br>be careful before visiting the website.<br>It could be malicious</div>";
            }
        ?>
    </div>

	<footer>
		<br><br><br>
		<nav>
			<span>
				<a href="Page1.html"class="hbtn hb-border-bottom-br3">Home</a>
				<a href="Page2.html"class="hbtn hb-border-bottom-br3">About</a>
				<a href="Page3.html"class="hbtn hb-border-bottom-br3">Types</a>
				<a href="Page4.html"class="hbtn hb-border-bottom-br3">Techniques</a>
				<a href="Page5.html"class="hbtn hb-border-bottom-br3">Safety</a>
			</span>
		</nav>
		<h4>Thanks for visiting. Stay safe and stay secure</h4>
	</footer>
</body>
</html>