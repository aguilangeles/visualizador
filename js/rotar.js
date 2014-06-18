var rotate_angle = 0;
        function rotarImagen()
        {
           $('#rotate_button').click(function(){
            alert('hice clic!'+rotate_angle);
            rotate_angle = ( rotate_angle >= 360 ) ? 0 : rotate_angle +10;
            alert(rotate_angle);
            $('#imgprincipal').rotate({ angle : rotate_angle });
            });
        }