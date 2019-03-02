<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "Auth\LoginController@showLoginForm")->name('login');
Route::get('/login', "Auth\LoginController@showLoginForm")->name('login');
Route::post('/login', "Auth\LoginController@login")->middleware('checkuserstatus');
Route::post('/logout', "Auth\LoginController@logout")->name('logout');

Route::group(['prefix' => 'password'], function () {
    Route::get('/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset','Auth\ResetPasswordController@reset')->name('password.update');
});

Route::get('/ambiente', "AmbienteController@index")->name("ambiente");
Route::get('/validarCertificado', function(){
    return view('validateCertification');
})->name("guest-form-validate-certificate");
Route::post('/validarCertificado', 'PdfViewController@validateCertificate')->name("guest-validate-certificate");

Route::group(['prefix' => 'usuario'], function () {
    Route::get('/', "UserController@index")->name("user-home");
    Route::get('/meus-cursos', "UserController@myCourses")->name("user-show-courses");
    Route::get('/certificate/{id}',"PdfViewController@index")->name('user-download-certificate');
    Route::get('/profile',"UserController@profile")->name('user-profile');
    Route::post('/profile/setImage',"UserController@setImageProfile")->name('user-set-image-profile');
    
    Route::group(['middleware' => 'checkcanaccesscourse'], function () {
        Route::get('/curso/{id}','UserController@showCourse')->name('user-show-course');
        Route::get('/curso/{id}/matricular','UserController@registerInCourse')->name('user-register-in-course');
        Route::get('/curso/{id}/continuar/{idItem?}','UserController@continueCourse')->name('user-continue-course');
        Route::post('/curso/{id}/resposta/{idQuestion}','UserController@responseQuestion')->name('user-response-question');

        Route::get('/curso/{id}/feedback','UserController@showFormFeedback')->name('user-show-form-feedback');
        Route::post('/curso/{id}/feedback','UserController@saveFeedback')->name('user-save-feedback');
    });
});

Route::group(['prefix' => 'instrutor'], function () {
    Route::get('/cursos', "InstructorController@index")->name("instructor-home");
    Route::get('/cursos/novo', "InstructorController@showCreateCourseForm")->name("instructor-form-create-course");
    Route::post('/cursos/novo', "InstructorController@registerCourse")->name("instructor-create-course");

    Route::group(['middleware' => 'checkcanhandlecourse'], function () { 
        Route::get('/cursos/{id}', "InstructorController@showCourse")->name("instructor-show-course");
        Route::get('/cursos/{id}/editar', "InstructorController@showUpdateCourseForm")->name("instructor-form-edit-course");
        Route::put('/cursos/{id}/editar', "InstructorController@updateCourse")->name("instructor-update-course");

        Route::delete('/cursos/{id}',"InstructorController@deleteCourse")->name("instructor-delete-course");
        Route::put('/cursos/{id}/publish',"InstructorController@publishCourse")->name("instructor-publish-course");
        Route::put('/cursos/{id}/suspend',"InstructorController@suspendCourse")->name("instructor-suspend-course");
        Route::put('/cursos/{id}/disable',"InstructorController@disableCourse")->name("instructor-disable-course");

        Route::get('/cursos/{id}/conteudo', "InstructorController@showCourseContent")->name("instructor-show-course-content");
        Route::get('/cursos/{id}/conteudo/editar', "InstructorController@showFormEditContent")->name("instructor-show-form-content");

        Route::post('/cursos/{id}/conteudo/unidade/', "InstructorController@addUnit")->name("instructor-add-unit");
        Route::put('/cursos/{id}/conteudo/unidade/', "InstructorController@orderUnits")->name("instructor-order-units");
        Route::put('/cursos/{id}/conteudo/unidade/{idUnit}', "InstructorController@editUnit")->name("instructor-edit-unit");
        Route::delete('/cursos/{id}/conteudo/unidade/{idUnit}', "InstructorController@removeUnit")->name("instructor-remove-unit");

        Route::put('/cursos/{id}/conteudo/items/', "ItemController@orderItems")->name("instructor-order-items");
        Route::delete('/cursos/{id}/conteudo/item/{idItem}',"ItemController@removeItem")->name('instructor-remove-item');

        Route::post('/cursos/{id}/conteudo/unidade/{idUnit}/videolessonOrLink/',"ItemController@addVideoLessonOrLink")->name('instructor-add-videolessonOrLink')->middleware('checkcanadditem');
        Route::put('/cursos/{id}/conteudo/videolessonOrLink/{idItem}',"ItemController@editVideoLessonOrLink")->name('instructor-edit-videolessonOrLink');
        
        Route::get('/cursos/{id}/conteudo/unidade/{idUnit}/test/',"ItemController@showFormCreateTest")->name('instructor-form-add-test');
        Route::post('/cursos/{id}/conteudo/unidade/{idUnit}/test/',"ItemController@addTest")->name('instructor-add-test');
        Route::get('/cursos/{id}/conteudo/test/{idItem}',"ItemController@showFormEditTest")->name('instructor-form-edit-test');
        Route::put('/cursos/{id}/conteudo/test/{idItem}',"ItemController@editTest")->name('instructor-edit-test');
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', "AdminController@index")->name("admin-home");

    Route::get('/novoUsuario', "AdminController@showRegistrationForm")->name("admin-form-register-user");
    Route::post('/novoUsuario', "AdminController@registration")->name("admin-register-user");

    Route::group(['middleware' => 'checkcanhandleuser'], function () {
        Route::get('/atualizarUsuario/{id}', "AdminController@showUpdateUserForm")->name("admin-form-update-user");
        Route::put('/atualizarUsuario/{id}', "AdminController@updateUser")->name("admin-update-user");
        Route::get('/showUser/{id}', "AdminController@showUser")->name("admin-show-user");

        Route::get('/user/{id}/certificate/{idSubscription}',"PdfViewController@downloadCertificate")->name('admin-download-certificate');        
    });
});
