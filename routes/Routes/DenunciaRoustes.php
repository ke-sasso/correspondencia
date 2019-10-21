<?php

Route::group(['prefix' => 'denuncia' , 'middleware' => ['auth' , 'verifypermissions']], function(){

    Route::get('/nueva',[
        'as' => 'nueva.denuncia',
        'permissions' => [483],
        'uses' => 'DenunciaController@nuevaDenuncia'
      ]); 

    Route::post('/storeDenuncia',[
        'as' => 'guardar.info.denuncia',
        'permissions' => [483],
        'uses' => 'DenunciaController@storeDenuncia'
      ]);

      Route::get('/nueva/telefonica',[
        'as' => 'ver.detalle.denuncia',
        'permissions' => [483],
        'uses' => 'DenunciaController@verDetalleDenuncia'
      ]);


      Route::get('/nueva/ciudadana',[
        'as' => 'nueva.denuncia.ciudadana',
        'permissions' => [483],
        'uses' => 'DenunciaController@nuevaCiudadana'
      ]);


      Route::post('/store/detalle',[
        'as' => 'store.detalle.denuncia',
        'permissions' => [483],
        'uses' => 'DenunciaController@storeDetalleDenuncia'
      ]);

      Route::post('/store/ciudadana',[
        'as' => 'store.denuncia.ciudadana',
        'permissions' => [483],
        'uses' => 'DenunciaController@storeCiudadana'
      ]);

      Route::get('/dt-row-solicitudes-denuncia',[
        'as' => 'get.denuncia.solicitudes',
        'permissions' => [469],
        'uses' => 'DenunciaController@rowslistaDenuncia'
      ]);
          Route::get('/dt-row-solicitudes-denuncia/archivadas',[
        'as' => 'get.denuncia.solicitudes.archivadas',
        'permissions' => [469],
        'uses' => 'DenunciaController@rowsListaArchivadas'
      ]);
       Route::get('/dt-row-solicitudes-denuncia/asignadas',[
        'as' => 'get.denuncia.asignadas',
        'permissions' => [469],
        'uses' => 'DenunciaController@rowsListaAsignadas'
      ]);


      Route::get('/dt-row-nuevas-denuncias',[
        'as' => 'get.denuncia.nuevas',
        'permissions' => [469],
        'uses' => 'DenunciaController@rowslistaNuevasDenuncia'
      ]);
       Route::get('/dt-row-revision-denuncias',[
        'as' => 'get.denuncia.revision',
        'permissions' => [469],
        'uses' => 'DenunciaController@rowslistaRevisionDenuncia'
      ]);


      Route::get('/lista',[
        'as' => 'lista.solicitud.denuncia',
        'permissions' => [469],
        'uses' => 'DenunciaController@listaDenuncia'
      ]);
        Route::get('/lista/asignadas',[
        'as' => 'lista.denuncia.asignadas',
        'permissions' => [469],
        'uses' => 'DenunciaController@listaAsignadas'
      ]);
       Route::get('/lista/archivadas',[
        'as' => 'lista.denuncia.archivadas',
        'permissions' => [469],
        'uses' => 'DenunciaController@listaArchivadas'
      ]);
       Route::get('/lista/nuevas',[
        'as' => 'lista.nuevas.denuncia',
        'permissions' => [469],
        'uses' => 'DenunciaController@listanuevasDenuncia'
      ]);
        Route::get('/lista/revision',[
        'as' => 'lista.revision.denuncia',
        'permissions' => [469],
        'uses' => 'DenunciaController@listarevisionDenuncia'
      ]);


        Route::get('/editar/telefonica/{idSolicitud}',[
      'as' => 'editarDenuncia',
      'permissions' => [483],
      'uses' => 'DenunciaController@editarDenuncia'
      ]);
        Route::get('/editar/ciudadana/{idSolicitud}',[
      'as' => 'editarCiudadana',
      'permissions' => [483],
      'uses' => 'DenunciaController@editarDenunciaCiudadana'
      ]);

        Route::post('/store/editarDenuncia',[
        'as' => 'store.editar.denuncia.solicitud',
        'permissions' => [483],
        'uses' => 'DenunciaController@storeEditarDenuncia'
      ]);
         Route::post('/store/editarCiudadana',[
        'as' => 'store.editar.denuncia.ciudadana',
        'permissions' => [483],
        'uses' => 'DenunciaController@storeEditarCiudadana'
      ]);

       Route::post('/store/finalizarColaborador',[
        'as' => 'finalizar.colaborador',
        'permissions' => [469],
        'uses' => 'DenunciaController@stroreFinalizarColaborador'
      ]);



      Route::get('/ver/telefonica/{idSolicitud}',[
        'as' => 'ver.denuncia',
        'permissions' => [469],
        'uses' => 'DenunciaController@verDenuncia'
      ]);

       Route::get('/ver/ciudadana/{idSolicitud}',[
        'as' => 'ver.denuncia.ciudadana',
        'permissions' => [469],
        'uses' => 'DenunciaController@verCiudadana'
      ]);

       Route::post('/storeComentario/denuncia',[
        'as' => 'guardar.comentario.denuncia',
        'permissions' => [469],
        'uses' => 'DenunciaController@storeComentarioDenuncia'
      ]);


       Route::post('/denun/storeComentario/colaborador',[
        'as' => 'guardar.denuncia.colaborador',
        'permissions' => [469],
        'uses' => 'DenunciaController@storeColaboradorDenuncia'
      ]);

       Route::get('/procede/opinion/{idSolicitud}/{formulario}/{tipoProcede}',[
          'as'=>'procede.denuncia',
          'permissions' => [469],
          'uses' => 'DenunciaController@procedeDenuncia'
          ]);


         Route::post('/stroreAsistencia/denucia',[
          'as' => 'guardar.asistencia.denuncia',
          'permissions' => [469],
          'uses' => 'DenunciaController@storeAsistenciaDenuncia'
        ]);

         Route::get('/pdf-boleta-denuncia',[
      'as' => 'pdf.boleta.denuncia',
      'permissions' => [469],
      'uses' => 'PdfController@pdfDenuncia'
      ]);
      Route::get('/pdf-boleta-acta/{idDenuncia}',[
      'as' => 'pdf.boleta.acta',
      'permissions' => [469],
      'uses' => 'PdfController@pdfActa'
      ]);

       Route::get('/dt-row-data-establecimientos-no',[
        'as' => 'get.establecimientos.no',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDataRowsEstablecimientosNo'
      ]);

         Route::get('/dt-row-data-establecimientos-si',[
        'as' => 'get.establecimientos.si',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDataRowsEstablecimientosSi'
      ]);

         Route::get('/dt-row-data-productos-no',[
        'as' => 'get.productos.no',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDataRowsProductosNo'
      ]);

          Route::get('/dt-row-data-productos-si',[
        'as' => 'get.productos.si',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDataRowsProductosSi'
      ]);

      Route::post('/nuevoEstablecimiento',[
        'as' => 'guardar.nuevo.establecimiento',
        'permissions' => [469],
        'uses' => 'CatalogoController@storeEstablecimiento'
      ]);

       Route::post('/nuevoProducto',[
        'as' => 'guardar.nuevo.producto',
        'permissions' => [469],
        'uses' => 'CatalogoController@storeProducto'
      ]);

      Route::post('/eliminarRegistro/soli',[
          'as' => 'eliminar.registro',
          'permissions' => [469],
          'uses' => 'DenunciaController@destroyDetalle'
        ]);

        Route::post('/eliminarRegistro/soli',[
          'as' => 'eliminar.registro',
          'permissions' => [469],
          'uses' => 'DenunciaController@destroyDetalle'
        ]);

         Route::post('/enviar/juntadirectiva',[
          'as' => 'enviar.junta.denuncia',
          'permissions' => [469],
          'uses' => 'DenunciaController@enviarJuntaDirectiva'
        ]);

      Route::get('/cat/medioRecepcion',[
        'as' => 'ver.cat.medios',
        'permissions' => [469],
        'uses' => 'CatalogoController@indexMedios'
      ]);

      Route::get('/dt-row-data-establecimientos-si',[
        'as' => 'get.medios.recepcion',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDataRowsMedios'
      ]);
         Route::get('/info/medios',[
          'as' => 'data.medios.recepcion',
          'permissions' => [469],
          'uses' => 'CatalogoController@getMedioRecepcion'
      ]); 

          Route::post('/store/cat/medios',[
          'as' => 'store.info.medios',
          'permissions' => [469],
          'uses' => 'CatalogoController@storeMedios'
        ]);


             Route::post('/enviar/comentario/pdfacta',[
          'as' => 'enviar.comentario.pdfacta',
          'permissions' => [469],
          'uses' => 'DenunciaController@enviarComentarioActa'
        ]);
   
});