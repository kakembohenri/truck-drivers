var radio = document.querySelectorAll('#radio')

radio.forEach(element => {
    element.addEventListener('click', function(e){
        console.log(e.target)
    })
})

