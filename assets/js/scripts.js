


//calendar source code js

const isLeapYear = (year) => {
    return (
        (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) ||
        (year % 100 === 0 && year % 400 === 0)
    );
  };
  
  const getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28;
  };
  
  let calendar = document.querySelector('.calendar');
  const month_names = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December',
  ];
  let month_picker = document.querySelector('#month-picker');
  let month_list = document.querySelector('.month-list');
  
  const dayTextFormate = document.querySelector('.day-text-formate');
  const timeFormate = document.querySelector('.time-formate');
  const dateFormate = document.querySelector('.date-formate');
  
  
  month_picker.onclick = () => {
      month_list.classList.remove('hideonce');
      month_list.classList.remove('hide');
      month_list.classList.add('show');
  };
  
  let dateClicked = false;
  let previousClickedDay = null;
  
  const generateCalendar = (month, year) => {
      let calendar_days = document.querySelector('.calendar-days');
      calendar_days.innerHTML = '';
      let calendar_header_year = document.querySelector('#year');
      let days_of_month = [
          31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
      ];
  
      let currentDate = new Date();
  
      month_picker.innerHTML = month_names[month];
  
      calendar_header_year.innerHTML = year;
  
      let first_day = new Date(year, month);
  
      const isSameDate = (date1, date2) => {
          return (
              date1.getFullYear() === date2.getFullYear() &&
              date1.getMonth() === date2.getMonth() &&
              date1.getDate() === date2.getDate()
          );
      };
      
      for (let i = 0; i < 42; i++) {
          let day = document.createElement('div');
          let currentDay = i - first_day.getDay() + 1;
          let currentFullDate = new Date(year, month, currentDay);
      
          if (i >= first_day.getDay() && currentDay <= days_of_month[month]) {
              day.innerHTML = currentDay;
      
              day.addEventListener('click', function() {
                  if (previousClickedDay) {
                      previousClickedDay.classList.remove('clicked-date');
                  }
                  saveDate(currentFullDate);
                  day.classList.add('clicked-date');
                  previousClickedDay = day;
              });
      
              if (
                  currentDay === currentDate.getDate() &&
                  year === currentDate.getFullYear() &&
                  month === currentDate.getMonth()
              ) {
                  // Do not add 'disabled' class for the current date
              } else if (currentFullDate < currentDate && !isSameDate(currentFullDate, currentDate)) {
                  day.classList.add('disabled');
              }
      
              if (currentFullDate.getDay() === 3) {
                  day.classList.add('disabled');
                  if (i === first_day.getDay()) {
                      day.classList.add('wednesday');
                  }
              }
          } else {
              day.classList.add('disabled');
          }
      
          calendar_days.appendChild(day);
      }
      
  };
  
  
  function saveDate(date) {
    // Format the date as YYYY-MM-DD
    const formattedDate = date.getFullYear() + '-' + 
                          ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                          ('0' + date.getDate()).slice(-2);
    
    // Set the value of the hidden input field to the formatted date
    document.getElementById('client_date').value = formattedDate;
    console.log("Clicked date:", formattedDate);
  }
  
  const currentMonthValue = new Date().getMonth();
  const currentYearValue = new Date().getFullYear();
  
  month_names.forEach((e, index) => {
    let month = document.createElement('div');
    month.innerHTML = `<div>${e}</div>`;
    month_list.append(month);
  
    if (index < currentMonthValue && currentYearValue === new Date().getFullYear()) {
        month.classList.add('disabled');
    } else {
        month.onclick = () => {
            currentMonth.value = index;
            generateCalendar(currentMonth.value, currentYear.value);
            month_list.classList.replace('show', 'hide');
        };
    }
  });
  
  document.querySelector('#pre-year').onclick = () => {
    const minYear = currentYear.value - 1;
    if (minYear >= currentYearValue) {
        --currentYear.value;
        generateCalendar(currentMonth.value, currentYear.value);
    }
  };
  
  document.querySelector('#next-year').onclick = () => {
    const currentYearValue = new Date().getFullYear();
    if (currentYear.value < currentYearValue) {
        ++currentYear.value;
        generateCalendar(currentMonth.value, currentYear.value);
    }
  };
  
  let currentDate = new Date();
  let currentMonth = { value: currentDate.getMonth() };
  let currentYear = { value: currentDate.getFullYear() };
  generateCalendar(currentMonth.value, currentYear.value);
  

// const todayShowTime = document.querySelector('.time-formate');
// const todayShowDate = document.querySelector('.date-formate');

// const currshowDate = new Date();
// const showCurrentDateOption = {
//   year: 'numeric',
//   month: 'long',
//   day: 'numeric',
//   weekday: 'long',
// };
// const currentDateFormate = new Intl.DateTimeFormat(
//   'en-US',
//   showCurrentDateOption
// ).format(currshowDate);
// todayShowDate.textContent = currentDateFormate;

// setInterval(() => {
//   const timer = new Date();
//   const option = {
//       hour: 'numeric',
//       minute: 'numeric',
//       second: 'numeric',
//   };
//   const formateTimer = new Intl.DateTimeFormat('en-us', option).format(timer);
//   let time = `${`${timer.getHours()}`.padStart(
//       2,
//       '0'
//   )}:${`${timer.getMinutes()}`.padStart(
//       2,
//       '0'
//   )}: ${`${timer.getSeconds()}`.padStart(2, '0')}`;
//   todayShowTime.textContent = formateTimer;
// }, 1000);


// terms and service modals js

document.getElementById('terms_conditions').addEventListener('change', function () {
    var modalButton = document.getElementById('modalButton');
    if (this.checked) {
        // Simulate button click
        modalButton.click();
        
        // Apply click effect visually
        modalButton.classList.add('click-effect');
        setTimeout(function() {
            modalButton.classList.remove('click-effect');
        }, 300); // 300ms is the duration of the transform transition
    }
});

document.getElementById('acceptBtn').addEventListener('click', function () {
    var checkBox = document.getElementById('terms_conditions');
    checkBox.checked = true;
    var modal = document.getElementById('termsAndConditions');
    var modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
});

document.getElementById('declineBtn').addEventListener('click', function () {
    var checkBox = document.getElementById('terms_conditions');
    checkBox.checked = false;
    var modal = document.getElementById('termsAndConditions');
    var modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
});

document.querySelector('.label-checkbox-custom').addEventListener('click', function () {
    document.getElementById('modalButton').click();
});