@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4 animate-fade-in">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-start gap-3">
        <div>
            <h1 class="h3 fw-bold text-danger mb-1">
                <i class="bi bi-graph-up-arrow me-2"></i>Dashboard de Eficiência
            </h1>
            <p class="text-muted mb-0 small">Planta A — Linhas de produto (Janeiro 2026)</p>
        </div>
        @if(!$data->isEmpty())
            <a href="{{ route('dashboard.export', $activeLine ? ['line' => $activeLine] : []) }}"
               class="btn btn-success btn-download btn-export-csv"
               title="Exportar dados em CSV (respeita o filtro atual)">
                <i class="bi bi-download me-2"></i>Exportar CSV
            </a>
        @endif
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4 animate-fade-in animate-delay-1">
    <div class="card-body">
        <h6 class="card-subtitle mb-3 text-muted fw-semibold">
            <i class="bi bi-funnel me-1"></i>Filtrar por linha de produção
        </h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dashboard.index') }}"
               class="btn btn-outline-secondary filter-btn {{ !$activeLine ? 'active' : '' }}">
                Todas
            </a>
            @foreach($productLines as $line)
                <a href="{{ route('dashboard.index', ['line' => $line]) }}"
                   class="btn btn-outline-secondary filter-btn {{ $activeLine === $line ? 'active' : '' }}">
                    {{ \App\Production::getLabel($line) }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card animate-fade-in animate-delay-2">
    <div class="card-body">
        @if($data->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                <p class="mb-0">Nenhum dado de produção encontrado para Janeiro 2026.</p>
                <p class="small">Execute: <code>php artisan db:seed</code></p>
            </div>
        @else
            {{-- Layout em cards para mobile --}}
            <div class="dashboard-mobile-cards d-md-none">
                @foreach($data as $index => $row)
                    @php
                        $eff = $row['efficiency'];
                        $effClass = $eff >= 96 ? 'efficiency-high' : ($eff >= 90 ? 'efficiency-medium' : 'efficiency-low');
                    @endphp
                    <div class="production-card" style="animation: fadeInUp 0.35s ease-out {{ $index * 0.05 }}s both">
                        <div class="card-row">
                            <span class="card-label">Linha do Produto</span>
                            <span class="card-value">{{ $row['product_line_label'] }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Quantidade Produzida</span>
                            <span class="card-value">{{ number_format($row['produced_quantity'], 0, ',', '.') }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Quantidade de Defeitos</span>
                            <span class="card-value">{{ number_format($row['defect_quantity'], 0, ',', '.') }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Eficiência (%)</span>
                            <span class="efficiency-badge {{ $effClass }}">{{ number_format($eff, 1) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tabela para desktop/tablet --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0" id="dashboard-table">
                    <thead class="table-light">
                        <tr>
                            <th>Linha do Produto</th>
                            <th class="text-end">Quantidade Produzida</th>
                            <th class="text-end">Quantidade de Defeitos</th>
                            <th class="text-end">Eficiência (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $row)
                            <tr style="animation: fadeInUp 0.35s ease-out {{ $index * 0.05 }}s both">
                                <td>
                                    <span class="fw-medium">{{ $row['product_line_label'] }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light text-dark">{{ number_format($row['produced_quantity'], 0, ',', '.') }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light text-dark">{{ number_format($row['defect_quantity'], 0, ',', '.') }}</span>
                                </td>
                                <td class="text-end">
                                    @php
                                        $eff = $row['efficiency'];
                                        $class = $eff >= 96 ? 'efficiency-high' : ($eff >= 90 ? 'efficiency-medium' : 'efficiency-low');
                                    @endphp
                                    <span class="efficiency-badge {{ $class }}">{{ number_format($eff, 1) }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="mt-3 text-secondary small animate-fade-in animate-delay-3">
    <i class="bi bi-info-circle me-1"></i>
    Eficiência = (Produzidos - Defeitos) / Produzidos × 100
</div>
@endsection
