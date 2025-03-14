@extends('layouts.app')

@section('content')
  @include('forms.simulation')
  
  <figure class="banner -single" style="--color: {{ $data['color'] }}">
    <img src="{{ $data['image'] }}" width="320" height="104" alt="{{ $data['title'] }}">
  </figure>

  <div class="tabpanel">
    <div class="tabpanel-tabs">
      <button class="tabpanel-tabs__button -selected" data-toggle="completo">Completo</button>
      <button class="tabpanel-tabs__button" data-toggle="resumido">Resumido</button>
    </div>
    
    <div class="tabpanel-content">
      <div class="tab -opened" data-tab="completo">
        @include('partials.tabs.complete')
      </div>
      <div class="tab" data-tab="resumido">
        @include('partials.tabs.summarized')
      </div>
    </div>
  </div>

  @if ($data['materials'])
  <section class="section -materials">
    <h3 class="section-title">Download dos materiais</h3>
    <ul class="list -materials">
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
