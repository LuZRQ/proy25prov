@php
    $backRoute = route('reportes.index'); 
    $title = 'Archivo  historico '; 
@endphp
@extends('layouts.crud')

@section('content')
    <div class="container mx-auto p-4">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-red-600"></i>
                {{ ucfirst(str_replace('_', ' ', $reporte->tipo)) }}
            </h1>
            <p class="text-gray-600 mt-1">Periodo: {{ $reporte->periodo }}</p>
            <p class="text-gray-600">Generado por: {{ $reporte->generadoPor }}</p>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <a href="{{ route('reportes.downloadPDFById', $reporte) }}">Descargar PDF</a>
            <a href="{{ route('reportes.downloadExcelById', $reporte) }}">Descargar Excel</a>

        </div>

        <div class="w-full h-[80vh] border rounded-xl shadow overflow-hidden">
            <iframe src="{{ route('reportes.verPDF', $reporte) }}" class="w-full h-[80vh]"></iframe>

        </div>
    </div>
@endsection
