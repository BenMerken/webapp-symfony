require('../css/app.css');

const $ = require('jquery');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
