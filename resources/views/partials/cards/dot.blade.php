@php
  $status = '';

  switch (sanitize_title($item['Disponibilidade'])) {
    case 'disponivel':
      $status = '-enabled';
      break;
    case 'reservado':
      $status = '-negotiating';
      break;
    default:
      $status = '-disabled';
      break;
  }
@endphp
<div class="map-dots__item {{ $status }}" style="--row: {{ $row }}; --col: {{ $col }}">
  <span class="map-dots__button" role="button" aria-label="Abrir detalhes" data-toggle="dot-{{ $item['Apartamento'] }}">{{ $item['Apartamento'] }}</span>
  <ul class="map-info">
    <li>
      <i aria-hidden="true" class="material-symbols-outlined">
        info
      </i>
      <span>Apartamento</span>
      <strong>{{ $item['Apartamento'] }}</strong>
    </li>
    <li>
      <i aria-hidden="true" class="material-symbols-outlined">
        attach_money
      </i>
      <span class="map-info__title">Preço</span>
      <strong class="map-info__value">{{ $item['Preço'] }}</strong>
      <button class="map-info__simulate" data-toggle="simulation" data-price="{{ str_replace(["R$", ".", ",", " "], ["", "", ".", ""], $item['Preço']) }}">Simular</button>
    </li>
    <li>
      <i aria-hidden="true" class="material-symbols-outlined">
        arrow_range
      </i>
      <span>Metragem</span>
      <strong>{{ $item['Metragem'] }}</strong>
    </li>
    <li>
      <i aria-hidden="true" class="material-symbols-outlined">
        bed
      </i>
      <span>Quartos</span>
      <strong>{{ $item['Dormitórios'] }}</strong>
    </li>
    <li>
      <i aria-hidden="true" class="material-symbols-outlined">
        space_dashboard
      </i>
      <span>Planta</span>
      <strong><a href="{{ $item['Planta'] }}">Visualizar planta</a></strong>
    </li>
  </ul>
</div>