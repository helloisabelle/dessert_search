<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/pure-min.css" integrity="sha384-oAOxQR6DkCoMliIh8yFnu25d7Eq/PHS21PClpwjOTeU2jRSq11vu66rf90/cZr47" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Dessert Search</title>
</head>

<body>

    <div id = "n"><a href = "index.php" style = "text-decoration: none;">Dessert Search</a></div>

    <form id = "s" class="pure-form pure-form-stacked" action = "exam.php" method = "GET">
	    <fieldset>
	        <legend>Dessert Search</legend>

	       <label for="loc">Enter location: </label>
	        <input id="loc" type="text" name="loc_field" required>
	        <span class="pure-form-message" style = "color:#510091;">Required field.</span>

	        <label for="name">Enter restaurant: </label>
	        <input id="name" type="text" name="name_field">
          <span class="pure-form-message" style = "color:#510091; padding-bottom: 2%;">Optional field for higher precision.</span>

          <span style = "padding-right: 3%;">Open At: </span>  
           <span style = "float:right;">Now </span><input style = "display: inline-block; float: right; width: 5%; padding: 0; margin-top:1.5%;" type="checkbox" id="now"> 


          <input id="time" type="time" name="time_field" >
          <span class="pure-form-message" style = "color:#510091; padding-bottom: 2%;">Optional field for higher precision.</span>

          <label for="sel">Sort By: </label>
          <select id = "sel" name = "sel">
            <option value = "best_match">Best Match</option>
            <option value = "distance">Distance</option>
            <option value = "rating">Rating</option>
            <option value = "review_count">Review Count</option>
        </select>

	        <button type="submit" class="btn" style = "color:#510091;">Submit </button>
	    </fieldset>
	</form>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.1/js/mdb.min.js"></script>
<script>
	$('#now').on('click', function(e) {
        if($(this).prop("checked") == true){
            var d = new Date();
            let x = d.getHours() + ":";
            if (d.getMinutes() < 10){
            x+="0";
            }
            x+= d.getMinutes();

            $("#time").val(x);
        }
        else if($(this).prop("checked") == false){
            $("#time").val("");
        }
    });
</script>

</html>



