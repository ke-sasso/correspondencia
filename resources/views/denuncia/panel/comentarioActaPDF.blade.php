<div class="modal fade" id="formComentarioActa" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                   Comentario  para PDF Acta
                </h4>
            </div>
            <div class="modal-body">
              <form action="{{route('enviar.comentario.pdfacta')}}" method="POST" class="form form-vertical" role="form" id="formComentarioPDFACTA">
                            
                    <div class="panel-body">                                                    
                
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                              
                                    <div class="input-group-addon"><b>Comentario</b></div>
                                    <textarea class="form-control summernote-sm" id="comentario" name="comentario" autocomplete="off" placeholder="Escribir comentario..."></textarea>
                               
                            </div>
                            </div> 
                           </div>  
                      
                                                                           
                    </div>
                   <div class="modal-footer">                          
                 <div class=" text-center">
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="idd2" id="idd2">

                        <button type="submit" class="btn btn-success btn-perspective">Enviar</button>
                        <button type="button" class="btn btn-default btn-perspective" data-dismiss="modal">
                                Cancelar</button>
                    </div>             
            </div>
                                
                    
                </form>     
               
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
           
        </div>
    </div>
</div>