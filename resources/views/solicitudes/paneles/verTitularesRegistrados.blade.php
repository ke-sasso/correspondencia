 <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Nombre del Titular</th>
                              <th>Telefonos</th>
                              <th>Correo</th>
                            </tr>
                          </thead>
                          <tbody>
                               @foreach($tit as $t)
                                <tr>
                               <td>{{$t->nombreTitular}}</td>
                                <td>{{json_decode($t->telefonosContacto)[0]}}<br>{{json_decode($t->telefonosContacto)[1]}}</td>
                                 <td>{{$t->emailContacto}}</td>
                                </tr>
                                @endforeach
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->