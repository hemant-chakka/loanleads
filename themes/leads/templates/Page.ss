<!DOCTYPE html>
<html lang="$ContentLocale">

<head>
	<% base_tag %>
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	$MetaTags(false)
	<link rel="shortcut icon" href="themes/leads/images/favicon.ico" />

  	<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>

		<!-- Font Awesome 4.7.0 -->
		<link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  	<!-- Bootstrap core CSS -->
  	<link href="/_resources/themes/leads/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  	<link href="/_resources/themes/leads/css/style.css" rel="stylesheet">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="background:#eee">
	<% include Header %>
  	$MessageV2
  <!-- Page Content -->
	<div class="container">
  $Layout
	</div>
  <% include Footer %>
  <!-- Bootstrap core JavaScript -->
  <script src="/_resources/themes/leads/vendor/jquery/jquery.min.js"></script>
  <script src="/_resources/themes/leads/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="https://kit.fontawesome.com/3198717f7e.js"></script> -->
  <% if $URLSegment == 'lead' %>
  	<script src="/_resources/themes/leads/javascript/lead.js"></script>
  	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=$PHPConstant('GOOGLE_MAPS_API_KEY')&callback=initMap">
    </script>
  <% end_if %>
  <% if $URLSegment == 'register' %>
  	<script src="/_resources/themes/leads/javascript/register.js"></script>
  <% end_if %>
  <% if $URLSegment == 'leads' %>
  	<script src="/_resources/themes/leads/javascript/leads.js"></script>
  <% end_if %>
  
</body>

</html>
