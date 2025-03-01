{{--
  Template Name: Homepage
--}}

@extends('layouts.app')

@section('content')
  <div class="warningbox">
    <i aria-hidden="true" class="material-symbols-outlined">
      feedback
    </i>
    <p>{{ $cub }}</p>
  </div>

  <ul class="list -enterprises">
    @foreach ($enterprises as $item)
      <li>
        <article class="card -enterprise" style="--color: {{ $item['color'] }}">
          @if ($item['materials'])
            <ul class="card-materials" data-modal="materials-{{$item['id']}}">
              @foreach ($item['materials'] as $material)
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
          @endif

          <a href="{{ $item['permalink'] }}">
            <figure class="card-cover">
              <img src="{{ $item['image'] }}" width="" alt="{{ $item['title'] }}" />
            </figure>
          </a>
          <footer class="card-footer">
            @if ($item['materials'])
              <button class="button" data-toggle="materials-{{$item['id']}}">
                <i aria-hidden="true" class="material-symbols-outlined">
                  menu_open
                </i>
                <span>Ver materiais</span>
              </button>
            @endif
            <a href="{{ $item['permalink'] }}" class="button">
              <span>Abrir tabela</span>
              <i aria-hidden="true" class="material-symbols-outlined">
                chevron_right
              </i>
            </a>
          </footer>
        </article>
      </li>
    @endforeach
  </ul>
@endsection
