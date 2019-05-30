<?php

use Illuminate\Http\Request;

Route::group(['prefix'=>'mark'],function(){

Route::post('/create','MarkController@store')->name('mark.create');
Route::post('/show','MarkController@read')->name('mark.read');

Route::post('/delete','MarkController@delete_mark')->name('mark.delete');

Route::post('/edit','MarkController@edit_mark')->name('mark.edit');

});
Route::group(['prefix'=>'stock'],function(){

Route::post('/create','StockController@store')->name('stock.create');
Route::post('/read','StockController@read')->name('stock.read');

Route::post('/delete','StockController@delete_stock')->name('stock.delete');

Route::post('/edit','StockController@edit_stock')->name('stock.edit');

});
