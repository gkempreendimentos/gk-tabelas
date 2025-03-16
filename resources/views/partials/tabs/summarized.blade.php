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
            @if ($key === 'Planta')
              <td class="{{ $colIndex == 0 ? 'column-primary' : '' }}" data-colname="{{ $key }}"><a href="{{ $value }}">Visualizar planta</a></td>
            @elseif ($key === 'Preço')
              <td class="{{ $colIndex == 0 ? 'column-primary' : '' }}" data-colname="{{ $key }}">
                {{ $value }}
                <button aria-label="Abrir calculadora de simulação" data-toggle="simulation" data-price="{{ str_replace(["R$", ".", ",", " "], ["", "", ".", ""], $value) }}">
                  <span class="material-symbols-outlined">
                    tune
                  </span>
                </button>
              </td>
            @else
              <td class="{{ $colIndex == 0 ? 'column-primary' : '' }}" data-colname="{{ $key }}">{{ $value }}</td>
            @endif

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