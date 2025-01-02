<!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVendorModalLabel">Add Vendor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  method="POST" id="add_vendor" enctype="multipart/form-data">
          <!-- Vendor Form Fields -->
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Vendor Name</label>
                <input type="text" id="popup_vendor_name" name="vendor_name" class="styled-input" placeholder="Enter Vendor Name" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">GSTIN</label>
                <input type="text" id="gstin" name="gstin" class="styled-input" placeholder="Enter GSTIN" />
              </div>
            </div>
          </div>
          <!-- Additional Fields (Contact, Address, etc.) -->
          <div class="row">
           
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="styled-input" placeholder="Enter Phone Number" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Email</label>
                <input type="email" id="email" name="email" class="styled-input" placeholder="Enter Email" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Vendor Type</label>
                <select name="vendor_type" id="vendor_type" class="styled-input">
                  <option value="Individual">Individual</option>
                  <option value="Company">Company</option>
                </select>
              </div>
            </div>
          </div>
          <!-- Bank Details -->
          <h4>Bank Details</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="styled-input" placeholder="Enter Bank Name" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-field-container">
                <label class="input-label">Account Number</label>
                <input type="text" id="account_number" name="account_number" class="styled-input" placeholder="Enter Account Number" />
              </div>
            </div>
             
            <div class="col-md-6">
            <div class="input-field-container">
    <label class="input-label">Address</label>
    <textarea id="address" name="address" class="styled-input" placeholder="Enter Address"></textarea>
  </div>
 </div>
            <div class="col-md-6">
  <!-- Services Provided -->
  <div class="input-field-container">
    <label class="input-label">Services Provided</label>
    <textarea id="services_provided" name="services_provided" class="styled-input" placeholder="Enter Services Provided"></textarea>
  </div>
 </div>
            <div class="col-md-6">
  <!-- Additional Notes -->
  <div class="input-field-container">
    <label class="input-label">Additional Notes</label>
    <textarea id="additional_notes" name="additional_notes" class="styled-input" placeholder="Enter Additional Notes"></textarea>
  </div>
 </div>
            
            <div class="col-md-6">
  <!-- IFSC Code -->
  <div class="input-field-container">
    <label class="input-label">IFSC Code</label>
    <input type="text" id="ifsc" name="ifsc" class="styled-input" placeholder="Enter IFSC Code" />
  </div>
 </div>
            <div class="col-md-6">
  <!-- Payment Terms -->
  <div class="input-field-container">
    <label class="input-label">Payment Terms</label>
    <textarea id="payment_terms" name="payment_terms" class="styled-input" placeholder="Enter Payment Terms"></textarea>
  </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>