<?php
/*
|--------------------------------------------------------------------------
| AskforQuote Routes
|--------------------------------------------------------------------------
*/

Route::post('company/{company}/askforquote','Shop\AskforQuoteController@add');
Route::get('company/{company}/askforquote_id/{askforquote_id}', 'Shop\AskforQuoteController@read');
Route::get('company/{company}/askforquote', 'Shop\AskforQuoteController@browse');
Route::delete('company/{company}/askforquote_id/{askforquote_id}', 'Shop\AskforQuoteController@delete');
Route::get('company/{company}/askforquote_delete/admin','Shop\AskforQuoteController@admin_delete');
Route::patch('company/{company}/askforquote_id/{askforquote_id}','Shop\AskforQuoteController@update');
Route::post('company/{company}/askforquote_id/{askforquote_id}/is_complete','Shop\AskforQuoteController@is_complete');

// shop AskforQuote Routes
//Route::post('shop/askforquote','Shop\AskforQuoteController@addtoSHOP');