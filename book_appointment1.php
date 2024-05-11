<?php
   // define('INCLUDED', true);
   require_once('connect.php');
   
   $query = "SELECT service_id, service_name, service_description, service_image FROM services";
   $result = mysqli_query($conn, $query);
   
   $query1 = "SELECT promo_id, promo_details, promo_image FROM promo";
   $result1 = mysqli_query($conn, $query1);
   ?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Browlesque</title>
      <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="./assets/css/style.css">
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-custom container-fluid">
         <div class="container">
            <a href="http://localhost/browlesque">
            <img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client" alt="Browlesque Logo">
            </a>
            <a class="navbar-toggler" href="Index.php" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
               <ul class="navbar-nav">
                  <li class="nav-item">
                     <a class="nav-link" href="Index.php">Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="book_appointment1.php">Book Appointment</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="index.php#about_us_section">About us</a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="container-fluid">
         <h1 class="fw-bold mt-2" id="title">BOOK YOUR APPOINTMENT</h1>
         <div class="container-md container-md-custom" id="dropdown-container">
            <form id="appointmentForm" method="POST" action="insert.php" onsubmit="validateForm(event);">
               <span class="label-checkbox mb-2"><span class="asterisk">*</span>Type of Service to be Availed: </span><br>
               <!-- Service checkboxes -->
               <?php
                  // Loop through services and display checkboxes
                  while ($row = mysqli_fetch_assoc($result)) {
                     $service_id = $row['service_id'];
                     $service_name = $row['service_name'];
                     $isDisabled = true;
                     echo '<div class="form-check">';
                     echo '<input class="form-check-input service-checkbox" type="checkbox" name="service_id[]" value="' . $service_id . '" id="Service_' . $service_id . '" data-disable="' . ($isDisabled ? 'true' : 'false') . '">';
                     echo '<label class="form-check-label" for="Service_' . $service_id . '">' . $service_name . '</label>';
                     echo '</div>';
                  }
               ?>
               <input type="hidden" name="service_id[]" id="service_id">
               <span class="label-checkbox mb-2"><span class="asterisk">*</span>Type of Promo to be Availed:</span><br>
                  <!-- Promo checkboxes -->
                  <?php
                     // Loop through promos and display checkboxes
                     while ($row1 = mysqli_fetch_assoc($result1)) {
                        $promo_id = $row1['promo_id'];
                        $promo_details = $row1['promo_details'];
                        $isDisabled = true;
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input promo-checkbox" type="checkbox" name="promo_id[]" value="' . $promo_id . '" id="Promo_' . $promo_id . '" data-disable="' . ($isDisabled ? 'true' : 'false') . '">';
                        echo '<label class="form-check-label" for="Promo_' . $promo_id . '">' . $promo_details . '</label>';
                        echo '</div>';
                     }
                  ?>
               <input type="hidden" name="promo_id[]" id="promo_id">
               <div class="mt-2 mb-4">
                  <div class="d-flex justify-content-end fixed-buttons me-4">
                     <button type="button" id="nextButton" class="btn custom-next me-2 fs-5 text-center" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="Please check at least one service or promo first">Next</button>
                  </div>
               </div>
               <!-- Calendar section -->
               <span id="clientDateError" style="color: red; display: none; padding-left:10%">Please select a date.</span>
               <div id="nameAndNumberContainer" style="display: none;">
                  <div id="calendarContainer" class="calendar-container container-fluid" style="display: none;">
                     <span class="label-checkbox mb-2" id="focus_date"><span class="asterisk">*</span>Set appointment date:</span><br>
                     <div class="calendar mb-3">
                        <div class="calendar-header">
                           <span class="month-picker" id="month-picker">May</span>
                           <div class="year-picker" id="year-picker">
                              <span class="year-change" id="pre-year">
                                 <pre><</pre>
                              </span>
                              <span id="year">2020</span>
                              <span class="year-change" id="next-year">
                                 <pre>></pre>
                              </span>
                           </div>
                        </div>
                        <div class="calendar-body">
                           <div class="calendar-week-days">
                              <div>Sun</div>
                              <div>Mon</div>
                              <div>Tue</div>
                              <div class="wednesday">Wed</div>
                              <div>Thu</div>
                              <div>Fri</div>
                              <div>Sat</div>
                           </div>
                           <div class="calendar-days"></div>
                        </div>
                     </div>
                     <!-- <div class="calendar-footer"></div>
                        <div class="date-time-formate">
                            <div class="day-text-formate">TODAY</div>
                            <div class="date-time-value">
                                <div class="time-formate">01:41:20</div>
                                <div class="date-formate">03 - march - 2022</div>
                            </div>
                        </div> -->
                     <div class="month-list"></div>
                  </div>
                  <input type="hidden" name="client_date" id="client_date">
                  <!-- time buttons section -->
                  <div id="timeButtonsContainer" style="display: none;">
                  <span id="clientTimeError" style="color: red; display: none; padding-left:10%">Please select a time.</span>
                     <span class="label-checkbox mb-2"><span class="asterisk">*</span>Set appointment time:</span><br>
                     <div class="btn-grid-container">
                        <div class="parent mb-3">
                           <div class="div1"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="9:00 AM-11:00 AM">9-11 AM</button></div>
                           <div class="div2"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="10:00 AM-12:00 PM">10-12 PM</button></div>
                           <div class="div3"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="11:00 AM-1:00 PM">11-1 PM</button></div>
                           <div class="div4"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="12:00 PM-2:00 PM">12-2 PM</button></div>
                           <div class="div5"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="1:00 PM-3:00 PM">1-3 PM</button></div>
                           <div class="div6"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="2:00 PM-4:00 PM">2-4 PM</button></div>
                           <div class="div7"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="3:00 PM-5:00 PM">3-5 PM</button></div>
                           <div class="div8"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="4:00 PM-6:00 PM">4-6 PM</button></div>
                           <div class="div9"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="5:00 PM-7:00 PM">5-7 PM</button></div>
                           <div class="div10"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="6:00 PM-8:00 PM">6-8 PM</button></div>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" name="start_time" id="start_time">
                  <input type="hidden" name="end_time" id="end_time">
                  <!-- <input type="hidden" name="client_time" id="client_time"> -->
                  <div class="container-fluid">
                     <div class="mb-3" id="clientNameDiv">
                        <label for="client_name" class="label-checkbox mb-2"><span class="asterisk">*</span>Name:</label>
                        <input type="text" class="form-control" id="client_name" name="client_name">
                        <span id="clientNameError" style="color: red; display: none; padding-left:10%">This field is required. Please input your name.</span>
                     </div>
                     <div class="mb-3" id="clientNumberDiv">
                        <label for="client_contactno" class="label-checkbox mb-2"><span class="asterisk">*</span>Enter your phone number:</label>
                        <input type="tel" class="form-control" id="client_contactno" name="client_contactno" value="09">
                        <span id="clientContactError" style="color: red; display: none; padding-left:10%">This field is required. Please input your contact number.</span>
                     </div>
                     <span id="clientCompanionError" style="color: red; display: none; padding-left:10%">Please select number of companion/s.</span>
                     <div class="mb-3 d-flex justify-content-center">
                        <div class="btn-grid-container">
                           <label for="no_of_companions" class="label-checkbox mb-2 me-4 text-center"><span class="asterisk text-center">*</span>No. of Companions:</label>
                           <div class="parent-comp mb-3">
                              <div class="div11"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="0">0</button></div>
                              <div class="div12"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="1">1</button></div>
                              <div class="div13"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="2">2</button></div>
                              <div class="div14"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="3">3</button></div>
                           </div>
                        </div>
                     </div>
                     <input type="hidden" name="no_of_companions" id="no_of_companions">
                     <div class="mb-3">
                        <label for="client_notes" class="label-checkbox mb-2">Notes (optional):</label>
                        <textarea class="form-control tall-input" id="client_notes" name="client_notes"></textarea>
                     </div>     
                     <div class="container mt-5">
                     <span id="clientTermsError" style="display: none; color: red; padding-left:10%">Please accept the terms and conditions to continue with the booking.</span>
                        <div class="mb-3 checkbox-container">
                           <input type="checkbox" class="form-check-input form-check-input-custom" id="terms_conditions" name="terms_conditions" value="Agree">
                           <label for="terms_conditions" class="label-checkbox-custom mb-2 click-effect"><span class="asterisk">*</span>Terms and Conditions</label>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary click-effect" data-bs-toggle="modal" data-bs-target="#termsAndConditions" id="modalButton" style="display: none;">
                        Launch static backdrop modal
                        </button>
                     </div>
                     <div class="button-appoint-in btn-center">
                        <button type="submit" name="client_submit" class="btn btn-primary btn-primary-custom fs-4">BOOK NOW!</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   <div class="container-flex add-black-bg">
      <div class="container">
        <div class="footer-container d-flex justify-content-between">
          <div class="contact-us">
            <span class="mb-3 mb-md-0 footer-text-white">Contact Us</span>
          </div>
          <div class="contacts">
            <ul class="nav d-flex align-items-center justify-content-end">
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/email.svg" alt="Email Icon"> browlesque@gmail.com
                </span>
              </li>
              <li class="ms-3">
                <a class="footer-text-white" href="https://www.facebook.com/BrowlesqueCavite">
                  <img src="./assets/images/icon/Facebook.svg" alt="Facebook Icon"> Browlesque Cavite
                </a>
              </li>
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/Phone.svg" alt="Phone Icon"> 09123456789
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

      <!-- Terms and Service Modal -->
      <div class="modal fade" id="termsAndConditions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="termsAndConditionsLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header text-center">
                  <h1 class="modal-title fs-5 mx-auto" id="termsAndConditionsLabel">Terms and Conditions</h1>
               </div>
               <div class="modal-body">
                  By accessing and using the reservation system provided by [Your Company Name], you agree to be bound by the following terms and conditions.
                  When making a reservation, you are required to provide accurate information, and payment in full is necessary to confirm the booking. Cancellations may incur fees, and refunds, if applicable, will be subject to our refund policy. Modifications to reservations are dependent on availability and may incur additional charges. While using our Services, you agree to conduct yourself lawfully and responsibly. We are not liable for any loss or damage to personal belongings. Our liability is limited to the extent permitted by law, and you agree to indemnify us against any claims arising from your use of the Services. Intellectual property rights in the reservation system belong to [Your Company Name]. These terms are governed by the laws of [Your Country], and we reserve the right to amend them at any time. If any provision is deemed unenforceable, the remaining terms shall remain in effect. By using our reservation system, you signify your acceptance of these terms and conditions.
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary btn-primary-custom-tc" id="acceptBtn">Accept</button>
                  <button type="button" class="btn btn-secondary btn-secondary-custom" id="declineBtn" data-bs-dismiss="modal">Decline</button>
               </div>
            </div>
         </div>
      </div>


      <div id="confirmationModal" data-bs-backdrop="static" class="modal">
         <div class="modal-content-c custom-modal-content d-flex flex-column align-items-center">
            <h1 class="text-center mt-3 mb-0">Are the information details correct?</h1>
            <h6 class="text-center custom-subtitle mt-2 mb-2">By confirming, this will submit the appointment with the information you provided. Please review and ensure that these details are correct.</h6>
                  <div class="d-flex justify-content-end mt-5">
                     <button type="button" id="confirmButton" class="btn btn-primary btn-primary-custom-tc me-2 fs-5 text-center" onclick="submitForm()">Confirm</button>
                     <button type="button" id="editButton" class="btn btn-secondary btn-secondary-custom me-2 fs-5 text-center" onclick="hideConfirmationModal()">Edit</button>
             </div>
         </div>
      </div>

      <script>

         document.getElementById("client_name").addEventListener("keypress", function(event) {
            var charCode = event.charCode;
            // Allow letters, whitespace, hyphens, and period
            if (!(charCode >= 65 && charCode <= 90) && // Uppercase letters
               !(charCode >= 97 && charCode <= 122) && // Lowercase letters
               !(charCode === 32) && // Whitespace
               !(charCode === 45) && // Hyphen
               !(charCode === 46)) { // Period
               event.preventDefault();
            }
         });
         document.getElementById('client_contactno').addEventListener('input', function(event) {
         let input = event.target.value;

         if (!input.startsWith('09')) {
            event.target.value = '09'; 
         } else {
            let numbersAfterPrefix = input.replace('09', ''); 
            let numbersOnly = /^\d*$/; 

            if (!numbersAfterPrefix.match(numbersOnly) || numbersAfterPrefix.length > 9) {
        event.target.value = '09' + numbersAfterPrefix.slice(0, 9);
    }
         }
      });
      function validateForm(event) {
    event.preventDefault(); // Prevent form submission by default

    var clientName = document.getElementById('client_name');
    var clientContact = document.getElementById('client_contactno');
    var clientDate = document.getElementById('client_date');
    var clientTime = document.getElementById('start_time');
    var clientCompanion = document.getElementById('no_of_companions');
    var clientNameError = document.getElementById('clientNameError');
    var clientContactError = document.getElementById('clientContactError');
    var clientDateError = document.getElementById('clientDateError');
    var clientTimeError = document.getElementById('clientTimeError');
    var clientCompanionError = document.getElementById('clientCompanionError');
    var termsCheckbox = document.getElementById('terms_conditions');

    // Check if client date is empty
    if (clientDate.value.trim() === '') {
        clientDateError.style.display = 'block';
        clientDateError.scrollIntoView();
        return false;
    } else {
        clientDateError.style.display = 'none';
    }

    // Check if client time is empty
    if (clientTime.value.trim() === '') {
        clientTimeError.style.display = 'block';
        clientTimeError.scrollIntoView();
        return false;
    } else {
        clientTimeError.style.display = 'none';
    }

    // Check if client name is empty
    if (clientName.value.trim() === '') {
        clientNameError.style.display = 'block';
        clientNameError.scrollIntoView();
        clientName.focus();
        return false;
    } else {
        clientNameError.style.display = 'none';
    }

    // Check if client contact number is only the pre-added value
    if (clientContact.value.trim() === '09') {
        clientContactError.style.display = 'block';
        clientContactError.scrollIntoView();
        clientContact.focus();
        return false;
    } else {
        clientContactError.style.display = 'none';
    }

    // Check if client companion value is empty
    if (clientCompanion.value.trim() === '') {
        clientCompanionError.style.display = 'block';
        clientCompanionError.scrollIntoView();
        return false;
    } else {
        clientCompanionError.style.display = 'none';
    }

    // Check if terms and conditions checkbox is checked
    if (!termsCheckbox.checked) {
        document.getElementById('clientTermsError').style.display = 'block';
        document.getElementById('clientTermsError').scrollIntoView();
        return false;
    } else {
        document.getElementById('clientTermsError').style.display = 'none';
    }

    // If all inputs have values, show the modal
    showConfirmationModal();

    return false; // Prevent form submission
}
// Listen for input events on the client name and contact fields
document.getElementById('client_name').addEventListener('input', function() {
    var clientNameError = document.getElementById('clientNameError');
    clientNameError.style.display = 'none';
});

document.getElementById('client_contactno').addEventListener('input', function() {
    var clientContactError = document.getElementById('clientContactError');
    clientContactError.style.display = 'none';
});

document.getElementById('client_date').addEventListener('input', function() {
    var clientDateError = document.getElementById('clientDateError');
    clientDateError.style.display = 'none';
});

document.getElementById('start_time').addEventListener('input', function() {
    var clientTimeError = document.getElementById('clientTimeError');
    clientTimeError.style.display = 'none';
});

         
         document.addEventListener('DOMContentLoaded', function() {
             var checkboxes = document.querySelectorAll('input[type="checkbox"]');
         var nextButton = document.getElementById('nextButton');
         var tooltip = new bootstrap.Tooltip(nextButton);
         var calendarContainer = document.getElementById('calendarContainer');
         var timeButtonsContainer = document.getElementById('timeButtonsContainer');
         var nameAndNumberContainer = document.getElementById('nameAndNumberContainer');
         var formContainer = document.getElementById('appointmentForm');
         
         // Function to check if any checkbox is checked
         function anyCheckboxChecked() {
             return Array.from(checkboxes).some(function(checkbox) {
                 return checkbox.checked;
             });
         }
         
         // Show tooltip initially
         tooltip.show();
         
         checkboxes.forEach(function(checkbox) {
             checkbox.addEventListener('change', function() {
                 var isChecked = anyCheckboxChecked();
                 nextButton.disabled = !isChecked;
         
                 // Show or hide tooltip based on checkbox state
                 if (isChecked) {
                     tooltip.hide();
                 } else {
                     tooltip.show();
                 }
             });
         });
         
         // Disable tooltip when hovering over the button
         nextButton.addEventListener('mouseover', function() {
             tooltip.disable();
         });
         
         // Enable tooltip when mouse leaves the button
         nextButton.addEventListener('mouseout', function() {
             tooltip.enable();
         });
         
         // Show calendar when "Next" button is clicked
         nextButton.addEventListener('click', function() {
             if (anyCheckboxChecked()) {
                 // Move calendarContainer into dropdown-container
                formContainer.appendChild(calendarContainer);
                formContainer.appendChild(timeButtonsContainer);
                formContainer.appendChild(nameAndNumberContainer);
                 // Set calendarContainer display style to 'block'
                 calendarContainer.style.display = 'block';
                 timeButtonsContainer.style.display = 'block';
                 nameAndNumberContainer.style.display = 'block';
                 nextButton.disabled = true;
                 // Record the checked checkboxes (you can store them in an array or any other data structure)
                 var checkedCheckboxes = [];
                 checkboxes.forEach(function(checkbox) {
                     if (checkbox.checked) {
                         checkedCheckboxes.push(checkbox.value);
                     }
                 });
                 console.log('Checked checkboxes:', checkedCheckboxes);
                 // Disable specific checkboxes
                 checkboxes.forEach(function(checkbox) {
                     if (checkbox.dataset.disable === 'true') {
                         checkbox.disabled = true;
                     }
                 });
         
                 // Hide the nextButton
                 nextButton.style.display = 'none';
             }
         });
         
// Function to update service IDs
function updateServiceIDs() {
    const serviceIDs = Array.from(document.querySelectorAll('.service-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('service_id').value = JSON.stringify(serviceIDs);
    console.log("Selected service IDs:", serviceIDs);
}

// Function to update promo IDs
function updatePromoIDs() {
    const promoIDs = Array.from(document.querySelectorAll('.promo-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('promo_id').value = JSON.stringify(promoIDs);
    console.log("Selected promo IDs:", promoIDs);
}

         
             // Function to format time to HH:MM:SS format
             function formatTime(time) {
                 const isAMPM = time.includes('AM') || time.includes('PM');
                 const [hours, minutes, seconds] = time.split(/:| /).map(part => parseInt(part, 10));
                 const formattedHours = isAMPM ? ((hours % 12) + (time.includes('PM') ? 12 : 0)) : hours;
                 const paddedHours = String(formattedHours).padStart(2, '0');
                 const paddedMinutes = String(minutes).padStart(2, '0');
                 const paddedSeconds = String(seconds || 0).padStart(2, '0');
                 return `${paddedHours}:${paddedMinutes}:${paddedSeconds}`;
             }
         
             // Add event listeners after DOM content is loaded
             const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
             const promoCheckboxes = document.querySelectorAll('.promo-checkbox');
             const buttons = document.querySelectorAll('.time-buttons');
             const companionButtons = document.querySelectorAll('.companion-btn');
         
             serviceCheckboxes.forEach(checkbox => checkbox.addEventListener('click', updateServiceIDs));
             promoCheckboxes.forEach(checkbox => checkbox.addEventListener('click', updatePromoIDs));
         
             buttons.forEach(button => {
                 button.addEventListener('click', function() {
                     buttons.forEach(btn => btn.classList.remove('highlight'));
                     this.classList.add('highlight');
         
                     const selectedTimeRange = this.value;
                     const [startTime, endTime] = selectedTimeRange.split('-').map(time => formatTime(time.trim()));
         
                     document.getElementById('start_time').value = startTime;
                     document.getElementById('end_time').value = endTime;
         
                     console.log("Selected time range:", selectedTimeRange);
                     console.log("Start time:", startTime);
                     console.log("End time:", endTime);
                 });
             });
         
             companionButtons.forEach(button => {
                 button.addEventListener('click', function() {
                     companionButtons.forEach(btn => btn.classList.remove('highlight'));
                     this.classList.add('highlight');
         
                     const selectedCompanions = this.value;
                     document.getElementById('no_of_companions').value = selectedCompanions;
         
                     // Log the current value of no_of_companions element
                     console.log("Current value of no_of_companions:", document.getElementById('no_of_companions').value);
                 });
             });
         
             
         });
      </script>
      <!-- Include your separate JavaScript file using the script tag with src attribute -->
      <script src="./assets/js/modal.js"></script>
      <script src="./assets/js/scripts.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </body>
</html>