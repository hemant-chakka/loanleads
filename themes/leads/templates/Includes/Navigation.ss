<div class="collapse navbar-collapse" id="navbarResponsive">
    <ul class="navbar-nav ml-auto">
         <% loop $Menu(1) %>
          	
          	<% if $Top.CurrentMember && $URLSegment == 'register' %>
          	<% else %>
	          	<li class="nav-item<% if $isCurrent %> active<% end_if %>">
		            <a href="$Link" title="Go to the $Title page" class="nav-link">
    		            $MenuTitle
        		        <% if $isCurrent %><span class="sr-only">(current)</span><% end_if %>
            		</a>
        		</li>
        	<% end_if %>
  		<% end_loop %>
  		<li class="nav-item">
	    	<% if $CurrentMember %>
	    		<a href="$LogoutURL" title="Go to the $Title page" class="nav-link">Logout</a>
	    	<% else %>
	    		<a href="/Security/login" title="Go to the $Title page" class="nav-link">Login</a>
	    	<% end_if %>
        </li>
        <% if $CurrentMember %>
	        <li class="nav-item">
	        	<a href="#" title="Available balance" class="nav-link">Balance: $FormatCurrency($CurrentMember.Balance)</a>
    	    </li>
        <% end_if %>
    </ul>
</div>
  