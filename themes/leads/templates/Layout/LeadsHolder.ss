<div class="row">
	<div class="col-sm-12 text-center">
	<h1 class="mt-5">New leads posted daily!</h1>
	$Content
</div>
</div>
		
		<div class="row">
			<div class="col-md-3">
				<% if $GetCategories %>
					<select id="category" class="filter">
						<option value=''>Filter by category..</option>
						<% loop $GetCategories %>
							<option value='$Category'>$Category</option>
						<% end_loop %>
					<select>
				<% end_if %>
			</div>
			<div class="col-md-3">
				<select id="amount" class="filter">
					<option value=''>Filter by amount..</option>
					<option value='10'>less than R10,000</option>
					<option value='20'>between R10,000 and R30,000</option>
					<option value='30'>more than R30,000</option>
				<select>
			</div>
			<div class="col-md-3">
				<select id="posted" class="filter">
					<option value=''>Filter by posted..</option>
					<option value='24'>posted in the last 24 hours</option>
					<option value='48'>posted in the last 48 hours</option>
				<select>
			</div>
			<div class="col-md-3">
				<select id="location" class="filter">
					<option value=''>Filter by location..</option>
					<option value='Johannesburg'>Johannesburg</option>
					<option value='Cape Town'>Cape Town</option>
					<option value='Durban'>Durban</option>
					<option value='Pretoria'>Pretoria</option>
				<select>
			</div>
		
		</div>
		
		<div>
			<a class="float-right" href="javascript:" id="leads-list-btn"><i class="fa fa-th-list" aria-hidden="true"></i></a>
			<a class="float-right" style="margin-right:10px;" href="javascript:" id="leads-grid-btn"><i class="fa fa-th" aria-hidden="true"></i></a>
		</div>
		
		<% if $PubLeads %>
		<% loop $PubLeads %><% if First %><div class="row" id="grid"><% end_if %>
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="border lead">
			<div class="col-sm-12 text-center">
				<span class="ref-id badge badge-light">ref #$ID</span>
				<br>
				<span class="small">posted <strong>$LeadAge</strong> ago</span>
			</div>
			
			<div class="col-sm-12 text-center">
				<hr>
				<div class="lead-intro">
					<p style="font-size:1.2em">An <strong><% if $Employment == "Yes" %>employed<% else %>unemployed<% end_if %></strong> person<% if $BadCredit == "Yes" %> with <strong>bad credit</strong><% end_if %> from <strong>$City</strong> is applying for <strong>$LoanType</strong> of <strong>$Top.FormatCurrency($LoanAmount)</strong></p>
				</div>
				<hr>
			</div>
			<div class="col-sm-12 small">
				<!--// Lead attributes -->
				<ul class="lead-attributes">
					<li><span>Name</span> <strong>$FirstName $HideInfo($LastName)</strong> <% if $NameVerified %><span class="text-uppercase badge badge-success"><i class="fa fa-tick"></i> verified</span><% else %><span class="text-uppercase badge badge-secondary">not verified</span><% end_if %></li>
					<li><span>Cellphone</span> <strong>$HideInfo($Mobile)</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
					<li><span>Email</span> <strong>$HideInfo($Email)</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
					<li><span>Employment</span> <strong>$Employment</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
					<li><span>Bad Credit</span> <strong>$BadCredit</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
					<% if $SubmittedIPAddress %><li><span>IP Address</span> <span class="text-uppercase badge badge-success">verified</span></li><% end_if %>
				</ul>
				<a href="$Link"><i class="fa fa-search"></i> View Full Application Details</a>
			</div>
			

			<div class="col-sm-12 text-center">
				<hr>
				<% if $SellStage != 0 %>
				<p class="price-was"><span class="small">was <strike>$Top.FormatCurrency($SalePrice)</strike></p>
				<p class="price-new"><span>$Top.FormatCurrency($NewSalePrice)</span></p>
				<% else %>
				<span class="price-original">$Top.FormatCurrency($NewSalePrice)</span>
				<% end_if %>
			</div>
			
			<div class="col-sm-12 text-center">
				<hr>
				<span class="small">This lead is</span><br>
				<% if $SellStage == 0 %>
				<span style="font-size:1.3em" class="text-uppercase badge badge-success" title="This lead has never been purchased"><i class="fa fa-star"></i> exclusive <i class="fa fa-star"></i></span>
				<% else_if $SellStage == 1 %>
				<span style="font-size:1.3em" class="text-uppercase badge badge-light" title="This lead has been purchased once">not exclusive</span>
				<% else_if $SellStage >= 2 %>
				<span style="font-size:1.3em" class="text-uppercase badge badge-light" title="This lead has been purchased twice">not exclusive</span>
				<% end_if %>
			</div>
			
			<div class="col-sm-12">
				<hr>
				<a style="width:100%" class="btn <% if $CurrentMember && $MemberPurchased %>btn-warning disabled<% else %>btn-primary<% end_if %>"  href="/lead/purchase/$ID"><% if $CurrentMember && $MemberPurchased %>Already Purchased<% else %>Buy Now<% end_if %></a>
			</div>
			
			<div class="col-sm-12 small text-center">
				<br>
				<% if $SellStage == 0 %>
				<span>*This lead has never been purchased.</span>
				<% else_if $SellStage == 1 %>
				<span>*This lead has been purchased once.</span>
				<% else_if $SellStage >= 2 %>
				<span>*This lead has been purchased twice.</span>
				<% end_if %>
			</div>
		</div>
			
			
		</div>
		<% if Last %></div><% end_if %>
		<% end_loop %>
		<% end_if %>
		
		
		
		
		
		
		
		<% if $PubLeads %>
<div id="list" style="display:none;">
<% loop $PubLeads %>


<div class="row border" style="width:100%;background:#fff;padding-top:1em;padding-bottom:1em;margin:1em auto;">


	<div class="col-md-12">
		<span style="font-size:1.25em" class="badge badge-light">ref #$ID</span><br><% if $SellStage == 0 %><span style="font-size:1.25em" class="text-uppercase badge badge-success"><i class="fa fa-star"></i> <strong>exclusive sale</strong> <i class="fa fa-star"></i></span><% else %><span class="text-uppercase badge badge-light"><i class="fa fa-star"></i> <strong>not exclusive</strong> <i class="fa fa-star"></i></span><% end_if %>
		<p class="small">This lead was posted <strong>$LeadAge</strong> ago,
			<% if $SellStage == 0 %>
			and is has never been purchased.
			<% else_if $SellStage == 1 %>
			but has been purchased once before.
			<% else_if $SellStage >= 2 %>
			but has been purchased twice before.
			<% end_if %></p>
		</div>
		<div class="col-sm-12">
			<hr>
			<p style="font-size:1.2em;margin-bottom:0">An <strong><% if $Employment == "Yes" %>employed<% else %>unemployed<% end_if %></strong> person<% if $BadCredit == "Yes" %> with <strong>bad credit</strong><% end_if %> from <strong>$City</strong> is applying for <strong>$LoanType</strong> of <strong>$Top.FormatCurrency($LoanAmount)</strong></p>
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collpase-$ID" aria-expanded="false" aria-controls="collpase-$ID">Show details</button>
		</div>






		<div class="col-sm-12 small collapse multi-collapse" id="collpase-$ID">
			<hr>
			<ul style="padding-left:1em">
				<li><span>Name</span> <strong>$FirstName $HideInfo($LastName)</strong></li>
				<li><span>Cellphone</span> <strong>$HideInfo($Mobile)</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
				<li><span>Email</span> <strong>$HideInfo($Email)</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
				<li><span>Employment</span> <strong>$Employment</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
				<li><span>Bad Credit</span> <strong>$BadCredit</strong> <span class="text-uppercase badge badge-secondary">not verified</span></li>
			</ul>
			<a href="$Link"><i class="fa fa-search"></i> View Full Application Details</a>
		</div>
			
		<div class="col-sm-12">
			<hr>
			<% if $SellStage != 0 %>
			<span>was <strike>$Top.FormatCurrency($SalePrice)</strike><br><span>now $Top.FormatCurrency($NewSalePrice)</span>
			<% else %>
			<span>$Top.FormatCurrency($NewSalePrice)</span>
			<% end_if %>
			<a class="btn <% if $CurrentMember && $MemberPurchased %>btn-warning disabled<% else %>btn-primary<% end_if %>" href="/lead/purchase/$ID"><% if $CurrentMember && $MemberPurchased %>Already Purchased<% else %>Buy Now<% end_if %></a>
		</div>
			
		<div class="col-sm-12 small">
			<hr>
			<% if $SellStage == 0 %>
			<span>*This lead has never been purchased.</span>
			<% else_if $SellStage == 1 %>
			<span>*This lead has been purchased once.</span>
			<% else_if $SellStage >= 2 %>
			<span>*This lead has been purchased twice.</span>
			<% end_if %>
		</div>
	</div>
	<% end_loop %>
	</div>
	<% end_if %>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	$Form
	$CommentsForm