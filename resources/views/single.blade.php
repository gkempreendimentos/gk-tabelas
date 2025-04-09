@php
  $show_complete_tab = get_field('show_complete_tab');
  $show_summary_tab = get_field('show_summary_tab');
  $show_complete = isset($show_complete_tab) && $show_complete_tab;
  $show_summary = isset($show_summary_tab) && $show_summary_tab;
@endphp

@extends('layouts.app')

@section('content')
  @include('forms.simulation')
  
  <figure class="banner -single" style="--color: {{ $data['color'] }}">
    <img src="{{ $data['image'] }}" width="320" height="104" alt="{{ $data['title'] }}">
  </figure>

  <div class="tabpanel">
    @if ($table)
    <div class="tabpanel-tabs">
      @if ($show_complete)
        <button class="tabpanel-tabs__button -selected" data-toggle="completo">Completo</button>
      @endif
      @if ($show_summary)
        <button class="tabpanel-tabs__button {{ !$show_complete ? '-selected' : '' }}" data-toggle="resumido">Resumido</button>
      @endif
    </div>
    
    <div class="tabpanel-content">
      @if ($show_complete)
        <div class="tab -opened" data-tab="completo">
          @include('partials.tabs.complete')
        </div>
      @endif

      @if ($show_summary)
        <div class="tab {{ !$show_complete ? '-opened' : '' }}" data-tab="resumido">
          @include('partials.tabs.summarized')
        </div>
      @endif
    </div>
    @else
    <p>Nenhum dado foi encontrado.</p>
    @endif
  </div>

  @if ($data['materials'])
  <section class="section -materials">
    <h3 class="section-title">Download dos materiais</h3>
    <ul class="list -materials">
      <li>
        <button onclick="downloadImage('{{ get_field('pdf_table') }}', '{{ $data['title'] }}.pdf')" target="_blank" rel="noopener noreferrer">
          <i aria-hidden="true" class="material-symbols-outlined">
            download
          </i>
          <strong>Tabela Resumida do empreendimento {{ $data['title'] }}</strong>
        </button>
      </li> 
      @foreach ($data['materials'] as $material)
        <li>
          <a href="{{ $material['url'] }}" target="_blank" rel="noopener noreferrer">
            <i aria-hidden="true" class="material-symbols-outlined">
              download
            </i>
            <span>{{ $material['title'] }}</span>
          </a>
        </li> 
      @endforeach
    </ul>
  </section>
  @endif
@endsection
