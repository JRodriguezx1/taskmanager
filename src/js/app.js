console.log(12);
const mobilemenu = document.querySelector('#mobile-menu');  //seleccion por id
const sidebar = document.querySelector('.sidebar');  //seleccion por calses
const btnmenux = document.querySelector('#mobile-menux');
const barra = document.querySelector('.barra-mobile');

if(mobilemenu){
    mobilemenu.addEventListener('click', function(){
        sidebar.classList.toggle('mostrar');
        barra.classList.toggle('ocultarmenu');
    });
}

if(btnmenux){
    btnmenux.addEventListener('click', function(){
        sidebar.classList.toggle('mostrar');
        barra.classList.toggle('ocultarmenu');
    });
}



window.addEventListener('resize', function(){  //evento que se ejecuta cuando se cambia el tamaño de la pantalla
    const anchopantalla = document.body.clientWidth;  //toma el ancho de la pantalla en el momento
    if(anchopantalla>=768){
        //sidebar.classList.remove('');
    }
});