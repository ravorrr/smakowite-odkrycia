
// funkcja ogólnodostępna czy element jest pusty
function isEmpty(value) {
  return (value == null || (typeof value === "string" && value.trim().length === 0));
}
// 

// tworzenie tooltipów
const tooltips = document.querySelectorAll('.tt')
tooltips.forEach(t => {
  new bootstrap.Tooltip(t)
})
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
    window.location.href = window.location.href.replace('user_settings/userSettings.php','main_website/website.php').split('?')[0];
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

document.getElementById('showPasswordButton').addEventListener('click', function () {
    togglePasswordVisibility('password', 'confirm_password', 'showPasswordButton', 'showConfirmPasswordButton');
});

document.getElementById('showConfirmPasswordButton').addEventListener('click', function () {
    togglePasswordVisibility('confirm_password', 'password', 'showConfirmPasswordButton', 'showPasswordButton');
});

function togglePasswordVisibility(inputId, otherInputId, buttonId, otherButtonId) {
    let passwordInput = document.getElementById(inputId);
    let confirmPasswordInput = document.getElementById(otherInputId);
    let button = document.getElementById(buttonId);
    let otherButton = document.getElementById(otherButtonId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        confirmPasswordInput.type = "text";
        button.innerHTML = '<i class="bi bi-eye-slash"></i>';
        otherButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-secondary");
        otherButton.classList.remove("btn-outline-secondary");
        otherButton.classList.add("btn-secondary");
    } else {
        passwordInput.type = "password";
        confirmPasswordInput.type = "password";
        button.innerHTML = '<i class="bi bi-eye"></i>';
        otherButton.innerHTML = '<i class="bi bi-eye"></i>';
        button.classList.remove("btn-secondary");
        button.classList.add("btn-outline-secondary");
        otherButton.classList.remove("btn-secondary");
        otherButton.classList.add("btn-outline-secondary");
    }
}