<style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
</style>
<div class="row">
      <div class="col-lg-12">
        <h1 class="mt-5">$Title</h1>
        $Content
       
        <% with $Lead %>
	        <h6>Posted $LeadAge ago</h6>
	        <% if $CurrentMember && $MemberPurchased %>
	        	<div class="alert alert-warning" role="alert">You have already purchased this lead on $MemberPurchased.Created.Format(hh:mma EEE dd LLLL Y)</div>
	        <% end_if %>
	        
	        <table class="table table-hover">
  				<tbody>
    				<tr>
      					<th scope="row">Reference</th>
      					<td>$ID</td>
    				</tr>
    				<tr>
      					<th scope="row">Name</th>
      					<td>$HideInfo($FirstName) $HideInfo($LastName)</td>
    				</tr>
    				<tr>
      					<th scope="row">Email</th>
      					<td>$HideInfo($Email)</td>
    				</tr>
    				<tr>
      					<th scope="row">Mobile</th>
      					<td>$HideInfo($Mobile)</td>
    				</tr>
    				<tr>
      					<th scope="row">City</th>
      					<td>$City</td>
    				</tr>
    				<tr>
      					<th scope="row">Employment</th>
      					<td>$Employment</td>
    				</tr>
    				<tr>
      					<th scope="row">Employment Contact</th>
      					<td>$HideInfo($EmploymentContact)</td>
    				</tr>
    				<tr>
      					<th scope="row">Bad Credit</th>
      					<td>$BadCredit</td>
    				</tr>
    				<tr>
      					<th scope="row">IP Address</th>
      					<td>$SubmittedIPAddress</td>
    				</tr>
    				<tr>
      					<th scope="row">ReferredFrom</th>
      					<td>$ReferredFrom</td>
    				</tr>
    				<tr>
      					<th scope="row">Location</th>
      					<td>$GoogleMapLocation</td>
    				</tr>
    				<tr>
      					<th scope="row">Loan Type</th>
      					<td>$LoanType</td>
    				</tr>
    				<tr>
      					<th scope="row">Loan Amount</th>
      					<td colspan="2">$Top.FormatCurrency($LoanAmount)</td>
    				</tr>
    				<tr>
      					<th scope="row">Repayment Period</th>
      					<td colspan="2">$RepaymentPeriod</td>
    				</tr>
    				<tr>
      					<th scope="row">Sale Type</th>
      					<td colspan="2">
      						<% if $SellStage == 0 %>
      							First Sale
      						<% else_if $SellStage == 1 %>
      							Second Sale
      						<% else_if $SellStage >= 2 %>
      							Third Sale
      						<% end_if %>
      					</td>
    				</tr>
    				<tr>
      					<th scope="row">Original Price</th>
      					<td colspan="2">$Top.FormatCurrency($SalePrice)</td>
    				</tr>
				    <tr>
      					<th scope="row">Current Price</th>
      					<td colspan="2">$Top.FormatCurrency($NewSalePrice)</td>
    				</tr>
    				<% if $CurrentMember %>
	    				<tr>
    	  					<th scope="row">Rate this lead</th>
      						<td id="rating-stars" colspan="2">
								<i id="1" data-id="$ID" <% if $MemberRating >= 1 %>style="color:orange;"<% end_if %> class="fa fa-star" aria-hidden="true"></i>
								<i id="2" data-id="$ID" <% if $MemberRating >= 2 %>style="color:orange;"<% end_if %> class="fa fa-star" aria-hidden="true"></i>
								<i id="3" data-id="$ID" <% if $MemberRating >= 3 %>style="color:orange;"<% end_if %> class="fa fa-star" aria-hidden="true"></i>
								<i id="4" data-id="$ID" <% if $MemberRating >= 4 %>style="color:orange;"<% end_if %> class="fa fa-star" aria-hidden="true"></i>
								<i id="5" data-id="$ID" <% if $MemberRating >= 5 %>style="color:orange;"<% end_if %> class="fa fa-star" aria-hidden="true"></i>
							</td>
    					</tr>
    				<% end_if %>
    				<tr>
      					<th scope="row"></th>
      					<td colspan="2"><a class="btn btn-primary <% if $CurrentMember && $MemberPurchased %>disabled<% end_if %>"  href="/lead/purchase/$ID"  role="button">Buy Now</a></td>
    				</tr>
  				</tbody>
			</table>
			<div id="map"></div>
    		<script>
				// Initialize and add the map
				function initMap() {
  				// The location of Uluru
  				var uluru = {lat: $Latitude, lng: $Longitude};
  				// The map, centered at Uluru
  				var map = new google.maps.Map(
      				document.getElementById('map'), {zoom: 10, center: uluru});
  					// The marker, positioned at Uluru
  					var marker = new google.maps.Marker({position: uluru, map: map});
				}
    		</script>			
    	<% end_with %>
      </div>
	  $Form
	  $CommentsForm
</div>