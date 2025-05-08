<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $pasien->name }}</td>
                            </tr>
                            <tr>
                                <td>Umur</td>
                                <td>: {{ $pasien->umur. ' Tahun' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        {{-- ✅ Tambahkan keterangan di sini --}}
                        <div class="alert alert-warning">
                            <strong>Perhatian!</strong> Pilih <strong>minimal 3 gejala</strong> yang sesuai dengan kondisi kucing peliharaan Anda. 
                            Jika Anda mengisi semua gejala yang sesuai, hasil diagnosis akan <strong>lebih akurat</strong>.
                        </div>

                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Gejala</th>
                                <th>Tingkat Keyakinan</th>
                            </tr>

                            @foreach ($gejala as $item)
                                @php
                                    $cek = App\Models\Diagnosa::whereGejalaId($item->id)->wherePasienId(session()->get('pasien_id'))->first();
                                @endphp

                                @if ($cek == false)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_gejala }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Pilih</button>
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=1.0">Sangat Yakin/Pasti</a>
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=0.8">Yakin</a>
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=0.6">Cukup Yakin</a>
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=0.4">Sedikit Yakin</a>
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=0.2">Tidak Yakin</a>
                                                    <a class="dropdown-item" href="/diagnosa/pilih?gejala_id={{ $item->id }}&nilai=0.0">Tidak/Tidak Tahu</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Gejala</th>
                                <th>Nilai Keyakinan User</th>
                                <th>#</th>
                            </tr>

                            @foreach ($gejelaTerpilih as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->gejala->kode_gejala }}</td>
                                    <td>{{ $item->gejala->name }}</td>
                                    <td>{{ $item->nilai_cf }}</td>
                                    <td>
                                        <a href="/diagnosa/hapus-gejala?gejala_id={{ $item->gejala_id }}" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        <a href="/diagnosa/proses" class="btn btn-primary btn-block"><i class="fas fa-circle"></i> Diagnosa</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
