@php
    $backRoute = route('reportes.index');
    $title = 'Visualizar archivo';
@endphp
@extends('layouts.crud')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-10">
        <h2 class="text-2xl font-bold mb-4">Reporte: {{ ucfirst(str_replace('_', ' ', $tipo)) }}</h2>

        @if (isset($pdfUrl))
            <div class="border rounded-lg overflow-hidden mb-4">
                <iframe src="{{ $pdfUrl }}" width="100%" height="600px"></iframe>
            </div>
        @endif

        <div class="flex gap-4">

            <a href="{{ route('reportes.downloadPDFByTipo', $tipo) }}"
                class="px-6 py-2 bg-amber-900 hover:bg-amber-700 text-white rounded-lg shadow">
                Descargar PDF
            </a>

            <a href="{{ route('reportes.downloadExcelByTipo', $tipo) }}"
                class="px-6 py-2 bg-green-700 hover:bg-green-500 text-white rounded-lg shadow">
                Descargar Excel
            </a>

        </div>
    </div>
@endsection
