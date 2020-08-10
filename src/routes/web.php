<?php

use Politie\Http\Controllers\PolitiebureausController;
use Politie\Http\Controllers\WijkagentenController;

Route::get('geoquery', 'GeoController@index')->name('geo-query');

Route::get('politiebureaus', 'PolitiebureausController@index')->name('politiebureaus');
Route::get('politiebureaus/all', 'PolitiebureausController@all')->name('politiebureaus');
Route::get('mijn-buurt/politiebureaus', 'PolitiebureausController@query')->name('politiebureaus-query');

 // @todo temp
Route::get('politiebureaus/todb', 'PolitiebureausController@toDb')->name('politiebureaus-todb');
Route::get('politiebureaus/get_pictures', 'PolitiebureausController@getPictures')->name('politiebureaus-get_pictures');

Route::get('wijkagenten/all', 'WijkagentenController@all')->name('wijkagenten');
