'use strict';

// tworzenie tooltipów
const tooltips = document.querySelectorAll('.tt')
tooltips.forEach(t => {
  new bootstrap.Tooltip(t)
})
// 

// MODAL

let height = document.querySelector('#height')
let weight = document.querySelector('#weight')
let bmiData = document.querySelectorAll('input[name="bmiData"]')
let gender = document.querySelectorAll('.gender')
let result = document.querySelector('.result')
let bmiBtn = document.querySelector('.bmiBtn')
let bmi

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
      trigger: 'click', // Zmiana triggera na 'click'
      title: function () {
          return 'Mniej niż 16 - Wygłodzenie<br>' +
              '16 - 16.99 - Wychudzenie<br>' +
              '17 - 18.49 - Niedowaga<br>' +
              '18.5 - 24.99 - Wartość prawidłowa<br>' +
              '25 - 29.99 - Nadwaga<br>' +
              '30 - 34.99 - I stopień otyłości<br>' +
              '35 - 39.99 - II stopień otyłości<br>' +
              'Powyżej 40 - Otyłość skrajna';
      },
      html: true // Włączanie obsługi HTML w treści tooltipa
  });
});

function validateInputs() {
  let isValid = !isNaN(height.value) && height.value > 0 &&
                !isNaN(weight.value) && weight.value > 0
                
  bmiBtn.disabled = !isValid              
}

height.addEventListener('input', ()=>{
  validateInputs();
})

weight.addEventListener('input', ()=>{
  validateInputs();
})

bmiBtn.addEventListener('click', ()=> {
  let selectedGender;
  for(const choice of gender){
    if (choice.checked) {
      if(choice.value === "male"){
        bmi = weight.value / Math.pow(height.value / 100, 2)
      } else {
        bmi = 1.3 * weight.value / Math.pow(height.value / 100, 2.5)
      }
      selectedGender = choice.value;
      break;
    }
  }
  result.innerHTML = "Wynik: " + bmi.toFixed(2);
})

//  KONIEC MODAL


// funkcja ogólnodostępna czy element jest pusty
function isEmpty(value) {
  return (value == null || (typeof value === "string" && value.trim().length === 0));
}
// 

// NAVBAR
const accontsSettingContainer = document.querySelector('.accounts_settings');
const avatarIcon = document.querySelector('.avatar_icon');
const dropDownMenu = document.querySelector('.links-container');
const toggleBtn = document.querySelector('.toggle_btn')
const backlogo = document.querySelector('#backLogo')

accontsSettingContainer.addEventListener('click', ()=> {
  if(!isEmpty(getCookie('email'))) {
    dropDownMenu.classList.toggle('open');
  } else {
    window.location.href = '../login_register/login_register.php';
  }

  removeEventListener('click', accontsSettingContainer);
})

backlogo.addEventListener('click', (event)=> {
    window.location.href = window.location.href.replace('recipe_website/recipe_website.php','main_website/website.php').split('?')[0];
    createCookie('searchValue', '', 0.2);
})

// tworzenie ciasteczka
function createCookie(name, value, days) {
  let expires;

  if(days) {
    let date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = ": expiers=" + date.toGMTString();
  }else { 
    expires = ""; 
  } 

  document.cookie = escape(name) + "=" + escape(value) + "; path=/";
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

accontsSettingContainer.addEventListener("mouseover", () => {
  avatarIcon.classList.add('bi-person-fill');
  avatarIcon.classList.remove('bi-person');

  removeEventListener('mouseover', accontsSettingContainer);
})

accontsSettingContainer.addEventListener("mouseout", () => {
  avatarIcon.classList.remove('bi-person-fill');
  avatarIcon.classList.add('bi-person');

  removeEventListener('mouseout', accontsSettingContainer);
})

// KONIEC NAVBAR

// IKONKI NAD SLIDER

const czas = document.querySelector('.czas')

let isBrAdded = false

function updateText(){
    if(window.innerWidth < 800 && !isBrAdded){
      czas.innerHTML = czas.innerHTML.replace(/\+/g, '+<br>');
      console.log(czas.value)
      isBrAdded = true
    } else if(window.innerWidth > 800 && isBrAdded){
      czas.innerHTML = czas.innerHTML.replace(/\+<br>/g, '+');
      isBrAdded = false
    }
}

document.addEventListener('DOMContentLoaded', ()=> {
  updateText();
})

window.addEventListener('resize', function() {
  updateText(); // Wywołaj funkcję przy zmianie rozmiaru okna
});


// LISTA SKŁADNIKÓW
let ingredientsCard = document.querySelector("#ingredientsCard")
let cardPin = document.querySelector("#pin")

cardPin.addEventListener('click', ()=>{
  ingredientsCard.classList.toggle("pinned")
  if(ingredientsCard.classList.contains("pinned")){
    cardPin.style.color = "red"
  } else {
    cardPin.style.color = "black"
  }
  removeEventListener('click', cardPin)
})

let listItems = document.querySelectorAll('label')

listItems.forEach((element)=> {
  element.addEventListener('change', (item)=>{
    element.classList.toggle('strikethrough')
    console.log(element)
  })
})

// SCROLL TO TOP

// Nasłuchiwanie zdarzenia przewijania
let scrollButton = document.querySelector(".scroll-to-top");
window.addEventListener('scroll', function() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    scrollButton.style.opacity = "1"
    scrollButton.classList.add('slideUp')
    scrollButton.classList.remove('slideDown')
  } else {
    scrollButton.style.opacity = "0"
    scrollButton.classList.remove('slideUp')
    scrollButton.classList.add('slideDown')
  }
  removeEventListener('scroll', window);
});

// Nasłuchiwanie zdarzenia kliknięcia na strzałkę
scrollButton.addEventListener('click', ()=> {
  scrollToTop();  

  removeEventListener('click', scrollButton);
});

function scrollToTop() {
  document.body.scrollTop = 0; // Dla wspierania przeglądarek starszych
  document.documentElement.scrollTop = 0; // Dla nowoczesnych przeglądarek
}
// END SCROLL TO TOP
