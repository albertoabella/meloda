<?php

//    crappy, dirty and quick API for MELODa 4.10 reusability metric
// 	  full model in http://www.meloda.org/wp-content/uploads/2016/10/Meloda4.102.pdf
//    More academic info about it in http://www.meloda.org/scientific-article-about-meloda/

//    Copyleft Alberto Abella García alberto.abella@meloda.org

//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.

//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

$dimensions=Array( 
	"1" => "legal", 
	"2" => "technical_standard", 
	"3" => "access", 
	"4" => "data_model",
	"5" => "geolocation",
	"6" => "timeliness");

// location of the logos for the qualified datasets
	
$logo = array (
	"black" => "http://www.meloda.org/wp-content/uploads/2016/01/deficient_data_reuse_meloda_black.png",
	"red" => "http://www.meloda.org/wp-content/uploads/2016/01/basic_data_reuse_meloda_red.png",
	"yellow" => "http://www.meloda.org/wp-content/uploads/2016/01/good_data_reuse_meloda_yellow.png",
	"green" => "http://www.meloda.org/wp-content/uploads/2016/01/optimum_data_reuse_meloda_green.png");

// retrieve the contents of a web page and dump them into a variable
function retrieve_URL( $url)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec ($ch);
curl_close ($ch);
return $data;
}

//check the parameters sent. First parameter is the action (and it is released in uppercase
function format_parameters( $entrada )
{
	$answer=array(
		"parameters_number" => "0",
		"command" => "no command"
		);
		
		for ($i=0;$i<sizeof($entrada);$i++)
		{
			$answer[$i]=$entrada[$i];
	}
	$tamano=sizeof($entrada);
	if ($tamano==0) 
	{
		$answer["parameters_number"]=$tamano;
		$answer["command"]="No command";
	}
	else 
	{
		$answer["parameters_number"]=$tamano;
		$answer["command"]=strtoupper($entrada[0]);
	}
	return $answer;
}

// currently not using this code
// get the HTTP method, path and body of the request
//$method = $_SERVER['REQUEST_METHOD'];

// split the url into the components 
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

// format it and check if they have more than one parameter
$respuesta=format_parameters($request);

 
// connect to the mysql database
// this user has only rights to select into the database
$link = mysqli_connect("localhost", "calendb0_api", "meloda410", "calendb0_meloda");

//mysqli_set_charset($link,'utf8');
 
// if a command has been detected in the URL
// otherwise returns nothing
if ($respuesta["command"]<>"No command") 
{
// the different action allowed in this API, currently WEIGHT, TEXT, MELODA
	switch($respuesta["command"]):
	
		case "WEIGHT": 
		// command WEIGHT returns the weight for the level of a specific dimension 
		// example
		// http://meloda.org/api/api.php/weight/data_model/3
		// it asks for the WEIGHT of dimension data model in its level 3
		//exmaple 
		//http://meloda.org/api/api.php/weight/4/3
		// it returns the same as the previous one becuase data_model is the parameter 4 
		// check variable $dimension at the beginning of this code
		
		// check if the # of paramters is the right one for this action
		if ($respuesta["parameters_number"]==3)
		{
			// check if the parameters is asked by its number 
			if (strlen($request[1])==1) // parameter send directly
			{
				$param=$request[1];
			}
			else
			{				
				//looking for the name of the parameter in the array $dimension
				$param=array_search($request[1], $dimensions); // founding the string of parameter in the dimensions
				
			}
			if (strlen($param)==0) // name of the parameter not found
			{
				$answer["result"]=FALSE;
				$answer["value"]="Not found such MELODA dimension";
			}
			else 
			{
				// sql instruction for retrieving the weight
				$sql= "SELECT * FROM M_API_values WHERE Parameter=$param AND Level=".$request[2]."";
				// excecute SQL statement
				$query_result = mysqli_query($link, $sql);
 
				// die if SQL statement failed
				if (!$query_result)
				{
					http_response_code(404);
					die(mysqli_error());
				}
				$row=$query_result->fetch_array(MYSQLI_ASSOC); // bring the result
				$answer= array(
					"value" => $row["Value"],
					"result" => (($row==NULL)?FALSE:TRUE) // if not found parameter then returns empty
				);
			}
		}	
		else
		{
			// the URL was malformed  
			$answer= array(
			"value" => "wrong # of parameters",
			"result" => FALSE
			);
		}
		break;
		
		case "TEXT":
		// instruction for retrieving a parameter and level value based
		// on the name of a standard 
		
		if ($respuesta["parameters_number"]==2)
		{
			// looking for the text into the database
			// parameters are stored in a text field separated with commas
			// that's why the search string is concatenated with a comma at the end
			$sql="SELECT * FROM `M_API_values` WHERE LOWER(`Texts`) LIKE LOWER(\"%".$request[1].",%\")";

			// execute mysql instruction
			$query_result = mysqli_query($link, $sql);

 
			// die if SQL statement failed
			if (!$query_result)
			{
				http_response_code(404);
				$answer["result"]=FALSE;
				$answer["value"]="Not possible connection with database";
				die(mysqli_error());
			}
			else
			{
				$row=$query_result->fetch_array(MYSQLI_ASSOC); // bring the result
				// the answer is filled with all the possible info
				$answer= array(
					"value" => $row["Value"],
					"result" => (($row==NULL)?FALSE:TRUE),
					"parameter" => $row["Parameter"],
					"level" => $row["Level"],
					"parameter_text" => $dimensions[$row["Parameter"]]
				);
			}
		}
		else
		{
			// mal formed URL for this action
			$answer= array(
			"value" => "wrong # of parameters",
			"result" => FALSE
			);
		}
		break;
		
		case "MELODA":
		//http://meloda.org/api410.php/meloda/L1/L2/L3/L4/L5/L6
		// L1..6 are the levels of the dimensions
		// example 
		// http://www.meloda.org/api/api410.php/meloda/3/4/5/5/5/4
		// returns 77.99 points
		// and the parameter green 
		// http:\/\/www.meloda.org\/wp-content\/uploads\/2016\/01\/optimum_data_reuse_meloda_green.png
		// meloda = 100* 6th sqare root of the dimensions product
		
		if ($respuesta["parameters_number"]==7)
		{
			$product=1; // variable to store the result of the product
			$meloda_ok=TRUE; // vairable to check if the sent parameters are in range
			for ($i=1;$i<7;$i++) // loop for the 6 dimensions
			{
				$dimen=$dimensions[$i]; // auxiliar variable
				$URL="http://meloda.org/api/api410.php/weight/".$dimen."/".$respuesta[$i]; // look for the weight with this API
				
				// retrieve the element using the API
				$pre_answer=(array)retrieve_URL($URL);
				
				// it is only needed the value not the full object
				// but it is embeded into an object that's why we need to select the element 0
				$answer=(array)json_decode($pre_answer["0"]);
				
				$meloda_ok=$meloda_ok*$answer["result"]; // checking if the parameters were valid
				$product=$product*$answer["value"]; // multiplying weights for each level's dimension
				
			}
			if($meloda_ok) // all parameters were right
			{
				$answer= array(
					"value" => round(100*pow($product, 1/6),2), // calculation of the value
					"result" => TRUE
				);
			// this loop provides the logo representing the reusability value
				if ($answer["value"]<25)
				{
					$answer["logo"]=$logo["black"];
				}
				elseif ($answer["value"]<50)
				{
					$answer["logo"]=$logo["red"];
				}
				elseif ($answer["value"]<75)
				{
					$answer["logo"]=$logo["yellow"];
				}
				else 
				{
					$answer["logo"]=$logo["green"];
				}
			}
			else
			{
				// there were som problem with the parameters therefore 
				$answer= array(
					"value" => "error",
					"result" => FALSE
				);
			}
		}
		else
		{
			// malformed URL
			$answer= array(
			"value" => "wrong # of parameters",
			"result" => FALSE,
			);
		}
			break;
		
		default:
			break;
	endswitch;
	
}		
echo (json_encode($answer));

 
//close mysql connection
mysqli_close($link);
?>
