// -----------------------------------------------------------
//  initialize Data table
// -----------------------------------------------------------


function loading(icon, title, message) {
    
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
}


// -----------------------------------------------------------
//  initialize Data table with button
// --------------------------------------------------------

// $(document).ready(function() {
//   $('#example').DataTable({
//       responsive: true,
//       dom: 'Bfrtip',
//       buttons: [
//         'csv', 'excel', 'pdf', 'print',
//       ]
//   });
// } );



// -----------------------------------------------------------
//  Generate Avatar Initials
// -----------------------------------------------------------
var colors = ["#69B0E9", "#717BB8", "#37B2C1", "#6B7DBD", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22", "#e74c3c", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"];

var fullname = $('.avatar-initials').data('name');

var splitname = fullname.split(' ');

if(splitname.length == 1) {
var avatarElement = $('.avatar-initials'),
avatarWidth = avatarElement.attr('width'),
avatarHeight = avatarElement.attr('height'),
  
name = avatarElement.data('name'),
initials = name.split('')[0].charAt(0).toUpperCase(),

charIndex = initials.charCodeAt(0) - 65,
colorIndex = charIndex % 19;

avatarElement.css({
    'background-color': colors[colorIndex],
    'width': avatarWidth,
    'height': avatarHeight,
    'font' : avatarWidth / 3 + "px Arial",
    'color': '#FFF',
    'textAlign': 'center',
    'lineHeight': 27 + 'px',
    'borderRadius': '50%'
})
.html(initials);
}
else {
var avatarElement = $('.avatar-initials'),
avatarWidth = avatarElement.attr('width'),
avatarHeight = avatarElement.attr('height'),
  
name = avatarElement.data('name'),
initials = name.split('')[0].charAt(0).toUpperCase() + name.split(" ")[1].charAt(0).toUpperCase(),

charIndex = initials.charCodeAt(0) - 65,
colorIndex = charIndex % 19;

avatarElement.css({
    'background-color': colors[colorIndex],
    'width': avatarWidth,
    'height': avatarHeight,
    'font' : avatarWidth / 3 + "px Arial",
    'color': '#FFF',
    'textAlign': 'center',
    'lineHeight': 27 + 'px',
    'borderRadius': '50%'
})
.html(initials);
}
