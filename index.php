<?php
require_once("flightxml.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>TrackMe</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="shortcut icon" href="favicon.ico">

<!-- Stylesheets -->
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/owl.carousel.css">
<link rel="stylesheet" href="css/owl.theme.css">
<link rel="stylesheet" href="css/prettyPhoto.css">
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.4.custom.min.css">
<link rel="stylesheet" href="rs-plugin/css/settings.css">
<link rel="stylesheet" href="css/theme.css">
<link rel="stylesheet" href="css/colors/turquoise.css" id="switch_style">
<link rel="stylesheet" href="css/responsive.css">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600,700">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/humanity/jquery-ui.css" type="text/css" />
<!-- Javascripts --> 
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script> 
<script type="text/javascript" src="js/bootstrap.min.js"></script> 
<script type="text/javascript" src="js/bootstrap-hover-dropdown.min.js"></script> 
<script type="text/javascript" src="js/owl.carousel.min.js"></script> 
<script type="text/javascript" src="js/jquery.parallax-1.1.3.js"></script>
<script type="text/javascript" src="js/jquery.nicescroll.js"></script>  
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script> 
<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script> 
<script type="text/javascript" src="js/jquery.jigowatt.js"></script> 
<script type="text/javascript" src="js/jquery.sticky.js"></script> 
<script type="text/javascript" src="js/waypoints.min.js"></script> 
<script type="text/javascript" src="js/jquery.isotope.min.js"></script> 
<script type="text/javascript" src="js/jquery.gmap.min.js"></script> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.plugins.min.js"></script> 
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
<script type="text/javascript" src="js/switch.js"></script> 
<script type="text/javascript" src="js/custom.js"></script> 


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>

<body>



<!-- Header -->
<header>
  <!-- Navigation -->
  <div class="navbar yamm navbar-default" id="sticky">
    <div class="container">
      <div class="navbar-header">
        <button type="button" data-toggle="collapse" data-target="#navbar-collapse-grid" class="navbar-toggle"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a href="index.php" class="navbar-brand">         
        <!-- Logo -->
        <div id="logo"> <img id="default-logo" src="images/logo.png" alt="Starhotel" style="height:64px;"> <img id="retina-logo" src="images/logo-retina.png" alt="Starhotel" style="height:44px;"> </div>
        </a> </div>
      <div id="navbar-collapse-grid" class="navbar-collapse collapse">
        
      </div>
    </div>
  </div>
</header>

<!-- Revolution Slider -->
<section class="revolution-slider">
  <div class="bannercontainer">
    <div class="banner">
      <ul>
        <!-- Slide 1 -->
        <li data-transition="fade" data-slotamount="7" data-masterspeed="1500" > 
          <!-- Main Image --> 
          <img src="http://cdn.images.cunard.com/webimage/HeroImage/Global/Images/Default%20Images/Flights_hero_image_42-42982796.jpg" style="opacity:0;" alt="slidebg1"  data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat"> 
          <!-- Layers -->           
          <!-- Layer 1 -->
          <div class="caption sft revolution-starhotel bigtext"  
          				data-x="10" 
                        data-y="130" 
                        data-speed="700" 
                        data-start="1700" 
                        data-easing="easeOutBack"> 
						<span> </span> Track In Real Time & 3D </div>
          <!-- Layer 2 -->
          <div class="caption sft revolution-starhotel smalltext"  
          				data-x="12" 
                        data-y="205" 
                        data-speed="800" 
                        data-start="1700" 
                        data-easing="easeOutBack">
						<span>Your Loved Ones !</span></div>
        <!-- Layer 3 -->
                 
        </li>

      </ul>
    </div>
  </div>
</section>

<!-- Reservation form -->
<section id="reservation-form">
  <div class="container">
    <div class="row">
      <div class="col-md-12">           
        <form class="form-inline reservation-horizontal clearfix" role="form" method="post">
        <div id="message"></div><!-- Error message display -->
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="email" accesskey="E">Search Type</label>
                  <select class="form-control" name="searchtype" id="searchtype">
                  <option selected="selected" disabled="disabled">Search Type</option>
                  <option value="1">Flight Number</option>
                  <option value="2">Origin/Destination</option>
              
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="room">Flight Number</label>
                    <input name="flightnumber" type="text" id="flightnumber" value="" class="form-control" placeholder="Flight Number" disabled=""/>
            
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="checkin">Flight Origin</label>
                 <input name="origin" type="text" id="origin" value="" class="form-control" placeholder="Flight Origin" disabled=""/>
            
                </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="checkout">Flight Destination</label>
              
               <input name="destination" type="text" id="destination" value="" class="form-control" placeholder="Flight Destination" disabled=""/>
            
                  </div>
            </div>
            
            <div class="col-sm-2">
              <button type="submit" class="btn btn-primary btn-block">Search</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>



      <?php 
      if (isset($_POST["searchtype"]) && (isset($_POST["flightnumber"]) || (isset($_POST["origin"]) && isset($_POST["destination"]))))
      {
        echo '<section class="rooms mt100">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="lined-heading"><span>Search Results</span></h3>
      </div>';
        $type = $_POST["searchtype"];
      if (isset($_POST["flightnumber"])) $flightnumber = $_POST["flightnumber"];
      else if (isset($_POST["origin"]) && isset($_POST["destination"])){
        $destination = $_POST["destination"];
        $origin =  $_POST["origin"];
      }
      switch ($type){
        case 1:
        $flight = GetFlightsFromID($flightnumber);
     //  var_dump($flight);
        if (strlen($flight->origin)>=3){
            $altitudestatus = $flight->altitudeStatus;
            $speed = $flight->groudspeed;
            $origin = $flight->origin;
            $flightid = $flight->faFlightID;
            $destination = $flight->destination;
            $departure = $flight->departureTime;
            $ident = $flight->ident;
            $arrival = $flight->arrivalTime;
            $altitude  = $flight->altitude;
            $heading  = $flight->heading;
            $longitude = $flight->longitude;
            $latitude = $flight->latitude;
            //$arrived = Arrived($destination,$ident);
            $city = getCityfromICAO($origin);
            $country = getCountryfromICAO($origin);
            $destinationData = explode(":",GetAirportLatitudeLongitudeFromAddress(getAirportNamefromICAO($destination)));
            $destinationlat = $destinationData[0];
            $destinationlng = $destinationData[1];
            //echo "Lat $destinationlat lng $destinationlng";
            $originData = explode(":",GetAirportLatitudeLongitudeFromAddress($origin));
            $originlat = $originData[0];
            $originlng = $originData[1];
            $dataimg = GetFlightMap($ident);
            $distanceLeft = round(distance($latitude,$longitude,$destinationlat,$destinationlng),0);
            $countrycode = GetCountryCodeFromCountryName(GetAirportCountryfromIATA(getIATAfromICAO($destination)));
              $distanceTraversed = round(distance($latitude,$longitude,$originlat,$originlng),0);
          
            $totalDistance = round(distance($originlat,$originlng,$destinationlat,$destinationlng),0);
            $percentage = round(($distanceTraversed)/($totalDistance)*100,0);
      //      echo $countrycode;
      if (!$speed) $speed="N/A";
            echo '
            <!-- Room -->
      <div class="col-sm-4" style="height:300px;">
        <div class="room-thumb" style="height:300px;"> <img src="http://www.geonames.org/flags/x/'.$countrycode.'.gif" alt="room 1" class="img-responsive" />
          <div class="mask" style="height:300px;" >
            <div class="main">
              <h5>From '.$city.', '.$country.'</h5>
              <div class="price">'.$ident.'</div>
            </div>
            <div class="content">
              <p><span>Do not worry everything is going well ! The Airplane still needs to traverse '.$distanceLeft.' Kms to land .</p>
              <div class="row">
                <div class="col-xs-6">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check-circle"></i><b>Speed: </b>'.$speed.'</li>
                    <li><i class="fa fa-check-circle"></i> <b>Latitude: </> '.$latitude.'</li>
                    <li><i class="fa fa-check-circle"></i> <b>Longitude: </b>'.$longitude.'</li>
                  </ul>
                </div>
                <div class="col-xs-6">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check-circle"></i> <b>Altitude: </b>'.$altitude.'</li>
                    <li><i class="fa fa-check-circle"></i><b>Arrival: </b>'.getCityfromICAO($destination).'</li>
                    <li><i class="fa fa-check-circle"></i><b>Progress:</b>'.$percentage.'%</li>
                  </ul>
                </div>
              </div>
              ';
              if ($percentage==100)
              echo '
              <a href="#" class="btn btn-primary btn-block">Plane Arrived</a> </div>';
              else
              echo '
               <a href="#"  class="btn btn-primary btn-block" action="notify" data="'.$ident.'">Notify Me On Arrival</a>
                <a href="download.php?lat='.$latitude.'&lng='.$longitude.'&alt='.$altitude.'&flight='.$ident.'" class="btn btn-primary btn-block">See Flight in 3D !</a> </div>';
               ';
               
              ';
              echo'
          </div>
        </div>
      </div>
      <img id="traject" flight="'.$ident.'" src="data:image/png;base64,' . $dataimg . '" height="300px" />
            ';
        }
        else
        echo "<center><h1>No Results Found</h1></center>";
        break;
        
        case 2;
      $origin = urlencode($origin);
      $destination = urlencode($destination);
      $originICOA = GetAirportICAOfromIATA(GetAirportCodeFromName($origin));
      $destinationICOA = GetAirportICAOfromIATA(GetAirportCodeFromName($destination));
   
        $flights = GetFlightsFromOriginDestination($originICOA,$destinationICOA);
 
        foreach ($flights as $flight){
     
        if (isset($flight->origin)){
            $altitudestatus = $flight->altitudeStatus;
            $speed = $flight->groudspeed;
            $origin = $flight->origin;
            $flightid = $flight->faFlightID;
            $destination = $flight->destination;
            $departure = $flight->departureTime;
            $ident = $flight->ident;
            $arrival = $flight->arrivalTime;
            $altitude  = $flight->altitude;
            $heading  = $flight->heading;
            $longitude = $flight->longitude;
            $latitude = $flight->latitude;
            //$arrived = Arrived($destination,$ident);
            $city = getCityfromICAO($origin);
            $country = getCountryfromICAO($origin);
            $destinationData = explode(":",GetAirportLatitudeLongitudeFromAddress(getAirportNamefromICAO($destination)));
            $destinationlat = $destinationData[0];
            $destinationlng = $destinationData[1];
            $originData = explode(":",GetAirportLatitudeLongitudeFromAddress($origin));
            $originlat = $originData[0];
            $originlng = $originData[1];
            
            $distanceLeft = round(distance($latitude,$longitude,$destinationlat,$destinationlng),0);
            $countrycode = GetCountryCodeFromCountryName(GetAirportCountryfromIATA(getIATAfromICAO($destination)));
            $distanceTraversed = round(distance($originlat,$originlng,$latitude,$longitude),0);
            
            $totalDistance = round(distance($originlat,$originlng,$destinationlat,$destinationlng),0);
            $percentage = round(($distanceTraversed)/($totalDistance)*100,0);
      //      echo $countrycode;
      if (!$speed) $speed="N/A";
            echo '
            <!-- Room -->
      <div class="col-sm-4" style="height:300px;">
        <div class="room-thumb" style="height:300px;"> <img src="http://www.geonames.org/flags/x/'.$countrycode.'.gif" alt="room 1" class="img-responsive" />
          <div class="mask" style="height:300px;" >
            <div class="main">
              <h5>From '.$city.', '.$country.'</h5>
              <div class="price">'.$ident.'</div>
            </div>
            <div class="content">
              <p><span>Do not worry everything is going well ! The Airplane still needs to traverse '.$distanceLeft.' Kms to land .</p>
              <div class="row">
                <div class="col-xs-6">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check-circle"></i><b>Speed: </b>'.$speed.'</li>
                    <li><i class="fa fa-check-circle"></i> <b>Latitude: </> '.$latitude.'</li>
                    <li><i class="fa fa-check-circle"></i> <b>Longitude: </b>'.$longitude.'</li>
                  </ul>
                </div>
                <div class="col-xs-6">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check-circle"></i> <b>Altitude: </b>'.$altitude.'</li>
                    <li><i class="fa fa-check-circle"></i><b>Destination: </b>'.getCityfromICAO($destination).'</li>
                    <li><i class="fa fa-check-circle"></i><b>Progress:</b>'.$percentage.'%</li>
                  </ul>
                </div>
              </div>
              ';
              if ($percentage==100)
              echo '
              <a href="#" class="btn btn-primary btn-block">Plane Arrived</a> </div>';
              else
              echo '
               <a href="#" class="btn btn-primary btn-block" action="notify" data="'.$ident.'">Notife Me On Arrival</a>
              <a href="download.php?lat='.$latitude.'&lng='.$longitude.'&alt='.$altitude.'&flight='.$ident.'" class="btn btn-primary btn-block">See Flight in 3D !</a> </div>';
                 ';
               
              ';
              echo'
          </div>
        </div>
      </div>
            
            ';
        }
        else{
            echo "<h1>No Results Found</h1>";
        }
        }
        break;
      }
      }
      
 
?>
</div>
</div>

<!-- Go-top Button -->
<div id="go-top"><i class="fa fa-angle-up fa-2x"></i></div>

</body>
 
       <script src="jqueryui.js"></script>
<script>

$('#origin').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'link.php',
		      			dataType: "json",
						data: {
						   city: request.term
						 
						},
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						}
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 1      	
		      });
              
              $('#destination').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'link.php',
		      			dataType: "json",
						data: {
						   city: request.term
						 
						},
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						}
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 1      	
		      });
              
              $("#searchtype").change(function(){
               valued = $(this).val();
               if (valued==1){
              
                $("#origin").attr("disabled","disabled");
                $("#destination").attr("disabled","disabled");
                
              $("#flightnumber").removeAttr("disabled");
               }
               
                else if (valued==2){
                $("#flightnumber").attr("disabled","disabled");
                  $("#origin").removeAttr("disabled");
                 $("#destination").removeAttr("disabled");
               }
              });
              
              
              $("a[action='notify']").click(function(e){
                e.preventDefault();
                flight = ($(this).attr("data"));
    var person = prompt("Please enter your phone number", "Phone Number");
    
    if (person != null) {
        
        $.post( "include/linker.php", { phone: person, flight: flight  })
  .done(function( data ) {
   if (data==1) alert("Added With Success");
  else alert("An Error Occured");
  });
        
     
      
    }
  
});


 </script>
</html>