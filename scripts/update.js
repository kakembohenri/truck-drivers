var forms = document.querySelectorAll('form.container-login-form')

var personal_details = document.querySelector(".personal-details")

var driving_details = document.querySelector(".Driving-details")

driving_details.addEventListener('click', function(e){
    personal_details.style.backgroundColor = 'white'
    driving_details.style.backgroundColor = 'lightgreen'
    forms[1].style.display = 'flex'
    forms[0].style.display = 'none'
})

personal_details.addEventListener('click', function(e){
    personal_details.style.backgroundColor = 'lightgreen'
    driving_details.style.backgroundColor = 'white'
    forms[1].style.display = 'none'
    forms[0].style.display = 'flex'
})

//console.log(forms)