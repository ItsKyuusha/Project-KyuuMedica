@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')

@section('content')
<div class="container">
    <h3>Pemeriksaan Pasien "{{ $daftar->pasien->nama }}"</h3>

    <form action="{{ route('dokter.pemeriksaan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_daftar_poli" value="{{ $daftar->id }}">
        <input type="hidden" name="tgl_periksa" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

        {{-- Keluhan --}}
        <div class="form-group">
            <label>Keluhan Pasien</label>
            <input type="text" class="form-control" value="{{ $daftar->keluhan }}" readonly>
        </div>

        {{-- Jadwal Pemeriksaan --}}
        <div class="form-group">
            <label>Jadwal Pemeriksaan</label>
            <input type="text" class="form-control" 
                value="{{ $daftar->jadwal->hari }}, {{ \Carbon\Carbon::createFromFormat('H:i:s', $daftar->jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $daftar->jadwal->jam_selesai)->format('H:i') }}"readonly>
        </div>

        {{-- Catatan --}}
        <div class="form-group">
            <label for="catatan">Catatan Pemeriksaan</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
        </div>

        {{-- Resep Obat --}}
        <div class="form-group">
            <label for="obat_ids">Resep Obat</label>
            <div id="obat-list" class="d-flex flex-wrap gap-3">
                @foreach ($obats as $obat)
                    <div class="form-check" style="min-width: 200px;">
                        <input
                            class="form-check-input obat-checkbox"
                            type="checkbox"
                            name="obat_ids[]"
                            value="{{ $obat->id }}"
                            id="obat{{ $obat->id }}"
                            data-harga="{{ $obat->harga }}"
                        >
                        <label class="form-check-label" for="obat{{ $obat->id }}">
                            {{ $obat->nama }} - Rp{{ number_format($obat->harga, 0, ',', '.') }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Total Biaya Obat --}}
        <div class="form-group">
            <label for="total_biaya">Total Biaya Obat</label>
            <input type="text" id="total_biaya" class="form-control" readonly>
        </div>

        {{-- Biaya Pemeriksaan (tetap) --}}
        <div class="form-group">
            <label for="biaya_pemeriksaan">Biaya Pemeriksaan</label>
            <input type="text" id="biaya_pemeriksaan" name="biaya_pemeriksaan" class="form-control"
                value="150000" readonly>
        </div>

        {{-- Total Semua --}}
        <div class="form-group">
            <label for="total_semua">Total Keseluruhan</label>
            <input type="text" id="total_semua" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Pemeriksaan</button>
    </form>

    <hr>
    <h4>Riwayat Pemeriksaan Pasien</h4>

    @if ($riwayatPemeriksaan->isEmpty())
        <p class="text-muted">Belum ada riwayat pemeriksaan.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Dokter</th>
                        <th>Keluhan</th>
                        <th>Catatan</th>
                        <th>Obat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayatPemeriksaan as $periksa)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d-m-Y') }}</td>
                            <td>{{ optional($periksa->daftarPoli->jadwal->dokter)->nama ?? '-' }}</td>
                            <td>{{ $periksa->daftarPoli->keluhan ?? '-' }}</td>
                            <td>{{ $periksa->catatan ?? '-' }}</td>
                            <td>
                                @if ($periksa->detailPeriksas->isEmpty())
                                    <span class="text-muted">Tidak ada</span>
                                @else
                                    <ul class="mb-0">
                                        @foreach ($periksa->detailPeriksas as $detail)
                                            <li>{{ $detail->obat->nama }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        function formatRupiah(angka) {
            return 'Rp' + angka.toLocaleString('id-ID');
        }

        function updateTotalBiaya() {
            let totalObat = 0;

            $('.obat-checkbox:checked').each(function () {
                totalObat += Number($(this).data('harga'));
            });

            $('#total_biaya').val(formatRupiah(totalObat));

            const biayaPemeriksaan = 150000;
            const totalSemua = totalObat + biayaPemeriksaan;

            $('#total_semua').val(formatRupiah(totalSemua));
        }

        $('.obat-checkbox').on('change', updateTotalBiaya);

        updateTotalBiaya(); // inisialisasi awal
    });
</script>
@endpush
