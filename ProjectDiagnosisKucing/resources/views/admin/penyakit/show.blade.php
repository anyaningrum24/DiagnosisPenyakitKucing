

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><b>{{ $penyakit->name }}</b></h5>
                        <p>
                            <b>Deskripsi</b><br>
                            {{ $penyakit->desc }}
                        </p>

                        <p>
                            <b>Penanganan</b><br>
                            {{ $penyakit->penanganan }}
                        </p>
                    </div>

                    <div class="col-md-6">

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                            <i class="fas fa-plus"></i> Tambah Gejala
                          </button>

                          <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Tambah Gejala</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">

                                  <form action="/admin/penyakit/add-gejala" method="post">
                                  @csrf

                                

                                  <input type="hidden" name="penyakit_id" value="{{ $penyakit->id }}">
                                  <div class="form-group">
                                    <div class="label">Gejala</div>
                                    <select name="gejala_id" class="form-control" id="">
                                      <option value="">-- Gejala --</option>
                                      @foreach ($gejala as $item)

                                        @php
                                            $cek = App\Models\Role::whereGejalaId($item->id)->wherePenyakitId($penyakit->id)->first();
                                        @endphp

                                        @if ($cek == false)
                                            
                                      
                                          <option value="{{ $item->id }}">{{ $item->kode_gejala.'-'.$item->name }}</option>
                                        @endif
                                         
                                        @endforeach
                                    </select>
                                  </div>

                                 
                                  <div class="form-group">
                                    <div class="label">Nilai CF</div>
                                    <select name="bobot_cf" class="form-control" required>
                                        <option value="">-- Pilih Nilai CF --</option>
                                        <option value="0.2">0.2</option>
                                        <option value="0.4">0.4</option>
                                        <option value="0.6">0.6</option>
                                        <option value="0.8">0.8</option>
                                        <option value="1.0">1.0</option>
                                    </select>
                                </div>

                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                  </div>

                                </form>


                                </div>
                               
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>
                          <!-- /.modal -->

                        <table class="table">
                            <tr>
                                <td>No</td>
                                <td>Kode</td>
                                <td>Name</td>
                                <td>Bobot CF</td>
                                <td>#</td>
                            </tr>

                            @foreach ($role as $item)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $item->gejala->kode_gejala }}</td>
                                  <td>{{ $item->gejala->name }}</td>
                                  <td>{{ $item->bobot_cf }}</td>
                                  <td>
                                    <form action="/admin/penyakit/delete-role/{{ $item->id }}" method="POST">
                                      @method('DELETE')
                                      @csrf
                                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                  </form>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>