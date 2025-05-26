@extends('adminlte::page')

@section('title', 'İhale Detayı')

@section('content_header')
    <h1>İhale Detayı</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">{{ $tender->title }}</h3>
                <div>
                    <a href="{{ route('admin.tenders.edit', $tender->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <a href="{{ route('admin.tenders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Listeye Dön
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>İhale Bilgileri</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Durum</th>
                                <td>
                                    <span class="badge {{ $tender->status == 'active' ? 'bg-success' : ($tender->status == 'cancelled' ? 'bg-danger' : 'bg-info') }}">
                                        {{ $tender->status_text }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>İhale Konusu</th>
                                <td>{{ $tender->title }}</td>
                            </tr>
                            <tr>
                                <th>İhale Birimi</th>
                                <td>{{ $tender->unit }}</td>
                            </tr>
                            <tr>
                                <th>KİK Kayıt No</th>
                                <td>{{ $tender->kik_no ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>İhale Tarihi/Saati</th>
                                <td>
                                    @if ($tender->tender_datetime)
                                        {{ $tender->tender_datetime->format('d.m.Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Oluşturulma Tarihi</th>
                                <td>{{ $tender->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>İletişim Bilgileri</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">İdare'nin Adresi</th>
                                <td>{{ $tender->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td>{{ $tender->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Faks</th>
                                <td>{{ $tender->fax ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>E-Posta</th>
                                <td>{{ $tender->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Döküman URL</th>
                                <td>
                                    @if ($tender->document_url)
                                        <a href="{{ $tender->document_url }}" target="_blank">{{ $tender->document_url }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5>İhale Özeti</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $tender->summary ?? 'İhale özeti bulunmamaktadır.' }}
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5>İhale Konusu, Hizmetin Niteliği, Türü ve Miktarı</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $tender->description ?? 'Bilgi bulunmamaktadır.' }}
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Teslim Bilgileri</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Teslim Yeri</th>
                                <td>{{ $tender->delivery_place ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Teslim Tarihi</th>
                                <td>{{ $tender->delivery_date ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>İhale Yeri</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $tender->tender_address ?? 'İhale yeri bilgisi bulunmamaktadır.' }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h5>İhale Metni</h5>
                    <div class="p-3 bg-light rounded">
                        {!! $tender->content ?? 'İhale metni bulunmamaktadır.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop 