const clear = document.querySelectorAll('input');
var reset = document.getElementById('reset');
var submit = document.getElementById('submit');

console.log(submit.attributes);
// For responsive clear button
clear.forEach(element => {
    element.addEventListener('change', function(e){
        if (e.target.value != ''){
            reset.style.backgroundColor = 'red'
            reset.style.cursor = 'pointer'
        }
        else{
            return reset.style.backgroundColor = ''
        }
    })
}); 

// For responsive login
clear.forEach(element => {
    element.addEventListener('change', function(e){
        if (e.target.value != ''){
            submit.style.backgroundColor = 'green'
            submit.style.cursor = 'pointer'
        }
        else{
            return submit.style.backgroundColor = ''
        }
    })
}); 

// For errors

var error = document.getElementsByTagName('small')

// Button

reset.addEventListener('click', function(){
    clear.forEach(element => {
        if (element.innerText == ''){
            reset.style.backgroundColor = ''
            submit.style.backgroundColor = ''
            reset.style.cursor = ''
            submit.style.cursor = ''
        }
    }); 
})
