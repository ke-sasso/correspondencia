<?php

Route::group(['prefix' => 'solicitud' , 'middleware' => ['auth' , 'verifypermissions']], function(){

    Route::get('/nueva',[
        'as' => 'nueva.solicitud',
        'permissions' => [470],
        'uses' => 'SolicitudesController@nuevaSolicitud'
      ]); 
           
       Route::get('/lista',[
        'as' => 'lista.solicitud',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaSolicitud'
      ]); 

       Route::get('/lista/EnRevision',[
        'as' => 'lista.solicitud.revision',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaSolicitudEnRevision'
      ]); 
          Route::get('/lista/asignadas',[
        'as' => 'lista.solicitud.asignadas.asesor',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaAsignadasAsesor'
      ]); 


         Route::get('/lista/pendientes',[
        'as' => 'lista.pendientes.asginar',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listapendientesAsignar'
      ]); 
        Route::get('/dt-row-data-pendientesasignar-s',[
        'as' => 'get.pendientes.asignar',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsPendientesAsignar'
      ]);

       Route::get('/lista/nuevas/recepcion',[
        'as' => 'lista.nuevas.recepcion',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaNuevasRecepcion'
      ]); 
        Route::get('/dt-row-data-nuevasrecepcion-s',[
        'as' => 'get.nuevas.recepcion',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsNuevasRecepcion'
      ]);



        Route::get('/lista/firma-revision/{idEstado}',[
        'as' => 'lista.firmar.soli',
        'permissions' => [469],
        'uses' => 'SolicitudesController@verFirmarRevision'
      ]); 
        Route::get('/dt-row-data-pendientesasignar',[
        'as' => 'get.firma.revision',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsRevisionFirma'
      ]);


        Route::get('/lista/asignadas/asistente',[
        'as' => 'lista.asignadas.asistente',
        'permissions' => [469],
        'uses' => 'SolicitudesController@asignadasAsistente'
      ]); 
        Route::get('/dt-row-data-asignadas-asiste',[
        'as' => 'get.asignar.asistente',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsAsignadasAsistente'
      ]);


       Route::get('/lista/all',[
        'as' => 'lista.todas.asistente',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listatodasAsistente'
      ]); 
       Route::get('/dt-row-data-todas-asiste',[
        'as' => 'get.todas.asistente',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsTodasAsistente'
      ]);

      Route::get('/lista/pendientes/participante',[
        'as' => 'lista.pendi.partici',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaPendienParti'
      ]); 
      Route::get('/dt-row-data-pednietes-participa',[
        'as' => 'get.pendientes.participante',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsPendiePartici'
      ]);

      Route::post('/storeArchivosAnexos',[
        'as' => 'guardar.archivos.anexos',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeArchivosAnex'
      ]);
         Route::post('/storeArchivosRespuestaFinal',[
        'as' => 'guardar.archivos.respuestaFinal',
        'permissions' => [469],
        'uses' => 'ComentariosController@storeArchivoRespFinal'
      ]);

       Route::post('/storeFirmarRevisionAsistente',[
        'as' => 'guardar.firmarevision.asistente',
        'permissions' => [469],
        'uses' => 'ComentariosController@storeFirmaRevision'
      ]);

       Route::get('/dt-row-data-personasNaturales',[
        'as' => 'get.persona.natural',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsPersonasNatural'
      ]);
         Route::get('/dt-row-data-solicitudes',[
        'as' => 'get.todas.solicitudes',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsSolicitudes'
      ]);

          Route::get('/dt-row-data-solicitudes-revision',[
        'as' => 'get.revision.solicitudes',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsSolicitudesRevision'
      ]);
               Route::get('/dt-row-data-solicitudes-asignadas-asesore',[
        'as' => 'get.asignadas.asesor',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsSolicitudesAsiAsesore'
      ]);

          Route::get('/empleadosJefes',[
        'as' => 'get.empleados.jefes',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getEmpleadosJefes'
      ]);

             Route::get('/comentarios',[
        'as' => 'get.comentarios.solicitud',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getComentarios'
      ]);
             Route::get('/TitularFecha',[
        'as' => 'get.fecha.Titular',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getParticipanteFecha'
      ]);

        Route::get('/dt-row-data-titular',[
        'as' => 'get.titular',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsTitular'
      ]);

    Route::post('/detalleSolicitud',[
          'as' => 'guardar.detalle.soli',
          'permissions' => [469],
          'uses' => 'SolicitudesController@storeDetalleSolicitud'
        ]); 

    Route::post('/storeSolicitud',[
        'as' => 'guardar.info.solicitud',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeSolicitud'
      ]);

     Route::post('/storeEditSolicitud',[
        'as' => 'editar.info.solicitud',
        'permissions' => [470],
        'uses' => 'SolicitudesController@storeEditSolicitud'
      ]);
      Route::post('/storeComentario',[
        'as' => 'guardar.comentario.solicitud',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeComentario'
      ]);
       Route::post('/storeComentario/colaborador',[
        'as' => 'guardar.comentario.colaborador',
        'permissions' => [469],
        'uses' => 'ComentariosController@storeComentarioColaborador'
      ]);
       Route::post('/storeComentario/asesor',[
        'as' => 'guardar.comentario.asesor',
        'permissions' => [469],
        'uses' => 'ComentariosController@storeComentarioAsesor'
      ]);
        Route::post('/storeComentario/responsable',[
        'as' => 'guardar.comentario.responsable',
        'permissions' => [469],
        'uses' => 'ComentariosController@storeComentarioResponsable'
      ]);
       Route::post('/storeComentario/asistente',[
        'as' => 'guardar.comentario.asistente',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeComentarioAsistente'
      ]);
      

          Route::get('/pdf-boleta-presentacion',[
      'as' => 'pdf.boleta.presentacion',
      'permissions' => [469],
      'uses' => 'PdfController@mostrarDatosPdf'
      ]);

      Route::get('/pdf-boleta-acta',[
      'as' => 'pdf.boleta.acta',
      'permissions' => [469],
      'uses' => 'PdfController@pdfActa'
      ]);

           Route::post('/storeParticipantes',[
        'as' => 'post.guardar.participantes',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeParticipantes'
      ]);
          
      Route::get('/ver-solicitud/{idSolicitud}',[
      'as' => 'verSolicitud',
      'permissions' => [469],
      'uses' => 'SolicitudesController@verSolicitud'
      ]);

        Route::get('/ver-solicitud-lectura/{idSolicitud}',[
      'as' => 'verSolicitudLectura',
      'permissions' => [469],
      'uses' => 'SolicitudesController@verSolicitudLectura'
      ]);

       Route::get('/editar-solicitud/{idSolicitud}',[
      'as' => 'editarSolicitud',
      'permissions' => [470],
      'uses' => 'SolicitudesController@editarSolicitud'
      ]);

        Route::get('/verDocumento/{urlDocumento}/{tipoArchivo}',[
        'as' => 'ver.documento',
        'permissions' => [469],
        'uses' => 'PdfController@download'
      ]); 


         Route::post('/aprobarSolicitud',[
        'as' => 'aprobar.solicitud',
        'permissions' => [472],
        'uses' => 'SolicitudesController@procesarSolicitud'
      ]);
          Route::post('/entregadaSolicitud',[
        'as' => 'entregada.solicitud',
        'permissions' => [470],
        'uses' => 'SolicitudesController@entregarSolicitud'
      ]);

         Route::post('/enviarNotificacion',[
        'as' => 'guardar.notificacion',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeNotificacion'
      ]);
         Route::post('/fechaParticipante',[
        'as' => 'guardar.fecha.part',
        'permissions' => [469],
        'uses' => 'SolicitudesController@storeFechaParticipante'
      ]);

         Route::post('/nuevoTitular',[
        'as' => 'guardar.nuevo.titular',
        'permissions' => [469],
        'uses' => 'CatalogoController@storeTitular'
      ]);
          Route::post('/nuevaPersonaNatural',[
        'as' => 'guardar.nueva.personaNatural',
        'permissions' => [469],
        'uses' => 'CatalogoController@storePersonaNatural'
      ]);

      Route::get('/documentoTipo',[
        'as' => 'get.tipoDocumento',
        'permissions' => [469],
        'uses' => 'CatalogoController@getTipoDocumento'
      ]);
       Route::get('/departamentos',[
        'as' => 'get.departamentos',
        'permissions' => [469],
        'uses' => 'CatalogoController@getDepartamentos'
      ]);
      Route::get('/municipios',[
        'as' => 'get.municipios.pn',
        'permissions' => [469],
        'uses' => 'CatalogoController@getMunicipios'
      ]);
      Route::post('/getMunicipiosAjax',[
            'as' => 'get.listMunicipio',
            'permissions' => [469],
          'uses' => 'CatalogoController@getComboboxMunicipiosAJAX'
      ]);
       Route::get('/tipoTratamiento',[
        'as' => 'get.tipo.tratamiento',
        'permissions' => [469],
        'uses' => 'CatalogoController@getTratamiento'
      ]);
 
       Route::get('/personaNatural/buscar',[
          'as' => 'pn.busqueda',
          'permissions' => [469],
          'uses' => 'CatalogoController@pnBusqueda'
        ]); 

       Route::get('/solicitud/buscar',[
          'as' => 'soli.busqueda',
          'permissions' => [469],
          'uses' => 'CatalogoController@solicitudBusqueda'
        ]); 
        Route::get('/participantes/buscar',[
          'as' => 'soli.participantes.busqueda',
          'permissions' => [469],
          'uses' => 'CatalogoController@participantesBusqueda'
        ]); 

        Route::post('/estadoComentario',[
          'as' => 'estado.comentario',
          'permissions' => [469],
          'uses' => 'SolicitudesController@aprobarComentario'
        ]);

        Route::post('/eliminarTitular/soli',[
          'as' => 'eliminar.titular',
          'permissions' => [469],
          'uses' => 'SolicitudesController@destroyTitular'
        ]); 
        Route::post('/eliminarArchivo/soli',[
          'as' => 'eliminar.archivo',
          'permissions' => [469],
          'uses' => 'SolicitudesController@destroyArchivo'
        ]);
          Route::post('/eliminarParticipante/soli',[
          'as' => 'eliminar.participante',
          'permissions' => [469],
          'uses' => 'SolicitudesController@destroyParticipante'
        ]);
          Route::post('/abrircaso/participante',[
          'as' => 'abrircaso.participante',
          'permissions' => [469],
          'uses' => 'SolicitudesController@abrircasoParticipante'
        ]);

       Route::post('/comentario/favorable',[
          'as' => 'comentario.favorable.asistente',
          'permissions' => [469],
          'uses' => 'ComentariosController@storeFavorable'
        ]);
       Route::post('/comentario/noaplica',[
          'as' => 'comentario.noaplica.asistente',
          'permissions' => [469],
          'uses' => 'ComentariosController@storeNoAplica'
        ]);
        
         Route::get('/comentario/asistencia/{idSolicitud}',[
          'as' => 'cometarios.asistencia',
          'permissions' => [469],
          'uses' => 'ComentariosController@comentarioAsistencia'
        ]); 
         

         Route::get('/comentario/opinion/{idSolicitud}',[
          'as'=>'comentario.opinion',
          'permissions' => [469],
          'uses' => 'ComentariosController@comentarioOpinion'
          ]);


         Route::post('/stroreAsistencia/soli',[
          'as' => 'guardar.asistencia.part',
          'permissions' => [469],
          'uses' => 'ComentariosController@storeAsistencia'
        ]);

          Route::get('/reporte/solicitudes-pendientes',[
          'as'=>'reporte.soli.pendientes',
          'permissions' => [469],
          'uses' => 'SolicitudesController@listaReportePendiente'
          ]);

       Route::get('/dt-row-data-pednietes-reporte',[
        'as' => 'get.reporte.pendientes.participante',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsPendieReporte'
      ]);


        Route::get('/lista/informativa/participante',[
        'as' => 'lista.informativa.partici',
        'permissions' => [469],
        'uses' => 'SolicitudesController@listaInformativaParti'
      ]); 
      Route::get('/dt-row-data-informativa-participa',[
        'as' => 'get.informativa.participante',
        'permissions' => [469],
        'uses' => 'SolicitudesController@getDataRowsInformaPartici'
      ]);

           Route::post('/reportar/sol/asignacion',[
          'as' => 'reportar.sol.asignacion',
          'permissions' => [469],
          'uses' => 'SolicitudesController@reportarAsignacion'
        ]);

        Route::post('/justificacion/prorroga/solicitud',[
          'as' => 'justificacion.prorroga.sol',
          'permissions' => [469],
          'uses' => 'SolicitudesController@justificacionProrroga'
        ]);
        
         Route::get('/dt-row-data-historial-prorroga',[
        'as' => 'get.historial.prorroga.sol',
        'permissions' => [469],
        'uses' => 'SolicitudesController@historialJustificacionProrroga'
      ]);
        

});