// Deleting asset
var del = document.querySelectorAll('.delete');
var back = document.querySelector('.backdrop');
var box = document.querySelector('.confirm')

//console.log(del)
del.forEach(element => {
    element.addEventListener('click', function(e){
        back.style.display = 'block'
        box.style.display = 'block'
    })
})

back.addEventListener('click', function(){
    box.style.display = 'none'
    back.style.display = 'none'
})