(function(){ // IIFE
    console.log('xx');
    obtenertareas();
    let tareas = [];
    let filtradas = [];
    //boton para mostrar el nodal(ventada) de agregar tarea
    const nuevatarea = document.querySelector('#agregar-tarea'); //selecciona el boton con id
    //nuevatarea.addEventListener('click', mostrarformulario); //aqui se pasa e.target automaticamnente o por default
    nuevatarea.addEventListener('click', function(){
        mostrarformulario(false); // aqui no se pasa evento e.target
    });



    ///////////////////////////////filtros de busqueda///////////////////////////////

    const filtros = document.querySelectorAll('#filtros input[type="radio"]');  //seleccionamos el div con id= filtros en proyecto.php
    filtros.forEach(inputradio=>{
        inputradio.addEventListener('input', filtrartareas);
    });

    function filtrartareas(e){ //e = evento de los inputs radios

        const filtro = e.target.value; //filtro puede tomar '', '0', '1'..
        if(filtro != ''){  //filtrar pendientes o completas
            filtradas = tareas.filter(element => element.estado === filtro); //se trae todos los que sean iguales
            if(filtro == '0' && filtradas.length == 0){  //todas estan compleadas
                filtradas[0]='0';
            }
            if(filtro == '1' && filtradas.length == 0){  //todas estan pendientes
                filtradas[0]='1';
            }
        }else{  //mostrar todas
            filtradas = ['2'];
           
        }
        mostrartareas();
      }
    
    

    //modelo de concurrencia y loop de eventos
    //stack-heap-queue  orden de prioridad de ejecucion en js

    async function obtenertareas(){
        try {
            const proyectoparams = new URLSearchParams(window.location.search);  //window.location = obtiene el link o url actual y .search es el ?id=xxx
            let proyecto =  Object.fromEntries(proyectoparams.entries()); //se obtiene los datos de la url en un objeto
            const url_id = proyecto.id;
            const url = "/api/tareas?id="+url_id;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado;  //tareas variable global, tareas = [{},{},{}]
            
            mostrartareas();
        } catch (error) {
            console.log(error);
        }
    }



    function mostrartareas(){
        
        let arraytareas = filtradas.length?filtradas:tareas;
        let msj = "No hay tareas"; 
        
        if(filtradas[0]=='0'){ //todas estan marcadas como completadas
             arraytareas = []; 
        }
        if(filtradas[0]=='1'){  //cuando no hay tareas completadas o todas estan marcadas pendientes
             arraytareas = [];      
        }
        if(filtradas[0]=='2'){
             arraytareas = tareas;
        }

        limpiartareas();
        if(arraytareas.length == 0){ //tareas = [{id: '3', nombre: 'consultar precio', estado: '0', idproyectos: '1'},{},{}]
        const ultareas = document.querySelector('#listado-tareas');  //seleccionamos el UL q esta en el archivo dashboard/proyecto.php
        const textonotareas = document.createElement('LI');
        textonotareas.textContent = "No hay tareas";
        if(filtradas[0]=='0')
        textonotareas.textContent = "No hay tareas pendientes";
        if(filtradas[0]=='1')
        textonotareas.textContent = "No hay tareas completadas";

        textonotareas.classList.add('no-tareas');
        ultareas.appendChild(textonotareas);
        }else{
            const estados = {
                0: 'Pendiente',
                1: 'Completa'
            }
            const ultareas = document.querySelector('#listado-tareas'); //seleccionamos el UL q esta en el archivo dashboard/proyecto.php
            arraytareas.forEach(element => {
                console.log(element); //{id: '4', nombre: 'añadir contenido', estado: '0', idproyectos: '3'} = un registo de la tabla tareas
                const contenedortarea = document.createElement('LI');
                contenedortarea.dataset.tareaid = element.id;
                contenedortarea.classList.add('tarea');

                //creacion de p
                const nombretarea = document.createElement('P');
                nombretarea.textContent = element.nombre;
                nombretarea.ondblclick = function(){
                    mostrarformulario(true, {...element}); //se pasa copia paraeditar nombre de la tarea
                }
                
                //creacion de div
                const opcionesdiv = document.createElement('DIV');
                opcionesdiv.classList.add('opciones');

                //boton estado q se agrega al div de class opciones(opcionesdiv)
                const btnestadotarea = document.createElement('BUTTON');
                btnestadotarea.classList.add('estado-tarea');
                btnestadotarea.classList.add(`${estados[element.estado].toLowerCase()}`);
                btnestadotarea.textContent = estados[element.estado];
                btnestadotarea.dataset.estadotarea = element.estado;
                btnestadotarea.ondblclick = function(){
                    cambiarestado({...element});  //{...element} = copia del objeto de pertenece a tareas
                }

                
      
                //boton eliminar q se agrega al div de class opciones(opcionesdiv)
                const btneliminartarea = document.createElement('BUTTON');
                btneliminartarea.classList.add('eliminar-tarea');
                btneliminartarea.dataset.idtarea = element.id;
                btneliminartarea.textContent = "Eliminar";
                btneliminartarea.ondblclick = function(){
                    eliminartarea({...element});  //{...element} = copia del objeto de pertenece a tareas
                }

                opcionesdiv.appendChild(btnestadotarea); //al div se le agrega el boton estado
                opcionesdiv.appendChild(btneliminartarea);  //al div se le agrega el boton eliminar
                contenedortarea.appendChild(nombretarea);  //al li se le agrega el P
                contenedortarea.appendChild(opcionesdiv);  //al li se le agrega el div (opcionesdiv)
                
                ultareas.appendChild(contenedortarea);


            });
        } //cierre del else

    }  //cierre de funcion



    function mostrarformulario(edit = false, element = {}){ //por defecto edit toma false si no se pasa true, //las funciones pertenece al stack de ejecucion modelo concurrente
        
        const modal = document.createElement('DIV');  //crea div con class modal
        modal.classList.add('modal');  //se añade clase modal
        modal.innerHTML = `
        <form class="formulario nueva-tarea" >

        <legend>${edit?'Editar nombre de la tarea':'Añade una nueva tarea'}</legend>
        <div class="campo">
            <label for="tarea">Tarea</label>
            <input id="tarea" type="text" name="tarea" placeholder="${element.nombre?'':'Añadir tarea al proyecto actual'}" value="${element.nombre?element.nombre:''}">
        </div>

        <div class="opciones">
        <input class="submit-nueva-tarea" type="submit" value="${element.nombre?'Guardar cambios':'Crear tarea'}"/>
        <button type="button" class="cerrar-modal">Cancelar</button>
        </div>

        </form>
        `;

        
        setTimeout(() => {  //la function settiemeout pertenece al queue 
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        document.querySelector('.dashboard').appendChild(modal);  //el formulario creado dentro de div modal se abrega al div de class dashboard



        modal.addEventListener('click', function(e){  //al div con class modal se le asigna evente click
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){  // si al dar click en el boton cancelar con class cerrar nodal
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 700);    
                
            }
            if(e.target.classList.contains('submit-nueva-tarea')){ //si da click en crear tarea
                const tarea = document.querySelector('#tarea').value.trim();  //trim elimina espacions al principio y al final
                if(tarea == ''){
                //mostrar alerta
                    mostraralerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;                                                           //selecciona el legend del formulario
                }
                if(edit){  //si es true es editar nombre de la tarea
                    element.nombre = tarea;
                    editarnombretarea(element);
                }else{ //agregando nueva tarea
                    agregartarea(tarea);
                }
            }    
        });
    }  //cierre funcion mostrar formulario
   


//consultar al servidor para añadir nueva tarea al proyecto actual 
    async function agregartarea(tarea){

        const proyectoparams = new URLSearchParams(window.location.search);  //window.location = obtiene el link o url actual y .search es el ?id=xxx
        let proyecto =  Object.fromEntries(proyectoparams.entries()); //se obtiene los datos de la url en un objeto
        const url_id = proyecto.id;   //?id=xxxx
        //return;

        const datos = new FormData(); //estos datos se envia por metodo post con el fetch y los recibe el metodo crear de tareas_controlador.php
        datos.append('nombre', tarea);
        datos.append('url', url_id); //la variable url_id lo toma del get o url de la parte superior

        

        const url = "/api/tarea";
        try {
            const respuesta = await fetch(url, {method: 'POST', body: datos}); //conecta y consulta a la api y envia datos por metodo post y en tarea_controlador.php se recibe en $_POST
            const resultado = await respuesta.json();
            console.log(resultado); //resultado viene del echo json_encode($respuesta) q esta en el metodo crear de tareas_controlador.php
            console.log(resultado.resultado[1]); //resultado = {tipo: 'exito', mensaje: 'tarea agregada correctamente', resultado: [true, id], 'idproyectos'=>$proyecto->id} //id = 9,8,10,11 es el numero de registro de la tabla tareas
            mostraralerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));
              
            if(resultado.tipo == 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                   // window.location.reload(); recarga la pagina
                }, 3000);
            }
            //agregar el objeto de tareasobj a la global de tareas
            const tareasobj = {  //objeto con los datos de tarea nueva recien creada
                id: String(resultado.resultado[1]),  //resultado.resultado[1] es el num de reg id de la tabla tareas
                nombre: tarea,
                estado: '0',
                idproyectos: resultado.idproyectos  //id de la tabla proyectos
            }
            tareas = [...tareas, tareasobj];
            mostrartareas();
            

        } catch (error) {
            console.log(error);
        }

    }


    function mostraralerta(mensaje, tipo, referencia){ //referencia selecciona el legend del formulario
        //peviene la creacion de multiples alertas
        
        const alertaprevia = document.querySelector('.alerta');
        if(alertaprevia){
            alertaprevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        //referencia.appendChild(alerta);
        //referencia.insertBefore(); //coloca elemento entre elemento padre y antes de elemento hijo
        //referencia = legend del formulario
        //referencia.parentElement: es el formulario
        //referencia.nextElementSibling = elemento siguiente al legend es decir el div con class="campo";
        //referencia.parentElement.insertBefore(alerta, referencia);  //al elemento padre que es el formulario le hace el insertbefore, donde
                                                                    //alerta es el elemento a insertar y referencia es el elemento hijo(legend).
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling); 
        setTimeout(() => {
            alerta.remove();
           // window.location.reload(); recarga la pagina
           const formulario = document.querySelector('.formulario'); 
           if(formulario){
                formulario.classList.add('cerrar'); //clase para cerra el formulario con efecto desplazo hacia arriba
                         }
        }, 2000);

    }
        
    
    function limpiartareas(){  //funcion que limpia las tareas en el html
        const ultareas = document.querySelector('#listado-tareas');
        //ultareas.innerHTML = '';
        while(ultareas.firstChild){ //mientras halla elementos
            ultareas.removeChild(ultareas.firstChild);
        }
    }


    async function cambiarestado(element){  //tareas es arreglo de objetos global, element es cada objeto si se modifica element tambien en tareas que es global, por eso se pasa copia {...element}
        const nuevoestado = element.estado === '1'?'0':'1';
        element.estado = nuevoestado;

        const proyectoparams = new URLSearchParams(window.location.search);  //window.location = obtiene el link o url actual y .search es el ?id=xxx
        let proyecto =  Object.fromEntries(proyectoparams.entries()); //se obtiene los datos de la url en un objeto
        const url_id = proyecto.id;   //?id=xxxx

        const {id, nombre, estado, idproyectos} = element;  //element = {id: '4', nombre: 'añadir contenido', estado: '0', idproyectos: '3'} = registro de tabla tareas
        
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('idproyectos', idproyectos);
        datos.append('url', url_id);

       /* for(let valor of datos.values()){
            console.log(valor);  //iterar formdate
        }*/

        const url = "/api/tarea/actualizar";
        try {
            const respuesta = await fetch(url, {method: 'POST', body: datos}); //conecta y consulta a la api y envia datos por metodo post y en tarea_controlador.php se recibe en $_POST
            const resultado = await respuesta.json();

            if(resultado.tipo == 'exito'){
                mostraralerta('Actualizado correctamente', 'exito', document.querySelector('.contenedor-nueva-tarea'));
            }
            
            //console.log(tareas); //tareas = [{},{},{}...]
            //tareas = [{id: '3', nombre: 'consultar precio', estado: '0', idproyectos: '1'},{},{}]
            tareas = tareas.map(tareamemoria =>{ //tareamemoria va tomando cada elemento {} del arreglo tareas y ejecuta la funcion, y la funcion va ir tomando cada elemento{}
            
                if(tareamemoria.id === id){
                    tareamemoria.estado = estado;
                }
                return tareamemoria;

            });
            mostrartareas();  
            

        }catch (error){
            console.log(error);
        }
    }


    async function editarnombretarea(element){ //element es copia, aqui el nombre ya viene modificado element.nombre = tarea 
        
        const proyectoparams = new URLSearchParams(window.location.search);  //window.location = obtiene el link o url actual y .search es el ?id=xxx
        let proyecto =  Object.fromEntries(proyectoparams.entries()); //se obtiene los datos de la url en un objeto
        const url_id = proyecto.id;   //?id=xxxx
        
        const {id, nombre, estado, idproyectos} = element;  //element = {id: '4', nombre: 'añadir contenido', estado: '0', idproyectos: '3'} = registro de tabla tareas
    
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('idproyectos', idproyectos);
        datos.append('url', url_id);

       /* for(let valor of datos.values()){
            console.log(valor);  //iterar formdate
        }*/

        const url = "/api/tarea/actualizar";
        try {
            const respuesta = await fetch(url, {method: 'POST', body: datos}); //conecta y consulta a la api y envia datos por metodo post y en tarea_controlador.php se recibe en $_POST
            const resultado = await respuesta.json();

            if(resultado.tipo == 'exito'){

                const modal = document.querySelector('.modal');
                
                setTimeout(() => {
                    modal.remove();
                }, 3000);

                mostraralerta('cambio de nombre de la tarea correctamente', 'exito', document.querySelector('.formulario legend'));
            }
            
            //console.log(tareas); //tareas = [{},{},{}...]
            //tareas = [{id: '3', nombre: 'consultar precio', estado: '0', idproyectos: '1'},{},{}]
            tareas = tareas.map(tareamemoria =>{ //tareamemoria va tomando cada elemento {} del arreglo tareas y ejecuta la funcion, y la funcion va ir tomando cada elemento{}
            
                if(tareamemoria.id === id){
                    tareamemoria.nombre = nombre;
                }
                return tareamemoria;

            });
            mostrartareas();  
            

        }catch (error){
            console.log(error);
        }

    }

/////////////////////////////// eliminar tareas ////////////////////////////////////

    function eliminartarea(element){
        Swal.fire({
            title: 'Eliminar tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'no'
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              eliminandotarea(element);
            } 
          })
    }

    async function eliminandotarea(element){

        const proyectoparams = new URLSearchParams(window.location.search);  //window.location = obtiene el link o url actual y .search es el ?id=xxx
        let proyecto =  Object.fromEntries(proyectoparams.entries()); //se obtiene los datos de la url en un objeto
        const url_id = proyecto.id;   //?id=xxxx

        const {id, nombre, estado, idproyectos} = element;

        const datos = new FormData();
        datos.append('id', id);  //id de tarea a eliminar
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('idproyectos', idproyectos);
        datos.append('url', url_id);

        const url = "/api/tarea/eliminar";

        try {
            const respuesta = await fetch(url, {method: 'POST', body: datos}); //conecta y consulta a la api y envia datos por metodo post y en tarea_controlador.php se recibe en $_POST  
            const resultado = await respuesta.json();
            console.log(resultado);  //resultado = {tipo: 'exito', resultado: true}
            if(resultado.resultado == true){         //tareas = [{},{},{}]
                //mostraralerta('Eliminado correctamente', resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));
                Swal.fire('Eliminado', 'Tarea Eliminada correctamente', 'success');

                tareas = tareas.filter(tareamemoria => tareamemoria.id != element.id  //filter tambien itera y crea nuevo arreglo como el .map
                    //filter indica al arreglo del objetos globla tareas, que se traga todos los elementos es decir objetos, menos el objeto que tenga un id = element.id que es el objeto seleccionado con el ondblclick 
                );
                mostrartareas();
            }
            
        } catch (error) {
            console.log(error);
        }
    }

})();

