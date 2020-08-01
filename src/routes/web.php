<?php

use Politie\Http\Controllers\PolitiebureausController;

Route::get('geoquery', 'GeoController@index')->name('geo-query');

Route::get('politiebureaus', 'Politie\Http\Controllers\PolitiebureausController@index')->name('politiebureaus');
Route::get('politiebureaus/all', 'Politie\Http\Controllers\PolitiebureausController@all')->name('politiebureaus');
Route::get('mijn-buurt/politiebureaus', 'Politie\Http\Controllers\PolitiebureausController@query')->name('politiebureaus-query');

 // @todo temp
Route::get('politiebureaus/todb', 'Politie\Http\Controllers\PolitiebureausController@toDb')->name('politiebureaus-todb');
Route::get('politiebureaus/get_pictures', 'Politie\Http\Controllers\PolitiebureausController@getPictures')->name('politiebureaus-get_pictures');