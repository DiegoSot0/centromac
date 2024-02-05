<!DOCTYPE html>
<html>

<head>
    <title>Consulta de DNI</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Estilos CSS personalizados para aumentar el tamaño del texto */
    .text-large {
        font-size: 38px;
        /* Ajusta el tamaño del texto según tus preferencias */
    }

    .container-box {
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        background-color: #f9f9f9;
        /* Cambia el color de fondo a gris */
        text-align: center;
        /* Centra horizontalmente */
    }

    .custom-border {
        border: 2px solid black;
        /* Cambia el color del borde según tus preferencias */
    }

    .image-container img {
        width: 45%;
        /* Hace que la imagen ocupe todo el ancho de su contenedor */
    }

    i {
        font-family: FontAwesome;
        font-style: normal;
        color: #FFF;

    }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <div class="container-box custom-border mt-4">
            <div class="text-center">
                <div class="image-container">
                    <img src="maclogo.png" alt="Imagen" class="img-fluid m-5">
                </div>
                <div class="input-group mb-3">
                    <input type="text" id="dni" autocomplete="off" name="dni" class="form-control text-large"
                        oninput="validarDNI(this)" inputmode="numeric" required>
                    <div class="input-group-append">
                        <button id="clearDNI" class="btn btn-danger btn-outline-secondary" type="button"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>
                <button id="prueba" class="btn btn-primary m-2 p-2 text-large">Consultar</button>
            </div>
            <br>
            <br>
            <div>
                <label id="nombreCompleto" class="text-large" style="color: black;"></label>
            </div>
            <div>
                <label id="errorMensaje" class="text-large" style="color: red;"></label>
            </div>
            <div>
                <button id="copiarNombre" class="btn btn-warning m-4 p-2 text-large" style="display: none;">Copiar
                    Nombre
                </button>
                <button id="copiarDNI" class="btn btn-info m-4 p-2 text-large" style="display: none;">Copiar
                    DNI</button>
            </div>

        </div>
    </div>

    <script>
    var segundaBusquedaRealizada = false;

    function consultarDNI() {
        var dni = $("#dni").val();

        $.ajax({
            type: "POST",
            url: "consulta-dni-ajax.php",
            data: 'dni=' + dni,
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    $("#nombreCompleto").empty();
                    $("#errorMensaje").html('El DNI tiene que tener 8 dígitos');
                    $("#copiarNombre").hide();
                    $("#copiarDNI").hide();
                    if (!segundaBusquedaRealizada) {
                        realizarSegundaBusqueda(dni);
                    }
                } else {
                    $("#errorMensaje").empty();
                    console.log(data);
                    $("#nombreCompleto").html(data.nombres + " " + data.apellidoPaterno + " " + data
                        .apellidoMaterno);
                    $("#copiarNombre").show();
                    $("#copiarDNI").show();
                }
            },
            error: function() {
                $("#nombreCompleto").empty();
                $("#copiarNombre").hide();
                $("#copiarDNI").hide();
                if (!segundaBusquedaRealizada) {
                    realizarSegundaBusqueda(dni);
                }
            }
        });
    }

    $("#prueba").click(function() {
        consultarDNI();
    });

    $("#dni").keypress(function(event) {
        if (event.which === 13) { // Si se presiona la tecla "Enter"
            consultarDNI();
            event.preventDefault(); // Evita que el formulario se envíe (si es un formulario)
        }
    });

    $("#copiarNombre").click(function() {
        var nombreCompleto = $("#nombreCompleto").text();

        // Crea un elemento de texto temporal (input) para copiar el contenido y lo selecciona
        var input = document.createElement("input");
        input.setAttribute("value", nombreCompleto);
        document.body.appendChild(input);
        input.select();

        // Copia el contenido seleccionado al portapapeles
        document.execCommand("copy");

        // Elimina el elemento temporal
        document.body.removeChild(input);

    });
    $("#copiarDNI").click(function() {
        var dni = $("#dni").val();

        // Crea un elemento de texto temporal (input) para copiar el DNI y lo selecciona
        var input = document.createElement("input");
        input.setAttribute("value", dni);
        document.body.appendChild(input);
        input.select();

        // Copia el contenido seleccionado al portapapeles
        document.execCommand("copy");

        // Elimina el elemento temporal
        document.body.removeChild(input);
    });
    $("#clearDNI").click(function() {
        $("#dni").val(""); // Borra el contenido del campo de entrada DNI
    });

    function validarDNI(input) {
        var dni = input.value;

        // Eliminar caracteres que no sean números
        dni = dni.replace(/\D/g, '');

        // Limitar a 8 caracteres
        if (dni.length > 8) {
            dni = dni.slice(0, 8);
        }

        // Actualizar el valor del campo
        input.value = dni;
    }

    function realizarSegundaBusqueda(dni) {
        $.ajax({
            type: "POST",
            url: "segundo-json.php", // Reemplaza "segundo-json.php" con la URL correcta del segundo JSON
            data: 'dni=' + dni,
            dataType: 'json',
            success: function(data) {
                if (!data.error) {
                    if (data.NOMBRE.trim()=="") {
                        $("#errorMensaje").html('DNI NO REGISTRADO');
                    } else {
                        // Se encontraron datos en el segundo JSON
                    $("#errorMensaje").empty();
                    console.log(data);
                    $("#nombreCompleto").html(data.NOMBRE + " " + data.APELLIDO_PATERNO + " " + data
                        .APELLIDO_MATERNO);
                    $("#copiarNombre").show();
                    $("#copiarDNI").show();
                    }
                    
                } else {
                    $("#errorMensaje").html('DNI NO REGISTRADO');
                }
            },
            error: function() {
                $("#errorMensaje").html('Error en la búsqueda del segundo JSON');
            }
        });
    }
    </script>
</body>

</html>