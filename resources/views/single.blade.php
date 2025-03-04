@extends('layouts.app')

@section('content')
  <figure class="banner -single" style="--color: {{ $data['color'] }}">
    <img src="{{ $data['image'] }}" width="320" height="104" alt="{{ $data['title'] }}">
  </figure>

  <div class="tabpanel">
    <div class="tabpanel-tabs">
      <button class="tabpanel-tabs__button -selected" data-toggle="completo">Completo</button>
      <button class="tabpanel-tabs__button" data-toggle="resumido">Resumido</button>
    </div>
    
    <div class="tabpanel-content">
      <div class="tab -opened" data-modal="completo">
        <section class="map">
          <form class="form -filter">
            <div class="form-control">
              <label for="" class="form-label">Selecione a torre</label>
              <div class="form-select">
                <select>
                  <option value="Torre A">Torre A</option>
                  <option value="Torre B">Torre B</option>
                </select>
              </div>
            </div>
      
            <div class="form-control">
              <label for="" class="form-label">Selecione o tipo de imóvel</label>
              <div class="form-select">
                <select>
                  <option value="Convencional">Convencional</option>
                  <option value="Cobertura">Cobertura</option>
                </select>
              </div>
            </div>
          </form>
        </section>
      </div>
      <div class="tab" data-modal="resumido">
        <section class="table">
          <table>
            <thead>
              <tr>
                @foreach ($table['headers'] as $header)
                  @php
                    $order_selected = request()->get('order_by', 'apartamento_asc');
                  @endphp
                  <th data-colname="{{ $header }}">
                    <div class="th-wrapper">
                      <span class="th-title">{{ $header }}</span>
                      <div class="th-order">
                        <a href="?order_by={{ $header }}_asc#resumido" class="table-order__button {{ $order_selected === $header . '_asc' ? '-selected' : ''}}">
                          <span class="material-symbols-outlined">
                            arrow_drop_up
                          </span>
                        </a>
                        <a href="?order_by={{ $header }}_desc#resumido" class="table-order__button {{ $order_selected === $header . '_desc' ? '-selected' : ''}}">
                          <span class="material-symbols-outlined">
                            arrow_drop_down
                          </span>
                        </a>
                      </div>
                    </div>
                  </th>  
                @endforeach
              </tr>
            </thead>
            <tbody>
              @php $rowIndex = 0 @endphp
              @foreach ($table['data']['summary'] as $row)
                <tr>
                  @php $colIndex = 0; @endphp

                  @foreach ($row as $key => $value)
                    <td class="{{ $colIndex == 0 ? 'column-primary' : '' }}" data-colname="{{ $key }}">{{ $value }}</td>

                    @php $colIndex++; @endphp
                  @endforeach
                </tr>

                @php $rowIndex++; @endphp
              @endforeach
            </tbody>
          </table>

          <nav class="pagination">
            @if ($table['pagination']['currentPage'] > 1)
                <a href="?pagina={{ $table['pagination']['currentPage'] - 1 }}&limit={{ $table['pagination']['itemsPerPage'] }}#resumido" class="button -icon" aria-title="Página anterior"><span aria-hidden="true" class="material-symbols-outlined">
                  chevron_left
                  </span></a>
            @endif
        
            <span>Página {{ $table['pagination']['currentPage'] }} de {{ $table['pagination']['totalPages'] }}</span>
        
            @if ($table['pagination']['currentPage'] < $table['pagination']['totalPages'])
                <a href="?pagina={{ $table['pagination']['currentPage'] + 1 }}&limit={{ $table['pagination']['itemsPerPage'] }}#resumido" class="button -icon" aria-title="Próxima página"><span aria-hidden="true" class="material-symbols-outlined">
                  chevron_right
                  </span></a>
            @endif
          </nav>
        </section>
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
