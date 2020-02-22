<div class="row">
      <div class="col-lg-12">
        <h1 class="mt-5">$Title</h1>
        $Content
        <p>&nbsp;</p>
        <p><h5><a href="/register">Edit profile</a></h5></p>
        <p><h5>Current Balance: $FormatCurrency($CurrentMember.Balance)</h5></p>
        <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" >Load Money</button></p>
       
        <h2 class="mt-5">Leads Purchased</h2>
		<% if $LeadsPurchased %>
	        <table class="table table-striped">
  				<thead>
    				<tr>
      					<th scope="col">Reference</th>
      					<th scope="col">Name</th>
				    	<th scope="col">Loan Type</th>
	      				<th scope="col">Loan Amount</th>
    	  				<th scope="col">Sale Price</th>
    	  				<th scope="col">Age</th>
    	  				<th scope="col">Purchased</th>
    	  				<th scope="col">View Invoice</th>
    				</tr>
  				</thead>
	  			<tbody>
    	    		<% loop $LeadsPurchased %>
    					<tr>
							<th scope="row">#$ID</th>
	    					<% with $LoanLead %>
      							<td><a href="$Link">$FirstName $LastName</a></td>
      							<td>$LoanType</td>
      							<td>$Top.FormatCurrency($LoanAmount)</td>
	      						<td>$Top.FormatCurrency($SalePrice)</td>
	      						<td>$LeadAge ago</td>
    						<% end_with %>
    						<td>$Created.Format(hh:mma EEE dd LLLL Y)</td>
    						<td><a href="/dashboard/invoice/$ID">View Invoice</a></td>
    					</tr>
        			<% end_loop %>
        		</tbody>
			</table>
		<% else %>
		<p>No leads purchased yet</p>
        <% end_if %>
      </div>
	  $Form
	  $CommentsForm
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Load Money</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/dashboard/paypalCreatePayment" method="post">
			<label>Amount:</label> <input type="text" name="Amount" required >
			<button type="submit" class="btn btn-primary">Pay</button>
		</form>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>