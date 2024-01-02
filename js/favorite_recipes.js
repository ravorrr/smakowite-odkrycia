'use strict';

// tworzenie tooltipów
const tooltips = document.querySelectorAll('.tt')
tooltips.forEach(t => {
  new bootstrap.Tooltip(t)
})
// 

// MODAL

let height = document.querySelector('#height');
let weight = document.querySelector('#weight');
let bmiData = document.querySelectorAll('input[name="bmiData"]');
let gender = document.querySelectorAll('.gender');
let result = document.querySelector('.result');
let bmiBtn = document.querySelector('.bmiBtn');
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

height.addEventListener('input', ()=> {
  validateInputs();
  removeEventListener('input', height);
})

weight.addEventListener('input', ()=> {
  validateInputs();
  removeEventListener('input', weight);
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
  removeEventListener('click', bmiBtn);
})

//  KONIEC MODAL

// NAVBAR
const accontsSettingContainer = document.querySelector('.accounts_settings');
const avatarIcon = document.querySelector('.avatar_icon');
const dropDownMenu = document.querySelector('.links-container');
const toggleBtn = document.querySelector('.toggle_btn');
const backlogo = document.querySelector('.logo_container');

backlogo.addEventListener('click', ()=> {
  window.location.href = window.location.href.replace('favorite_recipes/favorite_recipes.php','main_website/website.php').split('?')[0];
  createCookie('searchValue', '', 0.2);

  removeEventListener('click', backlogo);
})

toggleBtn.addEventListener('click', ()=> {
    dropDownMenu.classList.toggle('open')

    removeEventListener('click', toggleBtn);
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

// Pobranie ciasteczka
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

function removeItem(sKey, sPath, sDomain) {
  document.cookie = encodeURIComponent(sKey) + 
                "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + 
                (sDomain ? "; domain=" + sDomain : "") + 
                (sPath ? "; path=" + sPath : "");
}

// wypełninie ikonki avatara
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

function isEmpty(value) {
  return (value == null || (typeof value === "string" && value.trim().length === 0));
}

//SEARCH INPUT
let searchIcon = document.querySelector('.search-icon');
let searchInput = document.querySelector(".search");
let searchBar = document.querySelector(".searchBar");
let changeSearchWidth = document.querySelector(".changeSearchWidth")
let inputChar = document.querySelector('.inputChar')

changeSearchWidth.addEventListener('click', () => {
  if (!searchInput.classList.contains('searchOpen')) {
    searchInput.classList.add('searchOpen');
    inputChar.innerHTML = ""
    inputChar.classList.remove('visible');
    changeSearchWidth.innerHTML = '<i class="changeSearchWidthIcon fa-solid fa-chevron-left"></i>' 
    
    setTimeout(() => {
      searchInput.focus();
    }, 10);
  } else {
    searchInput.classList.remove('searchOpen');
    inputChar.innerHTML = searchInput.value
    setTimeout(()=> {
      inputChar.classList.add('visible');
      changeSearchWidth.innerHTML = '<i class="changeSearchWidthIcon fa-solid fa-chevron-right"></i>' 
    }, 500)
  
  }
});

searchInput.addEventListener("focus", ()=>{
  searchBar.style.boxShadow = "rgba(255, 255, 255, 0.12) 0px -12px 30px, rgba(255, 255, 255, 0.12) 0px 4px 6px, rgba(255, 255, 255, 0.17) 0px 12px 13px, rgba(255, 255, 255, 0.09) 0px -3px 5px"
  removeEventListener('focus', searchInput);
})

searchInput.addEventListener("focusout", ()=>{
  searchBar.style.boxShadow = "none"
  removeEventListener('focusout', searchInput);
})

// usuń wszyskie
document.querySelector('.delete_icon_confirm').addEventListener('click', () => {
  if(!document.querySelector('.favorite_recipes').children[0].classList.contains('coms')) {
    $.ajax({
      type: 'POST',
      url: 'delete_all_recipes.php',
      success: function() {
        window.location.reload();
      }
    })
  }
});

// wyszukanie przepisów
$('.search').on("keyup", function(e) {
  if(e.code === 'Enter') {
    let searchInput = $('.search');
    let searchTerm = encodeURIComponent(searchInput.val());

    let header = window.location.href;

    createCookie('searchValue', searchTerm, 0.2);
    $.ajax({
      type: 'POST',
      url: '../favorite_recipes/generate_favorite_recipes.php',
      data: { searchTerm: searchTerm, href: header},
      success: function(response) {
        $('.favorite_recipes').html(response);
          if(window.location.href.includes('#main')) {
          } else {
            window.location.href += '#main';
          }

          if(window.location.href.includes('?')) {
            if(isEmpty(searchTerm)) {
              window.location.href = window.location.href.replace( ( window.location.href.split('=')[1] ).split('&')[0],'1')
            }
          }
    
          // Usuń stare zdarzenia przed ponownym przypisaniem
          $('.favorite_recipes').off('click', '.recipe_element');
          $('.favorite_recipes').off('click', '.bi-arrow-right-circle');
          $('.favorite_recipes').off('click mouseover mouseout', '.recipe_icon_container');
    
          $('.favorite_recipes').on('click', '.recipe_element', function(event) {
            setAddEventLisenerToRecipes(event);
          });
    
          $('.favorite_recipes').on('click mouseover mouseout', '.recipe_icon_container', function(event) {
            setLisenerToFavorites(event.type, this, event);
          });
        },
        error: function(xhr, status, error) {
          console.error('Wystąpił błąd podczas wysyłania żądania.', error);
        }
    });
  }
});

// Działanie tooltipa
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// przycisk do czyszczenia inputa
$('.deleteSearchValue').on('click', () => {
  let searchInput = '';
  let searchTerm = encodeURIComponent(searchInput);
  let header = window.location.href;

  createCookie('searchValue', searchTerm, 0.2);
  $.ajax({
    type: 'POST',
    url: '../favorite_recipes/generate_favorite_recipes.php',
    data: { searchTerm: searchTerm, href: header},
    success: function(response) {
      $('.favorite_recipes').html(response);
      removeItem('searchValue', '/');
      $('#searchValue').val('');
      
      let searchInput = document.querySelector(".search");
      searchInput.classList.remove('searchOpen');
      inputChar.innerHTML = searchInput.value;
      setTimeout(()=> {
        inputChar.classList.add('visible');
        changeSearchWidth.innerHTML = '<i class="changeSearchWidthIcon fa-solid fa-chevron-right"></i>' 
      }, 500)
      
      if(window.location.href.includes('#main')) {
        window.location.href = window.location.href.split('?')[0];
      } else {
        window.location.href = window.location.href.split('?')[0] + '#main';
      }

      // Usuń stare zdarzenia przed ponownym przypisaniem
      $('.favorite_recipes').off('click', '.recipe_element');
      $('.favorite_recipes').off('click', '.bi-arrow-right-circle');
      $('.favorite_recipes').off('click mouseover mouseout', '.recipe_icon_container');

      $('.favorite_recipes').on('click', '.recipe_element', function(event) {
        setAddEventLisenerToRecipes(event);
      });

      $('.favorite_recipes').on('click mouseover mouseout', '.recipe_icon_container', function(event) {
        setLisenerToFavorites(event.type, this, event);
      });
    },
    error: function(xhr, status, error) {
      console.error('Wystąpił błąd podczas wysyłania żądania.', error);
    }
  })
});

// Otwieranie strony z przepisami
function setAddEventLisenerToRecipes(event) {
  if(event.target.classList.contains('recipe_icon_container') || event.target.classList.contains('delete-icon')) {
      $.ajax({
        type: "POST",
        url: 'remove_recipe.php',
        data: {recieToDelete: event.currentTarget.id},
        success: function() {
          createCookie('searchValue', '', 1);
          let notification = document.getElementById('notification');
          notification.innerHTML = "Przepis został usunięty";
          notification.style.display = 'block';
          window.location.reload();
          setTimeout(function() {
            notification.style.display = 'none';
          }, 3000);
        }
      })
  } else {
    window.location.href = window.location.href.replace('favorite_recipes/favorite_recipes.php','recipe_website/recipe_website.php');
    createCookie('recipeName', event.currentTarget.id, "10");
  }
}

// kliknięcie w ulubionę 
function setLisenerToFavorites(event, element, htmlEventValue) {
  switch(event) {
    case 'click': {
      break;
    }
    case 'mouseover': {
      htmlEventValue.currentTarget.children[0].classList.add('fa-heart-circle-xmark', 'del-icon-red');
    
      removeEventListener('mouseover', element);
      break;
    }
    case 'mouseout': {
      htmlEventValue.currentTarget.children[0].classList.add('fa-heart-circle-xmark');
      htmlEventValue.currentTarget.children[0].classList.remove('del-icon-red');
    
      removeEventListener('mouseout', element);
      break;
    }
    default: {
      break;
    }
  }
}

// strony ulubionych przepisów
let offset = 0;
let itemsPerPage = 12;
const pagesParent = document.querySelector('.pages');
function selectPage(type, event) {

  switch(type) {
    case 'right': {
      if(offset != ((pagesParent.childNodes[pagesParent.childElementCount-2].id)) * itemsPerPage) {
        offset += itemsPerPage;
      }
      break;
    }
    case 'left': {
      if(offset != 0) {
        offset = offset - itemsPerPage;
      }
      break;
    }
    case 'middle': {
      offset = (event.currentTarget.id-1) * itemsPerPage;
      break;
    }
    default: {
      break;
    }
  }
  
  Object.values(document.querySelectorAll('.nextPage')).filter((pageElement) => {
    pageElement.id != (offset / itemsPerPage)+1 ? pageElement.classList.remove('activePage') : pageElement.classList.add('activePage');
  })

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'generate_recipe.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      document.querySelector('.recipes_elements_contanier').innerHTML = xhr.responseText;

      document.querySelectorAll('.recipe_element').forEach((element) => {
        element.addEventListener('click', (event) => setAddEventLisenerToRecipes(event))
      })

      Object.values(document.querySelectorAll('.nextPage')).filter((pageElement) => {
        pageElement.id != (offset / itemsPerPage)+1 ? pageElement.classList.remove('activePage') : pageElement.classList.add('activePage');
      })

      $('.bi-arrow-right-circle').on("click", (event) => selectPage('right', event))
      $('.bi-arrow-left-circle').on("click", (event) => selectPage('left', event))
      document.querySelectorAll('.nextPage').forEach((element) => {
        element.addEventListener('click',  (event) => selectPage('middle', event))
      });

      starIcons = document.querySelectorAll('.recipe_icon_container');
      starIcons.forEach((element) => {
        element.addEventListener('click', (event) => setLisenerToFavorites('click', element, event))
        element.addEventListener('mouseover', (event) => setLisenerToFavorites('mouseover', element, event))
        element.addEventListener('mouseout', (event) => setLisenerToFavorites('mouseout', element, event))
      })
    } else {
      console.error('Wystąpił błąd podczas wysyłania żądania.');
    }
  };
  xhr.send('offset=' + encodeURIComponent(offset));
}

// otwieranie strony z przepisami
document.querySelectorAll('.recipe_element').forEach((element) => {
  element.addEventListener('click', (event) => setAddEventLisenerToRecipes(event))
})

// dodawanie do ulubionych 
document.querySelectorAll('.recipe_icon_container').forEach((element) => {
  element.addEventListener('click', (event) => setLisenerToFavorites('click', element, event))
  element.addEventListener('mouseover', (event) => setLisenerToFavorites('mouseover', element, event))
  element.addEventListener('mouseout', (event) => setLisenerToFavorites('mouseout', element, event))
})
  
// zmiana sortowania
const dropdownItems = document.querySelectorAll('.dropdown-item');
dropdownItems.forEach((element) => {
  if(getCookie('sortFilter') == element.id) {
    element.classList.add('active');
    document.querySelector('#dropdownMenuButton').innerHTML = element.textContent;
  }

  element.addEventListener('click', (event) => {
    Object.values(dropdownItems).filter((dropdownItem) => {
      dropdownItem.id !== event.target.id ? dropdownItem.classList.remove('active') : dropdownItem.classList.add('active');
      createCookie('sortFilter', element.id, '3');
      document.location.reload();
    })
    removeEventListener('click', event);
  })
})

  // Add slideDown animation to Bootstrap dropdown when expanding.
  $('.dropdown').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
  });

  // Add slideUp animation to Bootstrap dropdown when collapsing.
  $('.dropdown').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
  });