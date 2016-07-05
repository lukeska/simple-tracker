var elixir = require('laravel-elixir');

// waiting for Laravel Elixir 6
//require('laravel-elixir-browserify-official');

elixir(function(mix) {
    
    mix.styles(['main.css', 'responsive.css']);

    //mix.browserify('main.js');
});