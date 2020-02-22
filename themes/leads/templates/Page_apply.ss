<article class="col-sm-12 col-md-10 col-md-offset-1 text-left" id="content">
    <hr>
    <div class="col-md-6 col-md-offset-3 subscribe-embedded-form">
      <form id="applyForm" action="/home/saveLead" method="post" class="form-validate-jqueryz" novalidate="novalidate">
        <div class="field text">
          <label for="FIRST_NAME">First name<span class="text-danger">*</span></label>
          <input id="FIRST_NAME" placeholder="eg. John" value="" name="FIRST_NAME" class="required error" type="text" aria-invalid="true"><label id="FIRST_NAME-error" class="error" for="FIRST_NAME">This field is required.</label>
        </div>
        <div class="field text">
          <label for="LAST_NAME">Last name<span class="text-danger">*</span></label>
          <input id="LAST_NAME" placeholder="eg. Smith" value="" name="LAST_NAME" class="required" type="text">
        </div>
        <div class="field email text">
          <label for="EMAIL">Your e-mail address<span class="text-danger">*</span></label>
          <input id="EMAIL" placeholder="eg. email@domain.com" value="" name="EMAIL" class="required email" type="text">
        </div>
        <div class="field text">
          <label for="MOBILE">Best number to contact you on<span class="text-danger">*</span></label>
          <input id="MOBILE" placeholder="eg. 071-331-8866" value="" name="MOBILE" class="required" type="text">
        </div>
        <div class="field text">
          <label for="CITY_LOCALITY">City / Area<span class="text-danger">*</span></label>
          <input id="CITY_LOCALITY" placeholder="eg. Johannesburg" value="" name="CITY_LOCALITY" class="required" type="text">
        </div>
        <div class="form-group control-select field dropdown">
          <label for="LOAN_TYPE">What do you want to use the loan for?<span class="text-danger">*</span></label>
          <select id="LOAN_TYPE" name="LOAN_TYPE" class="select select-search required form-control required">
            <option value="Personal Loan">Personal Loan</option>
            <option value="Debt Consolidation">Debt Consolidation</option>
            <option value="Debt Counselling">Debt Counselling</option>
            <option value="Home Loan">Home Loan</option>
            <option value="Vehicle Finance">Vehicle Finance</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group control-text field text">
          <label for="LOAN_AMOUNT">How much do you want to borrow?<span class="text-danger">*</span></label>
          <input id="LOAN_AMOUNT" placeholder="eg. R2,500" value="" name="LOAN_AMOUNT" class="form-control required" type="text">
        </div>
        <div class="form-group control-select field dropdown">
          <label for="LOAN_REPAYMENT">Repayment<span class="text-danger">*</span></label>
          <select id="LOAN_REPAYMENT" name="LOAN_REPAYMENT" class="select select-search required form-control required">
            <option value="1 Month">1 Month</option>
            <option value="2 Months">2 Months</option>
            <option value="3 Months">3 Months</option>
            <option value="4 Months">4 Months</option>
            <option value="5 Months">5 Months</option>
            <option value="6 Months">6 Months</option>
            <option value="12 Months">12 Months</option>
            <option value="24 Months">24 Months</option>
            <option value="36 Months">36 Months</option>
            <option value="More than 36 Months">More than 36 Months</option>
          </select>
        </div>
        <div class="form-group control-select field dropdown">
          <label for="EMPLOYMENT">Are you currently in full-time employment?<span class="text-danger">*</span></label>
          <select id="EMPLOYMENT" name="EMPLOYMENT" class="select select-search required form-control required">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
        <div class="field text">
          <label for="WORK_TEL">If employed, your work contact number</label>
          <input id="WORK_TEL" placeholder="eg. 011-465-4455" value="" name="WORK_TEL" type="text">
        </div>
        <div class="form-group control-select field dropdown">
          <label for="BAD_CREDIT">Do you have bad credit?<span class="text-danger">*</span></label>
          <select id="BAD_CREDIT" name="BAD_CREDIT" class="select select-search required form-control required valid" aria-invalid="false">
            <option value="No">No</option>
            <option value="Yes">Yes</option>
          </select>
        </div>
        <input id="REFERRED_FROM"  value="{$AbsoluteBaseURL}apply/" name="REFERRED_FROM" type="hidden" >
        <div class="Actions text-center mg-tb1">
          <button id="submitForm" class="btn action">Submit</button>
        </div>
      </form>
      <hr>
      <p class="disclaimer text-center">By submitting this form you opt-in to receive marketing materials.<br><br>Please read through our <a href="/affiliate-disclaimer/">Disclaimer</a> before submitting this form.</p>
    </div>
</article>
	  