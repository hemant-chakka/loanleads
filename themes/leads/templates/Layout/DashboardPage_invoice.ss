<table class="table">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Invoice</h1></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><h2>Buy Leads</h2></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a onclick="window.print();" href="javascript:">print</a>/<a href="/dashboard/pdf/$LeadPurchase.ID">download</a></td>
    </tr>
    <tr>
      <td>$LeadPurchase.Created.Nice</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
      	Billed To:<br>
      	<% with $LeadPurchase.Member %>
      	  $Name<br>
      	  $Email<br>
      	  $Mobile
      	<% end_with %>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>Description</td>
      <td>Price</td>
      <td>Qty</td>
      <td>Amount</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><% with $LeadPurchase.LoanLead %>$LoanType of $Top.FormatCurrency($LoanAmount)<% end_with %></td>
      <td>$Top.FormatCurrency($LeadPurchase.Price)</td>
      <td>1</td>
      <td>$Top.FormatCurrency($LeadPurchase.Price)</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Invoice Total</td>
      <td>$Top.FormatCurrency($LeadPurchase.Price)</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
