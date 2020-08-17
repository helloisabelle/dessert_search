
<?php
    $API_KEY = '5n03Og1T-_ldbICV5t5j2_6aTqkGeBtSPIpLqQHQULJtmk8JiE5ptJ-6mxN-nBkhR_ubnM5wXLTEpNmSeybXw9lilDwdp-UJJsWpChZIgkWtaonS45mpKa_zTtg5X3Yx';
    $API_HOST = "https://api.yelp.com";
    $SEARCH_PATH = "/v3/businesses/search";
    $BUSINESS_PATH = "/v3/businesses/";
        
    $term = $_GET["name_field"];
    $location = $_GET["loc_field"];
    $name = $term;
    $address = $location;
    console.log($_GET["loc_field"]);
    console.log($location + 'here!!');
    function request($host, $path, $url_params = array()) {
        try {
            $curl = curl_init();
            if (FALSE === $curl)
                throw new Exception('Failed to initialize');

            $url = $host . $path . "?" . http_build_query($url_params);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . $GLOBALS['API_KEY'],
                    "cache-control: no-cache",
                ),
            ));

            $response = curl_exec($curl);

            if (FALSE === $response)
                throw new Exception(curl_error($curl), curl_errno($curl));
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status)
                throw new Exception($response, $http_status);

            curl_close($curl);
        } catch(Exception $e) {
            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        }

        return $response;
    }

    function search($term, $location) {
        $url_params = array();
        
        $url_params['term'] = $term;
        $url_params['location'] = $location;
        $url_params['limit'] = 5;
        $url_params['sort_by'] = $_GET["sel"];
        $url_params['open_at'] = getUnix();

        console.log($location + 'here');
        console.log($url_params['location']);
        
        return request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
    }

    function query_api($term, $location) {
        $response = json_decode(search($term, $location));

        if (count($response->businesses) == 0){
            echo "<div id = 'error'>Found nothing.</div>";
        }
        else{
            for ($i = 0; $i < count($response->businesses); $i++){
                $full_add = "";
            for ($j = 0; $j < count($response->businesses[$i]->location->display_address); $j++){
                $full_add = $full_add . " " . $response->businesses[$i]->location->display_address[$j];
            }
            
            echo " <div class = \"info\"> <img src = '".$response->businesses[$i]->image_url ."' class=\"img-thumbnail img-fluid\">
                        <br>
                        <br>
                        <h4><a target=\"_blank\" href = ' ".$response->businesses[$i]->url. "'>". $response->businesses[$i]->name."</a></h4>
                        <div>Rating: ";


                        for ($k = 1; $k <= $response->businesses[$i]->rating; $k++){
                            echo "<i class = 'fa fa-star'></i>";
                        }
                        for ($k = $response->businesses[$i]->rating; $k < 5; $k++){
                            if (round($k, 0) != $k){
                                echo "<i class = 'fa fa-star-half-o'></i>";
                                $k -= .5;
                            }
                            else echo "<i class = 'fa fa-star-o'></i> ";
                        }

                        echo $response->businesses[$i]->review_count." reviews</div>
                        <div>Distance: " .getMiles($response->businesses[$i]->distance). " miles away from ". $GLOBALS['name'] . "</div> <div>Address: ".$full_add . "</div>
                        </div>";
            if ($i != count($response->businesses) - 1) echo "<hr>";
            }
        } 
    }

    function query_api1($term, $location) {     
        $response = json_decode(search($term, $location));
        $full_add = "";

        if (count($response->businesses) == 0){

            if ($term = ""){
                echo "<div id = \"error\">Could not find anything that matches your input around this location.  </div>";
                exit();
            }
            else{
                echo "<div id = \"error\">Could not find exact business that matches your input.  Will search using your location input.</div>";
                return $location;
            }
            
        }
        else{
            for ($j = 0; $j < count($response->businesses[0]->location->display_address); $j++){
                $full_add = $full_add . " " .  $response->businesses[0]->location->display_address[$j];
            }
            return $full_add;
        }
    }

    function getMiles($i) {
        return round($i*0.000621371192, 2);
    }

    function getUnix(){
        $todayh = getdate();
        $d = $todayh["mday"];
        $m = $todayh["month"];
        $y = $todayh["year"];
        $x = $d  ." " . $m . " " . $y . " " . $_GET["time_field"] . " PST";
        return strtotime($x);

    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/pure-min.css" integrity="sha384-oAOxQR6DkCoMliIh8yFnu25d7Eq/PHS21PClpwjOTeU2jRSq11vu66rf90/cZr47" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Dessert Search</title>
</head>

<body>


    <div id = "n"><a href = "index.php" style = "text-decoration: none;">Dessert Search</a></div>


<?php


if (isset($_GET["name_field"]) && !empty($_GET["name_field"])){
    $address = query_api1($term,$location);

    if ($address == $location){
        $name = $location;
    }
}
else{
    $name = $location;
}

?>
<section class="pricing py-5">
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h6 class="card-price text-center">Ice cream</h6>
            <hr>
            <?php
             query_api("ice cream",$address) ?>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h6 class="card-price text-center">Coffee</h6>
            <hr>
                <?php query_api("coffee",$address) ?>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h6 class="card-price text-center">Boba</h6>
            <hr>
                <?php query_api("boba",$address) ?>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h6 class="card-price text-center">Bakery</h6>
            <hr>
                <?php query_api("pastries",$address) ?>
          </div>
        </div>
      </div>    
  </div>
</section>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</html>